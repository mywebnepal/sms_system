<?php
/**
* Assign to the group and staff in particular date
*/
class assign
{
	private $conn;
	private $tbl = 'assign';
	public $assign_id;
	public $grp_id;
	public $mem_id;
	public $assign_date;
	public $firstname;

	function __construct($con)
	{
		$this->conn = $con;
	}
	public function index(){

	}
	public function sngCreate(){
	$chk = mysqli_query($this->conn, "SELECT grp_id, mem_id, assign_date FROM ".$this->tbl." WHERE grp_id = '".$this->grp_id."' AND mem_id ='".$this->mem_id."' AND assign_date = '".$this->assign_date."'");
	$num = mysqli_num_rows($chk);
	if ($num >=1) {
		echo "<h4 class='alert alert-danger'>Oops already inserted try with another error code 10001</h4>";
	}else{
	$qry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(grp_id, mem_id, assign_date)VALUES('$this->grp_id', '$this->mem_id', '$this->assign_date')");
	   if ($qry) {
    	   echo "<h4 class='alert alert-success'>Successfully created..</h4>";

    	}else{
    		echo "<h4 class='alert alert-danger'>Oops get internet problem error code 1002</h4>";
    	}
	}
	}

	public function create($grp_id, $mem_id, $assign_date){
	foreach ($mem_id as $data) {
	$chk = mysqli_query($this->conn, "SELECT grp_id, mem_id, assign_date FROM ".$this->tbl." WHERE grp_id = '".$grp_id."' AND mem_id ='".$data."' AND assign_date = '".$assign_date."'");
	}
	$chk_row = mysqli_num_rows($chk);
	if ($chk_row >=1) {
		echo "<h4 class='alert alert-danger'>Oops already inserted try with another error code 10001</h4>";
	}else{
		foreach ($mem_id as $memData) {
			$qry = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(grp_id, mem_id, assign_date)VALUES($grp_id, $memData, $assign_date)");
		}
		if ($qry) {
    	   echo "<h4 class='alert alert-success'>Successfully created..</h4>";

    	}else{
    		echo "<h4 class='alert alert-danger'>Oops get internet problem error code 1002</h4>";
    	}
	}
	}
	public function update(){

	}
	public function delete($id){
    $sql = mysqli_query($this->conn, "DELETE FROM ".$this->tbl." WHERE assign_id = '".$id."'");
    if ($sql) {
    	echo "<h4 class='alert alert-success'>Successfully Deleted....</h4>";
    }else{
    	echo "<h4 class='alert alert-danger'>Couldn't deleted...</h4>";
    }
	}
	public function searchMemName(){
	$qry = mysqli_query($this->conn, "SELECT mem_id, firstname, lastname, phone, uniq_id FROM mem WHERE firstname LIKE '%".$this->firstname."%'");
	$num = mysqli_num_rows($qry);
	if ($num >=1) {
	  echo "<dl class='dl-horizontal dl_data'>";
	  while ($rows = mysqli_fetch_assoc($qry)) {
	    echo "<dt>"
	    ."<input type='hidden' name='hidden_mem_id' value=".$rows['mem_id']." class='hiddenId'>"
	         .$rows['firstname'].'&nbsp;'.$rows['lastname'].
	         "</dt>";
	    echo "<dd>".$rows['phone'].'&nbsp;&nbsp;'.$rows['uniq_id']."</dd>";
	  }
	  "</dl>";
	}else{
	  echo "<h4 class='alert alert-danger'>OOoops data is not found</h4>";
	}

	}
	public function getListFromGrp(){
    $qry = mysqli_query($this->conn, "SELECT mem.mem_id, mem.firstname, mem.lastname, mem.phone, assign.grp_id FROM mem, assign WHERE assign.grp_id = '".$this->grp_id."' AND mem.mem_id = assign.mem_id");
    if ($qry) {
    	$memList = array();
    	while ($rows = mysqli_fetch_assoc($qry)) {
    		$memList[] = $rows;
       }
       return $memList;
    }else{
    	echo "<h4 class='alert alert-danger'>Oops get internet problem error code 1002</h4>";
    }
    }
    public function getMemNameFromGrpId(){
    $qry = mysqli_query($this->conn, "SELECT mem.mem_id, mem.firstname, mem.lastname, mem.phone, mem.email,mem.addr, mem.uniq_id, grp.grp_name, grp.section, grp.grp_id, assign.assign_id, assign.grp_id, assign.mem_id FROM mem, grp, assign WHERE assign.grp_id = '".$this->grp_id."' AND mem.mem_id = assign.mem_id AND assign.grp_id = grp.grp_id");
    $qryNum = mysqli_num_rows($qry);
    if ($qryNum >=1) {
        $memListArray = array();
        while ($rows = mysqli_fetch_assoc($qry)) {
        	$memListArray[] = $rows;
        }
        return $memListArray;
        }else{
        	echo "<h4 class='alert alert-danger'>there is no list in this member</h4>";
        }
    }
}/*closing of the class*/

?>