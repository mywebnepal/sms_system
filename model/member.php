<?php
/*
   * member CRUD section
*/
class member
{
	private $tbl = 'mem';
	private $conn;
	public $mem_id;
	public  $firstname;
	public $lastname;
	public $email;
	public $phone;
	public $addr;
	public $uniq_id;
	
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

	public function show($id){
	$qry = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE mem_id = '".$id."'");
	if ($qry) {
		$rows = mysqli_fetch_assoc($qry);
		return $rows;
	}else{
		echo "<h3 class='alert alert-danger'>Oops there is no data error code 10000...</h3>";
	}
	}

	public function create(){
    $_chk = mysqli_query($this->conn, "SELECT phone FROM ".$this->tbl." WHERE phone = '".$this->phone."'");
	$_chk_num = mysqli_num_rows($_chk);
	if ($_chk_num >=1) {
	echo "<h3 class='alert alert-danger'>Phone no already exists ".$this->phone."error code 10001</h3>";
	}
	 $_chk_id = mysqli_query($this->conn, "SELECT uniq_id FROM ".$this->tbl." WHERE uniq_id = '".$this->uniq_id."'");
	 $_chk_id_num = mysqli_num_rows($_chk_id);
    if ($_chk_id_num >=1) {
    	echo "<h3 class='alert alert-danger'>Oops user id found".$this->uniq_id." error code 10001</h3>";
    }
	else{
		$_insert = mysqli_query($this->conn, "INSERT INTO ".$this->tbl."(firstname, lastname, email, phone, addr, uniq_id)VALUES('$this->firstname', '$this->lastname', '$this->email','$this->phone', '$this->addr', '$this->uniq_id')");
		if ($_insert) {
		      echo "<h3 class='alert alert-success'>Successfully create user</h3>";
		     }else{
		      echo "<h3 class='alert alert-danger'>Oops member  is not created error code 10002</h3>";
		     }
	    }
	}
	public function update(){
    $qry = mysqli_query($this->conn, "UPDATE ".$this->tbl." SET firstname = '".$this->firstname."', lastname = '".$this->lastname."', email = '".$this->email."', phone = '".$this->phone."', addr = '".$this->addr."', uniq_id = '".$this->uniq_id."' WHERE mem_id = '".$this->mem_id."'");

    if ($qry) {
    	echo "<h3 class='alert alert-success'>Successfully staff Updated</h3>";
    }else{
    	echo "<h3 class='alert alert-danger'>Oops sorry not able to update...</h3>";
        }
	}
	public function delete(){
    $qry = mysqli_query($this->conn, "DELETE FROM ".$this->tbl." WHERE mem_id = '".$this->mem_id."'");
    if ($qry) {
    	echo "<h3 class='alert alert-success'>Successfully Deleted staff</h3>";
    	$assignStaff = mysqli_query($this->conn, "DELETE FROM assign WHERE mem_id = '".$this->mem_id."'");
    	echo "<h3 class='alert alert-success'>Successfully Deleted from assign Group as well..</h3>";
    }else{
    	echo "<h3 class='alert alert-danger'>Oops not able to deleted...</h3>";
    }
	}
	public function searchIndMem(){
	$sql = mysqli_query($this->conn, "SELECT * FROM ".$this->tbl." WHERE firstname LIKE '%".$this->firstname."%' OR phone = '".$this->firstname."'");
	if ($sql) {
		$count = 1;
		echo "<table class='table table-striped table-hover'>
				<th>Sn</th>
				<th>Id</th>
				<th>Firstname</th>
				<th>Lastname</th>
				<th>Phoneno</th>
				<th>Email</th>
				<th>Address</th>
				<th>Action</th>";
		while ($rows = mysqli_fetch_assoc($sql)) {?>
			<tr>
				<td><?php echo $count++;   ?></td>
				<td><?php echo $rows['uniq_id']; ?></td>
				<td><?php echo $rows['firstname']; ?></td>
				<td><?php echo $rows['lastname']; ?></td>
				<td><?php echo $rows['phone']; ?></td>
				<td><?php echo $rows['email']; ?></td>
				<td><?php echo $rows['addr']; ?></td>
				<td>
				   <form action="?page=cStaff" method="post">
				   	  <input type="hidden" name="mem_id" value="<?php echo $rows['mem_id'];  ?>">
				      <button class="btn btn-success btn-sm">Update</button>
				   </form>
				</td>
			</tr>
		<?php
	    }
			echo "</table>";
	}else{
		echo "<h3 class='alert alert-danger'>Oops record is not found...</h3>";
	}
	}
}/*ending of the class*/

?>