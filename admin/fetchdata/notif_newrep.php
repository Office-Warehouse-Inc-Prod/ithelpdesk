<?php
require_once '../../database.php';

try {

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM reports WHERE status = 'NEW REPORT' AND store IS NOT NULL");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo (int)$row['total']; // ensures integer only

} catch (PDOException $e) {

    echo 0; // never expose DB errors in badge
}
