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
    $mem_data = $mem->index();
?>
<?php
     if ($usrName =='admin') {?>
       <div class="row">
       	<div class="col-sm-12 col-md-12 mainFrame">
       		<h4>List of Member..</h4><hr>
       		<div class="row">
       			<div class="col-sm-12">
       			<a href="?page=cStaff"><button class="btn btn-primary btn-md">Add Member</button></a><br>
                   <!-- searching staff details from here -->
                   <br><div class="input-group">
                     <input type="text" class="form-control srchStaff" placeholder="Search staff Details by name or phone numher" pattern="^[A-Za-z0-9]+" maxlength="50">
                     <span class="input-group-addon" id="basic-addon2">Search Staff Details</span>
                   </div><br>
                   <!-- searching staff details end -->
                       <div class="staffInfo"></div>
       				<table class="table table-striped table-hover">
       					<thead>
       						<th>sn</th>
       						<th>Id</th>
       						<th>Firstname</th>
       						<th>Lastname</th>
       						<th>Phoneno</th>
       						<th>Email</th>
       						<th>address</th>
       						<th colspan="3">Action</th>
       					</thead>
       					<tbody>
       						<tr>
       						    <?php
                       if (!empty($mem_data)) {
                       	$count = 1;
                       	foreach ($mem_data as $rows) {?>
                       	<tr>
                       		<td><?php echo $count++;  ?></td>
       									<td><?php echo $rows['uniq_id']; ?></td>
       									<td><?php echo $rows['firstname']; ?></td>
       									<td><?php echo $rows['lastname']; ?></td>
       									<td><?php echo $rows['phone']; ?></td>
       									<td><?php echo $rows['email']; ?></td>
       									<td><?php echo $rows['addr']; ?></td>
       									<td>
       									  <form action="?page=cStaff" method="post">
       									  	<input type="hidden" name="mem_id" value="<?php echo $rows['mem_id']; ?>">
       										<button class="btn btn-success btn-sm">Update</button>
       									  </form>
       									</td>
       									<td>
       										<form action="route/process.php" method="post" class="delStaff">
       									  	<input type="hidden" name="del_mem_id" value="<?php echo $rows['mem_id']; ?>">
       									  	<input type="hidden" name="tag" value="Del_Mem_Id">
       										<button class="btn btn-danger btn-sm">Delete</button>
       									  </form>
       									</td>
       									</tr>
                                   	<?php
                                      }
                                   }
       						    ?>
       					</tbody>
       				</table>
       			</div>
     <?php
     }else{
      echo "<h4 class='alert alert-danger'>Oops your don't have access!!!</h4>";
     }
?>

<script type="text/javascript">
	var delStaff  = $('.delStaff');
	var staffInfo = $('.staffInfo');
	staffInfo.hide();
	var srchStaff = $('.srchStaff');

	/*search staff name*/
	srchStaff.on('keyup', function(e){
    e.preventDefault();
    var getStaffNam = $(this).val();
    var tag = 'searchIndStaff';
    if (getStaffNam.length >=3) {
    	var frmUrl  = 'route/process.php';
    	var frmData = ({staffNam : getStaffNam, tag:tag});
    	$.ajax({
	  	type : 'post',
	  	url  : frmUrl,
	  	data : frmData,
	    timeout : 3000,
	    beforeSend :function(){
	    staffInfo.fadeIn(3000);
	    staffInfo.addClass('alert alert-info');
	    staffInfo.text('your data is processing');
	    },
	    success : function(data){
	    staffInfo.removeClass();
	    staffInfo.addClass('alert alert-success');
	    staffInfo.html(data);
	    },
	    error : function(data){
	    staffInfo.text(data);
	    }
	  });
    }else{
    	staffInfo.hide();
    }
	});
	/*------*/

	delStaff.on('submit', function(e){
	e.preventDefault();
	var con = confirm('Are you sure you want to delete this staff ?');
	if (con == true) {
	  var frmUrl = $(this).attr('action');
	  var frmData = $(this).serialize();
	  $.ajax({
	  	type : 'post',
	  	url  : frmUrl,
	  	data : frmData,
	    timeout : 3000,
	    beforeSend :function(){
	    staffInfo.fadeIn(3000);
	    staffInfo.addClass('alert alert-info');
	    staffInfo.text('your data is processing');
	    },
	    success : function(data){
	    staffInfo.removeClass();
	    staffInfo.addClass('alert alert-success');
	    staffInfo.html(data);
	    },
	    error : function(data){
	    staffInfo.text(data);
	    },
	    complete : function(){
	     setTimeout('window.location="home.php?page=indexStaff"', 3000);
	    }
	  });
}else{
	return false;
}
});
	/*--------------------------------------------*/
      var del_mem_details = $('.del_mem_details');
      del_mem_details.on('click', function(e){
      e.preventDefault();
      alert('ehllo');
      });
</script>
		</div>
	</div>
</div>