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
	require_once('model/assign.php');
	$db = new dbCon();
	$con = $db->connect();
	$grp = new group($con);
	$grp_data = $grp->index();
	$ass = new assign($con);
?>
<?php
     if ($usrName =='admin') {?>
       <div class="row">
        <div class="col-sm-12 mainFrame">
          <h4>Search List of member assign in Group</h4><hr>
          <div class="row">
            <div class="col-sm-12">
               <div class="assignListInfo"></div>
              <form action="?page=indexAssign" method="post" class="form-inline">
                <div class="form-group">
                  <select name="grp_id" class="form-control">
                    <option value="">Select Group Name........</option>
                    <?php
                                       if (!empty($grp_data)) {
                                        foreach($grp_data as $rows){?>
                                           <option value="<?php echo $rows['grp_id'];  ?>">
                                           <?php  echo $rows['grp_name'].'&nbsp;'.$rows['section'];    ?>
                                           </option>
                                        <?php
                                           }
                                       }
                    ?>
                  </select>
                  <input type="submit" name="searchGrpList" value="Search" class="btn btn-primary">
                </div>
              </form>
            </div>
          </div>
          <!-- table with group list -->
          <div class="row">
            <div class="col-sm-12">
               <?php
                          if (isset($_POST['searchGrpList'])) {
                            $grp_id = isset($_POST['grp_id'])?$_POST['grp_id']:'';
                            if (!empty($grp_id)) {
                              $ass->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $grp_id));
                              $ListOfMem = $ass->getMemNameFromGrpId();
                              if (!empty($ListOfMem)) {
                              echo "<hr><a href='?page=assignInGrp'><button class='btn btn-primary btn-sm'>Add Member</button></a>";
                              echo "<table class='table table-striped table-hover'>
                              <thead>
                                 <th>Sn</th>
                                 <th>Group</th>
                                 <th>MemId</th>
                                 <th>Name</th>
                                 <th>Address</th>
                                 <th>Phone</th>
                                 <th>Email</th>
                                 <th colspan='2'>Action</th>
                              </thead>";
                              $count =1;
                                  foreach ($ListOfMem as $data) {?>
                                  <tr>
                                    <td><?php echo $count++;  ?></td>
                                    <td><?php echo $data['grp_name'].'&nbsp'.$data['section'];  ?></td>
                                    <td><?php echo $data['uniq_id'];  ?></td>
                                    <td><?php echo $data['firstname'].'&nbsp'.$data['lastname'];  ?></td>
                                    <td><?php echo $data['addr'];  ?></td>
                                    <td><?php echo $data['phone'];  ?></td>
                                    <td><?php echo $data['email'];  ?></td>
                                    <td>
                                      <form action="route/process.php" method="post" class="delAssignIdFrm">
                                        <input type="hidden" name="tag" value="DelAssignId">
                                        <input type="hidden" name="assign_id" value="<?php echo $data['assign_id'];  ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                      </form>
                                    </td>
                                  </tr>
                                  <?php
                                   }
                              }else{
                                echo "<h4><i>There is no member in this group</i>";
                              }
                            }
                          }else{
                            echo "<hr><h4><i>Please search From first of see the Group List</i></h4>";
                          }
               ?>
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
  var delAssignIdFrm = $('.delAssignIdFrm');
  var assignListInfo = $('.assignListInfo');
  delAssignIdFrm.on('submit', function(e){
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
        assignListInfo.fadeIn(3000);
        assignListInfo.addClass('alert alert-info');
        assignListInfo.text('your data is processing');
        },
        success : function(data){
        assignListInfo.removeClass();
        assignListInfo.addClass('alert alert-success');
        assignListInfo.html(data);
        },
        error : function(data){
        assignListInfo.text(data);
        },
        complete : function(){
         setTimeout('window.location="home.php?page=?page=indexAssign"', 3000);
        }
      });
  }else{
    return false;
  }
  });
</script>
