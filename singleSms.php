<?php 
/*
  *VIEW ONLY SINGLE SMS
*/
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
?>
<?php
    require_once('model/db.php');
    require_once('model/smsMsg.php');
    $db = new dbCon();
    $con = $db->connect();
    $sms = new smsMsg($con);
    $sngView = isset($_POST['sngViewId'])?$_POST['sngViewId']:'';
    $smsData = $sms->viewSingleSms($sngView);
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
              	 		<th>Date</th>
              	 		<td><?php echo $smsData['msg_date']; ?></td>
              	 	</tr>
              	 	<tr>
              	 		<th>To</th>
              	 		<td><?php echo $smsData['phoneNumber']; ?></td>
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
			<a href="?page=singleSms"><button class="btn btn-primary btn-sm">Back.....</button></a>
			</div>
		</div>
	</div>
</div>
