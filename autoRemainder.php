<?php 
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
	echo "<script>
                 window.location.href = '/sms';
              </script>";
}
require_once('model/db.php');
require_once('model/group.php');
require_once('model/smsMsg.php');

$db = new dbCon();
$con = $db->connect();
$grp = new group($con);
$grp_data = $grp->index();
$sms = new smsMsg($con);
echo $updateId = isset($_POST['automatedUpdateId'])?$_POST['automatedUpdateId']:'';
if (!empty($updateId)) {
$data = $sms->getAutomatedSMSUpdate($updateId);
var_dump($data);
}


//the following block of code guards the license
$hit1 = stringMatchWithWildcard(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license'])) ? 1 : 0;
if(strlen($GLOBALS['license2'])>0)
	$hit2 = stringMatchWithWildcard(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license2'])) ? 1 : 0; else $hit2=0;
if($hit1==0 && $hit2==0) die();

?>
<?php
	if((!empty($updateId)))
	{
		$time1 = date_create($data['startDate']);
		$time2 = date_create($data['startDate']);
		$time3 = date_create($data['startDate']);
		date_add($time1, date_interval_create_from_date_string(($data['dayNum']).' days'));
		date_add($time2, date_interval_create_from_date_string(($data['s_dayNum']).' days'));
		date_add($time3, date_interval_create_from_date_string(($data['t_dayNum']).' days'));
		if($data['dayNum']==0 || empty($data['dayNum'])) $datesms_1=""; else $datesms_1 = $time1->format('Y-m-d');
		if($data['s_dayNum']==0 || empty($data['s_dayNum'])) $datesms_2=""; else $datesms_2 = $time2->format('Y-m-d');
		if($data['t_dayNum']==0 || empty($data['t_dayNum'])) $datesms_3=""; else $datesms_3 = $time3->format('Y-m-d');
	}
	else
	{$datesms_1 = NULL; $datesms_2 = NULL; $datesms_3 = NULL;}
   if ($usrName =='admin') {?>
   <form action="route/process.php" method="post" id="autoSendSmsFrm">
     <div class="row">
     	<div class="col-sm-12 col-md-12 mainFrame" style="margin: 0px !important; ">
      <div class="autoSendInfo"></div>
     		<h4>Create Auto Remainder SMS</h4><hr>
     		<div class="col-sm-6 col-md-6">
     			
     				<fieldset>
     					<label>To</label>
     					<select class="form-control" name="grp_id" required>
     						<option value="<?php if (!empty($data)) {
                  echo $data['grp_id'];
                } ?>">
                <?php 
                if (!empty($data)) {
                     echo $data['grp_name'].'&nbsp'.$data['section'];
                }else{
                  echo "Select Group Name";
                  }  ?>
                  </option>
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
     				</fieldset>
					     		 	<fieldset>
     				<label>SMS Box</label>
     					<textarea name="smsBody" rows="6" class="form-control" style="padding:0px; margin:0px;" required><?php 
               if (!empty($data)) {
                   echo $data['smsBody'];
                 } 
              ?></textarea>
     				</fieldset>

     				<!--  -->
            <fieldset>
           <!-- <label>Send SMS Date</label> -->
            <input type="hidden" name="smsStartDate" data-date="" data-date-format="DD MMMM YYYY" name="smsStartDate" placeholder="number of days..." class="form-control" value="<?php if (!empty($data)) {
              echo $data['startDate'];
            }   ?>" required>
            </fieldset>
          
     		 </div>
     		 <div class="col-sm-6 col-md-6">

     				<fieldset>
            <?php
                if (!empty($data)) {?>
                  <input type="hidden" name="hidden_UpdateId_autosend" value="<?php echo $data['automated_id'];  ?>">
               <?php
                 }
            ?>
               <input type="hidden" name="tag" value="autoSendSms">
     					<br><input type="submit" class="btn btn-primary" value="Submit">
						
     				</fieldset>
     		 </div>


     	</div>
		 <div class="col-sm-12 col-md-12 mainFrame" style="margin: 0px !important; ">
			 
			   <div style="float:left;">
            <label>First dispatch</label>
            <!--<input type="range" id="sliderBar" min="0" max="100" step="1" value="0" onChange="showValue(this.value);">-->
			<div id="calendarHere1"></div>
            <input type="text" name="smsDay" id="smsDay" title="date" placeholder="date..." class="form-control" value="<?php if (!empty($data)) {
              echo $datesms_1;
            }   ?>" <?PHP echo ((!empty($updateId))) ? "" : "required"; ?>>
            </div>
            <div style="float:left;">
            <label>Second dispatch</label>
            <!--<input type="range" id="sliderBar" min="0" max="100" step="1" value="0" onChange="showValue(this.value);">-->
			<div id="calendarHere2"></div>
            <input type="text" name="s_smsDay" id="s_smsDay" title="date" placeholder="date..." class="form-control" value="<?php if (!empty($data)) {
              echo $datesms_2;
            }   ?>">
            </div>
            <div style="float:left;">
            <label>Third dispatch</label>
            <!--<input type="range" id="sliderBar" min="0" max="100" step="1" value="0" onChange="showValue(this.value);">-->
			<div id="calendarHere3"></div>
            <input type="text" name="t_smsDay" id="t_smsDay" title="date" placeholder="date..." class="form-control" value="<?php if (!empty($data)) {
              echo $datesms_3;
            }   ?>">
            </div>
			 </div>
     </div>
	 			
	</form>
   <?php
   }else{
    echo "<h4 class='alert alert-danger'>Oops your don't have access!!!</h4>";
   }
