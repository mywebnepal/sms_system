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
     * Create group
   */ 
   $title = 'Create Group'; 
   require_once('model/db.php');
   require_once('model/group.php');
   require_once('model/login.php');
   $db = new dbCon();
   $con = $db->connect();
   $grp = new group($con);
   $grp_id = isset($_POST['hidden_grp_id'])?$_POST['hidden_grp_id']:'';
   if ($grp_id) {
     $grpData = $grp->show($grp_id);
   }
?>
<?php
     if ($usrName =='admin') {?>
       <div class="row">
        <div class="col-sm-12 col-md-12 mainFrame">
          <h4>Create Group/Class room</h4><hr>
        <div class="row">
        <div class="createGroupInfo"></div>
          <div class="col-sm-6">
            <form action="route/process.php" method="post" id="createGroup">
              <fieldset>
                <label>Group/Classroom name</label>
                <input type="text" name="group_name" class="form-control" pattern="^[A-Za-z0-9]+" maxlength="20" title="please insert alfa numbernic" placeholder="Create class room Or group Name" 
                 value="<?php if (!empty($grpData)) {
                        echo $grpData['grp_name'];
                 } ?>" required>
              </fieldset>
              <fieldset>
                <label>Section</label>
                <input type="text" name="section" pattern="^[A-Za-z0-9]+" maxlength="20" title="please insert alfa numbernic" placeholder="Enter section Name" class="form-control" value="<?php if (!empty($grpData)) {
                        echo $grpData['section'];
                 } ?>">
              </fieldset><br>
              <fieldset>
                   <?php 
                        if (!empty($grpData)) {?>
                           <input type="hidden" name="updateGrpId" value="<?php echo $grpData['grp_id'];  ?>">
                       <?php
                          }
                   ?>
                  <input type="hidden" name="tag" value="CreateGrp">
                <button type="submit" class="btn btn-primary btn-md">Submit.... </button>
              </fieldset>
            </form>
          </div>
        </div>
        </div>
     <?php
     }else{
      echo "<h4 class='alert alert-danger'>Oops your don't have access!!!</h4>";
     }
?>
	<script type="text/javascript">
	var createGroupFrm = $('#createGroup');
	var createGroupInfo = $('.createGroupInfo');
    createGroupFrm.on('submit', function(e){
          e.preventDefault();
          var frmUrl = $(this).attr('action');
          var frmData = $(this).serialize();
          $.ajax({
          	type : 'post',
          	url  : frmUrl,
          	data : frmData,
            timeout : 3000,
            beforeSend :function(){
            createGroupInfo.addClass('alert alert-info');
            createGroupInfo.text('your data is processing');
            },
            success : function(data){
            createGroupInfo.removeClass();
            createGroupInfo.addClass('alert alert-success');
            createGroupInfo.html(data);
            },
            error : function(data){
            createGroupInfo.html(data);
            },
            complete : function(){
            setTimeout('window.location="home.php?page=cGrp"', 3000);
            }
          });
          });
	</script>
</div>