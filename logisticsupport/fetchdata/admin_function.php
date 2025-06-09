<?php
//admin side functions
include '../../connection/db.php';
date_default_timezone_set("Asia/Manila");
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
        COUNT(CASE WHEN reports.`status` = 'SCHEDULE FOR PULL OUT' then 1 else NULL end ) as t_sched,
        COUNT(CASE WHEN reports.`status` = 'OKAY FOR PULL OUT' then 1 else NULL end) as t_okay,
        COUNT(CASE WHEN reports.`status` = 'READY TO PICK UP' then 1 else NULL end) as t_pickup,
		COUNT(CASE WHEN reports.`status` = 'APPROVED' then 1 else NULL end) as t_approved,
		COUNT(CASE WHEN reports.`status` = 'REPAIRED' then 1 else NULL end) as t_repaired,
		COUNT(CASE WHEN reports.`status` = 'RETURN TO STORE' then 1 else NULL end) as t_return,
		COUNT(CASE WHEN reports.`status` = 'CONFIRM PICK UP' then 1 else NULL end) as t_confirm,
		COUNT(CASE WHEN reports.`status` = 'ITEM RECEIVED' then 1 else NULL end) as t_received
        FROM
reports 
WHERE
	sub_id NOT IN ( '15', '28', '34', '35' ) 
	AND `status` NOT IN ( 'WAITING FOR IT HELDESK RESPONSE', 'NEW REPORT' ) 
	AND YEAR ( date_created ) IN (".$_POST['yr'] .")  
	AND reports.deptsel = '4'
	AND service_desc = 'REPAIR IMPORT'";
			// $query = "SELECT 
			// YEAR(date_created) as date_created,
			// COUNT(reports.`status`) AS t_all,
			// COUNT(CASE WHEN reports.`status` = 'OPEN' then 1 else NULL end ) as t_open,
			// COUNT(CASE WHEN reports.`status` = 'ATTENDED WITH FIX ASSET' then 1 else NULL end) as t_owfa,
			// COUNT(CASE WHEN reports.`status` = 'CLOSED' then 1 else NULL end) as t_close,
			// COUNT(CASE WHEN reports.`status` = 'FOR PICKUP' then 1 else NULL end) as t_pickup,
			// COUNT(CASE WHEN reports.`status` = 'DELIVERED TO SUPPLIER' then 1 else NULL end) as t_deliver_supplier,
			// COUNT(CASE WHEN reports.`status` = 'FOR DELIVERY TO STORE' then 1 else NULL end) as t_for_delivery
			// FROM
			// reports WHERE sub_id NOT IN ('15','28','34','35') AND `status` NOT IN ('WAITING FOR IT HELDESK RESPONSE','NEW REPORT') AND YEAR(date_created) IN (".$_POST['yr'] .") AND itsup = '{$_SESSION['tech_id']}' AND reports.deptsel = '4'";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

        foreach ($result as $row) {
        	$output[] = array(
        		'schedule' => $row["t_sched"], 
        		'okay' => $row["t_okay"], 
        		'pickup' => $row["t_pickup"], 
        		'approved' => $row["t_approved"],
        		'repaired' => $row["t_repaired"],
        		'return' => $row["t_return"],
        		'confirm' => $row["t_confirm"],
        		'received' => $row["t_received"]


        	);
        }
        return $output;

	}

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

	public function admin_data_table_res(){
	$query="SELECT * from vw6 WHERE vw6.deptsel = '4' AND
vw6.sub_id NOT IN ('15','28','34','35') AND status <> 'WAITING FOR IT HELPDESK RESPONSE' AND service_desc = 'REPAIR IMPORT' ";
	// $query="
	// Select * from vw6 WHERE
	// vw6.sub_id NOT IN ('15','28','34','35') AND status <> 'WAITING FOR IT HELPDESK RESPONSE'";
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
					// 'date_closed' => date('m/d/Y H:i',strtotime($row["date_closed"])),
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
			'concern'=> $row["concern"],
			'service_desc' => $row["service_desc"],
			'subject' => $row["subject"],
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


public function notif_techsupp(){

	$query="SELECT * FROM tbl_notif WHERE itsup = '{$_SESSION['tech_id']}' AND notif_val = '1' ORDER BY notif_date ASC ";
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



} // dbconfig end bracket

// $fn = new dbconfig();	
// $fn->notif_techsupp();

?>