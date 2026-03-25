<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

// Start session and check authentication
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$userId = (int)$_SESSION['user_id'];
$isRestrictedUser = in_array($userId, [282, 288]);

try {
    switch ($method) {
        case 'GET':
            // Get events for date range
            $start = $_GET['start'] ?? '';
            $end = $_GET['end'] ?? '';
            
            if ($isRestrictedUser) {
                // Restricted users can see all events but only manage their own
                $stmt = $pdo->prepare("SELECT e.*, emp.name as creator_name 
                                     FROM events e
                                     LEFT JOIN employees emp ON e.created_by = emp.userId
                                     WHERE e.date BETWEEN ? AND ?
                                     ORDER BY e.date, e.time");
                $stmt->execute([$start, $end]);
            } else {
                // Admin users can see all events
                $stmt = $pdo->prepare("SELECT e.*, emp.name as creator_name 
                                     FROM events e
                                     LEFT JOIN employees emp ON e.created_by = emp.userId
                                     WHERE e.date BETWEEN ? AND ?
                                     ORDER BY e.date, e.time");
                $stmt->execute([$start, $end]);
            }
            
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($events);
            break;
            
        case 'POST':
            // Create new event
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate data
            if (empty($data['employee_id']) || empty($data['title']) || empty($data['date']) || empty($data['time'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields']);
                exit;
            }
            
            // Check if restricted user is trying to create event for someone else
            if ($isRestrictedUser && $data['employee_id'] != $userId) {
                http_response_code(403);
                echo json_encode(['error' => 'You can only create events for yourself']);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO events 
            (employee_id, title, date, time, note, created_by) 
            VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['employee_id'],
                $data['title'],
                $data['date'],
                $data['time'],
                $data['note'] ?? null,
                $userId
            ]);
            
            echo json_encode(['id' => $pdo->lastInsertId()]);
            break;
            
        case 'PUT':
            // Update existing event
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing event ID']);
                exit;
            }
            
            // First get the existing event to check permissions
            $stmt = $pdo->prepare("SELECT employee_id FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $event = $stmt->fetch();
            
            if (!$event) {
                http_response_code(404);
                echo json_encode(['error' => 'Event not found']);
                exit;
            }
            
            // Check if restricted user is trying to modify someone else's event
            if ($isRestrictedUser && $event['employee_id'] != $userId) {
                http_response_code(403);
                echo json_encode(['error' => 'You can only modify your own events']);
                exit;
            }
            
            // Check if they're trying to change the employee_id (only admins can do this)
            if (isset($data['employee_id']) && $data['employee_id'] != $event['employee_id'] && $isRestrictedUser) {
                http_response_code(403);
                echo json_encode(['error' => 'You cannot reassign events']);
                exit;
            }
            
            $stmt = $pdo->prepare("UPDATE events SET 
            title = ?, 
            date = ?, 
            time = ?, 
            note = ? 
            WHERE id = ?");
            $stmt->execute([
                $data['title'],
                $data['date'],
                $data['time'],
                $data['note'] ?? null,
                $id
            ]);
            
            echo json_encode(['success' => true]);
            break;
            
        case 'DELETE':
            // First get the event to check permissions
            $stmt = $pdo->prepare("SELECT employee_id FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $event = $stmt->fetch();
            
            if (!$event) {
                http_response_code(404);
                echo json_encode(['error' => 'Event not found']);
                exit;
            }
            
            // Check if restricted user is trying to delete someone else's event
            if ($isRestrictedUser && $event['employee_id'] != $userId) {
                http_response_code(403);
                echo json_encode(['error' => 'You can only delete your own events']);
                exit;
            }
            
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>