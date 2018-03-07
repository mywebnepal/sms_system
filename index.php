<?php 
     require('inc/header.php'); 
     $a = rand(2,9);
     $b = rand(2, 9);
     $c = $a + $b;
?>
<div class="row nopadding nomargin">
   <div class="container">
   	   <div class="col-sm-12 col-md-12 col-lg-12">
	        <div class="smsLogin">
	        	<h3>SMS Login</h3>
	        	<div class="smsInfo"></div>
	        	<form action="route/process.php" method="post" id="sms_login">
	        		<fieldset>
	        			<label>Username</label>
	        			<input type="text" name="usrname" pattern="^[A-Za-z0-9]+" title="Only Alphabet and number are usded..." placeholder="Username" class="form-control" required>
	        		</fieldset>
	        		<fieldset>
	        			<label>Password</label>
	        			<input type="password" name="pwd" pattern="^[A-Za-z0-9]+" title="Only Alphabet and number are usded..."  placeholder="Password" class="form-control" required>
	        		</fieldset>
	        		<fieldset>
	        		   <p style="padding:1em 0em;">
	        			<input type="number" name="num1" class="inline num1" width="20px" required> +
	        			<input type="number" name="num2" class="inline num2" width="20px" required> = &nbsp;
	        			<strong><span id="tot"><?php echo $c;  ?></span></strong>
	        			</p>
	        		</fieldset>
	        		<fieldset>
	        		     <input type="hidden" name="tag" value="Login_process">
	        		     <input type="hidden" name="sumText" value="<?php echo $c;  ?>">
	        			<button type="submit" class="btn btn-primary">Login</button>
	        		</fieldset>
	        	</form>
	        </div>
	   </div>
   </div>
</div>
<script type="text/javascript">
    /*checking the number for login*/
	var smsInfo = $('.smsInfo');
	var smsLoginFrm = $('#sms_login');

	smsLoginFrm.on('submit', function(e){
    e.preventDefault();
    var frmUrl  = $(this).attr('action');
    var frmData = $(this).serialize();
    $.ajax({
      type : 'post',
      url  : frmUrl,
      data : frmData,
      timeout : 3000,
      beforeSend : function(){
      	smsInfo.addClass('alert alert-info');
      	smsInfo.text('your task is processing');
      },
      success : function(data){
      var rply = jQuery.parseJSON(data);
      console.log(rply);
      if (rply.res ==1) {
      	smsInfo.addClass('alert alert-success');
      	 smsInfo.html(rply.msg);
         setTimeout('window.location="/sms/home.php"', 4000);
      }else{
      	smsInfo.addClass('alert alert-danger');
         smsInfo.html(rply.msg);
      	 setTimeout('window.location="/sms"', 4000);
      }
      },
      error : function(data){
       smsInfo.html(data);
      },
      complete : function(){
      smsInfo.fadeOut(3000);
      }
    });
	});
</script>
<?php require('inc/footer.php'); ?>