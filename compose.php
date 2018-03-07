<?php 
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
         window.location.href = '/sms';
      </script>";
}
?>
<?php 
/*
  * create sms section
*/
$title = 'Compose SMS';
require_once('model/db.php');
require_once('model/group.php');
require_once('model/assign.php');
$db = new dbCon();
$con = $db->connect();
$grp = new group($con);
$grp_data = $grp->index();
$ass = new assign($con);
 ?>
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12 mainFrame">
		<h3>Create your SMS</h3>
		<div class="row">
			<div class="col-sm-6">
				<form action="?page=compose" method="post" class="form-inline">
					<select class="form-control" name="get_mem_grp">
						<option value="">Select Group to send sms</option>
						<?php
	                     if (!empty($grp_data)) {
	                     	foreach ($grp_data as $grp) {?>
	                     		<option value="<?php echo $grp['grp_id'];  ?>">
	                     		<?php  
	                     		   echo $grp['grp_name'].'&nbsp;&nbsp;'.$grp['section'];    ?>
	                     		</option>
	                     	<?php
	                      }
	                     }else{
	                     	echo "<h4 class='alert alert-danger'>Oops group is not created please create group first</h4>";
	                     }          
						?>
				     </select>
				     <input type="submit" name="smsSearch" value="Search" class="btn btn-success">
				  </form>
			</div>
		</div><!-- finding group user -->
		<hr>
		    <div class="row">
		    	<div class="col-sm-12 composeInfo"></div>
		    </div>
		<div class="row">
		    <div class="col-sm-6 col-md-6 col-lg-6">
             <?php    
                  if (isset($_POST['smsSearch'])) {
                  	$grp_id = isset($_POST['get_mem_grp'])?$_POST['get_mem_grp']:'';
                  	if (!empty($grp_id)) {
                  		$ass->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $grp_id));
                  		$List = $ass->getListFromGrp();
                  		if (!empty($List)) {?>
                  		    <form action="route/process.php" method="post" id="sendSMS">
	                  				<fieldset>
	                  					<label>Subject</label>
	                  					<input type="text" name="smsSub" class="form-control" placeholder="Please Enter subject here...">
	                  				</fieldset>
	                  				<fieldset>
  							    		<label>Message</label>
  							    		<textarea name="smsBody" class="form-control" rows="5" id="comment"></textarea>
  							    	</fieldset><br>
                                    <fieldset>
                                      <button class="btn btn-primary btn-md">Send</button>
                		    	    </fieldset>
		        </div>
		       <div class="col-sm-6 nopadding nomargin">
		           <div class="listOfMem" style="background:#DDD !important; padding-left:2em !important;">
	   					<label><input type='checkbox' id='selectall'>&nbsp;Select</label>
	   					<?php
	   	                  foreach ($List as $rows) {?>
	   	      	        <ul class="listOfmember">
	   	      				<li>
	   	      				<label>
	   	      				<input type="checkbox" name="mem_id[]" value="<?php echo $rows['mem_id'];  ?>">&nbsp;&nbsp;
	   	      				<?php echo '<i>'.$rows['firstname']. '&nbsp;'.$rows['lastname']. '&nbsp;/&nbsp;'. $rows['phone'].'</i>';  ?>
	   	      				</label>
	   	      				</li>
	   	      			</ul>
	   	                  <?php
	   	                  }
	   					?>
	   	      			 <input type="hidden" name="grp_id" value="<?php echo $rows['grp_id'];   ?>">
	   	      			 <input type="hidden" name="tag" value="SendSMSTo">
		           </div>
		   </div>
		   </form>
		   <?php
      	      }else{
      			echo "<h4><i>Oops there is no Memeber assign in this group</i></h4>";
      		}
      	}
      }
 ?>
	</div>
    </div><!-- ending of the 12 div -->
    </div><!-- ending of the row -->
	<script type="text/javascript">
	$('#selectall').click(function () {    
	    $('input:checkbox').prop('checked', this.checked);  
	});
	/*---------------------------------*/
	var composeInfo = $('.composeInfo');
	var sendSMS     = $('#sendSMS');
	sendSMS.on('submit', function(e){
    e.preventDefault();
    var frmUrl  = $(this).attr('action');
    var frmData = $(this).serialize();
    $.ajax({
    	type : 'post',
    	url  : frmUrl,
    	data : frmData,
      timeout : 3000,
      beforeSend :function(){
      composeInfo.addClass('alert alert-info');
      composeInfo.text('your data is processing');
      },
      success : function(data){
      composeInfo.removeClass();
      composeInfo.addClass('alert alert-success');
      composeInfo.html(data);
      },
      error : function(data){
      composeInfo.html(data);
      },
      complete : function(){
      setTimeout('window.location="home.php?page=compose"', 4000);
      }
    });
	});
	</script>
</div>