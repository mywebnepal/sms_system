<?php 
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
$updateId   = isset($_POST['QuotasIdUpdate'])?$_POST['QuotasIdUpdate']:'';
$username   = isset($_POST['uname'])?$_POST['uname']:'';
$alocQuotas = isset($_POST['aloc_quotas'])?$_POST['aloc_quotas']:'';
?>
<?php
     if ($usrName =='admin') {?>
     	<div class="row">
     		<div class="col-sm-12 col-md-12 mainFrame">
     			<h3>Update sysAdmin Quotas</h3><hr>
     			<div class="row">
     			<div class="updateQuotasInfo"></div>
     				<div class="col-sm-6">
     					<?php 
     					     if (!empty($updateId) && !empty($username) && !empty($alocQuotas)) {?>
     					     	<form action="route/process.php" method="post" id="updateQuotasFrm">
     					     	<fieldset>
     					     	   <label>Quotas Allocated</label>
     					     		<input name="UpdateQuotas" class="form-control" type="number" value="<?php echo $alocQuotas;  ?>">
     					     	</fieldset>
     					     	<fieldset>
     					     	    <input type="hidden" name="uname" value="<?php echo $username;  ?>">
     					     	    <input type="hidden" name="tag" value="UpdateQuotas">
     					     	    <input type="hidden" name="QuotasId" value="<?php echo $updateId; ?>">
     					     		<input type="submit" value="Update Quotas" class='btn btn-primary btn-sm'>
     					     	</fieldset>
     					     	</form>
     					     <?php
     					    }else{
     					    	echo "<h3>Ops you haven't fill from Form....</h3>";
     					    }
     					?>
     				</div>
     			</div>
     		</div>
     	</div>
     <?php
    }else{
     	echo "<h3 class='alert alert-danger'>Oosp you don't have access......</h3>";
     }
?>
<script type="text/javascript">
	var updateQuotasInfo = $('.updateQuotasInfo');
	var updateQuotasFrm  = $('#updateQuotasFrm');

	updateQuotasFrm.on('submit', function(e){
    e.preventDefault();
    var frmUrl  = $(this).attr('action');
    var frmData = $(this).serialize();
    alert(frmData);
    $.ajax({
    	type : 'post',
    	url  : frmUrl,
    	data : frmData,
      timeout : 3000,
      beforeSend :function(){
      updateQuotasInfo.addClass('alert alert-info');
      updateQuotasInfo.text('your data is processing');
      },
      success : function(data){
      updateQuotasInfo.removeClass();
      updateQuotasInfo.addClass('alert alert-success');
      updateQuotasInfo.html(data);
      },
      error : function(data){
      updateQuotasInfo.html(data);
      },
      complete : function(){
      setTimeout('window.location="home.php?page=systemUser"', 3000);
      }
    });
	});
</script>