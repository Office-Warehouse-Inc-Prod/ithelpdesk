<?php
require_once "database.php";
session_start();
header("Content-Type: application/json");

try {
    $deptsel_id = 16; // MERCH
    $subject = trim($_POST["subject"] ?? "");
    $concern = trim($_POST["concern"] ?? "");
    $created_by = $_POST["uId"] ?? ($_SESSION["user_id"] ?? null);

    if ($subject === "" || $concern === "") {
        throw new Exception("Subject and Concern are required.");
    }

    $connection->beginTransaction();

    // 1) LOCK the counter row for deptsel_id=16
    $stmt = $connection->prepare("
        SELECT ticket_no
        FROM tbl_ticket_counter
        WHERE deptsel_id = :deptsel_id
        FOR UPDATE
    ");
    $stmt->execute([":deptsel_id" => $deptsel_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Counter row not found for deptsel_id=16 (Merchandising).");
    }

    // 2) Increment counter
    $next = (int)$row["ticket_no"] + 1;

    // 3) Update counter table
    $upd = $connection->prepare("
        UPDATE tbl_ticket_counter
        SET ticket_no = :next
        WHERE deptsel_id = :deptsel_id
    ");
    $upd->execute([
        ":next" => $next,
        ":deptsel_id" => $deptsel_id
    ]);

    // 4) Build your ticket reference (choose your format)
    // Option A: numeric only (10542)
    // $ticket_ref = (string)$next;

    // Option B: prefixed format (MERCH-010542)
    $ticket_ref = "MERCH-" . str_pad((string)$next, 6, "0", STR_PAD_LEFT);

    // 5) Insert ticket header (CHANGE tbl_tickets + columns to match your real table)
    $ins = $connection->prepare("
        INSERT INTO tbl_tickets
            (ticket_no, deptsel, subject, concern, status, created_by, created_at)
        VALUES
            (:ticket_no, :deptsel, :subject, :concern, 'NEW REPORT', :created_by, NOW())
    ");
    $ins->execute([
        ":ticket_no" => $ticket_ref,
        ":deptsel" => $deptsel_id,
        ":subject" => $subject,
        ":concern" => $concern,
        ":created_by" => $created_by
    ]);

    $connection->commit();

    echo json_encode([
        "status" => "success",
        "ticket_no" => $ticket_ref
    ]);
    exit;

} catch (Exception $e) {
    if ($connection->inTransaction()) $connection->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    exit;
}