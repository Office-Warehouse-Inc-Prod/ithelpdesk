<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "helpdesk1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $title = $_POST['title'];
    $note = $_POST['note'];
    $start = $_POST['start'];

    if (empty($title)) {
        throw new Exception("Title cannot be empty.");
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing event
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE zevents SET title = :title, note = :note, start = :start WHERE id = :id");
        $stmt->bindParam(':id', $id);
    } else {
        // Insert new event
        $stmt = $conn->prepare("INSERT INTO zevents (title, note, start) VALUES (:title, :note, :start)");
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':start', $start);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Event saved successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    $conn = null; // Close connection
}
?>
