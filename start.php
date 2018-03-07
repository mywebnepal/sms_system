<?php 
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
?>
<?php
require_once('model/db.php');
$db = new dbCon();
$con = $db->connect();
?>
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12 mainFrame">
		<h4>WelCome Panel</h4>
		<div class="row">
			<div class="col-sm-12">
					<?php 
					  date_default_timezone_set('Asia/Kathmandu');
					  $msg='';
					        $dat=date('G');
					        if($dat>=5 && $dat<=11){
					        echo '<h3 align="center">'.$msg='Good Morning'.'</h3>';
					        }
					        else if($dat>=12 && $dat<=18){
					        echo '<h3 align="center">'.$msg='Good Afternoon'.'</h3>';
					        }
					        else if($dat>=19 || $dat<=4){
					        echo '<h3 align="center">'.$msg='Good Evening'.'<h3>';
					        }
					        echo '<h4 align="center">'.date('h:i:s').'</h4>';
				    ?><hr>
			</div>
			<div class="col-sm-12">
			<?php
			$username = $usrName;
			echo "<h3>".$username."</h3>";
			if ($username=='admin') {
				$admin_sql = mysqli_query($con, "SELECT user.username,user.grp_id, user.user_id, user.smsQuotas,grp.grp_id, grp.grp_name, grp.section, smsquotas.quotas, smsquotas.username FROM user, grp, smsquotas WHERE grp.grp_id = user.grp_id AND smsquotas.username = user.username");
				if ($admin_sql) {
					$count = 1;
					echo "<table class='table table-striped table-hover'>
					      <th>sn</th>
					      <th>username</th>
					      <th>Assign Group</th>
					      <th>Allocated Quotas</th>
					      <th>UsedQuotas</th>";
					while ($data = mysqli_fetch_assoc($admin_sql)) {?>
						<tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $data['username']; ?></td>
                             <td><?php echo $data['grp_name'].'&nbsp;'.$data['section']; ?></td>
                             <td><?php echo $data['smsQuotas']; ?></td>
                             <td><?php echo $data['quotas']; ?></td>
						</tr>
					<?php
				     }
				     echo "</table>";
				}else{
					echo "<h3 class='alert alert-danger'>Oops data is not found</h3>";
				}
			}else{
				$client_sql = mysqli_query($con, "SELECT user.username,user.grp_id, user.user_id, user.smsQuotas,grp.grp_id, grp.grp_name, grp.section, smsquotas.quotas, smsquotas.username FROM user, grp, smsquotas WHERE user.username = '".$username."' AND grp.grp_id = user.grp_id AND smsquotas.username = '".$username."'");
					if ($client_sql) {
					$count = 1;
					echo "<table class='table table-striped table-hover'>
					      <th>sn</th>
					      <th>username</th>
					      <th>Assign Group</th>
					      <th>Allocated Quotas</th>
					      <th>UsedQuotas</th>";
					while ($data = mysqli_fetch_assoc($client_sql)) {?>
						<tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $data['username']; ?></td>
                             <td><?php echo $data['grp_name'].'&nbsp;'.$data['section']; ?></td>
                             <td><?php echo $data['smsQuotas']; ?></td>
                             <td><?php echo $data['quotas']; ?></td>
						</tr>
					<?php
				     }
				     echo "</table>";
				}else{
					echo "<h3 class='alert alert-danger'>Oops data is not found</h3>";
				}
			}
            ?>
			</div>
		</div>
	</div>
</div>