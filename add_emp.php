<?php
session_start();
?>
<html>
<head>
    <title>Add New Employee</title>
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

$sql1 = "SELECT * FROM TEAM";
$result = $mysqli->query($sql1);

    if(isset($_POST['submit'])) {
        require_once './db_login.php';

        $name = $_POST['name'];
        $pw = $_POST['pw'];
        if(!empty($_POST['num']))
            $num = $_POST['num'];
        else $num = null;
        if(!empty($_POST['addr']))
            $addr = $_POST['addr'];
        else $addr = null;

        $sql2 = "INSERT INTO EMPLOYEE(empname,emppwd,empaddress,empphonenum) VALUES".
            "('$name','$pw','$addr','$num')";

        if ($mysqli->query($sql2) === TRUE)
		{
            echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";

            if(!empty($_POST['sal']) && $_POST['teamID'])
            {
                $newID = $mysqli->insert_id;
                $sal = $_POST['sal'];
                preg_match('/^[0-9]+/', $_POST['teamID'],$matches);
                $teamID = $matches[0];

                $insert = "INSERT INTO TEAM_EMPLOYEE VALUES".
                    "($newID,$teamID,$sal)";
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
			else{
                echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-info alert-dismissable\">
                            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                            <strong>Info!</strong> Indicates a neutral informative change or action. Missing information to add employee to a team.
                          </div></div>";
            }
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

    <h2>Register New Employee</h2>
    <form id="restricted"  method='POST'>
        <div class="form-group">
            <label for="name">*Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter the full name" required>
        </div>
        <div class="form-group">
            <label for="pwd">*Password:</label>
            <input type="password" class="form-control" id="pwd" name="pw" placeholder="Enter password" required>
        </div>
        <div class="form-group">
            <label for="addr">Address:</label>
            <input type="text" class="form-control" id="addr" name="addr" placeholder="Enter the address">
        </div>
        <div class="form-group">
            <label for="num">Phone Number:</label>
            <input type="text" class="form-control" id="num" name="num" placeholder="Enter the contact number">
        </div>
        <div class="form-group">
            <label for="projID">Team:</label>
            <select class="form-control" id="teamID" name="teamID" >
                <option></option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option>" . $row['teamID'] . " - " . $row['teamName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="sal">Salary:</label>
            <input type="number" class="form-control" id="sal" step="1" min="0" max="4294967295" name="sal" placeholder="Enter the salary">
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
