<?php
//admin side functions
include '../../connection/db.php';
date_default_timezone_set("Asia/Manila");
$cur_time = date("H:i:s");
/**
 *     
 */
class dbconfig extends dbconn
{
	/**
	 * Fetch cards result.
	 */
	public function fetch_cards_result(){

$yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

$dept_ids = isset($_POST['dept_id'])
    ? $_POST['dept_id']
    : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

		$output = array();



// Clean department IDs
$dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

if (empty($dept_ids_array)) {
    $dept_ids_array = range(1, 17);
}

$dept_ids_clean = implode(',', $dept_ids_array);
		$query = "
    SELECT 
        YEAR(date_created) AS date_created,

        COUNT(reports.`status`) AS t_all,

        COUNT(CASE 
            WHEN reports.`status` = 'ASSIGNED' 
            THEN 1 ELSE NULL 
        END) AS t_assigned,

        COUNT(CASE 
            WHEN reports.`status` = 'ON PROCESS' 
            THEN 1 ELSE NULL 
        END) AS t_onprocess,

		COUNT(CASE 
            WHEN reports.`status` = 'PENDING' 
            THEN 1 ELSE NULL 
        END) AS t_pending,

        COUNT(CASE 
            WHEN reports.`status` = 'ESCALATED' 
            THEN 1 ELSE NULL 
        END) AS t_nonesca,

        COUNT(CASE 
            WHEN reports.`status` = 'SUBJECT FOR CLOSING' 
            THEN 1 ELSE NULL 
        END) AS t_subforclosing,

        COUNT(CASE 
            WHEN reports.`status` = 'CLOSED' 
            THEN 1 ELSE NULL 
        END) AS t_closed


    FROM reports
    WHERE sub_id NOT IN ('15','28','34','35')
      AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT')
      AND YEAR(date_created) = {$yr}
      AND f_deptsel IN ({$dept_ids_clean})
";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

        foreach ($result as $row) {
        	$output[] = array(
        		'total_res' => $row["t_all"], 
        		'assigned_res' => $row["t_assigned"], 
        		'onprocess_res' => $row["t_onprocess"], 
        		'pending_res' => $row["t_pending"],
        		'nonesca_res' => $row["t_nonesca"],
				'subforclosing_res' => $row["t_subforclosing"],
        		'closed_res' => $row["t_closed"],


        	);
        }
        return $output;

	}

/**
 * Overallpie res.
 */
public function overallpie_res()
{
    $yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

    $dept_ids = isset($_POST['dept_id']) 
        ? $_POST['dept_id'] 
        : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

    // Clean dept ids para safe sa SQL
    $dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

    if (empty($dept_ids_array)) {
        $dept_ids_array = range(1, 17);
    }

    $dept_ids_clean = implode(',', $dept_ids_array);

    $query = "
        SELECT
            R.`status` AS stat_name,
            COUNT(R.`status`) AS points,
            YEAR(R.date_created) AS yr,
            S.stat_id
        FROM reports R
        LEFT JOIN tbl_status S 
            ON R.`status` = S.stat_desc
        WHERE R.`sub_id` NOT IN ('15','28','34','35')
          AND R.`status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT')
          AND YEAR(R.date_created) = {$yr}
          AND R.f_deptsel IN ({$dept_ids_clean})
        GROUP BY 
            R.`status`,
            YEAR(R.date_created),
            S.stat_id
        ORDER BY S.stat_id ASC
    ";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

		foreach ($result as $row) {
		$data[] = array(
		'stat_name' => $row["stat_name"], 
		'points' => $row["points"]

			);
		}
        return $data;
	}
// fix update cj for tech datatable
	public function bargrph_tech_res(){
		$query='';
		// $output= array();
		$query="SELECT
				reports.itsup AS itsup,
				it_tech.f_name AS it_name,
				it_tech.it_desc as it_desc,
				it_tech.cmp_role as cmp_role,
				users.img_name as img_name,
				Count(reports.itsup) AS total,
				reports.`status`,
				Count( CASE reports.`status` when 'CLOSED' then 1 else null end) as completed,
				Count( CASE reports.`status` when 'OPEN' then 1 else null end) as openrep,
				Count( CASE reports.`status` when 'OPEN WITH FIX ASSET' then 1 else null end) as opnwfxast
				FROM
				it_tech
				LEFT JOIN reports ON reports.itsup = it_tech.itsup
				INNER JOIN users ON users.tech_id = it_tech.itsup
				WHERE
				reports.sub_id NOT IN (15,28,34,35) AND reports.itsup NOT IN ('8') AND reports.deptsel = '2' and
				YEAR(reports.date_created) IN (".$_POST['yr'].")
				GROUP BY
				reports.itsup
				ORDER BY
				reports.itsup ASC
		";
        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

		foreach ($result as $row) {
		$data[] = array(
		'itsup' => $row["itsup"],
		'it_name' => $row["it_name"], 
		'it_desc' => $row["it_desc"], 
		'cmp_role' => $row["cmp_role"], 
		'img_name' => $row["img_name"], 
		'total' => $row["total"],
		'completed' => $row["completed"],
		'opncase' => $row["openrep"],
		'opnwfxast' => $row["opnwfxast"],
		'resassgncnt' => $this->count_reassigned($row["itsup"]),
		'res_sla' => $this->count_sla($row["itsup"]),
		'years' => $_POST['yr']

			);
		}
        return $data;
        
	}

	/**
	 * Linegraph.
	 */
	public function linegraph(){

		$query='';
		if ($_POST['yr'] != '2019,2020,2021,2022,2023') {
		$query="
		SELECT
		DATE( date_created ) AS DATEPART,
		Count(
		DATE( date_created )) AS total_number
		FROM
		reports
		WHERE
		year(date_created) BETWEEN '".$_POST['yr'] ."' AND '".$_POST['yr'] ."' and reports.sub_id NOT IN ('15','28','34','35') AND reports.deptsel = '2'
		GROUP BY
		DATEPART";	
		} else {
		$query="
		SELECT
		DATE( date_created ) AS DATEPART,
		Count(
		DATE( date_created )) AS total_number
		FROM
		reports
		WHERE
		year(date_created) BETWEEN '2019' AND '2022' and reports.sub_id NOT IN ('15','28','34','35') 
		GROUP BY
		DATEPART";
		}
		
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach ($result as $row) {
		$data[] = array(
		'date' => $row["DATEPART"], 
		'value' => $row["total_number"]
			);
		}
		return $data;
	}

	//admin dept new report
	public function pie(){

$query = "
    SELECT 
        T0.dept_desc,
        T0.dept_id,
        COUNT(*) AS ctn
    FROM tbl_dept T0
    INNER JOIN reports T1
        ON T1.f_deptsel = T0.dept_id
    WHERE T0.dept_id NOT IN ( 7, 12)
    GROUP BY T0.dept_id, T0.dept_desc
    ORDER BY T0.dept_desc ASC
";

    $statement = $this->connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $data = array();

    foreach ($result as $row) {
        $data[] = array(
            'type' => $row["dept_desc"],
            'percent' => $row["ctn"],
			'dept_id' => $row["dept_id"],
            'subs' => $this->subs($row['dept_id'])
        );
    }

    return $data;
}
	/**
	 * Subs.
	 */
	public function subs($id){

		$query= "SELECT sub_cat, count(*) as sctn, date_created FROM vwp WHERE cat_id='".$id."' AND deptsel = '2'  AND date_created IN (".$_POST['yr'] .")  GROUP BY sub_cat ORDER BY cat_desc ASC";

		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array('type' => $row['sub_cat'],'percent' => $row['sctn']);

		}
		return $data;
	}

	/**
	 * Area grph.
	 */
	public function area_grph(){

$yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

$dept_ids = isset($_POST['dept_id']) 
    ? $_POST['dept_id'] 
    : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

$dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

if (empty($dept_ids_array)) {
    $dept_ids_array = range(1, 17);
}

$dept_ids_clean = implode(',', $dept_ids_array);

// $query = " old query no breakdown
//     SELECT
//         B.area_num AS area_num,
//         A.area_desc AS area_desc,
//         YEAR(R.date_created) AS dc,
//         COUNT(R.date_created) AS cntarea
//     FROM reports R
//     JOIN tbl_branch B 
//         ON R.store = B.str_num
//     JOIN tbl_area A 
//         ON A.area_num = B.area_num
//     WHERE YEAR(R.date_created) = {$yr}
//       AND R.f_deptsel IN ({$dept_ids_clean})
//     GROUP BY
//         B.area_num,
//         A.area_desc,
//         YEAR(R.date_created)
//     ORDER BY
//         B.area_num ASC
// ";


$query = "
    SELECT
        B.area_num AS area_num,
        A.area_desc AS area_desc,
        YEAR(R.date_created) AS dc,

        COUNT(R.ticket_no) AS cntarea,

        SUM(CASE 
            WHEN UPPER(TRIM(R.status)) = 'ASSIGNED' 
            THEN 1 ELSE 0 
        END) AS assigned,

        SUM(CASE 
            WHEN UPPER(TRIM(R.status)) = 'ON PROCESS' 
            THEN 1 ELSE 0 
        END) AS on_process,

        SUM(CASE 
            WHEN UPPER(TRIM(R.status)) = 'PENDING' 
            THEN 1 ELSE 0 
        END) AS pending,

        SUM(CASE 
            WHEN UPPER(TRIM(R.status)) = 'SUBJECT FOR CLOSING' 
            THEN 1 ELSE 0 
        END) AS subject_for_closing,

        SUM(CASE 
            WHEN UPPER(TRIM(R.status)) = 'CLOSED' 
            THEN 1 ELSE 0 
        END) AS closed

    FROM reports R
    JOIN tbl_branch B 
        ON R.store = B.str_num
    JOIN tbl_area A 
        ON A.area_num = B.area_num

    WHERE YEAR(R.date_created) = {$yr}
      AND R.f_deptsel IN ({$dept_ids_clean})
      AND R.sub_id NOT IN ('15','28','34','35')
      AND R.status NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT')

    GROUP BY
        B.area_num,
        A.area_desc,
        YEAR(R.date_created)

    ORDER BY
        B.area_num ASC
";
$statement = $this->connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = array();

foreach ($result as $row) {
    $data[] = array(
        'area_num'            => $row['area_num'],
        'area_desc'           => $row['area_desc'],
        'dc'                  => $row['dc'],
        'cntarea'             => (int)$row['cntarea'],
        'assigned'            => (int)$row['assigned'],
        'on_process'          => (int)$row['on_process'],
        'pending'             => (int)$row['pending'],
        'subject_for_closing' => (int)$row['subject_for_closing'],
        'closed'              => (int)$row['closed']
    );
}

return $data;
	}

/**
 * Str grph.
 */
public function str_grph(){
$dept_id = $_POST['dept_id'];
    $query = "
        SELECT 
            C.cat_id,
            C.cat_desc,
            COUNT(DISTINCT R.ticket_no) AS ctn
        FROM reports R
        INNER JOIN tbl_dept D
            ON R.f_deptsel = D.dept_id
        INNER JOIN categories C
            ON R.cat_id = C.cat_id
        INNER JOIN tbl_branch B
            ON R.store = B.str_num
        WHERE C.deptsel = '{$dept_id}'
          AND C.cat_id NOT IN ('31')
        GROUP BY 
            C.cat_id,
            C.cat_desc
        ORDER BY C.cat_desc ASC
    ";

    $statement = $this->connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $data = array();

    foreach($result as $row)
    {
        $data[] = array(
            'cat_id'   => $row['cat_id'],   
            'cat_desc' => $row['cat_desc'],
            'ctn'      => $row['ctn']
        );
    }

    return $data;
}

/**
 * Str grphnew.
 */
public function str_grphnew()
{
    $yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

    $area_desc = isset($_POST['area_desc']) ? $_POST['area_desc'] : '';

    $dept_ids = isset($_POST['dept_id']) 
        ? $_POST['dept_id'] 
        : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

    // Clean department IDs
    $dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

    if (empty($dept_ids_array)) {
        $dept_ids_array = range(1, 17);
    }

    $dept_ids_clean = implode(',', $dept_ids_array);

    // Clean area_desc basic protection
    $area_desc_clean = addslashes($area_desc);

    if ($area_desc == "CENTRAL") {

        $query = "
            SELECT
                R.store AS store,
                YEAR(R.date_created) AS dc,
                D.dept_desc AS str_code,
                D.dept_desc AS str_dept,

                COUNT(R.ticket_no) AS cnt_ttl,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'ASSIGNED' 
                    THEN 1 ELSE 0 
                END) AS assigned,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'ON PROCESS' 
                    THEN 1 ELSE 0 
                END) AS on_process,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'PENDING' 
                    THEN 1 ELSE 0 
                END) AS pending,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'SUBJECT FOR CLOSING' 
                    THEN 1 ELSE 0 
                END) AS subject_for_closing,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'CLOSED' 
                    THEN 1 ELSE 0 
                END) AS closed

            FROM reports R
            INNER JOIN users U 
                ON R.userId = U.id 
                AND R.store = U.str_num
            INNER JOIN tbl_dept D 
                ON D.dept_id = U.dept_id
            INNER JOIN tbl_branch B 
                ON R.store = B.str_num

            WHERE R.store = '201'
              AND YEAR(R.date_created) = {$yr}
              AND R.f_deptsel IN ({$dept_ids_clean})
              AND R.sub_id NOT IN ('15','28','34','35')
              AND R.status NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT')

            GROUP BY 
                D.dept_id,
                D.dept_desc,
                R.store,
                YEAR(R.date_created)

            ORDER BY 
                D.dept_id ASC
        ";

    } else {

        $query = "
            SELECT
                R.store AS store,
                B.str_code AS str_code,
                B.area_num AS area_num,
                A.area_desc AS area_desc,
                YEAR(R.date_created) AS dc,

                COUNT(R.ticket_no) AS cnt_ttl,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'ASSIGNED' 
                    THEN 1 ELSE 0 
                END) AS assigned,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'ON PROCESS' 
                    THEN 1 ELSE 0 
                END) AS on_process,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'PENDING' 
                    THEN 1 ELSE 0 
                END) AS pending,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'SUBJECT FOR CLOSING' 
                    THEN 1 ELSE 0 
                END) AS subject_for_closing,

                SUM(CASE 
                    WHEN UPPER(TRIM(R.status)) = 'CLOSED' 
                    THEN 1 ELSE 0 
                END) AS closed

            FROM reports R
            JOIN tbl_branch B 
                ON R.store = B.str_num
            JOIN tbl_area A 
                ON A.area_num = B.area_num

            WHERE YEAR(R.date_created) = {$yr}
              AND A.area_desc = '{$area_desc_clean}'
              AND R.f_deptsel IN ({$dept_ids_clean})
              AND R.sub_id NOT IN ('15','28','34','35')
              AND R.status NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT')

            GROUP BY 
                R.store,
                B.str_code,
                B.area_num,
                A.area_desc,
                YEAR(R.date_created)

            ORDER BY 
                B.str_code ASC
        ";
    }

    $statement = $this->connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $data = array();

    foreach ($result as $row) {
        $data[] = array(
            'store'               => $row['store'],
            'str_code'            => $row['str_code'],
            'dc'                  => $row['dc'],
            'cnt_ttl'             => (int)$row['cnt_ttl'],
            'assigned'            => (int)$row['assigned'],
            'on_process'          => (int)$row['on_process'],
            'pending'             => (int)$row['pending'],
            'subject_for_closing' => (int)$row['subject_for_closing'],
            'closed'              => (int)$row['closed']
        );
    }

    return $data;
}

/**
 * Admin data table res.
 */
public function admin_data_table_res(){


$yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

$dept_ids = isset($_POST['dept_id']) 
    ? $_POST['dept_id'] 
    : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

// Clean department IDs
$dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

if (empty($dept_ids_array)) {
    $dept_ids_array = range(1, 17);
}

$dept_ids_clean = implode(',', $dept_ids_array);

$query = "
    SELECT *
    FROM vw6foradmin
    WHERE sub_id NOT IN ('15','28','34','35')
      AND status <> 'NEW REPORT'
      AND YEAR(date_created) = {$yr}
      AND f_deptsel IN ({$dept_ids_clean})
      AND (is_transfer = '0' OR is_transfer IS NULL)
";




    $statement = $this->connection->prepare($query);
    $statement->execute([
        ':yr' => $yr
    ]);

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $fetchdata = []; // ✅ init always

    foreach($result as $row){

        // ✅ Support BOTH old (itsup) and new (f_deptsel/dept_desc) view outputs
        $assigned_id   = $row['f_deptsel'] ?? ($row['itsup'] ?? '');
        $assigned_desc = $row['dept_desc'] ?? ($row['it_desc'] ?? '');
        $assigned_sel  = $row['dept_sel'] ?? ($row['it_sel'] ?? ''); // optional if you added something similar

        // ✅ Safe date parsing
        $date_created = !empty($row["date_created"]) ? date('m/d/Y H:i', strtotime($row["date_created"])) : "";
        $date_closed  = (!empty($row["date_closed"]) && strtoupper($row['status']) !== 'OPEN')
                        ? date('m/d/Y H:i', strtotime($row["date_closed"]))
                        : "";

        $date_refNo = !empty($row["date_refNo"]) ? date('m/d/Y H:i', strtotime($row["date_refNo"])) : "";

        $fetchdata[] = array(
            'ticket_no' => $row['ticket_no'] ?? '',
            'store' => $row['store'] ?? '',
            'str_code' => $row['str_code'] ?? '',
            'date_created' => $date_created,

            'subject' => $row['subject'] ?? '',
            'concern' => $row['concern'] ?? '',
            'via' => $row['via'] ?? '',
            'status' => $row['status'] ?? '',
			'contactNumber' => $row['contactNumber'] ?? '',
			'dept_email' => $row['dept_email'] ?? '',
            // ✅ new unified fields (department)
            'f_deptsel' => $assigned_id,
            'dept_desc' => $assigned_desc,
            'dept_sel'  => $row['dept_sel'],

            'cat_id' => $row['cat_id'] ?? '',
            'category' => $row['category'] ?? '',
            'sub_id' => $row['sub_id'] ?? '',
            'sub_category' => $row['sub_category'] ?? '',

            'date_closed' => $date_closed,

            'tdc' => (strtoupper($row['status'] ?? '') === 'OPEN')
                        ? (($row["dtdf"] ?? '') . " Days Unresolved")
                        : ($row['tdc'] ?? ''),

            'crdt' => $row['crdt'] ?? '',
            'dtdf' => $row['dtdf'] ?? '',
            'years' => $row['years'] ?? '',

            'close_by' => $row['close_by'] ?? '',
            'clusers' => $row['clusers'] ?? '',
            'remarks' => $row['remarks'] ?? '',

            'isp_id' => $row['isp_id'] ?? '',
            'isp_shortDesc' => $row['isp_shortDesc'] ?? '',

            'refNo' => $row['refNo'] ?? '',
            'date_refNo' => $date_refNo,

            'msg_cnt' => $row['msg_cnt'] ?? '0',
            'priority_desc' => $row['priority_desc'] ?? '0',
			'contactNumber' => $row['contactNumber'] ?? ''
        );
    }

    return $fetchdata;
}






/**
 * Newreporthist.
 */
public function newreporthist(){

	$query="SELECT
	reports.deptsel AS deptsel,
	reports.ticket_no AS ticket_no,
	reports.date_created AS date_created,
	reports.store AS store,
	tbl_branch.str_code AS str_code,
	reports.concern AS concern,
	reports.service_desc AS service_desc,
	reports.`subject` AS `subject`,
	reports.`status` AS `status`,
	reports.userId AS userId,
	reports.via AS via,
	reports.itsup AS itsup,
	reports.f_deptsel AS f_deptsel,
	it_tech.it_desc AS it_desc,
	reports.cat_id AS cat_id,
	categories.cat_desc AS cat_desc,
	concat_ws( '-', `reports`.`cat_id`, `categories`.`cat_desc` ) AS cat_x,
	reports.sub_id AS sub_id,
	subcat.sub_cat AS sub_cat,
	reports.date_closed AS date_closed,
	reports.remarks AS remarks,
	reports_msgcnt.msg_cnt AS msg_cnt,
	reports_newmsg.nmsg_stat AS nmsg_stat,
	users.fname AS fname,
	users.lstname AS lstname,
	concat_ws( ' ', `users`.`fname`, `users`.`lstname` ) AS full_name,
	tbl_deptsel.dept_desc AS dept_desc,
	GROUP_CONCAT(images.files_name SEPARATOR '|') AS attachment_files
FROM
	(
		(
			(
				(
					(
						(
							( reports JOIN tbl_branch ON ( tbl_branch.str_num = reports.store ) )
							LEFT JOIN it_tech ON ( it_tech.itsup = reports.itsup ) 
						)
						LEFT JOIN categories ON ( categories.cat_id = reports.cat_id ) 
					)
					LEFT JOIN subcat ON ( subcat.sub_id = reports.sub_id ) 
				)
				LEFT JOIN reports_msgcnt ON ( reports_msgcnt.ticket_no = reports.ticket_no ) 
			)
			LEFT JOIN reports_newmsg ON ( reports_newmsg.ticket_no = reports.ticket_no ) 
		)
		LEFT JOIN users ON ( users.id = reports.userId ) 
	)
	LEFT JOIN images ON ( images.ticket_no = reports.ticket_no )
	INNER JOIN tbl_deptsel ON reports.deptsel = tbl_deptsel.dept_id 
WHERE
	reports.`status` = 'NEW REPORT'
GROUP BY
	reports.ticket_no
ORDER BY
	reports.date_created DESC";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	$fetchdata = array();
	foreach ($result as $row) {
		$fetchdata[] = array(
			'ticket_no' => $row["ticket_no"],
			'store' => $row['store'],
			'str_code'=>$row["str_code"],
			'date_created' => date('m/d/Y H:i',strtotime($row["date_created"])), 
			'concern'=> $row["subject"],
			'service_desc' => $row["service_desc"],
			'subject' => $row["concern"],
			'via' => $row["via"],
			'status' => $row["status"],            
			'itsup' => $row["itsup"],
			'f_deptsel' => $row["f_deptsel"],
			'it_desc' => $row["it_desc"],
			'cat_desc' => $row["cat_desc"],
			'sub_cat' => $row["sub_cat"],
			'msg_cnt' => $row["msg_cnt"],
			'full_name' => $row["full_name"],
			'dept_desc' => $row["dept_desc"],
			'attachment_files' => $row["attachment_files"]

			// 'sub_cat' => $row["sub_cat"],
		);
	}	

	$data = array_filter($fetchdata);

		return $data;

}


public function fathist() {
        $query="SELECT 
                ar.ticket_no, 
                b.str_name, 
                CONCAT(u.fname, ' ', u.lstname) AS full_name, 
                ar.ticket_created, 
                ar.item_code,
                ar.description, 
                ar.serial_number, 
                ar.asset_tag_number, 
                ar.purpose_of_request, 
				ar.revised_request, 
				ar.technical_workoutput, 
				ar.is_technical, 
                it.it_desc,
                it.itsup,          
                ar.date_received, 
                ar.created_at,
               itt.it_desc AS noted_by_desc,       
                ar.status          
            FROM asset_requests ar
            LEFT JOIN it_tech it ON ar.item_received_by = it.itsup
            LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
            LEFT JOIN users u ON r.userId = u.id
			LEFT JOIN it_tech itt ON ar.noted_by = itt.itsup
            LEFT JOIN tbl_branch b ON r.store = b.str_num  WHERE ar.status IN ('VALIDATED')   ORDER BY ar.created_at ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $fetchdata = array();
        foreach ($result as $row) {
            $fetchdata[] = array(
                'ticket_no' => $row["ticket_no"],
                'str_name' => $row["str_name"],
                'full_name' => $row['full_name'],
                'ticket_created' => $row['ticket_created'],
                'item_code' => $row['item_code'],
                'description'=>$row["description"],
                'serial_number'=> $row["serial_number"],
                'asset_tag_number' => $row["asset_tag_number"],
                'purpose_of_request' => $row["purpose_of_request"],
				'revised_request' => $row["revised_request"],
					'is_technical' => $row["is_technical"],
				'technical_workoutput' => $row["technical_workoutput"],
                'it_desc' => $row["it_desc"],
                'date_received' => $row["date_received"],    
                'noted_by_desc' => $row["noted_by_desc"],  
                'status' => $row["status"]
            );
        }   
        return array_filter($fetchdata);
    }

/**
 * Reassign itsup.
 */
public function reassign_itsup(){
	$qry = $this->connection->prepare("SELECT * FROM tbl_reassigned");
	$qry->execute();
	$res = $qry->fetch(PDO::FETCH_ASSOC);
	// $ticknum= $res['ticket_no'];
	$statement= $this->connection->prepare("
		INSERT INTO tbl_reassigned (ticket_no, itsup, r_remarks, date_rasigned)
		VALUES (:ticket_no, :itsup, :r_remarks, :date_rasigned)
		");
	$result = $statement->execute(
		array(
			':ticket_no' =>  $_POST["ticket_no"],
			':itsup' => $_POST["itsup"],
			':r_remarks' => $_POST["remarks"],
			':date_rasigned' =>date('Y-m-d H:i:s')
		)
	);

}

/**
 * Usermtc table.
 */
public function usermtc_table(){

	$query="SELECT * FROM vw_usrmtc_data";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	$fetchdata = array();
	foreach ($result as $row) {
		$fetchdata[] = array(
			'user_id' => $row["id"],
			'fname' => $row["fname"],
			'lstname' => $row["lstname"],
			'flName' => strtoupper($row["flName"]),
			'username' => $row["email"],
			'password' => $row["password"],
			'role' => ucfirst($row["role"]),
			'dept_id' => $row["dept_id"],
			'dept_desc' => $row["dept_desc"],
			'str_num' => $row["str_num"],
			'str_code' => $row["str_code"],
			'usr_stat' => $row["usr_stat"]
			// 'img_name' => $row["img_name"],

		);
	}	

	$data = array_filter($fetchdata);
		// echo json_encode($data);
		return $data;

}

/**
 * Store dtable.
 */
public function store_dtable(){

	$query="SELECT
	tbl_branch.str_id, 
	tbl_branch.str_num, 
	tbl_branch.str_code, 
	tbl_branch.area_num, 
	tbl_branch.str_name, 
	tbl_branch.str_adrs, 
	tbl_branch.str_contact, 
	tbl_branch.str_add, 
	tbl_branch.itsup, 
	tbl_clusers.it_desc, 
	tbl_branch.AM, 
	users.fname, 
	users.lstname
FROM
	tbl_branch
	INNER JOIN
	tbl_clusers
	ON 
		tbl_branch.itsup = tbl_clusers.itsup
	INNER JOIN
	users
	ON 
		tbl_branch.AM = users.id";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	$fetchdata = array();
	foreach ($result as $row) {
		$fetchdata[] = array(
			'str_id' => $row["str_id"],
			'str_num' => $row["str_num"],
			'str_code' => $row["str_code"],
			'area_num' => $row["area_num"],
			'str_name' => $row["str_name"],
			'str_adrs' => $row["str_adrs"],
			'str_contact' => $row["str_contact"],
			'str_status' => $row["str_add"],
			'itsup' => $row["itsup"],
			'it_desc' => $row["it_desc"],
			'AMsup' => $row["AM"],
			'AMdesc' => $row["fname"].' '.$row["lstname"]

		);
	}	

	$data = array_filter($fetchdata);
		// echo json_encode($data);
		return $data;

}

/**
 * Changepass.
 */
public function changepass(){
		$query = "SELECT id, email, password FROM users";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();
		$fetchdata = array();
		foreach ($result as $row) {
			$fetchdata[] = array(
				'user_id' => $row["id"],
			    'username' => $row["email"],
			    'password' => $row["password"]

		);
	}	
	$data = array_filter($fetchdata);
		// echo json_encode($data);
		return $data;
		
}
 public function fareportsthist() {
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';
    $status = $_POST['status'] ?? ''; 

    $where = " WHERE 1=1 ";
    $params = [];

    if (!empty($month)) {
        $where .= " AND MONTH(...) = :month "; 
        $params[':month'] = $month;
    }
    if (!empty($year)) {
        $where .= " AND YEAR(...) = :year "; 
        $params[':year'] = $year;
    }
    // ADD STATUS FILTER
    if (!empty($status)) {
        $where .= " AND ar.status = :status ";
        $params[':status'] = $status;
    }

    // Get counts for Metric Cards
    $metricQuery = "SELECT status, COUNT(*) as count 
                    FROM asset_requests ar 
                    $where 
                    GROUP BY status";
    $mStmt = $this->connection->prepare($metricQuery);
    $mStmt->execute($params); 
    $metrics = $mStmt->fetchAll(PDO::FETCH_ASSOC);

    // Primary data selection query
    $query = "SELECT 
                ar.ticket_no, 
                b.str_name, 
                CONCAT(u.fname, ' ', u.lstname) AS full_name, 
                ar.ticket_created, 
                ar.item_code,
                ar.description, 
                ar.serial_number, 
                ar.asset_tag_number, 
                ar.purpose_of_request, 
				ar.revised_request,
				ar.is_technical,
					ar.technical_workoutput,
                it.it_desc,
                it.itsup,           
                ar.date_received, 
                ar.created_at,
                itt.it_desc AS noted_by_desc,        
                ar.status           
            FROM asset_requests ar
            LEFT JOIN it_tech it ON ar.item_received_by = it.itsup
            LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
            LEFT JOIN users u ON r.userId = u.id
            LEFT JOIN tbl_branch b ON r.store = b.str_num 
            LEFT JOIN it_tech itt ON ar.noted_by = itt.itsup
            $where 
            ORDER BY COALESCE(NULLIF(ar.created_at, ''), ar.ticket_created) DESC";

    $statement = $this->connection->prepare($query);
    $statement->execute($params); 
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    $fetchdata = array();
    foreach ($result as $row) {
        $fetchdata[] = array(
            'ticket_no'          => $row["ticket_no"],
            'str_name'           => $row["str_name"],
            'full_name'          => $row['full_name'],
            'ticket_created'     => $row['ticket_created'],
            'item_code'          => $row['item_code'],
            'description'        => $row["description"],
            'serial_number'      => $row["serial_number"],
            'asset_tag_number'   => $row["asset_tag_number"],
			  'is_technical'   => $row["is_technical"],
            'purpose_of_request' => $row["purpose_of_request"],
			 'technical_workoutput' => $row["technical_workoutput"],
			 'revised_request' => $row["revised_request"],
            'it_desc'            => $row["it_desc"],
            'date_received'      => $row["date_received"],    
            'noted_by_desc'      => $row["noted_by_desc"],  
            'status'             => $row["status"]
        );
    } 

    return [
        'table_data' => $fetchdata,
        'metrics'    => $metrics
    ];
}
/**
 * Notif techsupp.
 */
public function notif_techsupp(){

	$query="SELECT
	tbl_notif.ticket_no, 
	tbl_notif.store, 
	tbl_notif.itsup, 
	tbl_notif.notif_data, 
	tbl_notif.notif_date, 
	tbl_notif.notif_val, 
	tbl_notif.assigned_by
FROM
	tbl_notif
	LEFT JOIN
	reports
	ON 
		tbl_notif.ticket_no = reports.ticket_no
WHERE
	(tbl_notif.notif_val IN ('1','2','3') AND reports.status NOT IN ('ON PROCESS','Assigned'))
	OR
	(tbl_notif.notif_val = '7' AND reports.f_deptsel IS NOT NULL);
ORDER BY
	notif_date ASC";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	// $fetchdata = array();
	foreach ($result as $row) {
		$fetchdata[] = array(
			'notif_data' => $row["notif_data"],
			'ticket_no' => $row["ticket_no"],
			'notif_val' => $row["notif_val"],
			'notif_date' => $row["notif_date"]

		);
	}	

	$data = array_filter($fetchdata);

		return $data;
	// echo json_encode($data);

}


	/**
	 * Admin get reports.
	 */
	public function admin_get_reports(){
	$slct_area = $_POST['slct_area'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$query="
	Select * from vw_getrep WHERE area_num IN ({$slct_area}) AND date_created BETWEEN '{$start_date}' AND '{$end_date}' ORDER by area_num ASC ";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	// $fetchdata[] = array();

		foreach($result as $row)
				{
				$fetchdata[] = array(
					'ticket_no' => $row['ticket_no'],
					'store' => $row['store'],
					'str_code' => $row['str_code'],
					'area_num' => $row['area_num'],
					'area_desc' => $row['area_desc'],
					'date_created' => date('m/d/Y H:i',strtotime($row["date_created"])),
					'subject' => $row['subject'],
					'via' => $row['via'],
					'status' => $row['status'],
					'itsup' => $row['itsup'],
					'it_desc' => $row['it_desc'],
					'cat_id' => $row['cat_id'],
					'cat_desc' => $row['cat_desc'],
					'sub_id' => $row['sub_id'],
					'sub_cat' => $row['sub_cat'],
					// 'date_closed' => $row['date_closed'],
					'date_closed' => ($row['status'] == 'OPEN') ? $row["dtdf"]." "."Days Unresolved": date('m/d/Y H:i',strtotime($row["date_closed"])),
					'date_completion' => $row['tdc'],
					'remarks' => $row['remarks']

				);

				}
			$data = array_filter($fetchdata);
				return $data;
				// echo json_encode($data);

	}	

	/**
	 * Admin get reports bycat.
	 */
	public function admin_get_reports_bycat(){
	$slct_cat = $_POST['slct_cat'];
	$slct_stat = $_POST['slct_stat'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$def_time = "00:00:00";
	$cur_time = date("H:i:s");

	// $query="
	// Select * from vw6 WHERE
	// vw6.sub_id NOT IN ('15','28','34','35') AND cat_id IN ({$slct_cat}) AND `status` IN ('{$slct_stat}') AND date_created BETWEEN '{$start_date}' AND '{$end_date}' AND status <> 'WAITING FOR IT HELPDESK RESPONSE'";
	$query="
	Select * from vw6 WHERE
	vw6.sub_id NOT IN ('15','28','34','35') AND cat_id IN ({$slct_cat}) AND `status` IN ('{$slct_stat}') AND date_created BETWEEN '{$start_date}. .{$def_time}' AND '{$end_date}. .{$cur_time}' AND status <> 'WAITING FOR IT HELPDESK RESPONSE'";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	// $fetchdata[] = array();

		foreach($result as $row)
				{
				$fetchdata[] = array(
					'ticket_no' => $row['ticket_no'],
					'store' => $row['store'],
					'str_code' => $row['str_code'],
					'date_created' => date('m/d/Y H:i',strtotime($row["date_created"])),
					'subject' => $row['subject'],
					'concern' => $row['concern'],
					'via' => $row['via'],
					'status' => $row['status'],
					'itsup' => $row['itsup'],
					'it_desc' => $row['it_desc'],
					'it_sel' => $row['it_sel'],
					'cat_id' => $row['cat_id'],
					'category' => $row['category'],
					'sub_id' => $row['sub_id'],
					'sub_category' => $row['sub_category'],
					'date_closed' => ($row['status'] == 'OPEN' || 'OPEN WITH FIX ASSET') ? $row["dtdf"]." "."Days Unresolved": date('m/d/Y H:i',strtotime($row["date_closed"])),
					'tdc' => $row['tdc'],
					'crdt' => $row['crdt'],
					'dtdf' => $row['dtdf'],
					'years' => $row['years'],
					'close_by' => $row['close_by'],
					'clusers' => $row['clusers'],
					'remarks' => $row['remarks'],
					'isp_id' => $row['isp_id'],
					'isp_shortDesc' => $row['isp_shortDesc'],
					'refNo' => $row['refNo'],
					'date_refNo' => date('m/d/Y H:i',strtotime($row["date_refNo"]))

				);

				}
			$data = array_filter($fetchdata);
				return $data;
				// echo json_encode($data);

	}	

	/**
	 * Genrep statpie.
	 */
	public function genrep_statpie(){

	$slct_area = $_POST['slct_area'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
		$query='';
		// $output= array();
		$query="SELECT
		reports.`status` AS stat_name,
		Count(reports.`status`) AS points,
		YEAR(date_created) AS yr,
		tbl_branch.area_num
		FROM
		reports
		LEFT JOIN tbl_branch ON tbl_branch.str_num = reports.store
		where `reports`.`sub_id` NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT') AND date_created BETWEEN '{$start_date}' AND '{$end_date}' AND area_num IN ({$slct_area})
		GROUP BY `status`";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

		foreach ($result as $row) {
		$data[] = array(
		'stat_name' => $row["stat_name"], 
		'points' => $row["points"]

			);
		}
        return $data;
        // echo json_encode($data);
	}

	/**
	 * Genrep bycat pie.
	 */
	public function genrep_bycat_pie(){

	$slct_cat = $_POST['slct_cat'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
		// $query='';
		$query="
		SELECT
		reports.cat_id AS cat_id,
		reports.`status` AS stat_name,
		Count(reports.`status`) AS points,
		YEAR(date_created) AS yr
		FROM
		reports
		where `reports`.`sub_id` NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT') AND date_created  BETWEEN '{$start_date}' AND '{$end_date}' AND cat_id IN ({$slct_cat})
		GROUP BY `status`, cat_id";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

		foreach ($result as $row) {
		$data[] = array(
		'stat_name' => $row["stat_name"], 
		'points' => $row["points"]

			);
		}
        return $data;
        // echo json_encode($data);
	}


	/**
	 * Gencatpie.
	 */
	public function gencatpie(){
		$slct_area = $_POST['slct_area'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$query= "
		SELECT cat_desc,clr,cat_id, count(*) as ctn,date_created, area_num
		FROM vw_gencatsubpie
		WHERE date_created BETWEEN '{$start_date}' AND '{$end_date}' AND area_num IN ({$slct_area})
		GROUP BY cat_id ORDER BY ctn DESC";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach ($result as $row) {
		$data[] = array(
		'type' => $row["cat_desc"], 
		'percent' => $row["ctn"],
		'color' => $row["clr"],
		'subs' => $this->gensubpie($row['cat_id'])

			);
		}
		return($data);
		// echo json_encode($data);
	}

	/**
	 * Gensubpie.
	 */
	public function gensubpie($catid){
		$slct_area = $_POST['slct_area'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$query= "
		SELECT sub_cat, count(*) as sctn, date_created, area_num FROM vw_gencatsubpie WHERE cat_id='".$catid."' AND area_num IN ({$slct_area}) AND date_created BETWEEN '{$start_date}' AND '{$end_date}' GROUP BY sub_cat ORDER BY cat_desc ASC";

		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array('type' => $row['sub_cat'],'percent' => $row['sctn']);

		}
		return $data;
	}

	
	/**
	 * Genrepsubpie.
	 */
	public function genrepsubpie($catid){
		$slct_area = $_POST['slct_area'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$query= "
		SELECT sub_cat, count(*) as sctn, date_created, area_num FROM vw_gencatsubpie WHERE cat_id='".$catid."' AND area_num IN ({$slct_area}) AND date_created BETWEEN '{$start_date}' AND '{$end_date}' GROUP BY sub_cat ORDER BY cat_desc ASC";

		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array('type' => $row['sub_cat'],'percent' => $row['sctn']);

		}
		return $data;
	}


	/**
	 * Genrep bycat catpie.
	 */
	public function genrep_bycat_catpie(){
		$slct_stat = $_POST['slct_stat'];
		$slct_cat = $_POST['slct_cat'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$query= "
		SELECT cat_desc,clr,cat_id, count(*) as ctn,date_created, area_num
		FROM vw_gencatsubpie
		WHERE date_created BETWEEN '{$start_date}' AND '{$end_date}' AND cat_id IN ({$slct_cat}) AND `status` IN ('{$slct_stat}')
		GROUP BY cat_id ORDER BY ctn DESC";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach ($result as $row) {
		$data[] = array(
		'type' => $row["cat_desc"], 
		'percent' => $row["ctn"],
		'color' => $row["clr"],
		'subs' => $this->genrep_bycat_subpie($row['cat_id'])

			);
		}
		return($data);
		// echo json_encode($data);
	}

		/**
		 * Genrep bycat subpie.
		 */
		public function genrep_bycat_subpie($catid){
		$slct_stat = $_POST['slct_stat'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$query= "
		SELECT sub_cat, count(*) as sctn, date_created, area_num FROM vw_gencatsubpie WHERE cat_id='".$catid."' AND date_created BETWEEN '{$start_date}' AND '{$end_date}' AND `status` IN ('{$slct_stat}')  GROUP BY sub_cat ORDER BY cat_desc ASC";

		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array('type' => $row['sub_cat'],'percent' => $row['sctn']);

		}
		return $data;
	}

	/**
	 * Genstr grph.
	 */
	public function genstr_grph(){
		$slct_area = $_POST['slct_area'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$query="
				SELECT
				reports.store AS store,
				tbl_branch.str_code AS str_dept,
				tbl_branch.area_num AS area_num,
				reports.date_created AS dc,
				Count(reports.date_created) AS cnt_ttl
				FROM
				((reports
				JOIN tbl_branch ON (reports.store = tbl_branch.str_num)))
				WHERE
				reports.date_created BETWEEN '{$start_date}' AND '{$end_date}' AND area_num IN ({$slct_area})
				GROUP BY
				reports.store,
				tbl_branch.str_code
				ORDER BY area_num ASC
";

		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array(
			'str_code' => $row['str_dept'],
			'cnt_ttl' => $row['cnt_ttl']

		);

		}
		return $data;

	}
		/**
		 * Dtbl itsup.
		 */
		public function dtbl_itsup(){
// fix update please main obj
	$query="
	Select * from vw6 WHERE
	vw6.sub_id NOT IN ('15','28','34','35') AND status <> 'NEW REPORT' AND itsup = ".$_POST['itVal']." AND years IN (".$_POST['yrsx1'].") ORDER BY `status` DESC";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	// $fetchdata[] = array();

		foreach($result as $row)
				{
				$fetchdata[] = array(
					'ticket_no' => $row['ticket_no'],
					'store' => $row['store'],
					'str_code' => $row['str_code'],
					'date_created' => date('m/d/Y H:i',strtotime($row["date_created"])),
					'subject' => $row['subject'],
					'concern' => $row['concern'],
					'via' => $row['via'],
					'status' => $row['status'],
					'itsup' => $row['itsup'],
					'it_desc' => $row['it_desc'],
					'it_sel' => $row['it_sel'],
					'cat_id' => $row['cat_id'],
					'category' => $row['category'],
					'sub_id' => $row['sub_id'],
					'sub_category' => $row['sub_category'],
					'date_closed' => ($row['status'] == 'OPEN') ? $row["dtdf"]." "."Days Unresolved": date('m/d/Y H:i',strtotime($row["date_closed"])),
					'tdc' => $row['tdc'],
					'crdt' => $row['crdt'],
					'dtdf' => $row['dtdf'],
					'years' => $row['years'],
					'close_by' => $row['close_by'],
					'clusers' => $row['clusers'],
					'remarks' => $row['remarks'],
					'isp_id' => $row['isp_id'],
					'isp_shortDesc' => $row['isp_shortDesc'],
					'refNo' => $row['refNo'],
					'date_refNo' => date('m/d/Y H:i',strtotime($row["date_refNo"])),
					'years' => $row['years']

				);

				}
			$data = array_filter($fetchdata);
				return $data;

	}

/**
 * Count reassigned.
 */
public function count_reassigned($itsup){
$query="SELECT *, COUNT(itsup) AS cnt_resassgn FROM tbl_reassigned WHERE itsup ='".$itsup."'";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();



				return $result[0];
				// echo json_encode($data);

	}

/**
 * Count sla.
 */
public function count_sla($itsup){
	$query="SELECT itsup, years,
	COUNT(date_created) as dtotal,
	COUNT(CASE WHEN tdc <= '2' then 1 else NULL end ) as tdccl,
	ROUND(COUNT(CASE WHEN tdc <= '2' then 1 else NULL end ) * 100.0 / COUNT(date_created), 1) AS tclosdif
	FROM vw6
	WHERE itsup = '".$itsup."' and years IN (".$_POST['yr'] .")";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();



				return $result[0];
}

/**
 * Get percentage.
 */
public function get_percentage($total, $number)
{
  if ( $total > 0 ) {
   return round(($number * 100) / $total, 2);
  } else {
    return 0;
  }
}


/**
 * Tbl cat.
 */
public function tbl_cat(){

	$query="SELECT
	reports.ticket_no as ticket, 
	tbl_branch.str_code as store, 
	categories.cat_desc as category, 
	subcat.sub_cat as subcat
FROM
	reports
	INNER JOIN
	tbl_branch
	ON 
		reports.store = tbl_branch.str_num
	INNER JOIN
	categories
	ON 
		reports.cat_id = categories.cat_id
	INNER JOIN
	subcat
	ON 
		reports.sub_id = subcat.sub_id
WHERE
	reports.deptsel = '2' AND
	reports.date_created LIKE '%2024%'
	";
	
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	// $fetchdata[] = array();
	
	foreach($result as $row)
	{
	$fetchdata[] = array(
	'ticket' => $row['ticket'],
	'store' => $row['store'],
	'category' => $row['category'],
	'subcat' => $row['subcat'],
	
	
	
	
	);
	
	}
	$data = array_filter($fetchdata);
	// echo json_encode($data);
	return $data;
	
	}




		/**
		 * Polled store.
		 */
		public function polled_store(){
			$start_date = $_POST['fromPolled'];
			$end_date = $_POST['toPolled'];
		
			$query = "SELECT
				tbl_branch.AM, 
				COUNT(tbl_notpolledstr.`str_code`) AS cntstore, 
				tbl_notpolledstr.str_no, 
				tbl_notpolledstr.str_code, 
				tbl_notpolledstr.polling_date, 
				tbl_notpolledstr.generate_date
			FROM
				tbl_notpolledstr
				INNER JOIN tbl_branch
					ON tbl_notpolledstr.str_code = tbl_branch.str_code
			WHERE
				tbl_notpolledstr.polling_date BETWEEN ? AND ?
			GROUP BY
				tbl_notpolledstr.str_code";
				
			$statement = $this->connection->prepare($query);
			$statement->execute([$start_date, $end_date]);
			$result = $statement->fetchAll();
			
			$data = array(); // Remove the [] which creates an empty element
			
			foreach($result as $row) {
				$data[] = array(
					'str_code' => $row['str_code'],
					'cntstore' => (int)$row['cntstore'], // Ensure numeric value
					'AM' => $row['AM'] // Add AM if needed in tooltip
				);
			}
			
			return $data;
		}


/**
 * Trans tbl.
 */
public function trans_tbl(){

	$query="SELECT
	reports.deptsel AS deptsel,
	reports.ticket_no AS ticket_no,
	reports.date_created AS date_created,
	reports.store AS store,
	tbl_branch.str_code AS str_code,
	reports.concern AS concern,
	reports.service_desc AS service_desc,
	reports.`subject` AS `subject`,
	reports.`status` AS `status`,
	reports.userId AS userId,
	reports.via AS via,
	reports.itsup AS itsup,
	it_tech.it_desc AS it_desc,
	reports.cat_id AS cat_id,
	categories.cat_desc AS cat_desc,
	concat_ws( '-', `reports`.`cat_id`, `categories`.`cat_desc` ) AS cat_x,
	reports.sub_id AS sub_id,
	subcat.sub_cat AS sub_cat,
	reports.date_closed AS date_closed,
	reports.remarks AS remarks,
	reports_msgcnt.msg_cnt AS msg_cnt,
	reports_newmsg.nmsg_stat AS nmsg_stat,
	users.fname AS fname,
	users.lstname AS lstname,
	concat_ws( ' ', `users`.`fname`, `users`.`lstname` ) AS full_name,
	tbl_deptsel.dept_desc AS dept_desc,
	reports.is_transfer
FROM
	(
		(
			(
				(
					(
						(
							( reports JOIN tbl_branch ON ( tbl_branch.str_num = reports.store ) )
							LEFT JOIN it_tech ON ( it_tech.itsup = reports.itsup ) 
						)
						LEFT JOIN categories ON ( categories.cat_id = reports.cat_id ) 
					)
					LEFT JOIN subcat ON ( subcat.sub_id = reports.sub_id ) 
				)
				LEFT JOIN reports_msgcnt ON ( reports_msgcnt.ticket_no = reports.ticket_no ) 
			)
			LEFT JOIN reports_newmsg ON ( reports_newmsg.ticket_no = reports.ticket_no ) 
		)
		LEFT JOIN users ON ( users.id = reports.userId ) 
	)
	INNER JOIN tbl_deptsel ON reports.deptsel = tbl_deptsel.dept_id 
WHERE
	reports.is_transfer = '1' 
ORDER BY
	reports.date_created DESC";

	// '1' means i ca-call niya muna sa query yung transfer ticket
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	$fetchdata = array();
	foreach ($result as $row) {
		$fetchdata[] = array(
			'ticket_no' => $row["ticket_no"],
			'store' => $row['store'],
			'str_code'=>$row["str_code"],
			'date_created' => date('m/d/Y H:i',strtotime($row["date_created"])), 
			'concern'=> $row["subject"],
			'service_desc' => $row["service_desc"],
			'subject' => $row["concern"],
			'via' => $row["via"],
			'status' => $row["status"],            
			'itsup' => $row["itsup"],
			'it_desc' => $row["it_desc"],
			'cat_desc' => $row["cat_desc"],
			'sub_cat' => $row["sub_cat"],
			'msg_cnt' => $row["msg_cnt"],
			'full_name' => $row["full_name"],
			'dept_desc' => $row["dept_desc"]

			// 'sub_cat' => $row["sub_cat"],
		);
	}	

	$data = array_filter($fetchdata);

		return $data;

}

/**
 * Dept ticket datatable.
 */
public function dept_ticket_datatable($dept_id) { // for reference from now on in reporting

    $query = "SELECT
            ticket_no, 
            str_code, 
            date_created, 
            concern, 
            via, 
            `status`, 
            dtdf,
            f_deptsel, 
            category, 
            sub_category, 
            date_closed, 
            remarks
        FROM
            vw6foradmin
        WHERE
            f_deptsel = :dept_id
        ORDER BY
            date_created DESC";

    $statement = $this->connection->prepare($query);
    $statement->bindParam(':dept_id', $dept_id, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $fetchdata = array();

    foreach ($result as $row) {
        $fetchdata[] = array(
            'ticket_no'    => $row['ticket_no'],
            'str_code'     => $row['str_code'],
            'date_created' => !empty($row['date_created']) ? date('m/d/Y H:i', strtotime($row['date_created'])) : '',
            'concern'      => $row['concern'],
            'via'          => $row['via'],
            'status'       => $row['status'],
            'dtdf'         => $row['dtdf'],
            'f_deptsel'    => $row['f_deptsel'],
            'category'     => $row['category'],
            'sub_category' => $row['sub_category'],
            'date_closed'  => !empty($row['date_closed']) ? date('m/d/Y H:i', strtotime($row['date_closed'])) : '',
            'remarks'      => $row['remarks']
        );
    }

    return $fetchdata;
}

/**
 * Category status grph.
 */
public function category_status_grph()
{
    $yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $dept_ids = isset($_POST['dept_id'])
        ? $_POST['dept_id']
        : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

    $dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

    if (empty($dept_ids_array)) {
        $dept_ids_array = range(1, 17);
    }

    $dept_ids_clean = implode(',', $dept_ids_array);
    $status_clean = addslashes($status);

    $query = "
        SELECT
            C.cat_id AS cat_id,
            C.cat_desc AS cat_desc,
            COUNT(R.ticket_no) AS points,
            C.clr AS clr
        FROM reports R
        INNER JOIN categories C 
            ON C.cat_id = R.cat_id
        INNER JOIN subcat SC 
            ON SC.sub_id = R.sub_id 
            AND SC.cat_id = R.cat_id
        INNER JOIN tbl_branch B 
            ON B.str_num = R.store
        WHERE R.f_deptsel IN ({$dept_ids_clean})
          AND R.status = '{$status_clean}'
          AND YEAR(R.date_created) = {$yr}
          AND R.sub_id NOT IN ('15','28','34','35')
        GROUP BY
            C.cat_id,
            C.cat_desc,
            C.clr
        ORDER BY
              COUNT(R.ticket_no) DESC
    ";
$statement = $this->connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = array();

foreach ($result as $row) {
    $data[] = array(
        'cat_id'   => $row["cat_id"],
        'cat_desc' => $row["cat_desc"],
        'points'   => $row["points"],
        'clr'      => $row["clr"]
    );
}

return $data;
}



// public function category_all_grph()
// {
//     $yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

//     $dept_ids = isset($_POST['dept_id'])
//         ? $_POST['dept_id']
//         : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

//     $dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

//     if (empty($dept_ids_array)) {
//         $dept_ids_array = range(1, 17);
//     }

//     $dept_ids_clean = implode(',', $dept_ids_array);

//     $query = "
//         SELECT
//             C.cat_id AS cat_id,
//             C.cat_desc AS cat_desc,
//             COUNT(R.ticket_no) AS points,
//             C.clr AS clr
//         FROM reports R
//         INNER JOIN categories C 
//             ON C.cat_id = R.cat_id
//         INNER JOIN subcat SC 
//             ON SC.sub_id = R.sub_id 
//             AND SC.cat_id = R.cat_id
//         INNER JOIN tbl_branch B 
//             ON B.str_num = R.store
//         WHERE C.cat_desc <> 'GENERAL'
//           AND YEAR(R.date_created) = $yr
//           AND R.f_deptsel IN ($dept_ids_clean)
//           AND R.sub_id NOT IN ('15','28','34','35')
//           AND R.status NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT')
//         GROUP BY
//             C.cat_id,
//             C.cat_desc,
//             C.clr
//         ORDER BY
//               COUNT(R.ticket_no) DESC
//     ";

//     $statement = $this->connection->prepare($query);
//     $statement->execute();
//     $result = $statement->fetchAll(PDO::FETCH_ASSOC);

//     $data = array();

//     foreach ($result as $row) {
//         $data[] = array(
//             'cat_id'   => $row["cat_id"],
//             'cat_desc' => $row["cat_desc"],
//             'points'   => $row["points"],
//             'clr'      => $row["clr"]
//         );
//     }

//     return $data;
// }

public function category_ticket_dt()
{
    $yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $cat_desc = isset($_POST['cat_desc']) ? trim($_POST['cat_desc']) : '';

    $dept_ids = isset($_POST['dept_id'])
        ? $_POST['dept_id']
        : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

    $dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

    if (empty($dept_ids_array)) {
        $dept_ids_array = range(1, 17);
    }

    $dept_ids_clean = implode(',', $dept_ids_array);

    $query = "
        SELECT
          ticket_no, 
            str_code, 
            date_created, 
            concern, 
            via, 
            `status`, 
            dtdf,
            f_deptsel,
			dept_desc,
            category, 
            sub_category, 
            date_closed, 
            remarks
        FROM vw6foradmin
        WHERE f_deptsel IN ($dept_ids_clean)
          AND YEAR(date_created) = :yr
          AND UPPER(TRIM(`status`)) = UPPER(TRIM(:status))
          AND category = :cat_desc
          AND sub_id NOT IN ('15','28','34','35')
          AND status <> 'NEW REPORT'
        ORDER BY date_created DESC
    ";

    $statement = $this->connection->prepare($query);
    $statement->bindValue(':yr', $yr, PDO::PARAM_INT);
    $statement->bindValue(':status', $status, PDO::PARAM_STR);
    $statement->bindValue(':cat_desc', $cat_desc, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $data = array();

    foreach ($result as $row) {
        $data[] = array(
            'ticket_no'    => $row['ticket_no'],
            'str_code'     => $row['str_code'],
            'date_created' => $row['date_created'],
            'concern'      => $row['concern'],
            'via'          => $row['via'],
            'status'       => $row['status'],
            'dtdf'         => $row['dtdf'],
            'f_deptsel'    => $row['f_deptsel'],
            'dept_desc'    => $row['dept_desc'],
            'category'     => $row['category'],
            'sub_category' => $row['sub_category'],
            'date_closed'  => $row['date_closed'],
            'remarks'      => $row['remarks']
        );
    }

    return $data;
}


/**
 * Store ticket dt.
 */
public function store_ticket_dt()
{
    $yr = isset($_POST['yr']) ? intval($_POST['yr']) : date('Y');

    $store = isset($_POST['store']) ? trim($_POST['store']) : '';

    $dept_ids = isset($_POST['dept_id'])
        ? $_POST['dept_id']
        : '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';

    $dept_ids_array = array_filter(array_map('intval', explode(',', $dept_ids)));

    if (empty($dept_ids_array)) {
        $dept_ids_array = range(1, 17);
    }

    $dept_ids_clean = implode(',', $dept_ids_array);

    $query = "
        SELECT
            ticket_no, 
            str_code, 
            date_created, 
            concern, 
            via, 
            `status`, 
            dtdf,
            f_deptsel,
			  dept_desc,
            category, 
            sub_category, 
            date_closed, 
            remarks
        FROM vw6foradmin
        WHERE store = :store
          AND f_deptsel IN ($dept_ids_clean)
          AND YEAR(date_created) = :yr
          AND sub_id NOT IN ('15','28','34','35')
          AND status <> 'NEW REPORT'
        ORDER BY date_created DESC
    ";

    $statement = $this->connection->prepare($query);
    $statement->bindValue(':store', $store, PDO::PARAM_STR);
    $statement->bindValue(':yr', $yr, PDO::PARAM_INT);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $data = array();

    foreach ($result as $row) {
        $data[] = array(
            'ticket_no'    => $row['ticket_no'],
            'str_code'     => $row['str_code'],
            'date_created' => $row['date_created'],
            'concern'      => $row['concern'],
            'via'          => $row['via'],
            'status'       => $row['status'],
            'dtdf'         => $row['dtdf'],
            'f_deptsel'    => $row['f_deptsel'],
			 'dept_desc'    => $row['dept_desc'],
            'category'     => $row['category'],
            'sub_category' => $row['sub_category'],
            'date_closed'  => $row['date_closed'],
            'remarks'      => $row['remarks']
        );
    }

    return $data;
}





} // dbconfig end bracket

// $fn = new dbconfig();	
// $fn->count_reassigned();

?>
