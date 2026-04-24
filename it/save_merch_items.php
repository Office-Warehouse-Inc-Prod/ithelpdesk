<?php
// save_merch_items.php
require_once "database.php";
session_start();

header("Content-Type: application/json");

// 1) Validate required inputs
$ticket_no = $_POST["ticket_no"] ?? "";
$items_json = $_POST["merch_items_json"] ?? "";
$created_by = $_POST["uId"] ?? ($_SESSION["user_id"] ?? null);

if ($ticket_no === "" || $items_json === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Missing ticket_no or merch_items_json."
    ]);
    exit;
}

// 2) Decode JSON safely
$items = json_decode($items_json, true);
if (!is_array($items)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON payload."
    ]);
    exit;
}

if (count($items) === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "No items to save."
    ]);
    exit;
}

try {
    // 3) Prepare insert statement (PDO)
    $sql = "INSERT INTO tbl_ticket_items
            (ticket_no, alu, description, serial_no, defect, vendor, qty, classification, created_by)
            VALUES
            (:ticket_no, :alu, :description, :serial_no, :defect, :vendor, :qty, :classification, :created_by)";

    $stmt = $connection->prepare($sql);

    // 4) Transaction = all-or-nothing save
    $connection->beginTransaction();

    $rowsInserted = 0;

    foreach ($items as $it) {
        // Normalize / validate each item
        $alu = trim($it["alu"] ?? "");
        $description = trim($it["desc"] ?? $it["description"] ?? "");
        $serial_no = trim($it["serial"] ?? $it["serial_no"] ?? "");
        $defect = trim($it["defect"] ?? "");
        $vendor = trim($it["vendor"] ?? "");
        $qty = (int)($it["qty"] ?? 1);
        $classification = $it["classification"] ?? "";

        if ($alu === "" || $description === "" || $serial_no === "" || $defect === "" || $vendor === "" || $qty <= 0) {
            throw new Exception("One or more items have missing/invalid fields.");
        }

        // Ensure classification is valid enum
        if (!in_array($classification, ["STORE_UNIT", "CUSTOMER_UNIT"], true)) {
            throw new Exception("Invalid classification value.");
        }

        $stmt->execute([
            ":ticket_no" => $ticket_no,
            ":alu" => $alu,
            ":description" => $description,
            ":serial_no" => $serial_no,
            ":defect" => $defect,
            ":vendor" => $vendor,
            ":qty" => $qty,
            ":classification" => $classification,
            ":created_by" => $created_by
        ]);

        $rowsInserted++;
    }

    $connection->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Items saved successfully.",
        "ticket_no" => $ticket_no,
        "inserted" => $rowsInserted
    ]);
    exit;

} catch (Exception $e) {
    if ($connection->inTransaction()) {
        $connection->rollBack();
    }

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    exit;
}
?>