<?php
session_start();
?>
<html lang="en">
<head>
    <title>Project Phase Page</title>
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
$projID = $_GET["projID"];
$projName = $_GET["projName"];
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
            <?php require 'nav_proj.php'?>
        </div>
        <div class="col-sm-8">
            <h1>Project: <?=$projName?></h1>
            <h2>Phase List</h2>
            <hr>
            <?php
        $sql = "select phaseID, phaseName, projName,
        phaseDetails, phaseDateStart, phaseDateEnd from PHASE
        left join PROJECT on PHASE.projID=PROJECT.projID
        where PROJECT.projID = $projID
        order by PHASE.phaseDateStart";

        $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>Phase Name</th><th>Details</th><th>Phase Start Date</th><th>Phase End Date</th><th></th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td><a href=\"phase_task.php?phaseID=$row[phaseID]&phaseName=$row[phaseName]&projName=$row[projName]\">
                $row[phaseName]</td><td>$row[phaseDetails]</td><td>$row[phaseDateStart]</td><td>$row[phaseDateEnd]</td>
                <td><a href=\"update_phase.php?phaseID=$row[phaseID]&projID=$projID\">Update</a></td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            $mysqli->close();
        ?>
        </div>
    </div>
</div>
</body>
</html>
