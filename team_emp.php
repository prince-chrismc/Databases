<?php
session_start();
?>
<html lang="en">
<head>
    <title>Team Members</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap_main.css">
</head>
<body>
<?php
require_once 'db_login.php';
$teamID = $_GET["teamID"];
?>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Comp353 wsc_4</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php if(isset($_SESSION['empid'])) echo 'emp_index.php'; else if(isset($_SESSION['custid'])) echo 'cust_index.php';?>">Home</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid text-left">
    <div class="row content">
        <div class="col-sm-2 sidenav">
            <?php require('nav.php'); ?>
        </div>
        <div class="col-sm-8">
        <?php
        $sql = "select TEAM.teamName, empName, salary, empAddress, empPhoneNum from TEAM
        left join TEAM_EMPLOYEE on TEAM.teamID=TEAM_EMPLOYEE.teamID
        left join EMPLOYEE on TEAM_EMPLOYEE.empID=EMPLOYEE.empID
        where TEAM.teamID = $teamID
        order by EMPLOYEE.empName";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            echo "<h1>Team: $row[teamName]</h1><br>";
            echo "<hr>";
            echo "<table class=\"table table-striped\">";
            echo "<thead><tr><th>Employee Name</th><th>Salary($)</th><th>Address</th><th>Phone Number</th></tr>";
            // output data of each row
            while ($row) {
                echo "<tr><td>$row[empName]</td><td>$row[salary]</td><td>$row[empAddress]</td><td>$row[empPhoneNum]</td></tr>";
                $row = $result->fetch_assoc();
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        $mysqli->close()
        ?>
        </div>
    </div>
</div>
</body>
</html>