?>
<script type="text/javascript">
var myCalendar1;
var myCalendar2;
var myCalendar3;
						
$( window ).load(function() {

    var myCalendar1 = new Pikaday(
    {
        field: document.getElementById('smsDay'),
		firstDay: 1,
        <?PHP echo ((!empty($updateId))) ? "" : "minDate: new Date(), maxDate: new Date(2032, 12, 31),"; ?>
        yearRange: [2000,2032],
		bound: false
    });
	myCalendar1.show();
    var myCalendar2 = new Pikaday(
    {
        field: document.getElementById('s_smsDay'),
        firstDay: 1,
        <?PHP echo ((!empty($updateId))) ? "" : "minDate: new Date(), maxDate: new Date(2032, 12, 31),"; ?>
        yearRange: [2000,2032],
		bound: false
    });
	myCalendar2.show();    
	var myCalendar3 = new Pikaday(
    {
        field: document.getElementById('t_smsDay'),
        firstDay: 1,
        <?PHP echo ((!empty($updateId))) ? "" : "minDate: new Date(), maxDate: new Date(2032, 12, 31),"; ?>
        yearRange: [2000,2032],
		bound: false
    });
	myCalendar3.show();

});

  var autoSendInfo   = $('.autoSendInfo');
  var autoSendSmsFrm = $('#autoSendSmsFrm');
  autoSendSmsFrm.on('submit', function(e){
  e.preventDefault();
  var frmUrl  = $(this).attr('action');
  var frmData = $(this).serialize();
  //alert(frmData);
  $.ajax({
    type : 'post',
    url  : frmUrl,
    data : frmData,
    timeout : 3000,
    beforeSend :function(){
    autoSendInfo.addClass('alert alert-info');
    autoSendInfo.text('your data is processing');
    },
    success : function(data){
    autoSendInfo.removeClass();
    autoSendInfo.addClass('alert alert-success');
    autoSendInfo.html(data);
    },
    error : function(data){
    autoSendInfo.html(data);
    },
    complete : function(){
    setTimeout('window.location="home.php?page=indexAutoSend"', 3000);
    }
  });
  });
</script>
