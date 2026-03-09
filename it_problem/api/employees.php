<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("SELECT id, name, position, userId FROM employees ORDER BY id");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($employees);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch employees']);
}
?>