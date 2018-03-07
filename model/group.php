<?php
/**
* Group class
*/
class group
{
	private $conn;
	private $tbl = 'grp';
	public $grp_id;
	public $grp_name;
	public $grp_section;	
	function __construct($con)
	{
		$this->conn = $con;
	}
    /*admin grap data*/
    public function index(){
    $username =  $_SESSION['username'];
    $grp_id   =  $_SESSION['grp_id'];
    if ($username == 'admin') {
        $_index = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." order by grp_name ASC");
        $index_num = mysqli_num_rows($_index);
        if ($index_num >=1) {
            $data = array();
            while ($rows = mysqli_fetch_assoc($_index)) {
                $data[] = $rows;
            }
            return $data;
        }else{
                echo "<h4 class='alert alert-danger'>Oops there is no data error code 1000</h4>";
            }
    }else{
        return $data = $this->getuserIndex($grp_id);
    }
    }
    /*user grap data*/
    public function getuserIndex($id){
      $_index = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE grp_id = '".$id."' order by grp_name ASC");
      $index_num = mysqli_num_rows($_index);
      if ($index_num >=1) {
          $data = array();
          while ($rows = mysqli_fetch_assoc($_index)) {
              $data[] = $rows;
          }
          return $data;
      }else{
              echo "<h4 class='alert alert-danger'>Oops there is no data error code 1000</h4>";
          }
    }
    /*-----------------------*/
	public function create(){
    $_check = mysqli_query($this->conn, "SELECT grp_name, section FROM ".$this->tbl." WHERE grp_name = '".$this->grp_name."'  AND section = '".$this->grp_section."'");
    $check_num = mysqli_num_rows($_check);
    if ($check_num >=1) {
    	echo "<h4 class='alert alert-danger'>Oops already inserted try with another error code 10001</h4>";
    }else{
    	$_insert = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(grp_name, section)VALUES('$this->grp_name', '$this->grp_section')");
    	if ($_insert) {
    	   echo "<h4 class='alert alert-success'>Successfully created..</h4>";

    	}else{
    		echo "<h4 class='alert alert-danger'>Oops get internet problem error code 1002</h4>";
    	}
    }
	}
	public function update(){
    $qry = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET grp_name = '".$this->grp_name."', section = '".$this->grp_section."' WHERE grp_id = '".$this->grp_id."'");
    if ($qry) {
        echo "<h4 class='alert alert-success'>Successfully Updated....</h4>";
    }else{
        echo "<h4 class='alert alert-danger'>Oops sorry data is not update.....</h4>";
    }
	}
	public function delete(){
    $qry = mysqli_query($this->conn, "DELETE FROM ".$this->tbl." WHERE grp_id ='".$this->grp_id."'");
    if ($qry) {
         echo "<h4 class='alert alert-success'>Successfully deleted group....</h4>";
        $del_assign = mysqli_query($this->conn, "DELETE FROM assign WHERE grp_id = '".$this->grp_id."'");
        echo "<h4 class='alert alert-success'>Successfully deleted people who are assign in this group....</h4>";
    }else{
        echo "<h4 class='alert alert-danger'>Oops sorry couldn't deleted.....</h4>";
    }
	}
    public function show($id){
    $qry = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE grp_id = '".$id."'");
    if ($qry) {
        $rows = mysqli_fetch_assoc($qry);
        return $rows;
    }else{
        echo "<h4 class='alert alert-danger'>Oops there is no data error code 1000</h4>";
    }
    }
}
?>