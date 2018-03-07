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
    /*-----group data*/
    require_once('model/member.php');
    $mem = new member($con);
    $mem_data = $mem->index();
?>
<?php
     if ($usrName =='admin') {?>
       <div class="row">
        <div class="col-sm-12 col-md-12 mainFrame">
          <h4>Assign Staff/student in Group</h4>
          <div class="SmemInfo"></div>
          <form action="route/process.php" method="post" id="sng_mem_in_grp" class="navbar-form navbar-left" role="form">
                   <div class="input-group">
                       <span class="input-group-addon">
                       <i class="glyphicon glyphicon-user"></i></span>
                       <input type="text" class="form-control" name="sng_mem_name" value="" placeholder="search member name from here.........." id="mem_nam" pattern="^[A-Za-z\s]+" title="Enter string only not number not symbol" autocomplete="off" required>
                   </div>                                     
                   <div class="input-group">
                       <span class="input-group-addon">
                       <i class="glyphicon glyphicon-lock"></i></span>
                       <select class="form-control" name="sng_grp_id">
                        <option value="">Select Group Name....</option>
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
                   </div>
                   <div class="input-group">
                       <span class="input-group-addon">
                       <i class="glyphicon glyphicon-lock"></i></span>
                         <select name="sng_enroll_date" class="form-control" required>
                             <option value="">Please select Enroll date</option>
                             <?php for ($i=2073; $i < 2080; $i++) { ?>
                               <option value="<?php echo $i;  ?>"><?php echo $i;  ?></option>
                             <?php
                             }   
                             ?>
                           </select>                                     
                   </div>
                   <input type="hidden" name="tag" value="singleAssignInGrp">
                   <button type="submit" class="btn btn-primary">Insert In Group</button>
                    <div class="mem_data"></div>   
              </form>
                 <!-- single submit form -->

                 <div class="row"><!-- multiple add staff in class room -->
                  <div class="col-sm-12 col-md-12">
                 <hr>
                    <h4 class="text-left">Add Multiplely...</h4>
                    <form action="route/process.php" method="POST" id="multipleInsertIngrp">
                    <div class="col-sm-4 col-md-4 stdnam_list" style="background:#F1F1F1;">
                     <label><input type='checkbox' id='selectall'>&nbsp;Select All</label><br>
                    <?php 
                            if (!empty($mem_data)) {
                              foreach ($mem_data as $mem) {?>
                                <label><i>
                                <input type="checkbox" name="mem_id[]" class="nopadding nomargin chkStaff" value="<?php echo $mem['mem_id'];  ?>">&nbsp;&nbsp;&nbsp;<?php echo $mem['firstname']. '&nbsp;'.$mem['lastname']. '&nbsp&nbsp'.'/&nbsp'.$mem['phone'];?></i></label><br>
                              <?php
                              }
                            }
             
                    ?>
                    </div>
                    <div class="col-sm-8 col-md-8">
                      <select class="form-control" name="grp_id" required>
                            <option value="">Select Group Name....</option>
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
                        </select> <br>

                        <select name="enroll_date" class="form-control" required>
                           <option value="">Please select Enroll date</option>
                           <?php for ($i=2073; $i < 2080; $i++) { ?>
                             <option value="<?php echo $i;  ?>"><?php echo $i;  ?></option>
                           <?php
                           }   
                           ?>
                         </select> <br>

                              <input type="hidden" name="tag" value="multiple_data">
                              <button type="submit" class="btn btn-primary btn-md">Insert In Group.....</button> 
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
	/*search member name */
    var mem_name = $('#mem_nam');
    var mem_data = $('.mem_data');
    var s_mem_info = $('.SmemInfo');

    s_mem_info.hide();
    mem_data.hide();
    mem_name.on('keyup', function(){
    var mem_wrd = mem_name.val();
    if (mem_wrd.length >= 3) {
    var tag  = 'src_mem_name_id';
    var data = ({mem_data:mem_wrd, tag:tag});
    	$.ajax({
            type:'POST',
            url : 'route/process.php',
            data : data,
            beforeSend:function(){
            s_mem_info.fadeIn();
            s_mem_info.addClass('alert alert-info');
            s_mem_info.text('your are in working mode');
            },
            success:function(data){
            console.log(data);
            mem_data.fadeIn(2000);
            mem_data.addClass('alert alert-success');
            mem_data.html(data);
            }
    	});
    }else{
      s_mem_info.fadeOut(3000);
      mem_data.fadeOut(1000);
    }
    });
    $('#selectall').click(function () {    
        $('input:checkbox').prop('checked', this.checked);  
    });

    var hiddenMemId = $('.hiddenMemId');
    var hiddenId = $('.hiddenId').val();
    $(document).on('click', 'dt', function(){
    	mem_name.val($(this).text());
    	mem_data.fadeOut(1000);
    	s_mem_info.fadeOut(1000);
    });
    /*search member name */
     var sng_mem_frm = $('#sng_mem_in_grp');
     sng_mem_frm.on('submit', function(e){
     e.preventDefault();
     var frmUrl = $(this).attr('action');
     var frmData = $(this).serialize();
     $.ajax({
      type : 'post',
      url  : frmUrl,
      data : frmData,
       timeout : 3000,
       beforeSend :function(){
       s_mem_info.fadeIn();
       s_mem_info.addClass('alert alert-info');
       s_mem_info.text('your data is processing');
       },
       success : function(data){
       s_mem_info.removeClass();
       s_mem_info.addClass('alert alert-success');
       s_mem_info.html(data);
       },
       error : function(data){
       s_mem_info.html(data);
       },
       complete : function(){
       setTimeout('window.location="home.php?page=assignInGrp"', 3000);
       }
     });
   });
     /*single insert in group name*/

     /*multiple insert in user in group*/
     var multipleInsertIngrp = $('#multipleInsertIngrp');
     multipleInsertIngrp.on('submit', function(e){
     e.preventDefault();
     var frmUrl  = $(this).attr('action');
     var frmData = $(this).serialize();
     $.ajax({
     	type : 'post',
     	url  : frmUrl,
     	data : frmData,
       timeout : 3000,
       beforeSend :function(){
       s_mem_info.fadeIn();
       s_mem_info.addClass('alert alert-info');
       s_mem_info.text('your data is processing');
       },
       success : function(data){
       s_mem_info.removeClass();
       s_mem_info.addClass('alert alert-success');
       s_mem_info.html(data);
       },
       error : function(data){
       s_mem_info.html(data);
       },
       complete : function(){
       setTimeout('window.location="home.php?page=assignInGrp"', 3000);
       }
     });
     });

	</script>
</div>