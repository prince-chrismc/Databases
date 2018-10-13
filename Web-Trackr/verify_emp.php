<?php
#Connects to the
session_start();
require_once './db_login.php';

	#If exists has 1 row, then the login info was correct

		$empid = $_POST['empid'];
		$emppwd = $_POST['emppwd'];
		$query = "SELECT * FROM EMPLOYEE WHERE empID = '$empid' and empPWD = '$emppwd'";
		$results = $mysqli->query($query);
				
		if($results->num_rows == 1)
		{
			$_SESSION['empid'] = $empid;			
			header('Location: emp_index.php');
		}
		
		else
		{
			echo "<script> alert('Account id or password is incorrect'); </script>";
			echo"<script>window.location.href='index.php';</script>";
		}
?>