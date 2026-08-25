<?php
session_start();
include('database.php');

$conn = mysqli_connect("localhost", "root", "", "helpdesk1");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$data = array();


if ($type === 'category' || $type === 'category_id') {
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
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
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
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
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
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
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
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
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
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    } elseif ($val === '12') { //hr
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '12' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    } elseif ($val === '13') { //hr
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '13' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    } elseif ($val === '14') { //hr
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '14' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    } elseif ($val === '15') { //hr
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '15' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    } elseif ($val === '16') { //hr
        $stmt = $conn->prepare("SELECT * FROM categories WHERE deptsel = '16' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['cat_id'];
                    $cat = $row['cat_desc'];
                    $data[] = array('id' => ($type === 'category_id' ? $id : $cat), 'text' => $cat);
                }
            } else {
                $data[] = array('id' => 0, 'text' => 'No Data Found');
            }
            $stmt->close();
        }
    } else {
        // Handle invalid type
        $data[] = array('id' => 0, 'text' => 'Search for Data');
    }

    mysqli_close($conn);
    echo json_encode($data);
    exit;
} elseif ($type === 'sub_category') {
    $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 37;
    $stmt = $conn->prepare("SELECT sub_id, sub_cat FROM subcat WHERE cat_id = ?");
    $stmt->bind_param("i", $cat_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = array('id' => $row['sub_id'], 'text' => $row['sub_cat']);
            }
        }
    }
    $stmt->close();
    mysqli_close($conn);
    echo json_encode($data);
    exit;
}

elseif ($type === 'sub_category') {
    $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 37;
    $stmt = $conn->prepare("SELECT sub_id, sub_cat FROM subcat WHERE cat_id = ?");
    $stmt->bind_param("i", $cat_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = array('id' => $row['sub_id'], 'text' => $row['sub_cat']);
            }
        }
    }
    $stmt->close();
    mysqli_close($conn);
    echo json_encode($data);
    exit;
}




?>