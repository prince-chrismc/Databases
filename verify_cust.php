<?php
#Connects to the
session_start();
require_once './db_login.php';

#If exists has 1 row, then the login info was correct

$custid = $_POST['custid'];
$custpwd = $_POST['custpwd'];
$query = "SELECT * FROM CUSTOMER WHERE custID = '$custid' and custPWD = '$custpwd'";
$results = $mysqli->query($query);

if($results->num_rows == 1)
{
    $_SESSION['custid'] = $custid;
    header('Location: cust_index.php');
}
else
{
    echo "<script> alert('Account id or password is incorrect'); </script>";
    echo"<script>window.location.href='index.php';</script>";
}
?>