<?php
/**
* user system login 
*/
class usrLogin
{
	private $tbl = 'user';
	private $conn;
	public $user_id;
	public $firstname;
	public $lastname;
	public $username;
	public $password;
	public $token; 
	public $num1;
	public $num2;
	public $total;

	function __construct($connect) {
		$this->conn = $connect;
	}
	public function signIn(){
	    if(!isset($_SESSION)) {session_start();} 
	    $ret = array();
	    $num1 = $this->num1;
	    $num2 = $this->num2;
	    $final = $num1 + $num2;
	    $tot = $this->total;
	    $qry = mysqli_query($this->conn, "SELECT username, password, grp_id, sus FROM ".$this->tbl." WHERE username = '".$this->username."' AND password = '".$this->password."'");
	    $num = mysqli_num_rows($qry);
	    $rows = mysqli_fetch_assoc($qry);

	    if ($final != $tot) {
	      $ret['res'] = 0;
	      $ret['msg'] = 'please calculate the sum first properly...';
	    }
	    else if ($num ==1 && $rows['sus'] == '1') {
	      $username = $rows['username'];
	      $grp_id = $rows['grp_id'];
	      $_SESSION['username']= $username;
	      $_SESSION['grp_id']= $grp_id;

	      $_SESSION['start'] = time();
	      $_SESSION['expire']= $_SESSION['start'] + (60*60*24*365);
	      $ret['res'] = 1;
	      $ret['msg'] = 'Successfully login...';
	    }
	     /*CHECKING OF SUSPEND ID*/
	    else if ($rows['sus']==0) {
	      $ret['res'] = 0;
	      $ret['msg'] = 'your account has been suspend...';
	    }
	    else{
	      $ret['res'] = 0;
	      $ret['msg'] = 'Ooops username and password is not match try it again';
	    }
	    print json_encode($ret);
	}
	public function logOut(){
	if(!isset($_SESSION)) {session_start();} 
	session_destroy();
	ob_flush();
	echo "<h4 class='alert alert-success'><small>Wait..soon LogOut...</small></h4>";
	}
	public function signUp(){

	}
	public function deleteUser(){

	}
	
}

?>