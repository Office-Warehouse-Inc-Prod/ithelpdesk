<?php
session_start();
include('database.php');

$conn = mysqli_connect("localhost", "root", "", "helpdesk1");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$data = array();


if ($type === 'category') { 
    $val = $_GET['val'];

    // IT
    if ($val === '1') {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '1' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => $cat, 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    // ADMIN
    } elseif ($val === '2') {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '2' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => $cat, 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    // MARKETING
    } elseif ($val === '3') {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '3' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => $cat, 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    // VISUAL
    } elseif ($val === '6') {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '6' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => $cat, 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        } 
    } elseif ($val === '11') { //hr
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '11' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => $cat, 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        } 
    } 
    else {
        // Handle invalid type
        $data[] = array('id' => 0, 'text' => 'Search for Data');
    }

    mysqli_close($conn);
    echo json_encode($data);
    exit;
}


?>