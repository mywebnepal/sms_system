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
$smsData = $sms->getSingleSendBox();
?>
<?php
    if ($usrName=='admin') {?>
    	<div class="row">
    		<div class="col-sm-12 col-md-12 mainFrame">
    			<h4>Send Single SMS</h4><hr>
    			<div class="singleSmsInfo"></div>
    			<div class="col-sm-4">
    				<form action="route/process.php" method="post" id="singleSmsFrm">
            <div id="dynamicInput">
              Phone Number<br><input type="number" name="singlePhone[]" placeholder="Please Enter phone number here" title="Phone number with 10 digit" class="form-control sngNumber" required>
            </div>
            <input type="button" value="Add another phone Number" onClick="addInput('dynamicInput');">
    				<!--  -->
    				<fieldset>
    					<label><i>SMS</i></label>
    					<textarea name="single_sms_body" rows="6" pattern="^[A-Za-z0-9\s\@\.]+" title="only accepted a to z 1-9 and @ ." class="form-control" required></textarea>
    				</fieldset><br>
    				<fieldset>
    				    <input type="hidden" name="tag" value="<?php echo 'sendSingleSms';  ?>">
    					<input type="submit" class="btn btn-primary btn-sm">
    				</fieldset>
    			</form>
    			</div>
    			<div class="col-sm-8">
    				<h4>Single SMS Send Box</h4><hr>
    				<?php
                        if (!empty($smsData)) {
                        	$count = 1;
                        	echo "<table class='table table-striped table-hover'>
                                  <th>Sn</th>
                                  <th>Date</th>
                                  <th>PhoneNumber</th>
                                  <th>SMS</th>
                                  <th colspan='3'>Action</th>";
                        	foreach ($smsData as $data) {?>
                        		<tr>
                        			<td><?php echo $count++;  ?></td>
                        			<td><?php echo $data['msg_date'];  ?></td>
                        			<td><?php echo $data['phoneNumber'];  ?></td>
                        			<td><?php echo substr($data['body'], 0,10); ?></td>
                                    <td>
                                       <?php 
                                           if ($data['ack']==1) {?>
                                           	  <label class="label-success">Send</label>
                                           <?php
                                           }else{?>
                                              <label class="label-danger">Not-send</label>
                                          <?php
                                            }
                                       ?>
                                     </td>
                                     <td>
                                     <form action="?page=sngSmsView" method="post">
                                       <input type="hidden" name="sngViewId" value="<?php echo $data['msg_id'];  ?>">
                                      <button class="btn btn-primary btn-sm">View</button>
                                     </form>
                                     </td>
                                     <td>
                                        <form action="route/process.php" method="post" class="delSingleSMSFrm">
                                          <input type="hidden" name="tag" value="delsinSMS">
                                          <input type="hidden" name="delsinID" value="<?php echo $data['msg_id'];   ?>">
                                           <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                      </td>
                        		</tr>
                        	<?php
                            }
                            echo "</table>";
                        }
    				?>
    			</div>
    		</div>
    	</div>
    <?php
    }
?>
<script type="text/javascript">
  var counter = 1;
  var limit = 10;
  function addInput(divName){
       if (counter == limit)  {
            alert("You have reached the limit of adding " + counter + " inputs");
       }
       else {
            var newdiv = document.createElement('div');
            newdiv.innerHTML = "Phone Number " + (counter + 1) + "<br><input type='number' name='singlePhone[]'' placeholder='Please Enter phone number here' title='Phone number with 10 digit' class='form-control sngNumber' required>";
            document.getElementById(divName).appendChild(newdiv);
            counter++;
       }
  }
</script>
<script type="text/javascript">
	var singleSmsInfo = $('.singleSmsInfo');
	var singleSmsFrm  = $('#singleSmsFrm');
	singleSmsFrm.on('submit', function(e){
    e.preventDefault();
    var sngNumber     = $('.sngNumber').val();
	if (sngNumber.length >=10) {
    var frmUrl  = $(this).attr('action');
    var frmData = $(this).serialize();
    $.ajax({
      type : 'post',
      url  : frmUrl,
      data : frmData,
      timeout : 3000,
      beforeSend :function(){
      singleSmsInfo.addClass('alert alert-info');
      singleSmsInfo.text('your data is processing');
      },
      success : function(data){
      singleSmsInfo.removeClass();
      singleSmsInfo.addClass('alert alert-success');
      singleSmsInfo.html(data);
      },
      error : function(data){
      singleSmsInfo.html(data);
      },
      complete : function(){
      setTimeout('window.location="home.php?page=singleSms"', 3000);
      }
    });
	}else{
  singleSmsInfo.addClass('alert alert-danger');
    singleSmsInfo.text('number must be greater 10 length character');
    return false;
	}
	});
  var delSingleSMSFrm = $('.delSingleSMSFrm');
  delSingleSMSFrm.on('submit', function(e){
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
        singleSmsInfo.fadeIn(3000);
        singleSmsInfo.addClass('alert alert-info');
        singleSmsInfo.text('your data is processing');
        },
        success : function(data){
        singleSmsInfo.removeClass();
        singleSmsInfo.addClass('alert alert-success');
        singleSmsInfo.html(data);
        },
        error : function(data){
        singleSmsInfo.text(data);
        },
        complete : function(){
         setTimeout('window.location="home.php?page=?page=singleSms"', 3000);
        }
      });
  }else{
    return false;
  }
  });
</script>