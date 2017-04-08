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
    $phone = $_POST['phone'];
    $addr = $_POST['addr'];
    $pwd = $_POST['pwd'];


    $sql = "INSERT INTO CUSTOMER(custname,custpwd,custaddress,custphonenum) VALUES".
        "('$name','$pwd','$addr','$phone')";

    if ($mysqli->query($sql) === TRUE)
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information.
              </div></div>";

    $mysqli->close();
}
?>

<div class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Registar New Customer</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
           <label for="name">Name:</label>
           <input type="text" class="form-control" id="name" name="name" placeholder="Enter the full name">
        </div>
        <div class="form-group">
           <label for="pwd">Password:</label>
           <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password">
        </div>
        <div class="form-group">
           <label for="addr">Address:</label>
           <input type="text" class="form-control" id="addr" name="addr" placeholder="Enter the address">
        </div>
        <div class="form-group">
           <label for="num">Phone Number:</label>
           <input type="text" class="form-control" id="num" name="phone" placeholder="Enter the contact number">
        </div>
        <div class="form-group">
            <label for="projID">Project:</label>
            <select class="form-control" id="projID" name="projid">
                <option></option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option>" . $row['projID'] . " - " . $row['projName'] . "</option>";
                    }
                }?>
            </select>
        </div>
       <button type="submit" class="btn btn-default" name="submit">Submit</button>
       <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>


<script>
    $(document).ready(function(){
        $(".close").click(function(){
            $("#myAlert").alert("close");
        });
    });
</script>
</body>
