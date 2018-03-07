<?php
/*
   * create sms section
*/
class smsMsg
{
	private $tbl = 'msg';
	private $conn;
	public  $msg_id;
	public $subject;
	public $body;
	public $mem_id;
	public $grp_id;
	public $msg_date;
	public $ack;
  public $firstDay;
  public $secondDay;
  public $thirdDay;
	
	function __construct($con)
	{
		$this->conn = $con;
	}
	public function index(){
    $_index = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." order by firstname ASC");
    $index_num = mysqli_num_rows($_index);
    if ($index_num >=1) {
    	$data = array();
    	while ($rows = mysqli_fetch_assoc($_index)) {
    		$data[] = $rows;
    	}
    	return $data;
    }
    else{
    		echo "<h4 class='alert alert-danger'>Oops there is no data error code 1000</h4>";
    	}
	}

	public function create($sub, $body, $memId, $grp_id){
    date_default_timezone_set('Asia/Kathmandu');
    $sys_usr = $_SESSION['username'];
    $msg_date = date("Y-m-d h:i:sa");	
	 if ($sys_usr == 'admin') {
        $cnt = 0;
        foreach($memId as $mem){
        /*sending sms from this section*/
          $UsrPhone = mysqli_query($this->conn, "SELECT mem.phone,mem.mem_id, assign.mem_id, assign.grp_id, grp.grp_id FROM mem, assign, grp WHERE assign.mem_id = '".$mem."' AND assign.mem_id = mem.mem_id AND assign.grp_id = grp.grp_id");
          $phoneNumber = mysqli_fetch_assoc($UsrPhone);
          $counter = mysqli_num_rows($UsrPhone);
          if ($counter >=1) {
               $sendNum = $phoneNumber['phone'];/*GETTING PHONE NUMBER FROM DATABASE*/
			         $sendSms = $this->sparrowSms($sendNum, $body);
               if ($sendSms == 200) {
                 echo "<h4 class='alert alert-success'>".$cnt++."SMS Delivery</h4>";
                 $qry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser, phoneNumber, ack)VALUES('$sub', '$body', '$mem', '$grp_id', '$msg_date', '$username','$sendNum', '1')");

               }else{
                 echo "<h4 class='alert alert-danger'>Oops sms is not send try it again</h4>";
                 $qry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser,phoneNumber, ack)VALUES('$sub', '$body', '$mem', '$grp_id', '$msg_date', '$username','$sendNum', '0')");
               }

            }
          else{
             echo "<h4>Oops number there is no phone number please insert it</h4>";
             die();
          }
        /*ending of the sending sms*/
          }
      }else{
        $this->userCreate($sub, $body, $memId, $grp_id, $username);
      }
  }
    /*USER CREATE SMS */
    public function userCreate($sub, $body, $memId, $grp_id){
    $sys_usr = $_SESSION['username'];
    $alocQuots = $this->chkUsrQuotas($sys_usr);/*CHECKING USER QUOTAS*/
    $usrQuotas = $this->findSmsQuotas($sys_usr) + 1;/*ADDING SMS QOUTAS AFTER SEND*/
      /*here send sms code goes*/
    date_default_timezone_set('Asia/Kathmandu');
    $sys_usr = $_SESSION['username'];
    $msg_date = date("Y-m-d h:i:sa"); 
    $cnt = 0;
    foreach($memId as $mem){
    if ($alocQuots >= $usrQuotas || $sys_usr=='admin2') {
         $quotasCount = $usrQuotas ++;
        /*-----------send sms code---------------*/
        $UsrPhone = mysqli_query($this->conn, "SELECT mem.phone,mem.mem_id, assign.mem_id, assign.grp_id, grp.grp_id FROM mem, assign, grp WHERE assign.mem_id = '".$mem."' AND assign.mem_id = mem.mem_id AND assign.grp_id = grp.grp_id");
        $phoneNumber = mysqli_fetch_assoc($UsrPhone);
        /*echo $usedNumber = rtrim($phone, ',');*/
        $counter = mysqli_num_rows($UsrPhone);
        if ($counter >=1) {
           $sendNum = $phoneNumber['phone'];/*GETTING PHONE NUMBER FROM DATABASE*/
           $sendSms = $this->sparrowSms($sendNum, $body);
           if ($sendSms == 200) {
            $Susqry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser, phoneNumber, ack)VALUES('$sub', '$body', '$mem', '$grp_id', '$msg_date', '$sys_usr', '$sendNum', '1')");
             $updateSmsQuotas = mysqli_query($this->conn, "UPDATE smsquotas SET quotas = '".$quotasCount."'WHERE username = '".$sys_usr."'");
             echo "<h4 class='alert alert-success'>".$cnt++."SMS Delivery</h4>";

           }else{
            $Susqry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser,phoneNumber, ack)VALUES('$sub', '$body', '$mem', '$grp_id', '$msg_date', '$sys_usr','$sendNum', '0')");
             echo "<h4 class='alert alert-danger'>Oops sms is not send try it again</h4>";
           }

        }
        else{
           echo "<h4>Oops number there is no phone number please insert it</h4>";
           die();
        }
        /*-----------send sms code---------------*/
      }
      else{
          echo "<h3 class='alert alert-danger'>Your don't have enough sms Quotas... please contact to system admin</h3>";
         }
    }/*ENDING OF FOR LOOP*/
  }
  /*SENDING OF SMS FROM SPARROW*/
  public function sparrowSms($sendNum, $body){
    $args = http_build_query(array(
                   'token' => 'P1nQIqfAUtGa6aKPPeST',
                   'from'   => 'Demo',
                   'to'     =>$sendNum,
                   'text' => $body
                    ));
            $url = "http://api.sparrowsms.com/v2/sms/";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return $status_code;
        /**/
  }
    /*FINDING USER QUOTAS*/
    public function findSmsQuotas($username){
      $checkQuatos = mysqli_query($this->conn, "SELECT * FROM smsquotas WHERE username = '".$username."'");
      $quotasData = mysqli_fetch_assoc($checkQuatos);
      return $quotasData['quotas'];
    }
    /*CHECK USER QUOTAS*/
    public function chkUsrQuotas($username){
    $checkUsrQuotas = mysqli_query($this->conn, "SELECT username, smsQuotas FROM user WHERE username = '".$username."'");
    $usrData  = mysqli_fetch_assoc($checkUsrQuotas);
    return $usrData['smsQuotas'];
    }
    /*CREATE SINGLE SMS*/
    public function createSingle($phoneNumber, $body){
    date_default_timezone_set('Asia/Kathmandu');
    $msg_date = date("Y-m-d h:i:sa");
    $cnt = 0;
    foreach ($phoneNumber as $sendNum) {
       $sendSms = $this->sparrowSms($sendNum, $body);
       if($sendSms == 200) {
         $Susqry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser, phoneNumber, ack)VALUES('test', '$body', '0', '0', '$msg_date', '$sys_usr', '$sendNum', '1')");
          echo "<h3 class='alert alert-success'>Successfully send".$cnt ++." your sms</h3>";
       }
       else {
         $Susqry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser,phoneNumber, ack)VALUES('test', '$body', '0', '0', '$msg_date', '$sys_usr','$sendNum', '0')");
          echo "<h3 class='alert alert-success'>Your sms has been drafts please re-send it on time</h3>";
       }
    }
  }
    /*DELETING SINGLE SMS*/
    public function deleteSingleSMS($id){
    $qry = mysqli_query($this->conn, "DELETE FROM ".$this->tbl." WHERE msg_id = '".$id."'");
    if ($qry) {
    	echo "<h4 class='alert alert-success'>Successfully Deleted...</h4>";
    }else{
    	echo "<h4 class='alert alert-danger'>Ops couldn't deleted...</h4>";
    }
    }
    /*RESEND SECIOTN*/
    public function resend($id){
      $sql = mysqli_query($this->conn, "SELECT body, phoneNumber FROM msg WHERE msg_id = '".$id."'");
      $count = mysqli_num_rows($sql);
      if ($count >=1) {
        $data = mysqli_fetch_assoc($sql);
        $sendNum = $data['phoneNumber'];
        $body        = $data['body'];
          $sendSms = $this->sparrowSms($sendNum, $body);
          if($sendSms == 200) {
            $Susqry = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET ack = '1' WHERE msg_id = '".$id."'");
             echo "<h3 class='alert alert-success'>Successfully send your sms</h3>";
          }
          else {
            $Susqry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(subject, body, mem_id, grp_id, msg_date, sendUser,phoneNumber, ack)VALUES('test', '$body', '0', '0', '$msg_date', '$sys_usr','$sendNum', '0')");
             echo "<h3 class='alert alert-success'>Your sms has been drafts please re-send it on time</h3>";
          }
      }else{
        echo "<h4 class='alert alert-danger'>Data is not fount...</h4>";
      }
    }

    public function getSingleSendBox(){
    $sql = mysqli_query($this->conn, "SELECT msg_id, body, grp_id, phoneNumber, ack, msg_date FROM ".$this->tbl." WHERE grp_id ='0' AND mem_id = '0' ORDER BY msg_date asc");
    if ($sql) {
      $data = array();
      while ($rows = mysqli_fetch_assoc($sql)) {
      $data[] = $rows;
      }
      return $data;
    }else{
      echo "<h4 class='alert alert-danger'>Your haven't send single sms till now....</h4>";
    }
    }

    public function getSendBoxMsg(){
     $username =  $_SESSION['username'];
     $grp_id   =  $_SESSION['grp_id'];
     if ($username =='admin') {
       $qry = mysqli_query($this->conn, "SELECT msg.msg_id, msg.ack, msg.subject, msg.grp_id,msg.mem_id, msg.body,msg.msg_date, msg.sendUser, mem.firstname, mem.lastname,mem.mem_id, grp.grp_id, grp.grp_name,grp.section FROM msg, mem, grp WHERE msg.grp_id = grp.grp_id AND msg.mem_id = mem.mem_id AND msg.ack = '1' ORDER BY msg.msg_date ASC");
       if ($qry) {
           $smsArr = array();
           while ($rows = mysqli_fetch_assoc($qry)) {
               $smsArr [] = $rows;
           }
           return $smsArr;
       }else{
           echo "<h3 class='alert alert-danger'>Oops there is no message error code 10002</h3>";
       }
     }else{
      $data = $this->usrGetSendBoxMsg($grp_id);
      return $data;
     }
    }
    /*not admin getsendBoxMsg sms*/
    public function usrGetSendBoxMsg($id){
    $qry = mysqli_query($this->conn, "SELECT msg.msg_id, msg.subject, msg.grp_id,msg.mem_id, msg.body,msg.msg_date, msg.sendUser, mem.firstname, mem.lastname,mem.mem_id, grp.grp_id, grp.grp_name,grp.section FROM msg, mem, grp WHERE msg.grp_id = '".$id."' AND grp.grp_id = '".$id."'  AND msg.mem_id = mem.mem_id AND msg.ack = '1' ORDER BY msg.msg_date ASC");
    if ($qry) {
        $smsArr = array();
        while ($rows = mysqli_fetch_assoc($qry)) {
            $smsArr [] = $rows;
        }
        return $smsArr;
    }else{
        echo "<h3 class='alert alert-danger'>Oops there is no message error code 10002</h3>";
    }
    }

    public  function getSingleData($id){
    $qry = mysqli_query($this->conn, "SELECT msg.msg_id,msg.phoneNumber, msg.subject, msg.grp_id,msg.mem_id, msg.body,msg.msg_date, msg.sendUser, mem.firstname, mem.lastname,mem.mem_id,msg.ack, grp.grp_id, grp.grp_name,grp.section FROM msg, mem, grp WHERE msg.msg_id = '".$id."' AND msg.grp_id = grp.grp_id AND msg.mem_id = mem.mem_id");
    if ($qry) {
       $rows = mysqli_fetch_assoc($qry);
       return $rows;
    }else{
        echo "<h3 class='alert alert-danger'>Oops there is no message error code 10002</h3>";
         }
    }
    /*GETTING SINGLE SMS */
    public function viewSingleSms($id){
    $sql = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE msg_id = '".$id."'");
    $count = mysqli_num_rows($sql);
    if ($count >=1) {
      $data = mysqli_fetch_assoc($sql);
      return $data;
    }else{
      echo "<h4 class='alert alert-danger'>Oops there is no record</h4>";
    }
    
    }
  public function delete($id){
    $username = $_SESSION['username'];
    if ($username =='admin') {
      $qry = mysqli_query($this->conn, "DELETE FROM ".$this->tbl." WHERE msg_id = '".$id."'");
      if ($qry) {
          echo "<h4 class='alert alert-success'>Successfully Deleted your sms...</h4>";
        }else{
           echo "<h3 class='alert alert-danger'>Oops there is no message error code 10002</h3>";
        }
    }else{
      echo "<h4 class='alert alert-danger'>Oops your don't have access ACCESS DENIED</h4>";
    }
  }
    public function getDraftSms(){
    $username = $_SESSION['username'];
    $grp_id   = $_SESSION['grp_id'];
    if ($username == 'admin') {
      $qry = mysqli_query($this->conn, "SELECT msg.msg_id, msg.subject, msg.grp_id,msg.mem_id, msg.body,msg.msg_date, msg.sendUser, msg.ack, mem.firstname, mem.lastname,mem.mem_id, grp.grp_id, grp.grp_name,grp.section FROM msg, mem, grp WHERE msg.ack = '0' AND msg.grp_id = grp.grp_id AND msg.mem_id = mem.mem_id ORDER BY msg.msg_date ASC");
    if ($qry) {
        $smsArr = array();
        while ($rows = mysqli_fetch_assoc($qry)) {
            $smsArr [] = $rows;
        }
        return $smsArr;
    }else{
        echo "<h3 class='alert alert-danger'>Oops there is no message error code 10002</h3>";
    }
    }else{
      $data = $this->usrGetDraftSms($grp_id);
      return $data;
    }
    }
    public function usrGetDraftSms($id){
    $qry = mysqli_query($this->conn, "SELECT msg.msg_id, msg.subject, msg.grp_id,msg.mem_id, msg.body,msg.msg_date, msg.sendUser, msg.ack, mem.firstname, mem.lastname,mem.mem_id, grp.grp_id, grp.grp_name,grp.section FROM msg, mem, grp WHERE msg.ack = '0' AND msg.grp_id = '".$id."' AND msg.mem_id = mem.mem_id AND grp.grp_id = '".$id."' ORDER BY msg.msg_date ASC");
    if ($qry) {
        $smsArr = array();
        while ($rows = mysqli_fetch_assoc($qry)) {
            $smsArr [] = $rows;
        }
        return $smsArr;
    }else{
        echo "<h3 class='alert alert-danger'>Oops there is no message error code 10002</h3>";
    }
 }
 public function smsCount(){
  $username = $_SESSION['username'];
  $grp_id   = $_SESSION['grp_id'];
  if ($username == 'admin') {
    $qry = mysqli_query($this->conn, "SELECT count(*) FROM msg WHERE ack = '1'");
    $count =  mysqli_fetch_assoc($qry);
    if ($qry) {
        echo $count['count(*)'];
    }else{
        echo "0";
    }
  }else{
    return $data = $this->userSmsCount($grp_id);
  }
 }

 public function userSmsCount($id){
 $qry = mysqli_query($this->conn, "SELECT count(*) FROM msg WHERE ack = '1' AND grp_id = '".$id."'");
 $count =  mysqli_fetch_assoc($qry);
 if ($qry) {
     echo $count['count(*)'];
 }else{
     echo "0";
 }
 }
 public function homeSearch(){
  $username = $_SESSION['username'];
  if ($username =='admin') {
     $sql = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE body LIKE '%".$this->searchKey."%' OR phoneNumber LIKE '%".$this->searchKey."%'");
     if ($sql) {
      while ($rows = mysqli_fetch_assoc($sql)) {?>
        <dl class='col-sm-10 alert alert-success'>
          <div class="row">
            <div class="col-sm-12"><dt><?php echo $rows['subject'];  ?></dt></div>
          </div>
          <dd>
          <div class="row">
            <div class="col-sm-12">
              <?php  echo substr($rows['body'], 0,20); ?>
            </div>
              <div class="col-sm-3"><strong>Created By:</strong><?php echo $rows['sendUser'];   ?></div>
              <div class="col-sm-3"><strong>ACK:</strong><?php if ($rows['ack']=='1') {?>
              <label class="label label-success">Send</label>
             <?php
             } else{?>
             <label class="label label-danger">Not Send</label>
             <?php
               }    
             ?></div>
             <div class="col-sm-3">
             <form action="?page=singleView" method="post">
               <input type="hidden" name="msg_id" value="<?php echo $rows['msg_id'];  ?>">
               <input type="submit" value="View Details">
             </form>
             </div>
             <div class="col-sm-3">
               <strong>Send Date:</strong><small><?php echo $rows['msg_date'];   ?></small>
             </div>
          </div>
          </dd>
        </dl>
      <?php
      }
     }else{
      echo "<h3 class='alert alert-danger'>Oops there is no sms</h3>";
     }
  }else{
    $this->homeSearchUser($username);
  }
 }
 /*SEARCH HOME PAGE*/
 public function homeSearchUser($username){
  $sql = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE sendUser ='".$username."' AND body LIKE '%".$this->searchKey."%' OR phoneNumber LIKE '%".$this->searchKey."%'");
 if ($sql) {
       while ($rows = mysqli_fetch_assoc($sql)) {?>
         <dl class='col-sm-10 alert alert-success'>
           <div class="row">
             <div class="col-sm-12"><dt><?php echo $rows['subject'];  ?></dt></div>
           </div>
           <dd>
           <div class="row">
             <div class="col-sm-12">
               <?php  echo substr($rows['body'], 0,20); ?>
             </div>
               <div class="col-sm-3"><strong>Created By:</strong><?php echo $rows['sendUser'];   ?></div>
               <div class="col-sm-3"><strong>ACK:</strong><?php if ($rows['ack']=='1') {?>
               <label class="label label-success">Send</label>
              <?php
              } else{?>
              <label class="label label-danger">Not Send</label>
              <?php
                }    
              ?></div>
              <div class="col-sm-3">
              <form action="?page=singleView" method="post">
                <input type="hidden" name="msg_id" value="<?php echo $rows['msg_id'];  ?>">
                <input type="submit" value="View Details">
              </form>
              </div>
              <div class="col-sm-3">
                <strong>Send Date:</strong><small><?php echo $rows['msg_date'];   ?></small>
              </div>
           </div>
           </dd>
         </dl>
       <?php
       }
      }else{
       echo "<h3 class='alert alert-danger'>Oops there is no sms</h3>";
      }
 }
 /*SENDING AUTOMATED SMS*/
 public function automatedSMS(){
  $sql = mysqli_query($this->conn, "INSERT INTO automated(grp_id, dayNum, s_dayNum, t_dayNum,  startDate, smsBody, ack, status)
    VALUES('$this->grp_id', '$this->firstDay','$this->secondDay','$this->thirdDay', '$this->startDate', '$this->smsBody', '1', '1')");
  if ($sql) {
    echo "<h4 class='alert alert-success'>Successfully inserted....</h4>";
  }else{
    echo "<h4 class='alert alert-danger'>Oops couldn't inserted</h4>";
  }
 }
 public function getAutomatedSMS(){
 $sql = mysqli_query($this->conn, "SELECT grp.grp_name, grp.section, grp.grp_id, automated.grp_id, automated.dayNum,automated.s_dayNum, automated.t_dayNum, automated.startDate, automated.smsBody, automated.ack, automated.status, automated.automated_id FROM grp, automated WHERE grp.grp_id = automated.grp_id");
 $count = mysqli_num_rows($sql);
 if ($count >= 1) {
  $data = array();
   while ($rows = mysqli_fetch_assoc($sql)) {
       $data[] = $rows;
   }
   return $data;
 }else{
  echo "<h4 class='alert alert-danger'>Oops there is no data</h4>";
 }
 }
 /*UPDATING AUTOMATED ACK */
 public function UpdateAck($id, $ackValue){
  $qry = mysqli_query($this->conn, "UPDATE automated SET ack = '".$ackValue."' WHERE automated_id = '".$id."'");
  if ($qry) {
     echo "<h4 class='alert alert-success'>Successfully Update....</h4>";
  }else{
   echo "<h4 class='alert alert-danger'>Oops couldn't update.....</h4>";
  }
 }
 public function getAutomatedSMSUpdate($id){
  $sql = mysqli_query($this->conn, "SELECT grp.grp_name, grp.section, grp.grp_id, automated.grp_id, automated.dayNum,automated.s_dayNum, automated.t_dayNum, automated.startDate, automated.smsBody, automated.ack, automated.status, automated.automated_id FROM grp, automated WHERE grp.grp_id = automated.grp_id AND automated.automated_id ='".$id."'");
  if ($sql) {
    $UpdateIdData = mysqli_fetch_assoc($sql);
    return $UpdateIdData;
  }else{
     echo "<h4 class='alert alert-danger'>Oops there is no data</h4>";
  }
 }
 public function updateDateAutomated($id){
 $qry = mysqli_query($this->conn, "UPDATE automated SET grp_id = '".$this->grp_id."', dayNum = '".$this->firstDay."', s_dayNum = '".$this->secondDay."',t_dayNum = '".$this->thirdDay."', startDate = '".$this->startDate."', smsBody = '".$this->smsBody."' WHERE automated_id = '".$id."'");
 if ($qry) {
    echo "<h4 class='alert alert-success'>Successfully Update....</h4>";
 }else{
  echo "<h4 class='alert alert-danger'>Oops couldn't update.....</h4>";
 }
 }
 
  public function disableDateOfDateAutomated($id, $n_day){
 $qry = mysqli_query($this->conn, "UPDATE automated SET " . ($n_day==1?"dayNum='0'":($n_day==2?"s_dayNum='0'":"t_dayNum='0'")) ." WHERE automated_id = '".$id."'");
 }
 
 public function AutomatedDelete($id){
 $sql = mysqli_query($this->conn, "DELETE FROM automated WHERE automated_id = '".$id."'");
 if ($sql) {
   echo "<h4 class='alert alert-success'>Successfully Deleted</h4>";
 }else{
  echo "<h4 class='alert alert-danger'>Oops couldn't Deleted....</h4>";
 }
 }
}/*ending of the class*/
?>