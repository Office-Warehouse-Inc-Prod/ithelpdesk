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
    YEAR(date_created) AS date_created,
    COUNT(CASE WHEN reports.`status` != 'NEW REPORT' THEN 1 ELSE NULL END) AS t_all,
   	COUNT(CASE WHEN reports.`status` NOT IN ('NEW REPORT', 'SUBJECT FOR CLOSING', 'CLOSED') THEN 1 ELSE NULL END) AS t_open,
    COUNT(CASE WHEN reports.`status` = 'OPEN' AND DATEDIFF(CURDATE(), reports.date_created) > 3 THEN 1 ELSE NULL END) AS t_owfa,
    COUNT(CASE WHEN reports.`status` = 'CLOSED' THEN 1 ELSE NULL END) AS t_close,
    tbl_branch.area_num
FROM
    reports
INNER JOIN tbl_branch ON tbl_branch.str_num = reports.store
WHERE
 YEAR(date_created) IN (".$_POST['yr'] .") AND tbl_branch.AM LIKE '%{$_SESSION['user_id']}%'
GROUP BY
    YEAR(date_created);
";

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
		// $output= array();
		$query="SELECT
	COUNT(reports.`status`) AS cnt, 
	YEAR(date_created) AS yr, 
	tbl_branch.str_code AS store
FROM
	reports
	INNER JOIN
	tbl_branch
	ON 
		reports.store = tbl_branch.str_num
WHERE
	`status` NOT IN ('NEW REPORT', 'SUBJECT FOR CLOSING', 'CLOSED') AND
	YEAR(date_created) IN (".$_POST['yr'] .") AND tbl_branch.AM LIKE '%{$_SESSION['user_id']}%'
