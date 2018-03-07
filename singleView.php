<?php
/*
   * single view page
*/
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
require_once('model/db.php');
require_once('model/smsMsg.php');
$db = new dbCon();
$con = $db->connect();
$sms = new smsMsg($con);
$msg_id = isset($_POST['msg_id'])?$_POST['msg_id']:'';
$smsData = $sms->getSingleData($msg_id);
if (empty($msg_id)) {
	echo "<h4 class='alert alert-danger'>Oops Id is not pass</h4>";
	die();
}


//the following block of code guards the license
$hit1 = stringMatchWithWildcard(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license'])) ? 1 : 0;
if(strlen($GLOBALS['license2'])>0)
	$hit2 = stringMatchWithWildcard(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license2'])) ? 1 : 0; else $hit2=0;
if($hit1==0 && $hit2==0) die();

?>
<div class="row">
	<div class="col-sm-12 mainFrame">
		<h3>View Single Data</h3>
		<div class="row">
			<div class="col-sm-12">
			<?php
              if (empty($smsData)) {
              	 echo "<h4 class='alert alert-danger'>there is no sms in database error code 10002</h4>";
              	 die();
              }else{?>
              	 <table class="table table-striped table-hover">
              	 	<tr>
              	 		<th>Group</th>
              	 		<td><?php echo $smsData['grp_name'].'&nbsp;'.$smsData['section']; ?></td>
              	 	</tr>
              	 	<tr>
              	 		<th>Date</th>
              	 		<td><?php echo $smsData['msg_date']; ?></td>
              	 	</tr>
              	 	<tr>
              	 		<th>Subject</th>
              	 		<td><?php echo $smsData['subject']; ?></td>
              	 	</tr>
              	 	<tr>
              	 		<th>Body</th>
              	 		<td><?php echo $smsData['body']; ?></td>
              	 	</tr>
              	 	<tr>
              	 		<th>Send By</th>
              	 		<td><?php echo $smsData['sendUser']; ?></td>
              	 	</tr>
                  <tr>
                    <th>To</th>
                    <td><?php echo $smsData['phoneNumber']; ?></td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>
                       <?php 
                          if ($smsData['ack']==0) {
                            echo '<label class="label label-danger label-lg">SMS Not Send</label>';
                          }else{
                            echo '<label class="label label-success label-lg">SMS Send !!!</label>';
                          }
                       ?>
                      </td>
                  </tr>

              	 </table>
              <?php
              }
			?>	
			<a href="?page=sendSms"><button class="btn btn-primary btn-sm">Back.....</button></a>
			</div>
		</div>
	</div>
</div>