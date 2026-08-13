<?php
//admin side functions
include '../../connection/db.php';
date_default_timezone_set("Asia/Manila");
/**
 * 
 */
class dbconfig extends dbconn
{
public function fetch_cards_result()
	{
		$output = array();
        $years = isset($_POST['yr']) ? preg_replace('/[^0-9,]/', '', $_POST['yr']) : '';
        if ($years === '') {
            $years = date('Y');
        }

        $yearList = array_values(array_filter(array_map('trim', explode(',', $years)), function ($year) {
            return $year !== '';
        }));

        if (empty($yearList)) {
            $yearList = array(date('Y'));
        }

        $placeholders = implode(',', array_fill(0, count($yearList), '?'));

        $query = "SELECT
                    COUNT(*) AS t_all,
                    SUM(CASE WHEN reports.`status` = 'ON PROCESS' THEN 1 ELSE 0 END) AS t_open,
                    SUM(CASE WHEN reports.`status` = 'PENDING' THEN 1 ELSE 0 END) AS t_owfa,
                    SUM(CASE WHEN reports.`status` = 'CLOSED' THEN 1 ELSE 0 END) AS t_close,
                    SUM(CASE WHEN reports.`status` = 'SUBJECT FOR CLOSING' THEN 1 ELSE 0 END) AS t_day
                FROM reports
                WHERE reports.sub_id NOT IN ('15','28','34','35')
                  AND reports.`status` NOT IN ('WAITING FOR IT HELDESK RESPONSE','NEW REPORT')
                  AND reports.f_deptsel = '1' AND itsup = '{$_SESSION['tech_id']}'
                  AND YEAR(reports.date_created) IN ($placeholders)";

        $statement = $this->connection->prepare($query);
        $statement->execute($yearList);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $output[] = array(
            'total_res' => (int) ($row['t_all'] ?? 0),
            'open_res' => (int) ($row['t_open'] ?? 0),
            'owfa_res' => (int) ($row['t_owfa'] ?? 0),
            'cls_res' => (int) ($row['t_close'] ?? 0),
            't_res' => (int) ($row['t_day'] ?? 0)
        );

		return $output;

	}

	/**
	 * Overallpie res.
	 */
	public function overallpie_res(){

		$query='';
		// $output= array();
		$query="SELECT `status` as stat_name, COUNT(`status`) as points, YEAR(date_created) as yr from reports where `reports`.`sub_id` NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT') AND YEAR(date_created) = '".$_POST['yr'] ."'   GROUP BY `status`";

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

	/**
	 * Bargrph tech res.
	 */
	public function bargrph_tech_res(){
		$query='';
		// $output= array();
		$query="SELECT
				reports.itsup,
				it_tech.f_name AS it_name,
				Count(reports.itsup) AS total,
				reports.`status`,
				Count( CASE reports.`status` when 'CLOSED' then 1 else null end) as completed
				FROM
				it_tech
				LEFT JOIN reports ON reports.itsup = it_tech.itsup
				WHERE
				reports.sub_id NOT IN (15,28,34,35) AND reports.itsup NOT IN ('8')and
				YEAR(reports.date_created) = '".$_POST['yr'] ."'
				GROUP BY
				reports.itsup,
				YEAR(reports.date_created)
				ORDER BY
				reports.`status` ASC
		";
        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

		foreach ($result as $row) {
		$data[] = array(
		'it_name' => $row["it_name"], 
		'total' => $row["total"],
		'completed' => $row["completed"]

			);
		}
        return $data;
	}

	/**
	 * Linegraph.
	 */
	public function linegraph(){

		$query='';
		// $output= array();
		$query="
		SELECT
		DATE( date_created ) AS DATEPART,
		Count(
		DATE( date_created )) AS total_number
		FROM
		reports
		WHERE
		year(date_created) BETWEEN '".$_POST['yr'] ."' AND '".$_POST['yr'] ."' and reports.sub_id NOT IN ('15','28','34','35')
		GROUP BY
		DATEPART";

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

	/**
	 * Pie.
	 */
	public function pie(){

		$query= "
		SELECT cat_desc,clr,cat_id, count(*) as ctn, date_created
		FROM vwp 
		WHERE date_created = '".$_POST['yr'] ."'
		GROUP BY cat_id ORDER BY cat_desc ASC";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach ($result as $row) {
		$data[] = array(
		'type' => $row["cat_desc"], 
		'percent' => $row["ctn"],
		'color' => $row["clr"],
		'subs' => $this->subs($row['cat_id'])

			);
		}
		return($data);

	}

	/**
	 * Subs.
	 */
	public function subs($id){

		$query= "SELECT sub_cat, count(*) as sctn, date_created FROM vwp WHERE cat_id='".$id."' AND date_created='".$_POST['yr'] ."' GROUP BY sub_cat ORDER BY cat_desc ASC";

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

		$query="
				select `reports`.`store` AS `store`,`tbl_branch`.`str_code` AS `str_code`,`tbl_branch`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,year(`reports`.`date_created`) AS `dc`,count(`reports`.`date_created`) AS `cntarea` from ((`reports` join `tbl_branch` on(`reports`.`store` = `tbl_branch`.`str_num`)) join `tbl_area` on(`tbl_area`.`area_num` = `tbl_branch`.`area_num`)) WHERE YEAR(`reports`.`date_created`) = '".$_POST['yr'] ."' group by `tbl_branch`.`area_num`

		";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array(
			'area_desc' => $row['area_desc'],
			'cntarea' => $row['cntarea'],
			'fyr' => $row['dc']

		);

		}
		return $data;
	}

	/**
	 * Str grph.
	 */
	public function str_grph(){
		$query="

			select `reports`.`store` AS `store`,`tbl_branch`.`str_code` AS `str_code`,`tbl_branch`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,year(`reports`.`date_created`) AS `dc`,count(`reports`.`date_created`) AS `cnt_ttl` from ((`reports` join `tbl_branch` on(`reports`.`store` = `tbl_branch`.`str_num`)) join `tbl_area` on(`tbl_area`.`area_num` = `tbl_branch`.`area_num`)) WHERE YEAR(`reports`.`date_created`) = '".$_POST['yr'] ."' AND area_desc = '".$_POST['area_desc'] ."' group by str_code, area_desc ORDER BY str_code ASC

		";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array(
			'str_code' => $row['str_code'],
			'cnt_ttl' => $row['cnt_ttl']

		);

		}
		return $data;

	}
