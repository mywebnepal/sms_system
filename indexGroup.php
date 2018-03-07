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
    require_once('model/group.php');
    $db = new dbCon();
    $con = $db->connect();
    $grp = new group($con);
    $grp_data = $grp->index();
?>
<?php
     if ($usrName =='admin') {?>
       <div class="row">
       <div class="grpIndexInfo"></div>
       <div class="col-sm-12 mainFrame">
        <h4>Search Staff in this group</h4><hr>
        <div class="row">
           <div class="col-sm-12 col-md-12">
           <a href="?page=cGrp"><button class="btn btn-primary">Add Group name</button></a>
            <table class="table table-striped table-hover">
             <thead>
              <th>Sn</th>
              <th>Group name</th>
              <th>Section</th>
              <th colspan="2">Action</th>
             </thead>
             <tbody>
              <?php
                if (!empty($grp_data)) {
                  $count = 1;
                  foreach ($grp_data as $key => $rows) {?>
                    <tr>
                      <td><?php echo $count++;  ?></td>
                      <td><?php echo $rows['grp_name'];  ?></td>
                      <td><?php echo $rows['section'];  ?></td>
                      <td>
                      <form action="?page=cGrp" method="post">
                          <input type="hidden" name="hidden_grp_id" value="<?php echo $rows['grp_id']; ?>">
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                      </form>
                      </td>
                      <td>
                      <form action="route/process.php" method="post" class="DelGroup">
                               <input type="hidden" name="tag" value="delGrp_id">
                          <input type="hidden" name="del_grp_id" value="<?php echo $rows['grp_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
           </div>
</div>
</div>
     <?php
     }else{
      echo "<h4 class='alert alert-danger'>Oops your don't have access!!!</h4>";
     }
?>

<script type="text/javascript">
   var DelGroup     = $('.DelGroup');
   var grpIndexInfo = $('.grpIndexInfo');
   DelGroup.on('submit', function(e){
       e.preventDefault();
         var delGrp = confirm('Are your sure you want to delete this group');
         if (delGrp == true) {
             var frmUrl = $(this).attr('action');
             var frmData = $(this).serialize();
            $.ajax({
              type : 'post',
              url  : frmUrl,
              data : frmData,
              timeout : 3000,
              beforeSend :function(){
              grpIndexInfo.addClass('alert alert-info');
              grpIndexInfo.text('your data is processing');
              },
              success : function(data){
              grpIndexInfo.removeClass();
              grpIndexInfo.addClass('alert alert-success');
              grpIndexInfo.html(data);
              },
              error : function(data){
              grpIndexInfo.html(data);
              },
              complete : function(){
              setTimeout('window.location="home.php?page=indexGroup"', 3000);
              }
            });
         }else{
           return false;
         }
   });
</script>