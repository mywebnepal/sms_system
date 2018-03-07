<?php 
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
$smsData = $sms->getSendBoxMsg();
?>
<div class="row">
    <div class="indexInfo" style="postion:fixed; z-index: 99999;"></div>
	<div class="col-sm-12 col-md-12 mainFrame">
		<h4>Send SMS box </h4>
		<a href="?page=compose"><button class="btn btn-primary">Composer</button></a>
		<table class="table table-striped table-hover table-border">
		<thead>
			<th>Sn</th>
			<th>Group name</th>
			<th>Staff name</th>
			<th>Subject</th>
			<th>Body</th>
			<th>Data and Time</th>
			<th>Action</th>
		</thead>
		<?php
         if (!empty($smsData)) {
         	$count = 1;
         	foreach ($smsData as $data) {?>
         		<tr>
         			<td><?php echo $count++;  ?></td>
         			<td><?php echo $data['grp_name'].'&nbsp;'.$data['section'];  ?></td>
         			<td><?php echo $data['firstname'].'&nbsp;'.$data['lastname'];  ?></td>
         			<td><?php echo substr($data['subject'], 0,10); ?></td>
         			<td><i><?php echo substr($data['body'], 0,20); ?></i></td>
         			<td><?php echo $data['msg_date']; ?></td>
         			<td>
         			<form action="?page=singleView" method="post">
         			   <input type="hidden" name="msg_id" value="<?php echo $data['msg_id'];   ?>">
         			   <button class="btn btn-primary btn-sm">View</button>	
         			</form>
         			</td>
         			<td>
         			<form action="route/process.php" method="post" class="delSms">
         			 <input type="hidden" name="msg_id" value="<?php echo $data['msg_id'];   ?>">
         			 <input type="hidden" name="tag" value="DelSms">
         			 <button class="btn btn-danger btn-sm">Delete</button>
         			</form>
         			</td>
         		</tr>
         	<?php
            }
         }else{
         	echo "<h4 class='alert alert-danger'>Oops there is empty sms</h4>";
         }
		?>
		</table>
	</div>
<script type="text/javascript">
var delSmsFrm = $('.delSms');
var indexInfo = $('.indexInfo');
delSmsFrm.on('submit', function(e){
e.preventDefault();
var smsDelete = confirm('Are your sure you want to delete this sms');
   if (smsDelete == true) {
   var frmUrl  = $(this).attr('action');
   var frmData = $(this).serialize();
   $.ajax({
            type : 'post',
            url  : frmUrl,
            data : frmData,
           timeout : 3000,
           beforeSend :function(){
           indexInfo.addClass('alert alert-info');
           indexInfo.text('your data is processing');
           },
           success : function(data){
           indexInfo.removeClass();
           indexInfo.addClass('alert alert-success');
           indexInfo.html(data);
           },
           error : function(data){
           indexInfo.html(data);
           },
           complete : function(){
           setTimeout('window.location="home.php?page=sendSms"', 3000);
           }
         });
}else{
    return false;
}
});
</script>
</div>