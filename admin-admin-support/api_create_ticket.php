<?php
require_once "database.php";
session_start();
header("Content-Type: application/json");

try {
    // ====== 1) Generate ticket number using counter table ======
    // Example counter table:
    // tbl_ticket_counter(id INT PK, last_no INT)
    // Make sure you have 1 row with id=1

    $connection->beginTransaction();

    // Lock counter row (prevents duplicates)
    $stmt = $connection->prepare("SELECT last_no FROM tbl_ticket_counter WHERE id=1 FOR UPDATE");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Counter not initialized. Create tbl_ticket_counter row id=1.");
    }

    $nextNo = (int)$row["last_no"] + 1;
    $ticket_no = "HD-" . str_pad((string)$nextNo, 6, "0", STR_PAD_LEFT); // HD-000001

    $upd = $connection->prepare("UPDATE tbl_ticket_counter SET last_no=:n WHERE id=1");
    $upd->execute([":n" => $nextNo]);

    // ====== 2) Insert ticket header ======
    // Adjust table/columns to match your system
    $created_by = $_POST["uId"] ?? ($_SESSION["user_id"] ?? null);
    $deptsel = 2; // merchandising forced
    $subject = trim($_POST["subject"] ?? "");
    $concern = trim($_POST["concern"] ?? "");
    $status = "NEW REPORT";

    if ($subject === "" || $concern === "") {
        throw new Exception("Subject and Concern are required.");
    }

    $ins = $connection->prepare("
        INSERT INTO tbl_tickets
            (ticket_no, deptsel, subject, concern, status, created_by, created_at)
        VALUES
            (:ticket_no, :deptsel, :subject, :concern, :status, :created_by, NOW())
    ");

    $ins->execute([
        ":ticket_no" => $ticket_no,
        ":deptsel" => $deptsel,
        ":subject" => $subject,
        ":concern" => $concern,
        ":status" => $status,
        ":created_by" => $created_by
    ]);

    $connection->commit();

    echo json_encode([
        "status" => "success",
        "ticket_no" => $ticket_no
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