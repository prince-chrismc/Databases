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
        $num = $_POST['num'];
        $addr = $_POST['addr'];
        $pw = $_POST['pw'];		
		$sal = $_POST['sal'];
		preg_match('/^[0-9]+/', $_POST['teamID'],$matches);
		$teamID = $matches[0];

        $sql2 = "INSERT INTO EMPLOYEE(empname,emppwd,empaddress,empphonenum) VALUES".
            "('$name','$pw','$addr','$num')";

        if ($mysqli->query($sql2) === TRUE)
		{
			$newID = $mysqli->insert_id;
			$insert = "INSERT INTO TEAM_EMPLOYEE VALUES".		
			"($newID,$teamID,$sal)";
			$mysqli->query($insert);
			echo "<script>alert('Success')</script>";
		}
            
        else
            echo "<script>alert('Please verify that your entry is correct')</script>";
		
        $mysqli->close();
    }
?>

<div class="container">
<form action="emp_index.php">
    <button type="submit" class="btn btn-default">Back</button>
</form>

    <h2>Register New Employee</h2>
    <form id="myform"  method='POST'>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter the full name">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" name="pw" placeholder="Enter password">
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
            <select class="form-control" id="teamID" name="teamID">
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
            <input type="number" class="form-control" id="sal" step="500" name="sal" placeholder="Enter the salary">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>
</body>