/**
 * Admin data table res.
 */
public function admin_data_table_res()
{
    $query = "SELECT * FROM vw6
              WHERE vw6.deptsel = '1'
              AND vw6.sub_id NOT IN ('15','28','34','35')
              AND status <> 'NEW REPORT'
              AND itsup = :tech_id";

    $statement = $this->connection->prepare($query);
    $statement->bindParam(':tech_id', $_SESSION['tech_id']);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    $fetchdata = array();

    foreach ($result as $row) {
        $fetchdata[] = array(
            'ticket_no'      => $row['ticket_no'],
            'store'          => $row['store'],
            'str_code'       => $row['str_code'],
            'date_created'   => !empty($row["date_created"]) ? date('m/d/Y H:i', strtotime($row["date_created"])) : '',
            'subject'        => $row['subject'],
            'via'            => $row['via'],
            'status'         => $row['status'],
            'itsup'          => $row['itsup'],
            'it_desc'        => $row['it_desc'],
            'it_sel'         => $row['it_sel'],
            'cat_id'         => $row['cat_id'],
            'category'       => $row['category'],
            'sub_id'         => $row['sub_id'],
            'sub_category'   => $row['sub_category'],
            'date_closed'    => ($row['status'] == 'OPEN')
                                ? $row["dtdf"] . " Days Unresolved"
                                : (!empty($row["date_closed"]) ? date('m/d/Y H:i', strtotime($row["date_closed"])) : ''),
            'tdc'            => $row['tdc'],
            'crdt'           => $row['crdt'],
            'dtdf'           => $row['dtdf'],
            'years'          => $row['years'],
            'close_by'       => $row['close_by'],
            'clusers'        => $row['clusers'],
            'remarks'        => $row['remarks'],
            'isp_id'         => $row['isp_id'],
            'isp_shortDesc'  => $row['isp_shortDesc'],
            'refNo'          => $row['refNo'],
            'date_refNo'     => !empty($row["date_refNo"]) ? date('m/d/Y H:i', strtotime($row["date_refNo"])) : ''
        );
    }

    return $fetchdata;
}
public function admin_data_table_transfer()
{
    $query = "SELECT DISTINCT vw_transfer.*
    FROM vw_transfer
    LEFT JOIN users ON vw_transfer.ursID = users.id
    WHERE vw_transfer.deptsel = '1' 
    AND vw_transfer.f_deptsel NOT IN ('1') 
    AND vw_transfer.status NOT IN ('ATTENDED WITH FIX ASSET','NEW REPORT', 'Assigned', 'ASSIGNED') 
    AND vw_transfer.sub_id NOT IN ('15', '28', '34', '35')";

    $statement = $this->connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
    
    $fetchdata = array();

    foreach ($result as $row) {
        $fetchdata[] = array(
            'ticket_no' => $row['ticket_no'],
            'store' => $row['store'],
            'str_code' => $row['str_code'], 	
            'date_created' => !empty($row["date_created"]) ? date('m/d/Y H:i', strtotime($row["date_created"])) : '',
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
            'date_closed' => ($row['status'] == 'OPEN') ? " " : (!empty($row["date_closed"]) ? date('m/d/Y H:i', strtotime($row["date_closed"])) : ''),
            'tdc' => ($row['status'] == 'OPEN') ? $row["dtdf"] . " Days Unresolved" : $row['tdc'],
            'crdt' => $row['crdt'],
            'dtdf' => $row['dtdf'],
            'years' => $row['years'],
            'close_by' => $row['close_by'],
            'clusers' => $row['clusers'],
            'remarks' => $row['remarks'],
            'isp_id' => $row['isp_id'],
            'isp_shortDesc' => $row['isp_shortDesc'],
            'refNo' => $row['refNo'],
            'date_refNo' => !empty($row["date_refNo"]) ? date('m/d/Y H:i', strtotime($row["date_refNo"])) : '',
            'msg_cnt' => $row['msg_cnt'],
            'is_transfer' => $row['is_transfer'] ?? 0
        );
    }
    return $fetchdata;
}

