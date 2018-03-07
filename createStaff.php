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
    require_once('model/member.php');
    $db = new dbCon();
    $con = $db->connect();
    $mem = new member($con);
    $mem_id = isset($_POST['mem_id']) ? $_POST['mem_id']:'';
    if (!empty($mem_id)) {
       $mem_data = $mem->show($mem_id);
    }

 ?>
 <?php
     if ($usrName =='admin') {?>
     	<div class="row">
     		<div class="col-sm-12 col-md-12 col-lg-12 mainFrame">
     			<h4><i>Create Staff Details</i></h4><hr>
     			<div class="row">
     			<div class="memInfo"></div>
     			<div class="col-sm-6 col-md-6 col-lg-6">
     				<form action="route/process.php" method="post" id="createMem">
     				    <fieldset>
     						<label>Staff ID</label>
     						<input type="text" name="stf_id"  class="form-control" placeholder='Enter Staff ID if have..' pattern="^[A-Za-z0-9]+" title="needed alfa numberic" 
     						value="<?php if (!empty($mem_data)) {
     							echo $mem_data['uniq_id'];
     						}  ?>">
     					</fieldset>

     					<fieldset>
     						<label>First name</label>
     						<input type="text" name="fname" class="form-control" placeholder='Enter full name here..' pattern="^[A-Za-z]+"  maxlength="20"  title="need alphabet not number or any symbol" value="<?php if (!empty($mem_data)) {
     							echo $mem_data['firstname'];
     						}  ?>" required>
     					</fieldset>

     					<fieldset>
     						<label>Last name</label>
     						<input type="text" name="lname" class="form-control" placeholder='Enter full name here..' pattern="^[A-Za-z]+"  maxlength="20"  title="need alphabet not number or any symbol" value="<?php if (!empty($mem_data)) {
     							echo $mem_data['lastname'];
     						}  ?>" required>
     					</fieldset>

     					<fieldset>
     						<label>Address</label>
     						<input type="text" name="addr" class="form-control" placeholder='Enter full name here..' pattern="^[A-Za-z0-9\s]+" title="alfa numberic is needed"  maxlength="60"  title="need alphabet Or number" value="<?php if (!empty($mem_data)) {
     							echo $mem_data['addr'];
     						}  ?>" required>
     					</fieldset>

     					
     	       </div>
     	       <div class="col-sm-6 col-md-6 col-lg-6">
     					<fieldset>
     						<label>Phone No</label>
     						<input type="text" name="phone" class="form-control" placeholder='Enter full name here..' pattern="^[0-9]+"  maxlength="13" minlength='10'  title="need  number Only with 10 length" value="<?php if (!empty($mem_data)) {
     							echo $mem_data['phone'];
     						}  ?>" required>
     					</fieldset>

     					<fieldset>
     						<label>Email</label>
     						<input type="email" name="email" class="form-control" placeholder='Enter full name here..' pattern="^[[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$]+" title="email format is not match" value="<?php if (!empty($mem_data)) {
     							echo $mem_data['email'];
     						}  ?>">
     					</fieldset>

     					<?php
     	                    if (!empty($mem_data)) {?>
     	                    	<input type="hidden" name="hiddenMemId" value="<?php echo $mem_data['mem_id'];  ?>">
     	                    <?php
     	                   }
     					?>

     					<fieldset>
     					    <input type="hidden" name="tag" value="createMember">
     						<p  style="margin-top:1em"><button type="submit" class="btn btn-primary btn-md">Submit.....</button></p>
     					</fieldset>
     	        </div>
     				</form>
     			</div>
     			</div>
     		</div>
     <?php
     }else{
     	echo "<h4 class='alert alert-danger'>Oops you don't have access</h4>";
     }

 ?>
	<script type="text/javascript">
	          var memInfo = $('.memInfo');
	          var memFrm = $('#createMem');
	          memFrm.on('submit', function(e){
	          e.preventDefault();
	          var frmUrl = $(this).attr('action');
	          var frmData = $(this).serialize();
	          $.ajax({
	          	type : 'post',
	          	url  : frmUrl,
	          	data : frmData,
	            timeout : 3000,
	            beforeSend :function(){
	            memInfo.addClass('alert alert-info');
	            memInfo.text('your data is processing');
	            },
	            success : function(data){
	            memInfo.removeClass();
	            memInfo.addClass('alert alert-success');
	            memInfo.html(data);
	            },
	            error : function(data){
	            memInfo.text(data);
	            },
	            complete : function(){
	             setTimeout('window.location="home.php?page=indexStaff"', 3000);
	            }
	          });
	          });
		</script>
</div>