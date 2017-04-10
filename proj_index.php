<?php
session_start();
?>
<html lang="en">
<head>
    <title>Project Page</title>
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
$sql = "SELECT * FROM PROJECT WHERE projID=$projID";
$projInfo = $mysqli->query($sql)->fetch_array();
$projName = $projInfo['projName'];
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
            <h2>Project Info</h2>
            <hr>
            <?php
            echo "<h4>Name: $projName</h4>";
            echo "<p>Project Details: $projInfo[projDetails]</p>";
            echo "<p>Budget Remaining: $projInfo[budgetAmount]$</p>";

            $sql_tot_trans = "select proj, sum(cost) as totCost from
            (select projID as proj, transCost as cost from TRANSACTION_MATERIAL
            union all
            select projID as proj,  transCost as cost  from TRANSACTION_SALARY
            union all
            select projID as proj, transCost as cost  from TRANSACTION_TASK) t1
            where proj=$projID
            group by proj";

            echo "<p>Total Transactions Amount: ";

            $tot_trans = $mysqli->query($sql_tot_trans);
            if($tot_trans->num_rows > 0){
                $row = $tot_trans->fetch_array();
                echo "$row[totCost]$</p>";
                echo "<p>Total Budget Invested: " . ($row["totCost"] + $projInfo["budgetAmount"] . "$</p>");
            }
            else {
                echo "0$</p>";
                echo "<p>Total Budget Invested: " . ($projInfo["budgetAmount"] . "$</p>");
            }

            echo "<p>Estimated Costs: $projInfo[estimatedCost]$</p>";
            echo "<p>Starting Date: $projInfo[projDateStart]</p>";
            echo "<p>Ending Date: $projInfo[projDateEnd]</p>";

            $sql_proj = "select phaseID, phaseName, projName,
            phaseDetails, phaseDateStart, phaseDateEnd from PHASE
            left join PROJECT on PHASE.projID=PROJECT.projID
            where PHASE.projID = $projID
            AND phaseDateEnd is null
            order by phaseDateStart";
            $projResult = $mysqli->query($sql_proj);
            echo "Current Phase: ";

            if($projResult->num_rows > 0){
                $row = $projResult->fetch_array();
                echo "<a href=\"phase_task.php?phaseID=$row[phaseID]&phaseName=$row[phaseName]&projName=$row[projName]\">$row[phaseName]</a>";
            }
            else {
                echo "None<br><br>";
            }
            $mysqli->close();
            ?>
        </div>
    </div>
</div>
</body>
</html>