/**
 * Newreporthist.
 */
public function newreporthist() {
    $query = "
        SELECT 
            ar.ticket_no, 
            b.str_name, 
            CONCAT(u.fname, ' ', u.lstname) AS full_name, 
            ar.ticket_created, 
            ar.item_code,
            ar.description, 
            ar.serial_number, 
            ar.asset_tag_number, 
            ar.purpose_of_request, 
		
            it.it_desc,
            it.itsup,          
            ar.date_received, 
            ar.created_at,
            ar.noted_by, 
            fat.problem_reported,
            fat.verification_findings,
            fat.work_done,
            fat.status_workoutput,
            fat.recommendation,    
            ar.status          
        FROM asset_requests ar
        LEFT JOIN fixed_asset_techoutput fat ON ar.ticket_no = fat.ticket_no
        LEFT JOIN it_tech it ON ar.item_received_by = it.itsup
        LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
        LEFT JOIN users u ON r.userId = u.id
        LEFT JOIN tbl_branch b ON r.store = b.str_num 
        WHERE ar.item_received_by = :tech_id
        ORDER BY ar.created_at ASC
    ";

    $statement = $this->connection->prepare($query);
    
    $statement->execute([':tech_id' => $_SESSION['tech_id']]);
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);

    
    $fetchdata = [];
	
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
			'problem_reported' => $row["problem_reported"],
			'verification_findings' => $row["verification_findings"],
			'work_done' => $row["work_done"],
			'status_workoutput' => $row["status_workoutput"],
			'recommendation' => $row["recommendation"],
			'it_desc' => $row["it_desc"],
			'date_received' => $row["date_received"],    
			'noted_by' => $row["noted_by"],  
			'status' => $row["status"]
		);
	}	


		return $data;

}
public function deptthist() {
    $query = "SELECT
        `reports`.`deptsel` AS `dept_id`,           
        `tbl_dept`.`dept_desc` AS `deptsel`,        
        `reports`.`ticket_no` AS `ticket_no`,
        `reports`.`date_created` AS `date_created`,
        `reports`.`store` AS `store`,
        `tbl_branch`.`str_name` AS `str_name`,
        `reports`.`concern` AS `concern`,
        `reports`.`service_desc` AS `service_desc`,
        `reports`.`subject` AS `subject`,
        `reports`.`status` AS `status`,
        `reports`.`userId` AS `userId`,
        `reports`.`via` AS `via`,
        `reports`.`itsup` AS `itsup`,
        `it_tech`.`it_desc` AS `it_desc`,
        `reports`.`cat_id` AS `cat_id`,            
        `categories`.`cat_desc` AS `cat_desc`,      
        CONCAT_WS('-', `reports`.`cat_id`, `categories`.`cat_desc`) AS `cat_x`,
        `reports`.`sub_id` AS `sub_id`,         
        `subcat`.`sub_cat` AS `sub_cat`,             
        `reports`.`date_closed` AS `date_closed`,
        `reports`.`remarks` AS `remarks`,
        `reports_msgcnt`.`msg_cnt` AS `msg_cnt`,
        `reports_newmsg`.`nmsg_stat` AS `nmsg_stat`,
        `users`.`fname` AS `fname`,
        `users`.`lstname` AS `lstname`,
        CONCAT_WS(' ', `users`.`fname`, `users`.`lstname`) AS `full_name`,
        GROUP_CONCAT(`images`.`files_name` SEPARATOR '|') AS `attachment_files` 
    FROM `reports`
    LEFT JOIN `tbl_branch` ON `tbl_branch`.`str_num` = `reports`.`store`
    LEFT JOIN `it_tech` ON `it_tech`.`itsup` = `reports`.`itsup`
    LEFT JOIN `tbl_dept` ON `tbl_dept`.`dept_id` = `reports`.`deptsel`
    LEFT JOIN `categories` ON `categories`.`cat_id` = `reports`.`cat_id`
    LEFT JOIN `subcat` ON `subcat`.`sub_id` = `reports`.`sub_id`
    LEFT JOIN `reports_msgcnt` ON `reports_msgcnt`.`ticket_no` = `reports`.`ticket_no`
    LEFT JOIN `reports_newmsg` ON `reports_newmsg`.`ticket_no` = `reports`.`ticket_no`
    LEFT JOIN `users` ON `users`.`id` = `reports`.`userId`
    LEFT JOIN `images` ON `images`.`ticket_no` = `reports`.`ticket_no`
    WHERE `reports`.`userId` = :userId
    GROUP BY `reports`.`ticket_no`
    ORDER BY `reports`.`ticket_no` DESC";

    $statement = $this->connection->prepare($query);
    
    $statement->execute([
        ':userId' => $_SESSION['user_id']
    ]);
    
    $result = $statement->fetchAll();
    $fetchdata = array();
    
    foreach ($result as $row) {
        $fetchdata[] = array(
            'ticket_no'        => $row["ticket_no"],
            'store'            => $row["store"],         
            'str_name'         => $row["str_name"],
            'full_name'        => $row['full_name'],
            'date_created'     => $row['date_created'],
            'dept_id'          => $row['dept_id'],       
            'deptsel'          => $row['deptsel'],       
            'concern'          => $row['concern'],
            'service_desc'     => $row['service_desc'],
            'cat_desc'         => $row['cat_desc'],    
            'sub_cat'          => $row['sub_cat'],     
            'it_desc'          => $row['it_desc'],
            'subject'          => $row['subject'],
            'status'           => $row['status'],
            'cat_x'            => $row['cat_x'],
            'via'              => $row['via'],           
            'itsup'            => $row['itsup'],         
            'cat_id'           => $row['cat_id'],       
            'sub_id'           => $row['sub_id'],       
            'date_closed'      => $row['date_closed'],   
            'remarks'          => $row['remarks'],       
            'msg_cnt'          => $row['msg_cnt'],       
            'nmsg_stat'        => $row['nmsg_stat'],     
            'attachment_files' => $row['attachment_files']
        );
    }   
    
    return $fetchdata;
}

