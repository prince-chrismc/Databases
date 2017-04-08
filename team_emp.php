<?php
session_start();
?>
<html>
<head>
    <title>Team Members</title>
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

$teamID = $_GET["teamID"];

$sql = "select TEAM.teamName, empName, salary, empAddress, empPhoneNum from TEAM
left join TEAM_EMPLOYEE on TEAM.teamID=TEAM_EMPLOYEE.teamID
left join EMPLOYEE on TEAM_EMPLOYEE.empID=EMPLOYEE.empID
where TEAM.teamID = $teamID
order by EMPLOYEE.empName";
$result = $mysqli->query($sql);
?>

<header>
    <?php
    echo "<h1>Team Information</h1>";
    if(isset($_SESSION['empid']) || isset($_SESSION['custid']))
        echo "<span class='menu' href='javascript:void(0)' onmouseover='openNav()'> Menu";
    ?>
</header>
<?php require('nav.php'); ?>

<?php

if ($result->num_rows > 0) {
    echo "<div class='results'>";
    $row = $result->fetch_assoc();

    echo "<h1>Team: $row[teamName]</h1><br>";

    echo "<table border=1>";
    echo "<tr><td>Employee Name</td><td>Salary($)</td><td>Address</td><td>Phone Number</td></tr>";
    // output data of each row
    while ($row) {
        echo "<tr><td>$row[empName]</td><td>$row[salary]</td><td>$row[empAddress]</td><td>$row[empPhoneNum]</td></tr>";
        $row = $result->fetch_assoc();
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "0 results";
}

$mysqli->close()
?>

</body>
</html>

