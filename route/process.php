<?php
if(!isset($_SESSION)) {session_start();} 
require_once('../model/db.php');
require_once('../model/login.php');
require_once('../model/user.php');
require_once('../model/group.php');
require_once('../model/member.php');
require_once('../model/assign.php');
require_once('../model/smsMsg.php');
/*require_once('../model/');*/
$db = new dbCon();
$con = $db->connect();
$userLogin = new usrLogin($con);
$user = new userMnt($con);
$grp = new group($con);
$mem = new member($con);
$ass = new assign($con);
$sms = new smsMsg($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$tag = isset($_POST['tag'])?$_POST['tag']:'';
    /*createing user*/
	 if (!empty($tag) && $tag =='CreateUser') {
		if (!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['username']) && !empty($_POST['assign_id'])) {
			$pwd1 = htmlspecialchars(strtolower($_POST['pwd1']));
			$pwd2 = htmlspecialchars(strtolower($_POST['pwd2']));
		if ($pwd1 !=$pwd2) {
			echo "<h3 class='alert alert-danger'>Confirm Password is not match try with another</h3>";
			exit();
		}else{
			$password = md5($pwd1);
			$user->fname = htmlspecialchars(mysqli_real_escape_string($con, $_POST['fname']));
			$user->lname = htmlspecialchars(mysqli_real_escape_string($con, $_POST['lname']));
			$user->username = htmlspecialchars(mysqli_real_escape_string($con, $_POST['username']));
			$user->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['assign_id']));
			$user->password = htmlspecialchars(mysqli_real_escape_string($con, $password));
			$user->sms      = htmlspecialchars(mysqli_real_escape_string($con, $_POST['alc_sms_qta']));
			if (!empty($_POST['upd_usr_details'])) {
			$user->user_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['upd_usr_details']));
				$user->update();
			}else{
                $user->create();
			}
		}
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*delete user*/
		else if (!empty($tag) && $tag == 'delUsrInfo') {
			if (!empty($_POST['del_user_id'])) {
				$user ->user_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['del_user_id']));
				$user ->username = htmlspecialchars(mysqli_real_escape_string($con, $_POST['delCreditUsr']));
				$user->delete();
			}
		} 
		/*UPDATING QUOTAS TO THE USER*/
		else if (!empty($tag) && $tag =='UpdateQuotas') {
			   if (!empty($_POST['UpdateQuotas']) && !empty($_POST['QuotasId'])) {
			   	$user->user_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['QuotasId']));
			   	$user->quotas  = htmlspecialchars(mysqli_real_escape_string($con, $_POST['UpdateQuotas']));
                $user->username = htmlspecialchars(mysqli_real_escape_string($con, $_POST['uname']));
			   	$user->updateQuotas();
			   }
		}
	   /*login system*/
	   else if (!empty($tag) && $tag =='Login_process') {
	   	  if (!empty($_POST['usrname']) && !empty($_POST['pwd']) && !empty($_POST['num1']) && !empty($_POST['num2']) && !empty($_POST['sumText'])) {
	   	  	$userLogin->username = htmlspecialchars(mysqli_real_escape_string($con, $_POST['usrname']));
	   	  	$userLogin->password = md5(htmlspecialchars(mysqli_real_escape_string($con, $_POST['pwd'])));
	   	  	$userLogin->num1     = htmlspecialchars(mysqli_real_escape_string($con, $_POST['num1']));
	   	  	$userLogin->num2     = htmlspecialchars(mysqli_real_escape_string($con, $_POST['num2']));
	   	  	$userLogin->total    = htmlspecialchars(mysqli_real_escape_string($con, $_POST['sumText']));
	   	  	$userLogin->signIn();
	   	  }else{
	   	  	echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
	   	  }
	   }
	   
	   /*logOut */
	   else if (!empty($tag) && $tag =='UsrLogOut') {
	   	 	$userLogin->logOut();
	   }
	   /*user suspend section*/
	   else if (!empty($tag) && $tag =='usr_notify') {
	   	   if (!empty($_POST['hidden_sysAdm_id'])) {
	   	   	$user->user_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['hidden_sysAdm_id']));
            $user->susVal = htmlspecialchars(mysqli_real_escape_string($con, $_POST['susUsr']));
            $user->usrSuspend();
	   	   }else{
	   	   	 echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
	   	   }
	   }
	   /*change password of user*/
	   else if (!empty($tag) &&  $tag == 'changeMyPwd') {
	   	    if (!empty($_POST['newPwd']) && !empty($_POST['conPwd']) && !empty($_POST['chng_usrId'])) {
	   	    	if ($_POST['newPwd'] != $_POST['conPwd']) {
	   	    	  echo "<h3 class='alert alert-danger'>Oops password is not match</h3>";
	   	    	  exit();
	   	    	}else{
                  $user->user_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['chng_usrId']));
                  $user->password = md5(htmlspecialchars(mysqli_real_escape_string($con, $_POST['newPwd'])));
                  $user->changePassword();
	   	    	}
	   	    }else{
	   	    	echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
	   	    }
	   }
		/*ending of the user creation*/
	   else if(!empty($tag) && $tag =='CreateGrp') {
		if (!empty($_POST['group_name'])) {
			$grp ->grp_name   = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['group_name'])));
			$grp->grp_section = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['section'])));
			if (!empty($_POST['updateGrpId'])) {
			$grp->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['updateGrpId']));
			$grp->update();
			}else{
			$grp->create();
			}
			exit();
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*delete group */
	else if (!empty($tag) && $tag == 'delGrp_id') {
		if (!empty($_POST['del_grp_id'])) {
			$grp->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['del_grp_id']));
			$grp->delete();
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*creating of member*/
	else if (!empty($tag) && $tag =='createMember') {
		if (!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['addr']) && !empty($_POST['phone'])) {
			$mem->firstname = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['fname'])));
			$mem->lastname = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['lname'])));
			$mem->addr = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['addr'])));
			$mem->phone = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['phone'])));
			$mem->email = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['email'])));
			$mem->uniq_id = htmlspecialchars(mysqli_real_escape_string($con, strtolower($_POST['stf_id'])));
			if (!empty($_POST['hiddenMemId'])) {
				$mem->mem_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['hiddenMemId']));
				$mem->update();
			}else{
			 $mem->create();
			}
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*delete member Id*/
	else if (!empty($tag) && $tag =='Del_Mem_Id') {
		if (!empty($_POST['del_mem_id'])) {
			$mem->mem_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['del_mem_id']));
			$mem->delete();
		}
	}
	/*seearch individual staff*/
    else if (!empty($tag) && $tag =='searchIndStaff') {
    	if (!empty($_POST['staffNam'])) {
        $mem->firstname = htmlspecialchars(mysqli_real_escape_string($con, $_POST['staffNam']));
        $mem->searchIndMem();
    	}
    }
	/*ending of tag notification*/
	
	/*search member name name its id for single assign form*/
	else if (!empty($tag) && $tag =='src_mem_name_id') {
		if (!empty($_POST['mem_data'])) {
			$ass->firstname = htmlspecialchars(mysqli_real_escape_string($con, $_POST['mem_data']));
			$ass->searchMemName();
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*single assign in group*/
    else if (!empty($tag) && $tag =='singleAssignInGrp') {
    	if (!empty($_POST['hidden_mem_id']) && !empty($_POST['sng_grp_id']) && $_POST['sng_enroll_date']) {
    		$ass->mem_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['hidden_mem_id']));
    		$ass->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['sng_grp_id']));
    		$ass->assign_date = htmlspecialchars(mysqli_real_escape_string($con, $_POST['sng_enroll_date']));
    		$ass->sngCreate();
    	}
    }
    else if (!empty($tag) && $tag =='DelAssignId') {
    	if (!empty($_POST['assign_id'])) {
    		$assignId = htmlspecialchars(mysqli_real_escape_string($con, $_POST['assign_id']));
    		$ass->delete($assignId);
    	}else{
    		echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }

	/*multiple add staff in group */
	else if (!empty($tag) && $tag == 'multiple_data') {
		if (!empty($_POST['mem_id']) && !empty($_POST['grp_id']) && !empty($_POST['enroll_date'])) {
			$mem_id = array();
			$grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['grp_id']));
			$mem_id = $_POST['mem_id'];
			/*$mem_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['mem_id']));*/
			$assign_date = htmlspecialchars(mysqli_real_escape_string($con, $_POST['enroll_date']));
			$ass->create($grp_id, $mem_id, $assign_date);
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*getting member from group */
	else if (!empty($tag) && $tag =='getListFromGrp') {
		if (!empty($_POST['grp_id'])) {
			$ass ->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['grp_id']));
			$ass->getListFromGrp();
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
		# code...
	}
	/*getting list of member from group*/
	else if (!empty($tag) && $tag =='getListNam') {
		if (!empty($_POST['grp_id'])) {
			$ass->grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['grp_id']));
			$ass->getMemNameFromGrpId();
		}else{
			echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
		}
	}
	/*SENDING SMS COMPOSE*/
    else if (!empty($tag) && $tag =='SendSMSTo') {
    	if (!empty($_POST['smsSub']) && !empty($_POST['smsBody']) && !empty($_POST['mem_id']) && !empty($_POST['grp_id'])) {
    		/*filtering*/
    		$sub  = htmlspecialchars(mysqli_real_escape_string($con, $_POST['smsSub']));
    		$body = htmlspecialchars(mysqli_real_escape_string($con, $_POST['smsBody']));
    		$grp_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['grp_id']));
    		$memId = array();
    		$memId = $_POST['mem_id'];
    		$sms->create($sub, $body, $memId, $grp_id);
    	}else{
    		echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
    /*SENDING SINGLE SMS*/
    else if (!empty($tag) && $tag =='sendSingleSms') {
    	if (!empty($_POST['singlePhone']) && !empty($_POST['single_sms_body'])) {
    		$phoneNumber = $_POST['singlePhone'];
    		$body        = htmlspecialchars(mysqli_real_escape_string($con, $_POST['single_sms_body']));
    		$sms->createSingle($phoneNumber, $body); 
    	}else{
            echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
    /*RESEND smsResend*/
    else if (!empty($tag) && $tag == 'smsResend') {
    	if (!empty($_POST['msg_id'])) {
    	 $id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['msg_id']));
    	 $sms->resend($id);
    	}else{
    		echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
    /*DELETING SINGLE SMS*/
    else if (!empty($tag) && $tag =='delsinSMS') {
    	if (!empty($_POST['delsinID'])) {
    		$msg_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['delsinID']));
    		$sms->deleteSingleSMS($msg_id);
    	}else{
             echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
    /*SENDING AUTO SEND SMS*/
    else if (!empty($tag) && $tag =='autoSendSms') {
			
    	if (!empty($_POST['grp_id']) && (!empty($_POST['smsDay']) || !empty($_POST['s_smsDay']) || !empty($_POST['t_smsDay'])) && !empty($_POST['smsBody'])) {
    		$todayDate = date('yyyy/mm/dd');
			if(empty($_POST['smsDay']) || $_POST['smsDay']==0)
			{
				$time1 = date_create($_POST['smsStartDate']);
				$sms->firstDay = 0;
			} else { $time1 = date_create($_POST['smsDay']); $sms->firstDay  = 1; }
			date_add($time1, date_interval_create_from_date_string('-1 days'));
			$datesms_1 = $time1->format('Y-m-d');
    		$sms->grp_id     = htmlspecialchars(mysqli_real_escape_string($con, $_POST['grp_id']));
    		
			if(!empty($_POST['s_smsDay']))
			{
				$time2 = date_create($_POST['s_smsDay']);
				$interval1 = $time1->diff($time2);
				$sms->secondDay     = $interval1->format('%a');//htmlspecialchars(mysqli_real_escape_string($con, $_POST['s_smsDay']));
    		} else $sms->secondDay = NULL;
			if(!empty($_POST['t_smsDay']))
			{
				$time3 = date_create($_POST['t_smsDay']);
				$interval2 = $time1->diff($time3);
				$sms->thirdDay     = $interval2->format('%a');//htmlspecialchars(mysqli_real_escape_string($con, $_POST['s_smsDay']));
    		} else $sms->thirdDay = NULL;
			
			//$sms->thirdDay     = htmlspecialchars(mysqli_real_escape_string($con, $_POST['t_smsDay']));
    		$sms ->startDate = $datesms_1;//htmlspecialchars(mysqli_real_escape_string($con, $_POST['smsStartDate']));
    		$sms->smsBody    = htmlspecialchars(mysqli_real_escape_string($con, $_POST['smsBody']));

    		if (!empty($_POST['hidden_UpdateId_autosend'])) {
    			$updateId = htmlspecialchars(mysqli_real_escape_string($con, $_POST['hidden_UpdateId_autosend']));
    			$sms->updateDateAutomated($updateId);
    		}else{
    		   $sms ->automatedSMS();
    		}
    	}else{
            echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
    /*UPDATING AUTO SEND EITHER SEND RO NOT SEND*/
    else if (!empty($tag) && $tag == 'UpdateAutomatedAckId') {
    	if (!empty($_POST['updateAutomatedAckId'])) {
    		$id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['updateAutomatedAckId']));
    		$ackValue = htmlspecialchars(mysqli_real_escape_string($con, $_POST['ackValue']));
    		$sms->UpdateAck($id, $ackValue);
    	}
    }
    /*DELETE AUTO MATED ID*/
    else if (!empty($tag) && $tag == 'DelAutomatedID') {
    	if (!empty($_POST['DelAutomatedSMS'])) {
    		$id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['DelAutomatedSMS']));
    		$sms->AutomatedDelete($id);
    	}else{
    		 echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
    /*SENDING AUTO SEND CAMPAIN*/
    else if (!empty($tag) && $tag == 'DelSms') {
    	if (!empty($_POST['msg_id'])) {
    		$del_sms_id = htmlspecialchars(mysqli_real_escape_string($con, $_POST['msg_id']));
    		$sms->delete($del_sms_id);
    	}else{
    		echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
    	}
    }
	else if (!empty($tag) && $tag =='homeSearchData') {
	   if (!empty($_POST['data'])) {
	   	$sms->searchKey = htmlspecialchars($_POST['data']);
	   	$sms->homeSearch();
	   }else{
	   	echo "<h3 class='alert alert-danger'>Please complete the form field first</h3>";
	   }
	}

	else{
		echo "<h3 class='alert alert-danger'>tag value is losing....please contact to the administrator</h3>";
	}
}/*server closing*/
else{
	echo "<h3 class='alert alert-danger'>You are robuts</h3>";
	exit();
}
$con->close();
unset($tag);
?>