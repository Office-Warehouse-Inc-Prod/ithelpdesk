<?php
include '../condb.php'; 

if (isset($_POST['yr']) && isset($_POST['mo'])) {
    header('Content-Type: application/json');
    $years_array = array_filter(array_map('intval', explode(',', $_POST['yr'])));
    if (empty($years_array)) { $years_array = [date('Y')]; }
    $months_array = array_filter(array_map('intval', explode(',', $_POST['mo'])));
    if (empty($months_array)) { $months_array = [1,2,3,4,5,6,7,8,9,10,11,12]; }

    $allowed_depts = [1, 2, 3, 6, 8, 11, 13, 15, 16];
    $year_placeholders  = implode(',', array_fill(0, count($years_array), '?'));
    $month_placeholders = implode(',', array_fill(0, count($months_array), '?'));
    $dept_placeholders  = implode(',', array_fill(0, count($allowed_depts), '?'));
    
    $query1 = "SELECT d.dept_desc AS DEPARTMENT,
               SUM(r.status = 'Assigned') AS ASSIGNED,
               SUM(r.status = 'ON PROCESS') AS ON_PROCESS,
               SUM(r.status = 'PENDING') AS PENDING,
               SUM(r.status = 'CLOSED') AS CLOSED,
               COUNT(r.status) AS GRAND_TOTAL 
        FROM tbl_dept d
        INNER JOIN reports r ON r.f_deptsel = d.dept_id 
        WHERE r.status IN ('Assigned', 'ON PROCESS', 'PENDING', 'CLOSED') 
          AND YEAR(r.date_created) IN ($year_placeholders) 
          AND MONTH(r.date_created) IN ($month_placeholders) 
          AND r.f_deptsel IN ($dept_placeholders) 
        GROUP BY d.dept_id 
        ORDER BY GRAND_TOTAL DESC";

    $query2 = "SELECT
            r.ticket_no AS ticket_no,
            b.str_code AS str_code,
            r.date_created AS date_created,
            r.concern AS concern,
            r.status AS status,
            d.dept_desc AS `Assigned Department`,
            c.cat_desc AS category,
            s.sub_cat AS sub_category,
            r.remarks AS remarks,
            isp.isp_shortDesc AS isp_shortDesc,
            r.subject AS subject
        FROM reports r
        JOIN tbl_branch b ON b.str_num = r.store
        LEFT JOIN tbl_dept d ON d.dept_id = r.f_deptsel
        LEFT JOIN categories c ON c.cat_id = r.cat_id
        LEFT JOIN subcat s ON s.sub_id = r.sub_id
        LEFT JOIN tbl_clusers cu ON cu.itsup = r.close_by
        LEFT JOIN tbl_isp isp ON isp.isp_id = r.isp_id
        LEFT JOIN reports_msgcnt mc ON mc.ticket_no = r.ticket_no
        LEFT JOIN tbl_priority p ON p.priority_id = r.priority_level
        WHERE r.status IN ('PENDING', 'ASSIGNED', 'ON PROCESS')
          AND YEAR(r.date_created) IN ($year_placeholders)
          AND MONTH(r.date_created) IN ($month_placeholders)
          AND r.f_deptsel IN ($dept_placeholders)
          AND r.f_deptsel NOT IN (4, 5)
        ORDER BY r.date_created ASC";

    $query_totals = "SELECT 
               SUM(status = 'Assigned') AS TOTAL_ASSIGNED,
               SUM(status = 'ON PROCESS') AS TOTAL_ON_PROCESS,
               SUM(status = 'PENDING') AS TOTAL_PENDING,
               SUM(status = 'CLOSED') AS TOTAL_CLOSED,
               COUNT(status) AS OVERALL_GRAND_TOTAL 
        FROM reports r
        WHERE status IN ('Assigned', 'ON PROCESS', 'PENDING', 'CLOSED') 
          AND YEAR(r.date_created) IN ($year_placeholders) 
          AND MONTH(r.date_created) IN ($month_placeholders) 
          AND f_deptsel IN ($dept_placeholders)";

    try {
        $db = new dbconfig();
        $params = array_merge($years_array, $months_array, $allowed_depts);
        $types = str_repeat('i', count($years_array)) . 
                 str_repeat('i', count($months_array)) . 
                 str_repeat('i', count($allowed_depts));
        
        $output_payload = [
            'department_stats' => [],
            'ticket_details'   => [],
            'global_totals'    => null
        ];

        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param($types, ...$params);
        $stmt1->execute();
        $output_payload['department_stats'] = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt1->close();

        $stmt2 = $db->prepare($query2);
        $stmt2->bind_param($types, ...$params);
        $stmt2->execute();
        $output_payload['ticket_details'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();

        $stmt3 = $db->prepare($query_totals);
        $stmt3->bind_param($types, ...$params);
        $stmt3->execute();
        $res_totals = $stmt3->get_result()->fetch_assoc();
        $output_payload['global_totals'] = $res_totals;
        $stmt3->close();
        
        echo json_encode($output_payload);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}
?>