GROUP BY
	tbl_branch.str_code";

        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data = array();

		foreach ($result as $row) {
		$data[] = array(
		'stat_name' => $row["store"], 
		'points' => $row["cnt"]

			);
		}
        return $data;
	}

	public function bargrph_tech_res(){
		$query='';
		// $output= array();
		$query="SELECT
				reports.itsup AS itsup,
				it_tech.f_name AS it_name,
				Count(reports.itsup) AS total,
				reports.`status`,
				Count( CASE reports.`status` when 'CLOSED' then 1 else null end) as completed
				FROM
				it_tech
				LEFT JOIN reports ON reports.itsup = it_tech.itsup
				WHERE
				reports.sub_id NOT IN (15,28,34,35) AND reports.itsup NOT IN ('8')and
				YEAR(reports.date_created) IN (".$_POST['yr'] .")
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
		'total' => $row["total"],
		'completed' => $row["completed"]

			);
		}
        return $data;
	}

	public function linegraph(){

		$query='';
		if ($_POST['yr'] != '2019,2020,2021') {
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
		year(date_created) BETWEEN '2019' AND '2021' and reports.sub_id NOT IN ('15','28','34','35')
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

		$query= "SELECT
	tbl_deptsel.dept_id,
  tbl_deptsel.dept_desc as Dept,
	count(*) AS ctn
FROM
	vwp
	INNER JOIN
	tbl_deptsel
	ON 
		vwp.deptsel = tbl_deptsel.dept_id
WHERE
	vwp.date_created IN (".$_POST['yr'] .") AND vwp.AM LIKE '%{$_SESSION['user_id']}%' AND vwp.`status` NOT IN ('NEW REPORT', 'SUBJECT FOR CLOSING', 'CLOSED')
GROUP BY
  Dept
ORDER BY
	vwp.cat_desc ASC";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach ($result as $row) {
		$data[] = array(
		'type' => $row["Dept"], 
		'percent' => $row["ctn"],
		'subs' => $this->subs($row['dept_id'])

			);
		}
		return($data);

	}

	public function subs($id){

		$query= "SELECT
	count(*) AS sctn, 
	cat_desc
FROM
	vwp
WHERE
	deptsel = '".$id."' AND
	vwp.date_created IN (".$_POST['yr'] .")
	AND vwp.AM LIKE '%{$_SESSION['user_id']}%' AND
	vwp.`status` NOT IN ('NEW REPORT','SUBJECT FOR CLOSING','CLOSED')
GROUP BY
	cat_desc
ORDER BY
	cat_desc ASC";

		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array('type' => $row['cat_desc'],'percent' => $row['sctn']);

		}
		return $data;
	}

	public function area_grph(){

		$query="SELECT
  `reports`.`store` AS `store`,
  `tbl_branch`.`str_code` AS `str_code`,
  `tbl_branch`.`area_num` AS `area_num`,
  `tbl_area`.`area_desc` AS `area_desc`,
  YEAR(`reports`.`date_created`) AS `dc`,
  count(`reports`.`date_created`) AS `cntarea`
FROM
  (
    (`reports` JOIN `tbl_branch` ON (`reports`.`store` = `tbl_branch`.`str_num`))
    JOIN `tbl_area` ON (`tbl_area`.`area_num` = `tbl_branch`.`area_num`)
  )
WHERE
  YEAR(`reports`.`date_created`) IN (".$_POST['yr'] .") AND `tbl_branch`.`AM` LIKE '%{$_SESSION['user_id']}%' AND tbl_branch.area_num <> '202' AND reports.`status` != 'NEW REPORT' AND sub_id IS NOT NULL
GROUP BY
  str_code";
		$statement = $this->connection->prepare($query);
		$statement-> execute();
		$result = $statement->fetchAll();
		$data[] = array();

		foreach($result as $row)
		{
		$data[] = array(
			'area_id' => $row['store'],
			'area_desc' => $row['str_code'],
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

	$query="SELECT *
FROM
  vw_amdtb
WHERE
  vw_amdtb.sub_id NOT IN ('15', '28', '34', '35')
  AND STATUS NOT IN ('NEW REPORT')
  AND years IN (".$_POST['yr'] .")
  AND AM LIKE '%{$_SESSION['user_id']}%'";
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
					'close_by' => $row['close_by'], //close id user
					'clusers' => $row['clusers'], //close user name
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
			tbl_notpolledstr.polling_date BETWEEN ? AND ? AND tbl_branch.AM LIKE '%{$_SESSION['user_id']}%'
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


	//CJ UPDATE 2025 for SALES DASHBOARD STARTS HERES ======== =========


		public function zreading_data(){

	$query="SELECT
	tbl_sales_yesterday.CREATED_DATE, 
	tbl_sales_yesterday.STORE_NO, 
	tbl_sales_yesterday.STORE_CODE, 
	SUM(tbl_sales_yesterday.TENDER_TOTAL_OPEN) AS TENDER_TOTAL_OPEN, 
	SUM(tbl_sales_yesterday.TENDER_TOTAL_CLOSE) AS TENDER_TOTAL_CLOSE, 
	SUM(tbl_sales_yesterday.OVER_SHORT_AMT) AS OVER_SHORT_AMT, 
	SUM(TENDER_TOTAL_CLOSE-TENDER_TOTAL_OPEN-OVER_SHORT_AMT) AS ttl,
	AM
FROM
	tbl_sales_yesterday
	INNER JOIN
	tbl_branch
	ON 
		tbl_sales_yesterday.STORE_CODE = tbl_branch.str_code
		WHERE tbl_branch.AM  LIKE '%{$_SESSION['user_id']}%'
GROUP BY
	tbl_sales_yesterday.STORE_CODE";
	$statement = $this->connection->prepare($query);
	$statement-> execute();
	$result = $statement->fetchAll();
	$data[] = array();
	// $fetchdata[] = array();

		foreach($result as $row)
				{
				$fetchdata[] = array(
					'CREATED_DATE' => date('m/d/Y',strtotime($row["CREATED_DATE"])),
					'STORE_NO' => $row['STORE_NO'],
					'STORE_CODE' => $row['STORE_CODE'],
					'TENDER_TOTAL_OPEN' => number_format($row['TENDER_TOTAL_OPEN'],2),
					'TENDER_TOTAL_CLOSE' => number_format($row['TENDER_TOTAL_CLOSE'],2),
					'OVER_SHORT_AMT' => number_format($row['OVER_SHORT_AMT'],2),
					'ttl' => number_format($row['ttl'],2)

				);

				}
			$data = array_filter($fetchdata);
				return $data;

	}
	

} // dbconfig end bracket

// $fn = new dbconfig();	
// $fn->genrep_statpie();

?>