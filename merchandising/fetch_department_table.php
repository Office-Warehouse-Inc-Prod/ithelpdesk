<?php
include '../condb.php'; 

header('Content-Type: application/json');

$query1 = "SELECT MONTH(r.date_created) AS MONTH_NUM,
           SUM(r.status = 'Assigned') AS ASSIGNED,
           SUM(r.status = 'ON PROCESS') AS ON_PROCESS,
           SUM(r.status = 'PENDING') AS PENDING,
           SUM(r.status = 'CLOSED') AS CLOSED,
           COUNT(r.status) AS GRAND_TOTAL,
           SUM(CASE WHEN r.status = 'CLOSED' AND r.date_closed IS NOT NULL AND DATEDIFF(r.date_closed, r.date_created) <= r.sla_days THEN 1 ELSE 0 END) AS MET_SLA
    FROM reports r 
    WHERE r.status IN ('Assigned', 'ON PROCESS', 'PENDING', 'CLOSED') 
      AND YEAR(r.date_created) = 2026 
      AND r.f_deptsel = 4
    GROUP BY MONTH(r.date_created)
    ORDER BY MONTH_NUM ASC";

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
    WHERE r.status IN ('PENDING', 'ASSIGNED', 'ON PROCESS')
      AND YEAR(r.date_created) = 2026 
      AND r.f_deptsel = 4
    ORDER BY r.date_created ASC";

$query_totals = "SELECT 
           SUM(status = 'Assigned') AS TOTAL_ASSIGNED,
           SUM(status = 'ON PROCESS') AS TOTAL_ON_PROCESS,
           SUM(status = 'PENDING') AS TOTAL_PENDING,
           SUM(status = 'CLOSED') AS TOTAL_CLOSED,
           COUNT(status) AS OVERALL_GRAND_TOTAL,
           SUM(CASE WHEN status = 'CLOSED' AND date_closed IS NOT NULL AND DATEDIFF(date_closed, date_created) <= sla_days THEN 1 ELSE 0 END) AS TOTAL_MET_SLA
    FROM reports r
    WHERE status IN ('Assigned', 'ON PROCESS', 'PENDING', 'CLOSED') 
      AND YEAR(r.date_created) = 2026 
      AND r.f_deptsel = 4";

try {
    $db = new dbconfig();
    
    $output_payload = [
        'department_stats' => [],
        'ticket_details'   => [],
        'global_totals'    => null
    ];

    $stmt1 = $db->prepare($query1);
    $stmt1->execute();
    $output_payload['department_stats'] = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt1->close();

    $stmt2 = $db->prepare($query2);
    $stmt2->execute();
    $output_payload['ticket_details'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();

 
    $stmt3 = $db->prepare($query_totals);
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
?>