public function notif_techsupp(){
  
    $query = "SELECT
        tbl_notif.ticket_no, 
        tbl_notif.store, 
        tbl_notif.notif_data, 
        tbl_notif.notif_date, 
        tbl_notif.notif_val, 
        reports.status AS status,
        tbl_notif.assigned_by
    FROM
        tbl_notif
    LEFT JOIN
        reports ON tbl_notif.ticket_no = reports.ticket_no 
    WHERE 
        reports.itsup = :tech_id 
        AND (
            (tbl_notif.notif_val IN ('1', '2') AND reports.f_deptsel = 1)
            OR 
            (tbl_notif.notif_val IN ('9','10') AND reports.f_deptsel IS NOT NULL)
        )
    ORDER BY 
        tbl_notif.notif_date DESC";

    $statement = $this->connection->prepare($query);
    
    $statement->execute([
        ':tech_id' => $_SESSION['tech_id']
    ]);
    
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $fetchdata = array();
    
    foreach ($result as $row) {
        $fetchdata[] = array(
            'notif_data' => $row["notif_data"],
            'ticket_no'  => $row["ticket_no"],
            'notif_val'  => $row["notif_val"],
            'status'     => $row["status"],
            'notif_date' => $row["notif_date"]
        );
    }   

    return array_filter($fetchdata);
}

