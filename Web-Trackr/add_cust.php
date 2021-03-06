<?php
session_start();
?>
<html>
<head>
    <title>Add New Costumer</title>
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

$sql = "select PROJECT.projID, projName from PROJECT";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])) {
    require_once './db_login.php';

    $name = $_POST['name'];
    $pwd = $_POST['pwd'];
        if(!empty($_POST['phone']))
        $phone = $_POST['phone'];
    else $phone = null;
    if(!empty($_POST['addr']))
        $addr = $_POST['addr'];
    else $addr = null;

    $sql = "INSERT INTO CUSTOMER(custname,custpwd,custaddress,custphonenum) VALUES".
        "('$name','$pwd','$addr','$phone')";

    if ($mysqli->query($sql) === TRUE)
    {
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";

        /*if(!empty($_POST['projid']))
        {
            preg_match('/^[0-9]+/', $_POST['projid'],$matches);
            $projid = $matches[0];
            $newID = $mysqli->insert_id;
            $insert = "INSERT INTO PROJECT_TEAM VALUES".
                "($newid,$projid)";

            if($mysqli->query($insert))
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
        else
        {
            echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-info alert-dismissable\">
                            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                            <strong>Info!</strong> Indicates a neutral informative change or action. Missing information to add customer to a project.
                          </div></div>";
        }*/
    }
    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information. ". mysqli_error($mysqli) ."
              </div></div>";

    $mysqli->close();
}
?>

<div id='restricted' class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Registar New Customer</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
           <label for="name">*Name:</label>
           <input type="text" class="form-control" id="name" name="name" placeholder="Enter the full name" required>
        </div>
        <div class="form-group">
           <label for="pwd">*Password:</label>
           <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password" required>
        </div>
        <div class="form-group">
           <label for="addr">Address:</label>
           <input type="text" class="form-control" id="addr" name="addr" placeholder="Enter the address">
        </div>
        <div class="form-group">
           <label for="num">Phone Number:</label>
           <input type="text" class="form-control" id="num" name="phone" placeholder="Enter the contact number">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>
<?php
if(isset($_SESSION['custid']))
{
    echo"<div class='jumbotron jumbotron-fluid'>
    <div class='container'>
        <h1 class='display-3'>Access Denied</h1>
        <p class='lead'>You are not allowed to be on this page!</p>
        <hr>
        <form action=\"cust_index.php\">
            <button type=\"submit\" class=\"btn btn-default\">Back</button>
        </form>
    </div>
</div>";

    echo "<script>$('#restricted').hide();</script>";
}?>
</body>
