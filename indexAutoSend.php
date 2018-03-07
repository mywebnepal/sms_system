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
$data = $sms->getAutomatedSMS();
?>
<?php

function num_abr($number)
{
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if (($number %100) >= 11 && ($number%100) <= 13)
	   $abbreviation = $number. 'th';
	else
	   $abbreviation = $number. $ends[$number % 10];
	   return $abbreviation;
}
     if ($usrName =='admin') {?>
       <div class="row">
       	<div class="col-sm-12 col-md-12 mainFrame">
       		<h4>List of Auto Send SMS</h4><hr>
       		<div class="row">
       			<div class="col-sm-12">
            <div class="automatedInfo"></div>
       			<a href="?page=remainder"><button class="btn btn-primary btn-sm">Create Auto Send</button></a><br>
       				<br><table class="table table-striped table-hover">
       				   <th>sn</th>
       				   <th>Group Name</th>
                 <th>SMS Date</th>
                 <th>First Day</th>
                 <th>Second Day</th>
                 <th>Third Day</th>
       				   <th>Auto Send Days</th>
                 <th>SMS Body</th>
       				   <th colspan="3">Action</th>
                 <?php
                      $count = 1;
                      if (!empty($data)) {
                      foreach ($data as $data) {?>
                         <tr>
                              <td><?php echo $count++;  ?></td>
                              <td><?php echo $data['grp_name'].'&nbsp'.$data['section'];  ?></td>
                              <td><?php $sd = date_create($data['startDate']); date_add($sd, date_interval_create_from_date_string('1 days')); echo $sd->format('Y-m-d');  ?></td>
                              <td><?php echo $data['dayNum']==0?"":num_abr($data['dayNum']);  ?></td>
                              <td><?php echo $data['s_dayNum']==0?"":num_abr($data['s_dayNum']);  ?></td>
                              <td><?php echo $data['t_dayNum']==0?"": num_abr($data['t_dayNum']);  ?></td>
                              <td><?php echo $data['smsBody']; ?></td>
                              <td>
                                <form action="?page=remainder" method="post">
                                  <input type="hidden" name="automatedUpdateId" value="<?php echo $data['automated_id'];  ?>">
                                  <button type="submit" class="btn btn-success btn-sm">Update</button>
                                </form>
                              </td>
                              <td>
                              <form action="route/process.php" method="post" class="DelAutomatedSMS">
                                <input type="hidden" name="DelAutomatedSMS" value="<?php echo $data['automated_id'];   ?>">
                                <input type="hidden" name="tag" value="DelAutomatedID">
                                 <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form>
                              </td>
                              <td>
                              <form action="route/process.php" method="post" class="AutoSendAckFrm">
                              <input type="hidden" name="updateAutomatedAckId" value="<?php echo $data['automated_id'];  ?>">
                              <input type="hidden" name="tag" value="UpdateAutomatedAckId">
                                <?php
                                    if ($data['ack']=='1') {?>
                                      <input type="hidden" name="ackValue" value="0">
                                      <button type="submit" class="btn btn-danger btn-sm">Stop..</button>
                                    <?php
                                    }else{?>
                                      <input type="hidden" name="ackValue" value="1">
                                      <button type="submit" class="btn btn-primary btn-sm">process..</button>
                                    <?php
                                    }
                                ?>
                              </form>
                              </td>
                         </tr>
                     <?php
                     }
                   }else{
                    echo "<h3 class='alert alert-danger'>Oops Auto Send Sms is not created yet...</h3>";
                   }
                 ?>
       				</table>
       			</div>
       		</div>
       	</div>
       </div>
     <?php
     }else{
      echo "<h4 class='alert alert-danger'>Oops your don't have access!!!</h4>";
     }
?>
<script type="text/javascript">
  var automatedInfo   = $('.automatedInfo');
  var DelAutomatedSMS = $('.DelAutomatedSMS');
  DelAutomatedSMS.on('submit', function(e){
  e.preventDefault();
  var con = confirm('Are you Sure you want to Delete ?');
  if (con == true) {
    var frmUrl = $(this).attr('action');
    var frmData = $(this).serialize();
   $.ajax({
     type : 'post',
     url  : frmUrl,
     data : frmData,
     timeout : 3000,
     beforeSend :function(){
     automatedInfo.addClass('alert alert-info');
     automatedInfo.text('your data is processing');
     },
     success : function(data){
     automatedInfo.removeClass();
     automatedInfo.addClass('alert alert-success');
     automatedInfo.html(data);
     },
     error : function(data){
     automatedInfo.html(data);
     },
     complete : function(){
     setTimeout('window.location="home.php?page=indexAutoSend"', 3000);
     }
   });
  }else{
    return false;
  }
  });
  var AutoSendAckFrm = $('.AutoSendAckFrm');
  AutoSendAckFrm.on('submit', function(e){
  e.preventDefault();
   var frmUrl = $(this).attr('action');
   var frmData = $(this).serialize();
  $.ajax({
    type : 'post',
    url  : frmUrl,
    data : frmData,
    timeout : 3000,
    beforeSend :function(){
    automatedInfo.addClass('alert alert-info');
    automatedInfo.text('your data is processing');
    },
    success : function(data){
    automatedInfo.removeClass();
    automatedInfo.addClass('alert alert-success');
    automatedInfo.html(data);
    },
    error : function(data){
    automatedInfo.html(data);
    },
    complete : function(){
    setTimeout('window.location="home.php?page=indexAutoSend"', 3000);
    }
  });
  });
</script>