<?php
session_start();
?>
    <html>
    <head>
        <title>Update Costumer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            function clearForm(){ document.getElementById("myform").reset(); }
        </script>
    </head>
    <body style="margin-top: 3em">

<?php
require_once 'db_login.php';
$empID = $_SESSION['empid'];

if(!isset($_GET['custID']) || empty($_GET['custID']))
    echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-danger alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. There is not customer selected to update!
              </div></div>";

$custID = $_GET['custID'];


if(isset($_POST['submit']))
{

    $new_name = $_POST['name'];
    $new_pwd = $_POST['pwd'];
    $new_addr = $_POST['addr'];
    $new_num = $_POST['phone'];

    $update = "UPDATE CUSTOMER SET custName='$new_name', custPwd='$new_pwd', custAddress='$new_addr', custPhoneNum='$new_num' WHERE custID=$custID";
    if($mysqli->query($update))
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information. ". mysqli_error($mysqli) ."
              </div></div>";
}


$sql = "select * from CUSTOMER where custID=$custID";
$result = $mysqli->query($sql);
$cust = $result->fetch_assoc();

$name = $cust['custName'];
$pwd = $cust['custPwd'];
$addr = $cust['custAddress'];
$num = $cust['custPhoneNum'];


?>

<div class="container">
    <form action="<? if(isset($_SESSION['custid'])) echo 'cust_index.php'; else echo 'cust_list.php';?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Update Customer</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="name">*Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?echo $name?>" placeholder="Enter the full name" required>
        </div>
        <div class="form-group">
            <label for="pwd">*Password:</label>
            <input type="password" class="form-control" id="pwd" name="pwd" value="<?echo $pwd?>" placeholder="Enter password" required>
        </div>
        <div class="form-group">
            <label for="addr">Address:</label>
            <input type="text" class="form-control" id="addr" name="addr" value="<?echo $addr?>" placeholder="Enter the address">
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?echo $num?>" placeholder="Enter the contact number">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>
    </body>
