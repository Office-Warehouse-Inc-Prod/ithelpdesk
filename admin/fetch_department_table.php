<?php
include '../condb.php'; 

if (isset($_POST['yr']) && isset($_POST['mo'])) {
    header('Content-Type: application/json');
    
    try {
        $years_array = array_filter(array_map('intval', explode(',', $_POST['yr'])));
        if (empty($years_array)) { $years_array = [date('Y')]; }
        
        $months_array = array_filter(array_map('intval', explode(',', $_POST['mo'])));
        if (empty($months_array)) { $months_array = [1,2,3,4,5,6,7,8,9,10,11,12]; }

        $allowed_depts = [1, 2, 3, 6, 11, 13, 15, 16];
        
        $year_placeholders  = implode(',', array_fill(0, count($years_array), '?'));
        $month_placeholders = implode(',', array_fill(0, count($months_array), '?'));
        $dept_placeholders  = implode(',', array_fill(0, count($allowed_depts), '?'));
        
        $types = str_repeat('i', count($years_array)) . 
                 str_repeat('i', count($months_array)) . 
                 str_repeat('i', count($allowed_depts));
                 
        $params = array_merge($years_array, $months_array, $allowed_depts);

        $query1 = "SELECT d.dept_desc AS DEPARTMENT,
                   SUM(LOWER(r.status) = 'assigned') AS ASSIGNED,
                   SUM(LOWER(r.status) = 'on process') AS ON_PROCESS,
                   SUM(LOWER(r.status) = 'pending') AS PENDING,
                   SUM(LOWER(r.status) = 'closed') AS CLOSED,
                   COUNT(r.status) AS GRAND_TOTAL,
                   SUM(CASE WHEN LOWER(r.status) = 'closed' AND r.date_closed IS NOT NULL AND DATEDIFF(r.date_closed, r.date_created) <= r.sla_days THEN 1 ELSE 0 END) AS MET_SLA
            FROM tbl_dept d
            INNER JOIN reports r ON r.f_deptsel = d.dept_id 
            WHERE LOWER(r.status) IN ('assigned', 'on process', 'pending', 'closed') 
              AND YEAR(r.date_created) IN ($year_placeholders) 
              AND MONTH(r.date_created) IN ($month_placeholders) 
              AND r.f_deptsel IN ($dept_placeholders) 
            GROUP BY d.dept_id, d.dept_desc
            ORDER BY GRAND_TOTAL DESC";

        $query2 = "SELECT
                r.ticket_no AS TICKET_NO,
                b.str_code AS STR_CODE,
                r.date_created AS DATE_CREATED,
                r.concern AS CONCERN,
                r.status AS STATUS,
                d.dept_desc AS ASSIGNED_DEPARTMENT,
                c.cat_desc AS CATEGORY,
                s.sub_cat AS SUB_CATEGORY,
                r.remarks AS REMARKS,
                isp.isp_shortDesc AS ISP_SHORTDESC,
                r.subject AS SUBJECT
            FROM reports r
            JOIN tbl_branch b ON b.str_num = r.store
            LEFT JOIN tbl_dept d ON d.dept_id = r.f_deptsel
            LEFT JOIN categories c ON c.cat_id = r.cat_id
            LEFT JOIN subcat s ON s.sub_id = r.sub_id
            LEFT JOIN tbl_clusers cu ON cu.itsup = r.close_by
            LEFT JOIN tbl_isp isp ON isp.isp_id = r.isp_id
            LEFT JOIN reports_msgcnt mc ON mc.ticket_no = r.ticket_no
            LEFT JOIN tbl_priority p ON p.priority_id = r.priority_level
            WHERE LOWER(r.status) IN ('pending', 'assigned', 'on process')
              AND YEAR(r.date_created) IN ($year_placeholders)
              AND MONTH(r.date_created) IN ($month_placeholders)
              AND r.f_deptsel IN ($dept_placeholders)
              AND r.f_deptsel NOT IN (4, 5)
            ORDER BY r.date_created ASC";

        $query3 = "SELECT 
            d.dept_desc AS DEPARTMENT,
            COUNT(filtered_tickets.ticket_no) AS ticket_count,
            ROUND((COUNT(filtered_tickets.ticket_no) / SUM(COUNT(filtered_tickets.ticket_no)) OVER ()) * 100, 2) AS ticket_percentage
        FROM (
            SELECT DISTINCT
                T0.ticket_no, 
                T0.f_deptsel
            FROM reports T0
            INNER JOIN reports_comments T1 ON T0.ticket_no = T1.ticket_no 
            WHERE T0.status = 'ESCALATED' 
              AND YEAR(T0.date_created) IN ($year_placeholders)
              AND MONTH(T0.date_created) IN ($month_placeholders)
              AND T0.f_deptsel IN ($dept_placeholders)
              AND T0.f_deptsel NOT IN (4, 5) 
              AND T1.userId NOT IN (11, 16, 18, 20, 26, 27, 48, 54, 55, 56, 64, 65, 77, 78) 
            GROUP BY T0.ticket_no
        ) AS filtered_tickets
        INNER JOIN tbl_dept d ON filtered_tickets.f_deptsel = d.dept_id
        GROUP BY d.dept_id, d.dept_desc
        ORDER BY ticket_count DESC";

         $query4 = "SELECT
                r.ticket_no AS TICKET_NO,
                b.str_code AS STR_CODE,
                r.date_created AS DATE_CREATED,
                DATEDIFF(CURRENT_DATE, DATE(date_created)) AS DAYS_UNRESOLVED,
                r.sla_days AS SLA_DAYS,
                r.concern AS CONCERN,
                r.status AS STATUS,
                d.dept_desc AS ASSIGNED_DEPARTMENT,
                c.cat_desc AS CATEGORY,
                s.sub_cat AS SUB_CATEGORY,
                r.remarks AS REMARKS,
                r.subject AS SUBJECT
            FROM reports r
            JOIN tbl_branch b ON b.str_num = r.store
            LEFT JOIN tbl_dept d ON d.dept_id = r.f_deptsel
            INNER JOIN reports_comments rc ON r.ticket_no = rc.ticket_no 
            LEFT JOIN categories c ON c.cat_id = r.cat_id
            LEFT JOIN subcat s ON s.sub_id = r.sub_id
            LEFT JOIN tbl_clusers cu ON cu.itsup = r.close_by
            LEFT JOIN tbl_isp isp ON isp.isp_id = r.isp_id
            LEFT JOIN reports_msgcnt mc ON mc.ticket_no = r.ticket_no
            LEFT JOIN tbl_priority p ON p.priority_id = r.priority_level
            WHERE r.status = 'ESCALATED' 
              AND YEAR(r.date_created) IN ($year_placeholders)
              AND MONTH(r.date_created) IN ($month_placeholders)
              AND r.f_deptsel IN ($dept_placeholders)
              AND r.f_deptsel NOT IN (4, 5)
              AND rc.userId NOT IN (11, 16, 18, 20, 26, 27, 48, 54, 55, 56, 64, 65, 77, 78) 
            GROUP BY r.ticket_no";

        $query5 = "SELECT
              it_tech.it_desc AS HELPDESK_USER, 
              tbl_dept.dept_desc AS DEPARTMENT, 
              user_login.login_date
            FROM it_tech
            INNER JOIN user_login ON it_tech.itsup = user_login.user_id
            INNER JOIN tbl_dept ON user_login.dept_id = tbl_dept.dept_id 
            WHERE YEAR(user_login.login_date) IN ($year_placeholders)
              AND MONTH(user_login.login_date) IN ($month_placeholders)
              AND tbl_dept.dept_id IN ($dept_placeholders)
            ORDER BY user_login.login_date DESC";

        $query6 = "SELECT
        res.ticket_no,
        COALESCE(store_info.str_name, 'Unknown Branch') AS store_name,
        dept_from.dept_desc AS from_department,
        dept_to.dept_desc AS to_department,
        orig_tech.it_desc AS original_support,
        new_tech.it_desc AS new_support,
        req.STATUS AS request_status,
        res.r_remarks AS admin_remarks,
        req.created_by AS requested_by,
        req.created_at AS request_date,
        res.date_rasigned AS approval_date,
        CONCAT(
            TIMESTAMPDIFF(HOUR, req.created_at, res.date_rasigned),
            'h ',
            MOD(TIMESTAMPDIFF(MINUTE, req.created_at, res.date_rasigned), 60),
            'm ',
            MOD(TIMESTAMPDIFF(SECOND, req.created_at, res.date_rasigned), 60),
            's'
        ) AS turnaround_time
        FROM
        tbl_reassigned res
        LEFT JOIN tbl_reports_transfer_logs req ON res.ticket_no = req.ticket_no
        LEFT JOIN tbl_dept dept_from ON res.f_deptsel = dept_from.dept_id
        LEFT JOIN tbl_dept dept_to ON res.deptsel = dept_to.dept_id
        LEFT JOIN it_tech orig_tech ON req.itsup = orig_tech.itsup
        LEFT JOIN it_tech new_tech ON res.nw_sup = new_tech.itsup
        LEFT JOIN tbl_branch store_info ON req.store = store_info.str_num
        WHERE YEAR(req.created_at) IN ($year_placeholders)
        AND MONTH(req.created_at) IN ($month_placeholders)
        ORDER BY res.date_rasigned DESC";

        $query_totals = "SELECT 
                   SUM(LOWER(status) = 'assigned') AS TOTAL_ASSIGNED,
                   SUM(LOWER(status) = 'on process') AS TOTAL_ON_PROCESS,
                   SUM(LOWER(status) = 'pending') AS TOTAL_PENDING,
                   SUM(LOWER(status) = 'closed') AS TOTAL_CLOSED,
                   COUNT(status) AS OVERALL_GRAND_TOTAL,
                   SUM(CASE WHEN LOWER(status) = 'closed' AND date_closed IS NOT NULL AND DATEDIFF(date_closed, date_created) <= sla_days THEN 1 ELSE 0 END) AS TOTAL_MET_SLA
            FROM reports r
            WHERE LOWER(status) IN ('assigned', 'on process', 'pending', 'closed') 
              AND YEAR(r.date_created) IN ($year_placeholders) 
              AND MONTH(r.date_created) IN ($month_placeholders) 
              AND f_deptsel IN ($dept_placeholders)";

        $db = new dbconfig();
        
        $output_payload = [
            'department_stats'       => [],
            'ticket_details'         => [], 
            'nonesca_ticket_details' => [],
            'non_escalated'          => [],
            'user_login'             => [],
            'transfer_logs'          => [],
            'global_totals'          => null
        ];

        $stmt1 = $db->prepare($query1);
        if (!$stmt1) throw new Exception("Query 1 Error: " . $db->error);
        $stmt1->bind_param($types, ...$params);
        $stmt1->execute();
        $output_payload['department_stats'] = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt1->close();

        $stmt2 = $db->prepare($query2);
        if (!$stmt2) throw new Exception("Query 2 Error: " . $db->error);
        $stmt2->bind_param($types, ...$params);
        $stmt2->execute();
        $output_payload['ticket_details'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();

        $stmt3 = $db->prepare($query4);
        if (!$stmt3) throw new Exception("Query 4 Error: " . $db->error);
        $stmt3->bind_param($types, ...$params);
        $stmt3->execute();
        $output_payload['nonesca_ticket_details'] = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt3->close();

        $stmt4 = $db->prepare($query_totals);
        if (!$stmt4) throw new Exception("Query Totals Error: " . $db->error);
        $stmt4->bind_param($types, ...$params);
        $stmt4->execute();
        $output_payload['global_totals'] = $stmt4->get_result()->fetch_assoc();
        $stmt4->close();

        $stmt5 = $db->prepare($query3);
        if (!$stmt5) throw new Exception("Query 3 Error: " . $db->error);
        $stmt5->bind_param($types, ...$params);
        $stmt5->execute();
        $raw_non_escalated = $stmt5->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt5->close();
        $total_non_escalated = array_sum(array_column($raw_non_escalated, 'ticket_count'));
        foreach ($raw_non_escalated as &$row) {
            $row['ticket_percentage'] = $total_non_escalated > 0 ? round(($row['ticket_count'] / $total_non_escalated) * 100, 2) : 0;
        }
        $output_payload['non_escalated'] = $raw_non_escalated;

        $stmt6 = $db->prepare($query5);
        if (!$stmt6) throw new Exception("Query 5 Error: " . $db->error);
        $stmt6->bind_param($types, ...$params);
        $stmt6->execute();
        $output_payload['user_login'] = $stmt6->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt6->close();

        $types6 = str_repeat('i', count($years_array)) . str_repeat('i', count($months_array));
        $params6 = array_merge($years_array, $months_array);

        $stmt7 = $db->prepare($query6);
        if (!$stmt7) throw new Exception("Query 6 Error: " . $db->error);
        $stmt7->bind_param($types6, ...$params6);
        $stmt7->execute();
        $output_payload['transfer_logs'] = $stmt7->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt7->close();
        
        echo json_encode($output_payload);
        
    } catch (Throwable $e) { 
        http_response_code(500);
        echo json_encode([
            "error" => "Database or Script Error",
            "message" => $e->getMessage(),
            "file" => $e->getFile(),
            "line" => $e->getLine()
        ]);
    }
    exit;
}
?>