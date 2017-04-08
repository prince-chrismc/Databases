<?php
session_start();
?>
<html>
<head>
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="styling.css"/>
    <script>
        function openNav() { document.getElementById("nav").style.width = "250px"; }
        function closeNav() { document.getElementById("nav").style.width = "0px"; }
    </script>
</head>
<body>

<?php

require_once './db_login.php';

$phaseID = $_GET["phaseID"];
$phaseName= $_GET["phaseName"];
$projName= $_GET["projName"];

$sql = "select TASK.taskID, taskDetails,
taskCost, taskEstimateHours, taskDateStart, taskDateEnd
from TASK
left join PHASE on TASK.phaseID=PHASE.phaseID
where PHASE.phaseID = $phaseID
order by TASK.taskDateStart";



$result = $mysqli->query($sql);
?>

<header>
    <?php
    echo "<h1>Project: $projName</h1>";
    if(isset($_SESSION['empid']) || isset($_SESSION['custid']))
        echo "<span class='menu' href='javascript:void(0)' onmouseover='openNav()'> Menu";
    ?>
</header>
<?php require('nav.php'); ?>

<?php
echo "<h2>Phase: $phaseName</h2>";
echo "<h3>Task List</h3>";

if ($result->num_rows > 0) {
    echo "<div class='results'>";
    echo "<table border=1>";
    echo "<tr><td>Task ID</td><td>Task Details</td><td>Task Cost($)</td>
            <td>Time Estimate(h)</td><td>Start Date</td><td>End Date</td></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["taskID"] . "</td><td>" . $row["taskDetails"] . "</td><td>" . $row["taskCost"] . "</td><td>" .
            $row["taskEstimateHours"] . "</td><td>" . $row["taskDateStart"] . "</td><td>" . $row["taskDateEnd"] . "</td></tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "0 results";
}
$mysqli->close();


?>
</body>
</html>
