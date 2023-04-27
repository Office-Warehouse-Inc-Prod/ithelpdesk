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
	public function fetch_cards_result(){
		$query = '';
		$output = array();
		$query = "SELECT 
        YEAR(date_created) as date_created,
        COUNT(reports.`status`) AS t_all,
        COUNT(CASE WHEN reports.`status` = 'OPEN' then 1 else NULL end ) as t_open,
        COUNT(CASE WHEN reports.`status` = 'ATTENDED WITH FIX ASSET' then 1 else NULL end) as t_owfa,
        COUNT(CASE WHEN reports.`status` = 'CLOSED' then 1 else NULL end) as t_close
        FROM
        reports WHERE sub_id NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELDESK RESPONSE','NEW REPORT') AND YEAR(date_created) IN (".$_POST['yr'] .")";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

        foreach ($result as $row) {
        	$output[] = array(
        		'total_res' => $row["t_all"], 
        		'open_res' => $row["t_open"], 
        		'owfa_res' => $row["t_owfa"], 
        		'cls_res' => $row["t_close"]

        	);
        }
        return $output;

	}

	public function overallpie_res(){

		$query='';
		// $query="SELECT `status` as stat_name, COUNT(`status`) as points, YEAR(date_created) as yr from reports where `reports`.`sub_id` NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT') AND YEAR(date_created) IN (".$_POST['yr'] .")   GROUP BY `status` ";
		$query="
			SELECT
			reports.`status` AS stat_name,
			Count(reports.`status`) AS points,
			YEAR(date_created) AS yr,
			tbl_status.stat_id
			FROM
			reports
			INNER JOIN tbl_status ON reports.`status` = tbl_status.stat_desc
			where `reports`.`sub_id` NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELPDESK RESPONSE','NEW REPORT') AND YEAR(date_created) IN (".$_POST['yr'] .")
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
				reports.sub_id NOT IN (15,28,34,35) AND reports.itsup NOT IN ('8')and
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

	public function linegraph(){

		$query='';
		if ($_POST['yr'] != '2019,2020,2021,2022') {
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

	public function pie(){

		$query= "
		SELECT cat_desc,clr,cat_id, count(*) as ctn, date_created
		FROM vwp 
		WHERE date_created IN (".$_POST['yr'] .")
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

	public function subs($id){

		$query= "SELECT sub_cat, count(*) as sctn, date_created FROM vwp WHERE cat_id='".$id."' AND date_created IN (".$_POST['yr'] .") GROUP BY sub_cat ORDER BY cat_desc ASC";

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

	public function area_grph(){

		$query="
				select `reports`.`store` AS `store`,`tbl_branch`.`str_code` AS `str_code`,`tbl_branch`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,year(`reports`.`date_created`) AS `dc`,count(`reports`.`date_created`) AS `cntarea` from ((`reports` join `tbl_branch` on(`reports`.`store` = `tbl_branch`.`str_num`)) join `tbl_area` on(`tbl_area`.`area_num` = `tbl_branch`.`area_num`)) WHERE YEAR(`reports`.`date_created`) IN (".$_POST['yr'] .") group by `tbl_branch`.`area_num`

		";
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

	public function str_grph(){
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
			$query="

			select `reports`.`store` AS `store`,`tbl_branch`.`str_code` AS `str_dept`,`tbl_branch`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,year(`reports`.`date_created`) AS `dc`,count(`reports`.`date_created`) AS `cnt_ttl` from ((`reports` join `tbl_branch` on(`reports`.`store` = `tbl_branch`.`str_num`)) join `tbl_area` on(`tbl_area`.`area_num` = `tbl_branch`.`area_num`)) WHERE YEAR(`reports`.`date_created`) IN (".$_POST['yr'] .") AND area_desc = '".$_POST['area_desc'] ."' group by reports.store, str_code, area_desc ORDER BY str_code ASC

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

	public function admin_data_table_res(){

	// $query="
	// Select * from vw6 WHERE 
	// vw6.sub_id NOT IN ('15','28','34','35') AND status <> 'WAITING FOR IT HELPDESK RESPONSE' AND YEAR(vw6.date_created) IN (2022)";

	$query="
	Select * from vw6 WHERE 
	vw6.sub_id NOT IN ('15','28','34','35') AND status <> 'WAITING FOR IT HELPDESK RESPONSE' AND YEAR(vw6.date_created) IN (".$_POST['yr'] .")";
	
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
					'date_closed' => ($row['status'] == 'OPEN') ? " ": date('m/d/Y H:i',strtotime($row["date_closed"])),
					'tdc' => ($row['status'] == 'OPEN') ? $row["dtdf"]." "."Days Unresolved": $row['tdc'],
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

	}

public function newreporthist(){

	$query="SELECT * FROM vw_wfittable";
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
			'full_name' => $row["full_name"]
			// 'sub_cat' => $row["sub_cat"],
		);
	}	

	$data = array_filter($fetchdata);

		return $data;

}

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

public function usermtc_table(){

	$query="SELECT * FROM vw_usrmtc_data WHERE usr_stat = 'A'";
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
			'str_code' => $row["str_code"]
			// 'img_name' => $row["img_name"],

		);
	}	

	$data = array_filter($fetchdata);
		// echo json_encode($data);
		return $data;

}

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

public function notif_techsupp(){

	$query="SELECT * FROM tbl_notif WHERE  notif_val IN ('2','3') ORDER BY notif_date ASC ";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	$fetchdata = array();
	foreach ($result as $row) {
		$fetchdata[] = array(
			'notif_data' => $row["notif_data"],
			'ticket_no' => $row["ticket_no"],
			'notif_val' => $row["notif_val"]

		);
	}	

	$data = array_filter($fetchdata);

		return $data;
	// echo json_encode($data);

}


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
		public function dtbl_itsup(){
// fix update please main obj
	$query="
	Select * from vw6 WHERE
	vw6.sub_id NOT IN ('15','28','34','35') AND status <> 'WAITING FOR IT HELPDESK RESPONSE' AND itsup = ".$_POST['itVal']." AND years IN (".$_POST['yrsx1'].") ORDER BY `status` DESC";
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

public function count_reassigned($itsup){
$query="
	SELECT *, COUNT(itsup) AS cnt_resassgn FROM tbl_reassigned WHERE itsup ='".$itsup."'";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();



				return $result[0];
				// echo json_encode($data);

	}

public function count_sla($itsup){
	$query="
	SELECT itsup, years,
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

public function get_percentage($total, $number)
{
  if ( $total > 0 ) {
   return round(($number * 100) / $total, 2);
  } else {
    return 0;
  }
}

} // dbconfig end bracket

// $fn = new dbconfig();	
// $fn->count_reassigned();

?>