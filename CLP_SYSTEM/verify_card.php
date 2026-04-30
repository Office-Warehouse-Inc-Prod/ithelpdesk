<?php
header('Content-Type: application/json');

include 'config/db.php';

$cardID = $_POST['card_id'] ?? '';

if (empty($cardID)) {
    echo json_encode([
        'success' => false,
        'message' => 'Card ID is required.'
    ]);
    exit;
}

try {
    $query = "
        SELECT
            id, 
            last_transact_dt, 
            invc_no, 
            member_code, 
            amt, 
            computed_points, 
            final_points, 
            points_tag, 
            store_code, 
            date_imported, 
            member_id, 
            store_no, 
            CardID, 
            Expiry
        FROM tbl_clpmembers_dtl
        WHERE CardID = :card_id
        LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':card_id', $cardID);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode([
            'success' => true,
            'data' => $row
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Card not found.'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error.'
    ]);
}
?>