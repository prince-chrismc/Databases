<?php
session_start();
?>
<html>
<head>
    <title>Team List</title>
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

$sql = "select TEAM.teamName, TEAM.teamID from PROJECT
left join PROJECT_TEAM on PROJECT.projID=PROJECT_TEAM.projID
left join TEAM on PROJECT_TEAM.teamID=TEAM.teamID
where PROJECT.projID=$projID
order by teamName";

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

echo "<h2>Team List</h2>";

if ($result->num_rows > 0) {

    echo "<table border=1>";
    echo "<tr><td>Team Name</td><td>Team ID</td></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td><a href=\"team_emp.php?teamID=$row[teamID]\">$row[teamName]</td><td>$row[teamID]</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$mysqli->close();


?>
</body>
</html>