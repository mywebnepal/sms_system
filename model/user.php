<?php
/**
* user management
*/
class userMnt
{
	private $tbl = 'user';
	private $conn;
	public $fname;
	public $lname;
	public $username;
	public $password;
	public $grp_id;
	public $user_id;
	public $quotas;
	public $susVal;
	public $sms;
	/*-------*/
	function __construct($con)
	{
		$this->conn = $con;
	}
	public function index(){
	$qry = mysqli_query($this->conn, "SELECT user.user_id, user.fname, user.lname, user.username, user.smsQuotas, user.sus, grp.grp_id, grp.grp_name, grp.section FROM user, grp WHERE grp.grp_id = user.grp_id ");
	if ($qry) {
		$usrData = array();
		while ($rows = mysqli_fetch_assoc($qry)) {
			$usrData[] = $rows;
		}
		return $usrData;
	}else{
		echo "<h4 class='alert alert-danger'>Oops there is no user please assign it</4>";
	}
	}
	public function create(){
	$token = substr(md5(rand()), 0, 7);
	$_Cusr = mysqli_query($this->conn, "SELECT username FROM ".$this->tbl." WHERE username = '".$this->username."'");
	$_Cusrnum = mysqli_num_rows($_Cusr);
	if ($_Cusrnum >=1) {
		echo "<h3 class='alert alert-danger'>User already exists ".$this->username."error code 10001</h3>";
	}else{
     $_Cqry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(fname, lname, username, password, token, grp_id, smsQuotas, sus) VALUES('$this->fname', '$this->lname', '$this->username', '$this->password', '".$token."', '$this->grp_id', '$this->sms','1')");
     /*INSERTIN USERNAME IN SMS QUOTAS LIMITER*/
     $_Cqry = mysqli_query($this->conn, "INSERT INTO smsquotas(username, quotas) VALUES('$this->username', '0')");

     if ($_Cqry) {
      echo "<h3 class='alert alert-success'>Successfully create user</h3>";
     }else{
      echo "<h3 class='alert alert-danger'>Oops user is not created error code 10002</h3>";
     }
	}
	}
	public function update(){
    $sql = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET fname = '".$this->fname."',lname = '".$this->lname."', username = '".$this->username."',  smsQuotas = '".$this->sms."', grp_id = '".$this->grp_id."' WHERE user_id = '".$this->user_id."'");
    if ($sql) {
    	echo "<h4 class='alert alert-success'>successfully updated..</h4>";
    }else{
    	echo "<h4 class='alert alert-danger'>Oops couldn't updated..</h4>";
    }
	}
	public function delete(){
    $sql = mysqli_query($this->conn, "DELETE FROM ".$this->tbl." WHERE user_id = '".$this->user_id."'");
    $qry = mysqli_query($this->conn, "DELETE FROM smsquotas WHERE username = '".$this->username."'");
    if ($sql) {
    	echo "<h4 class='alert alert-success'>Successfully deleted....</h4>";
    }else{
    	echo "<h4 class='alert alert-danger'>Oops couldn't deleted...</h4>";
    }
	}
	public function changePassword(){
    $token = substr(md5(rand()), 0, 7);
    $sql = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET password = '".$this->password."', token = '".$token."' WHERE user_id = '".$this->user_id."'");
    if ($sql) {
    	echo "<h4 class='alert alert-success'>Successfully change password</h4>";
    }else{
    	echo "<h4 class='alert alert-danger'>Oops password is not change....</h4>";
    }
	}
	public function getIndividualData($id){
    $qry = mysqli_query($this->conn, "SELECT user.user_id, user.fname, user.lname, user.username, user.smsQuotas, user.sus, grp.grp_id, grp.grp_name, grp.section FROM user, grp WHERE user.user_id = '".$id."' AND grp.grp_id = user.grp_id ");
    if ($qry) {
    	$usrData = array();
    	while ($rows = mysqli_fetch_assoc($qry)) {
    		$usrData[] = $rows;
    	}
    	return $usrData;
    }else{
    	echo "<h4 class='alert alert-danger'>Oops there is no user please assign it</4>";
    }
	}
	public function usrSuspend(){
	$qry = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET sus = '".$this->susVal."' WHERE user_id = '".$this->user_id."'");
	if ($qry) {
		echo "<h4 class='alert alert-success'>Successfully Changed</h4>";
	}else{
        echo "<h4 class='alert alert-danger'>Oops couldn't change</h4>";
	}
	}
	public function show($id){
    $qry = mysqli_query($this->conn, "SELECT user.user_id, user.fname, user.lname, user.username, user.smsQuotas, user.sus, grp.grp_id, grp.grp_name, grp.section FROM user, grp WHERE user.user_id = '".$id."' AND grp.grp_id = user.grp_id ");
    if ($qry) {
    	while ($rows = mysqli_fetch_assoc($qry)) {
    		$usrData = $rows;
    	}
    	return $usrData;
    }else{
    	echo "<h4 class='alert alert-danger'>Oops there is no user please assign it</4>";
    }
	}
	public function updateQuotas(){
		echo "UPDATE smsquotas SET quotas = '0' WHERE username = '".$this->username."'";
	 $sql = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET smsQuotas = '".$this->quotas."' WHERE user_id = '".$this->user_id."'");
	 $Update_sql = mysqli_query($this->conn, "UPDATE smsquotas SET quotas = '0' WHERE username = '".$this->username."'");
	 if ($sql) {
	 	echo "<h4 class='alert alert-success'>Successfully Updated...</h4>";
	 }else{
	 	echo "<h4 class='alert alert-danger'>Oops couldn't update </h4>";
	 }
	}
}
?>