<?php
session_start();
?>
<html>
<head>
    <title>Phase List</title>
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

$sql = "select phaseID, phaseName, projName,
phaseDetails, phaseDateStart, phaseDateEnd from PHASE
left join PROJECT on PHASE.projID=PROJECT.projID
where PROJECT.projID = $projID
order by PHASE.phaseDateStart";

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

<h2>Phase List</h2>

<?php
if ($result->num_rows > 0) {
    echo "<div class='results'>";
    echo "<table border=1>";
    echo "<tr><td>Phase Name</td><td>Details</td><td>Phase Start Date</td><td>Phase End Date</td></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td><a href=\"phase_task.php?phaseID=$row[phaseID]&phaseName=$row[phaseName]&projName=$row[projName]\">
                $row[phaseName]</td><td>$row[phaseDetails]</td><td>$row[phaseDateStart]</td><td>$row[phaseDateEnd]</td></tr>";
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
