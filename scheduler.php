<?php 
//file_put_contents('./scheduler_log.txt', time(). " ", FILE_APPEND); die();
if(!isset($_SESSION)) {session_start();} 
$_SESSION['username'] = 'admin'; 
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
require_once('model/db.php');
require_once('model/smsMsg.php');
require_once('model/assign.php');

		
$db = new dbCon();
$con = $db->connect();
$ass = new assign($con);
$sms = new smsMsg($con);
$data = $sms->getAutomatedSMS();

 foreach ($data as $data) {
	if($data['ack']!=0)
	{
		$time1 = date_create($data['startDate']);
		$time2 = date_create($data['startDate']);
		$time3 = date_create($data['startDate']);
		date_add($time1, date_interval_create_from_date_string(($data['dayNum']).' days'));
		date_add($time2, date_interval_create_from_date_string(($data['s_dayNum']).' days'));
		date_add($time3, date_interval_create_from_date_string(($data['t_dayNum']).' days'));
		$datesms_1 = $time1->format('Y-m-d');
		$datesms_2 = $time2->format('Y-m-d');
		$datesms_3 = $time3->format('Y-m-d');
		$datenow = date('Y-m-d', time());
		if( (strcmp($datesms_1, $datenow) == 0 && $data['dayNum'] !==0) || (strcmp($datesms_2, $datenow) == 0 && $data['s_dayNum'] !== 0) ||
			(strcmp($datesms_3, $datenow) == 0 && $data['t_dayNum'] !== 0))
		{
			$ass->grp_id = $data['grp_id'];
			$mem_list = $ass->getListFromGrp();
			$memidrr = array();
			foreach($mem_list as $mem){
			$memidarr[] = $mem['mem_id'];
			}
			$sms->create("automated", $data['smsBody'], $memidarr, $data['grp_id']);
			$chk1=0; $chk2=0; $chk3=0;
			if(strcmp($datesms_1, $datenow) == 0 && $data['dayNum'] !== 0) {$sms->disableDateOfDateAutomated($data['automated_id'], 1); $data['dayNum']=0;}
			if(strcmp($datesms_2, $datenow) == 0 && $data['s_dayNum'] !== 0) {$sms->disableDateOfDateAutomated($data['automated_id'], 2); $data['s_dayNum']=0;}
			if(strcmp($datesms_3, $datenow) == 0 && $data['t_dayNum'] !== 0) {$sms->disableDateOfDateAutomated($data['automated_id'], 3); $data['t_dayNum']=0;}
			if($data['dayNum'] == 0 && $data['s_dayNum'] == 0 && $data['t_dayNum'] == 0) $sms->AutomatedDelete($data['automated_id']);
		}
	}
 }
?>