/**
 * Netpie.
 */
public function netpie(){

	$query= "SELECT cat_desc,clr,cat_id, count(*) as ctn, date_created
	FROM vwp 
	WHERE deptsel = '1' AND cat_id ='3' AND date_created IN (".$_POST['yr'] .")
	GROUP BY cat_id ORDER BY cat_desc ASC";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();

	foreach ($result as $row) {
	$data[] = array(
	'type' => $row["cat_desc"], 
	'percent' => $row["ctn"],
	'color' => $row["clr"],
	'subs' => $this->netsubs($row['cat_id'])

		);
	}
	return($data);

}

/**
 * Netsubs.
 */
public function netsubs($id){

	$query= "SELECT sub_cat, count(*) as sctn, date_created FROM vwp WHERE cat_id='".$id."' AND deptsel = '1'  AND date_created IN (".$_POST['yr'] .")  GROUP BY sub_cat ORDER BY cat_desc ASC";

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
 * Overallnet res.
 */
public function overallnet_res(){

	$query="SELECT
		reports.`status` AS stat_name,
		Count(reports.`status`) AS points,
		YEAR(date_created) AS yr,
		tbl_status.stat_id
		FROM
		reports
		LEFT JOIN tbl_status ON reports.`status` = tbl_status.stat_desc
		where `reports`.`sub_id` NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT') AND YEAR(date_created) IN (".$_POST['yr'] .") AND reports.deptsel = '1' AND cat_id = '3'
		GROUP BY `status`
		ORDER BY stat_id ASC
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


/**
 * Areanet grph.
 */
public function areanet_grph(){

	$query="SELECT
	`reports`.`store` AS `store`,
	`tbl_branch`.`str_code` AS `str_code`,
	`tbl_branch`.`area_num` AS `area_num`,
	`tbl_area`.`area_desc` AS `area_desc`,
	YEAR ( `reports`.`date_created` ) AS `dc`,
	count( `reports`.`date_created` ) AS `cntarea` 
FROM
	((
			`reports`
			JOIN `tbl_branch` ON ( `reports`.`store` = `tbl_branch`.`str_num` ))
	JOIN `tbl_area` ON ( `tbl_area`.`area_num` = `tbl_branch`.`area_num` )) 
WHERE
	YEAR ( `reports`.`date_created` )  IN ( ".$_POST['yr'] ." ) AND deptsel = '1' AND cat_id = '3'
GROUP BY
	`tbl_branch`.`area_num`";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();

	foreach($result as $row)
	{
	$data[] = array(
		'area_id' => $row['area_num'],
		'area_desc' => $row['area_desc'],
		'cntarea' => $row['cntarea'],
		'fyr' => $row['dc']

	);

	}
	return $data;
}

/**
 * Strnet grph.
 */
public function strnet_grph(){
	if ($_POST['area_desc'] == "CENTRAL") {
		$query=" SELECT
				count(reports.ticket_no) as cnt_ttl,
				tbl_branch.str_code,
				tbl_dept.dept_desc as str_dept,
				reports.store
				FROM
				reports
				INNER JOIN users ON reports.userId = users.id AND reports.store = users.str_num
				INNER JOIN tbl_dept ON tbl_dept.dept_id = users.dept_id
				INNER JOIN tbl_branch ON reports.store = tbl_branch.str_num
				where reports.store ='201' AND YEAR(`reports`.`date_created`) IN (".$_POST['yr'] .")
				GROUP BY tbl_dept.dept_id 
";
	}
	else  
	 {
		$query="select `reports`.`store` AS `store`,`tbl_branch`.`str_code` AS `str_dept`,`tbl_branch`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,year(`reports`.`date_created`) AS `dc`,count(`reports`.`date_created`) AS `cnt_ttl` from ((`reports` join `tbl_branch` on(`reports`.`store` = `tbl_branch`.`str_num`)) join `tbl_area` on(`tbl_area`.`area_num` = `tbl_branch`.`area_num`)) WHERE YEAR(`reports`.`date_created`) IN (".$_POST['yr'] .") AND area_desc = '".$_POST['area_desc'] ."' AND deptsel = '1' AND cat_id = '3' group by reports.store, str_code, area_desc ORDER BY str_code ASC

			";

	}
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

} // dbconfig end bracket

// $fn = new dbconfig();	
// $fn->notif_techsupp();

?>
