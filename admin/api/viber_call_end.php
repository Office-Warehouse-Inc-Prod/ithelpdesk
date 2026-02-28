<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../databaseforcall.php'; // <-- your file that creates $connection

try {
    $log_id = (int)($_POST['log_id'] ?? 0);
    $call_result = $_POST['call_result'] ?? null;
    $duration_seconds = $_POST['duration_seconds'] ?? null;
    $remarks = trim($_POST['remarks'] ?? '');

    $allowed = ['ANSWERED','NO_ANSWER','BUSY','FAILED','CANCELLED'];

    if ($log_id <= 0) {
        echo json_encode(['ok' => false, 'message' => 'Missing log_id']);
        exit;
    }
    if ($call_result !== null && !in_array($call_result, $allowed, true)) {
        echo json_encode(['ok' => false, 'message' => 'Invalid call_result']);
        exit;
    }

    $duration_seconds = ($duration_seconds === '' || $duration_seconds === null)
        ? null
        : max(0, (int)$duration_seconds);

    $stmt = $connection->prepare("
        UPDATE viber_call_logs
        SET ended_at = NOW(),
            call_result = :call_result,
            duration_seconds = :duration_seconds,
            remarks = :remarks
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':call_result' => $call_result,
        ':duration_seconds' => $duration_seconds,
        ':remarks' => $remarks,
        ':id' => $log_id
    ]);

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
