<?php

class dbConnection
{
	function myDB()
	{
		$connection = mysqli_connect("localhost", "root", "", "digiora");
		if(!$connection)
		{
			Die("Failed to connect".mysqli_connect_error());
		}
		return $connection;
		//echo "connection success";
	}
}



?>