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

$projID = $_GET["projID"];
$projName = $_GET["projName"];

$sql = "select TASK.phaseID, TASK.taskID, PHASE.phaseName, taskDetails,
taskCost, taskEstimateHours, taskDateStart, taskDateEnd
from TASK
left join PHASE on TASK.phaseID=PHASE.phaseID
where PHASE.projID = $projID
order by PHASE.phaseID, TASK.taskID";

$result = $mysqli->query($sql);
?>

<header>
    <h1>Project: <?=$projName?></h1>
    <span class="menu" href="javascript:void(0)" onmouseover="openNav()">Project Menu</span>
</header>

<nav id="nav">
    <ul>
        <li><a href="javascript:void(0)" onclick="closeNav()"> Close</a></li>
        <li><a href="index.php"> Home</a></li>
        <li><a href="proj_team.php?projID=<?=$projID?>&projName=<?=$projName?>">Teams In The Project</a></li>
        <li><a href="proj_phase.php?projID=<?=$projID?>&projName=<?=$projName?>">Phases In The Project</a></li>
        <li><a href="proj_task.php?projID=<?=$projID?>&projName=<?=$projName?>">Tasks In The Project</a></li>
        <li><a href="proj_trans.php?projID=<?=$projID?>&projName=<?=$projName?>">Transactions In The Project</a></li>
    </ul>
</nav>

<?php
echo "<h2>Task List</h2>";

if ($result->num_rows > 0) {
    echo "<div class='results'>";
    echo "<table border=1>";
    echo "<tr><td>Phase ID</td><td>Task ID</td><td>Phase Name</td><td>Task Details</td><td>Task Cost($)</td>
            <td>Time Estimate(h)</td><td>Start Date</td><td>End Date</td></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["phaseID"] . "</td><td>" . $row["taskID"] . "</td><td>" . $row["phaseName"] .
            "</td><td>" . $row["taskDetails"] . "</td><td>" . $row["taskCost"] . "</td><td>" .
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
