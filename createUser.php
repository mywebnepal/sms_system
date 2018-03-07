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
     *Create User
   */
   require_once('model/db.php');
   require_once('model/group.php');
   require_once('model/login.php');
   require_once('model/user.php');
   $db = new dbCon();
   $con = $db->connect();
   $user = new userMnt($con);
   $grp = new group($con);
   $grpData = $grp->index();

   $UpdateId = isset($_POST['updateUsrId'])?$_POST['updateUsrId']:'';
   if (!empty($UpdateId)) {
     $data = $user ->show($UpdateId);
   }
?>
<?php
     if ($usrName =='admin') {?>
       <div class="row">
        <div class="col-sm-12 mainFrame">
          <h4>Create User</h4><hr>
             <div class="row">
               <div class="usrCreateInfo"></div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                  <form action="route/process.php" method="post" id="usrCreateForm">
                    <?php if (!empty($data)) {?>
                        <input type="hidden" name="upd_usr_details" value="<?php echo $data['user_id'];  ?>">
                     <?php
                        } 
                     ?>
                <fieldset>
                  <label>First name</label>
                  <input type="text" name="fname" class="form-control" placeholder="Enter first name here" pattern="^[A-Za-z]+"  maxlength="20"  title="need alphabet not number or any symbol" value="<?php if (!empty($data)) {
                        echo $data['fname'];
                   }?>" required>
                </fieldset>
                <fieldset>
                  <label>Last name</label>
                  <input type="text" name="lname" class="form-control" placeholder="Enter first name here" pattern="^[A-Za-z]+" maxlength="20" title="need alphabet not number or any symbol" value="<?php if (!empty($data)) {
                        echo $data['lname'];
                   }?>"  required>
                </fieldset>
                <fieldset>
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" placeholder="Enter username here" pattern="^[A-Za-z0-9]+" maxlength="8" title="need alphabet and number only..." value="<?php if (!empty($data)) {
                        echo $data['username'];
                   }?>" required>
                </fieldset>
                 <?php
                     if (empty($data)) {?>
                       <fieldset>
                         <label>Password</label>
                         <input type="password" name="pwd1" class="form-control" placeholder="Enter password" pattern="^[A-Za-z0-9]+" maxlength="16" title="need alphabet and number only..." required>
                       </fieldset>
                       <fieldset>
                         <label>Confirm Password</label>
                         <input type="password" name="pwd2" class="form-control" placeholder="Enter password" pattern="^[A-Za-z0-9]+" maxlength="16" title="need alphabet and number only..." required>
                       </fieldset>
                     <?php
                     }
                 ?>
               
                </div>
                <div class="col-sm-6">
                 <fieldset>
                   <label>Allocate SMS Quotas</label>
                   <?php
                        if (empty($UpdateId)) {?>
                         <input type="number" name="alc_sms_qta" placeholder="allocate sms quotas" class="form-control" value="">
                       <?php
                        }else{?>
                        <input type="number" name="alc_sms_qta" placeholder="allocate sms quotas" class="form-control" value="<?php if (!empty($data)) {
                           echo $data['smsQuotas'];
                         }  ?>"  readonly>
                        <?php
                       }
                   ?>
                   
                 </fieldset>
                <fieldset>
                  <label>Assign In Group</label>
                  <select class="form-control" name="assign_id">
                     <option value="<?php if (!empty($data)) {
                         echo $data['grp_id'];
                      }else{
                       echo "";
                       }  ?>"><?php if (!empty($data)) {
                         echo $data['grp_name'].'&nbsp;'.$data['section'];
                       }else{
                         echo "Please select goup name";
                         }  ?></option>
                     <?php
                                    if (!empty($grpData)) {
                                        foreach ($grpData as $data) {?>
                                         <option value="<?php echo $data['grp_id']; ?>">
                                         <?php echo $data['grp_name'].'&nbsp'.$data['section'];  ?>
                                         </option>
                                       <?php
                                        }
                                    }else{
                                      echo "<h4 class='alert alert-danger'>Oops there is no data for selecting group please insert in group first</h4>";
                                    }
                     ?>
                  </select>
                </fieldset>
                <fieldset>
                     <input type="hidden" name="tag" value="CreateUser">
                  <br><button type="submit" class="btn btn-primary btn-md">Create....</button>
                </fieldset>
                </div>
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
          var usrCreateInfo = $('.usrCreateInfo');
          var usrCreateForm = $('#usrCreateForm');
          usrCreateForm.on('submit', function(e){
          e.preventDefault();
          var frmUrl = $(this).attr('action');
          var frmData = $(this).serialize();
          $.ajax({
          	type : 'post',
          	url  : frmUrl,
          	data : frmData,
            timeout : 3000,
            beforeSend :function(){
            usrCreateInfo.addClass('alert alert-info');
            usrCreateInfo.text('your data is processing');
            },
            success : function(data){
            usrCreateInfo.removeClass();
            usrCreateInfo.addClass('alert alert-success');
            usrCreateInfo.html(data);
            },
            error : function(data){
            usrCreateInfo.text(data);
            },
            complete : function(){
             setTimeout('window.location="home.php?page=systemUser"', 3000);
            }
          });
          });

	</script>
</div>