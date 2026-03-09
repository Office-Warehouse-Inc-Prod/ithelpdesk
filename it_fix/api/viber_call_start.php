<?php
header('Content-Type: application/json');
require_once __DIR__ . '../../databaseforcall.php'; // <-- your file that creates $connection

try {
    if (!isset($connection)) {
        throw new Exception("PDO connection not found. Check database.php");
    }

    $user_id = (int)($_POST['user_id'] ?? 0);
    $ticket_no = trim($_POST['ticket_no'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');

    if ($user_id <= 0 || $phone_number === '') {
        echo json_encode(['ok' => false, 'message' => 'Missing user_id or phone_number']);
        exit;
    }

    $stmt = $connection->prepare("
        INSERT INTO viber_call_logs (user_id, ticket_no, phone_number, initiated_at)
        VALUES (:user_id, :ticket_no, :phone_number, NOW())
    ");
    $stmt->execute([
        ':user_id' => $user_id,
        ':ticket_no' => $ticket_no,
        ':phone_number' => $phone_number
    ]);

    echo json_encode(['ok' => true, 'log_id' => $connection->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
}
