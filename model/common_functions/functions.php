<?php
session_start();
require('../model/connection/connection.php');


abstract class functionsDetails
{
	abstract public function insertData($sql);
	abstract public function updateDataORDelete($sql);
	abstract public function listData($sql);
	abstract public function ReturnCountValue($SQL);
	abstract public function userAuthenticationforcheck($SQL,$password);
}

class commonFunctions extends functionsDetails
{
	public $conn, $result;
	function __construct()
	{
		$connection = new dbConnection();
		$this->conn = $connection->myDB();
	}
	
	function ReturnCountValue($SQL)
	{
			$this->result = mysqli_query($this->conn,$SQL);
			$affected_status = mysqli_num_rows($this->result);
			return $affected_status;
	}
	
	public function userAuthenticationforcheck($SQL,$password)
	{
		$userID;
		$username;
		$userPassword;
		$this->result = mysqli_query($this->conn,$SQL);
		$row_count = mysqli_num_rows($this->result);
		while($rows=mysqli_fetch_assoc($this->result))
		{
			$userID =$rows['ids'];
			$username = $rows['name'];
			$userPassword = $rows['password'];
		}
		
		if($row_count>=1)
		{			   	
			if($password==$userPassword)
			{
			   $_SESSION['loginStatus']="pageload";
			   $_SESSION['user_id'] = $userID;
			   $_SESSION['user_name'] = $username;
			   return "redirect-success";
			}
			else
			{
				return 'Please provide correct password...!';
			}

		}
		else
		{
			return 'Username does not Exists...!';
		}	
		
	}


	
	function insertData($sql)
	{
		//mysqli_query($this->conn, $sql);
		if(mysqli_query($this->conn, $sql))
		{
		   $this->result = mysqli_insert_id($this->conn);
		   return $this->result;
		}
		else
		{
			return mysqli_error($this->conn);
		}

	}
	
	function listData($sql)
	{
		$temp = array();
		$this->result = mysqli_query($this->conn, $sql); 
		if ($this->result) { 
			while ($row = mysqli_fetch_assoc($this->result)) { 
				 $temp['data'][] = $row;
			}
			echo json_encode($temp);
		} else {
			echo "Error: " . mysqli_error($this->conn); 
		}
	}
	
	function updateDataORDelete($sql)
	{
		if(mysqli_query($this->conn, $sql))
		{
		   $this->result = mysqli_affected_rows($this->conn);
		   return $this->result;
		}
		else
		{
			return mysqli_error($this->conn);
		}
	}
	
	function __destruct()
	{
		//mysqli_free_result($this->result);
		mysqli_close($this->conn);
	}
	
}
$obj = new commonFunctions();



?>