<?php 
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
require_once('model/db.php');
require_once('model/user.php');
$db = new dbCon();
$con = $db->connect();
$sysUsr = new userMnt($con);

$usr_id = isset($_POST['changePwdId'])?$_POST['changePwdId']:'';
$sysData = $sysUsr->getIndividualData($usr_id);
	if (!empty($usr_id) && $usrName == 'admin') {?>
		<div class="row">
			<div class="col-sm-12 col-md-12 mainFrame">
				<h4>Change Password</h4><hr>
				 <div class="changePwdInfo"></div>
				<div class="col-sm-6">
				     <?php
                          foreach ($sysData as $data) {?>
						    <h4><?php echo 'Username:-&nbsp;'.$data['username'];  ?>&nbsp;&nbsp;
						    <small><?php echo $data['grp_name'].'&nbsp;'.$data['section'];  ?></small></h4>
                          <?php
                         }
				     ?>
					<form action="route/process.php" method="post" id="ChngPwd">
					    <fieldset>
					    	<label>New Password</label>
					    	<input type="password" name="newPwd" class="form-control" placeholder="Please enter new password here...">
					    </fieldset>
					    <fieldset>
					    	<label>Confirm Password</label>
					    	<input type="password" name="conPwd" class="form-control" placeholder="please confirm password">
					    </fieldset>
					    <fieldset>
					           <input type="hidden" name="chng_usrId" value="<?php echo $usr_id ?>">
					           <input type="hidden" name="tag" value="changeMyPwd">
					    	<br><input type="submit" class="btn btn-primary btn-sm">
					    </fieldset>
					</form>
				</div>
			</div>
		</div>
	<?php
    }else{
		echo "<h4 class='alert alert-danger'>you haven't select user Or your are not admin</h4>";
	}
?>
<script type="text/javascript">
	var ChngPwd = $('#ChngPwd');
	var changePwdInfo = $('.changePwdInfo');
	ChngPwd.on('submit', function(e){
    e.preventDefault();
          var frmUrl = $(this).attr('action');
          var frmData = $(this).serialize();
          alert(frmData);
          $.ajax({
          	type : 'post',
          	url  : frmUrl,
          	data : frmData,
            timeout : 3000,
            beforeSend :function(){
            changePwdInfo.addClass('alert alert-info');
            changePwdInfo.text('your data is processing');
            },
            success : function(data){
            changePwdInfo.removeClass();
            changePwdInfo.addClass('alert alert-success');
            changePwdInfo.html(data);
            },
            error : function(data){
            changePwdInfo.html(data);
            },
            complete : function(){
            setTimeout('window.location="home.php?page=systemUser"', 4000);
            }
          });
	});
</script>