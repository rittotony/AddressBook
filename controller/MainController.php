<?php

require('../model/common_functions/functions.php');

class mainController
{
	private $myFunction;
    private $formdata;
    private $username;
	private $phone;
	private $email;
    private $password;
	private $ids;
	private $sessionId;
	public $dbConnection;
	function __construct()
	{
		$this->myFunction = new commonFunctions();
		$this->formdata = isset($_POST['formdata']) ? $_POST['formdata'] : array();
		$this->name = isset($this->formdata['name']) ? $this->formdata['name'] : "";
		$this->email = isset($this->formdata['email']) ? $this->formdata['email'] : "";
		$this->phone = isset($this->formdata['phone']) ? $this->formdata['phone'] : "";
		$this->password = isset($this->formdata['password']) ? $this->formdata['password'] : "";
		$this->ids = isset($this->formdata['ids']) ? $this->formdata['ids'] : "";
	    isset($_SESSION['user_id']) ? $this->sessionId = $_SESSION['user_id'] : $this->sessionId="";
	}

	function sqlQueries()
	{
		$array = array();
		
		$array[0] = "INSERT INTO tbl_register(name, email, phone, password) VALUES('".$this->name."', '".$this->email."', '".$this->phone."', '".$this->password."')";
		
		$array[1] = "SELECT * FROM tbl_address WHERE created_by_id='".$this->sessionId."'";
		
		$array[2] = "INSERT INTO tbl_address(name, email, phone, created_by_id) VALUES('".$this->name."', '".$this->email."', '".$this->phone."', '".$this->sessionId."')";
		
		$array[3] = "UPDATE tbl_address SET name='".$this->name."', email='".$this->email."', phone='".$this->phone."' WHERE ids='".$this->ids."'";
		
		$array[4] = "SELECT * FROM tbl_register WHERE email='".$this->email."' AND password='".$this->password."'";
		
		return $array;
	}
	
	function event($action)
	{
		$SQL=$this->sqlQueries();
		switch($action)
		{
			case "registration":
			   $this->result = $this->myFunction->insertData($SQL[0]);
			   if($this->result>=1)
			   {
				   $_SESSION['user_id'] = $this->result;
				   $_SESSION['user_name'] = $this->name;
			   }
			   echo $this->result;
			break;
			
			case "list_user_address":
			   $this->result = $this->myFunction->listData($SQL[1]);
			   echo $this->result;
			break;
			
			case "add_address":
			   $this->result = $this->myFunction->insertData($SQL[2]);
			   echo $this->result;
			break;
			
			case "edit_address":
			   $this->result = $this->myFunction->updateDataORDelete($SQL[3]);
			   echo $this->result;
			break;
			
			case "login":
			    $this->result = $this->myFunction->userAuthenticationforcheck($SQL[4],$this->password);
			    echo $this->result;
			break;
			
			default:
			     "No Action Found !";
		    break;		 
		}
	}
	
	
}

$obj = new mainController();
$obj->event($_POST['action']);


?>