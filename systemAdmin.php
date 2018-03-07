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
	$sysData = $sysUsr->index();
?>
<div class="row">
	<div class="col-sm-12 col-md-12 mainFrame">
	<h4>List Of System User</h4><hr>
	    <a href="?page=cUser"><button class="btn btn-primary btn-sm">Add System User</button></a><br>
	    <div class="usrInfo" style="postion:fixed; z-index:99999;"></div>
		<br><table class="table table-striped table-hover">
		   <th>Sn</th>
		   <th>Full Name</th>
		   <th>Username</th>
		   <th>Group</th>
		   <th>Quotas</th>
		   <th colspan="5">Action</th>
		   <?php
                if (!empty($sysData)) {
                	$count = 1;
                	foreach ($sysData as $data) {?>
                		<tr>
                			<td><?php echo $count++; ?></td>
                			<td><?php echo $data['fname'].'&nbsp;'.$data['lname']; ?></td>
                			<td><?php echo $data['username']; ?></td>
                			<td><?php echo $data['grp_name'].'&nbsp'.$data['section']; ?></td>
                			<td><?php echo $data['smsQuotas'];  ?></td>
                			<td>
                			<form action="route/process.php" method="post" class="userNotice">
                				<input type="hidden" name="hidden_sysAdm_id" value="<?php echo $data['user_id']; ?>">
                				<input type="hidden" name="tag" value="usr_notify">
                				<?php
                				    $suspend = $data['sus'];
                                    if ($suspend == 1) {?>
                                    <button type='submit' class='btn btn-danger btn-sm'>suspend</button>
                                    <input type="hidden" name="susUsr" value="0">
                                   <?php
                                      }else{?>
                                    	<button type='submit' class='btn btn-default btn-sm'>Unsuspend</button>
                                    	<input type="hidden" name="susUsr" value="1">
                                   <?php
                                     }
                				?>
                			</form>
                			</td>
                			<td>
                			   <form action="?page=changePassword" method="post">
                			   	  <input type="hidden" name="changePwdId" value="<?php echo $data['user_id'];  ?>">
                			      <button class="btn btn-info btn-sm">Change Password</button>
                			   </form>
                			</td>
                			<td>
                			   <form action="?page=cUser" method="post">
                			   	   <input type="hidden" name="updateUsrId" value="<?php echo $data['user_id'];  ?>">
                			       <button type="submit" class="btn btn-primary btn-sm">Update</button>
                			   </form>
                			</td>
                			<td>
                			 <form action="route/process.php" method="post" class="delUsrFrm">
                			   <input type="hidden" name="tag" value="delUsrInfo">
                			   <input type="hidden" name="del_user_id" value="<?php echo $data['user_id'];  ?>">
                         <input type="hidden" name="delCreditUsr" value="<?php echo $data['username'];  ?>">
                			   <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                			  </form>
                			 </td>

                             <td>
                             <form action="?page=updateQuotas" method="post">
                               <input type="hidden" name="aloc_quotas" value="<?php echo $data['smsQuotas'];  ?>">
                               <input type="hidden" name="uname" value="<?php echo $data['username'];  ?>">
                               <input type="hidden" name="QuotasIdUpdate" value="<?php echo $data['user_id'];  ?>">
                               <button type="submit" class="btn btn-success btn-sm">update Quotas</button>
                              </form>
                             </td>
                		</tr>
                	<?php
                    }
                }else{
                	echo "<h3 class='alert alert-danger'>Oops There is no user</h3>";
                }

		   ?>
			
		</table>
	</div>
	<script type="text/javascript">
    var userNotice = $('.userNotice');
    var usrInfo    = $('.usrInfo');
    usrInfo.hide();
    userNotice.on('submit', function(e){
    e.preventDefault();
    var frmUrl = $(this).attr('action');
    var frmData = $(this).serialize();
    $.ajax({
          	type : 'post',
          	url  : frmUrl,
          	data : frmData,
            timeout : 3000,
            beforeSend :function(){
            usrInfo.fadeIn(3000);
            usrInfo.addClass('alert alert-info');
            usrInfo.text('your data is processing');
            },
            success : function(data){
            usrInfo.removeClass();
            usrInfo.addClass('alert alert-success');
            usrInfo.html(data);
            },
            error : function(data){
            usrInfo.text(data);
            },
            complete : function(){
             setTimeout('window.location="home.php?page=systemUser"', 3000);
            }
          });
    });
    var delUsrFrm = $('.delUsrFrm');
    delUsrFrm.on('submit', function(e){
    e.preventDefault();
    var confon = confirm("Are you sure your want to Delete ?");
    if (confon==true) {
       var frmUrl = $(this).attr('action');
       var frmData = $(this).serialize();
       $.ajax({
                type : 'post',
                url  : frmUrl,
                data : frmData,
               timeout : 3000,
               beforeSend :function(){
               usrInfo.fadeIn(3000);
               usrInfo.addClass('alert alert-info');
               usrInfo.text('your data is processing');
               },
               success : function(data){
               usrInfo.removeClass();
               usrInfo.addClass('alert alert-success');
               usrInfo.html(data);
               },
               error : function(data){
               usrInfo.text(data);
               },
               complete : function(){
                setTimeout('window.location="home.php?page=systemUser"', 3000);
               }
             });
    }else{
        return false;
    }
  });
	</script>
</div>