<html>
<head>
    <title>Project Page</title>
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
$sql = "SELECT * FROM PROJECT WHERE projID=$projID";
$projInfo = $mysqli->query($sql)->fetch_array();
$projName = $projInfo['projName'];

echo "<h2>Project Info: </h2><br>";
echo "Name: $projName<br>";
echo "Details: $projInfo[projDetails]<br>";
echo "Allocated Balance: $projInfo[budgetAmount]<br>";
echo "Estimated Costs: $projInfo[estimatedCost]<br>";
echo "Starting Date: $projInfo[projDateStart]<br>";
echo "Ending Date: $projInfo[projDateEnd]<br>";

$sql = "select phaseID, phaseName, projName,
phaseDetails, phaseDateStart, phaseDateEnd from PHASE
left join PROJECT on PHASE.projID=PROJECT.projID
where PROJECT.projID = $projID
AND phaseDateEnd is null
order by phaseDateStart";
$projResult = $mysqli->query($sql);
echo "Current Phase: ";
if($projResult->num_rows > 0){
    $row = $projResult->fetch_array();
    echo "<a href=\"phase_task.php?phaseID=$row[phaseID]&phaseName=$row[phaseName]&projName=$row[projName]\">$row[phaseName]</a><br><br>";
}
else{
    echo "None<br><br>";
}

echo "<li><a href=\"proj_team.php?projID=$projID&projName=$projName\">Teams In The Project</a></li>";
echo "<li><a href=\"proj_phase.php?projID=$projID&projName=$projName\">Phases In The Project</a></li>";
echo "<li><a href=\"proj_task.php?projID=$projID&projName=$projName\">Tasks In The Project</a></li>";
echo "<li><a href=\"proj_trans.php?projID=$projID&projName=$projName\">Transactions In The Project</a></li>";

$mysqli->close();

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
</body>
</html>

