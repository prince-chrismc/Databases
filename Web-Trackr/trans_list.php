<?php
session_start();
?>
<html>
<head>
    <title>Transaction List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap_main.css">
</head>
<body>
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
        <div id='restricted' class="col-sm-8">
            <h1>Transaction List</h1>
            <hr>
            <?php
            require_once './db_login.php';
            $sql = "select transID, PROJECT.projID, PROJECT.projName, MATERIAL.matID, matName,
              SUPPLIER.supID, supName, transCost, transDate
              from TRANSACTION_MATERIAL
               join PROJECT on TRANSACTION_MATERIAL.projID=PROJECT.projID
               join MATERIAL on TRANSACTION_MATERIAL.matID=MATERIAL.matID
               join SUPPLIER on TRANSACTION_MATERIAL.supID=SUPPLIER.supID
               order by transDate";
            echo "<h2>Material Transactions</h2>";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>ID</th><th>Project ID</th><th>Project Name</th><th>Material ID</th><th>Material Name</th>
              <th>Supplier ID</th><th>Supplier Name</th><th>Transaction Costs($)</th><th>Transaction Date</th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["transID"] . "</td><td>" . $row["projID"] . "</td>
              <td><a href=\"proj_index.php?projID=$row[projID]\">" . $row["projName"] .
                        "</td><td>" . $row["matID"] . "</td><td>$row[matName]</td><td>$row[supID]</td><td>$row[supName]</td><td>" .
                        $row["transCost"] . "</td><td>" . $row["transDate"]. "</td></tr>";
                }
                echo "</table>";


            } else {
                echo "0 results";
            }
            echo "<hr>";
            $sql = "select transID, PROJECT.projID, PROJECT.projName, TASK.taskID, taskDetails,
      transCost, transDate
      from TRANSACTION_TASK
       join PROJECT on TRANSACTION_TASK.projID=PROJECT.projID
       join TASK on TRANSACTION_TASK.taskID=TASK.taskID
       order by transDate";
            echo "<h2>Task Transactions</h2>";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>ID</th><th>Project ID</th><th>Project Name</th><th>Task ID</th><th>Task Details</th>
              <th>Transaction Costs($)</th><th>Transaction Date</th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["transID"] . "</td><td>" . $row["projID"] . "</td>
              <td><a href=\"proj_index.php?projID=$row[projID]\">" . $row["projName"] .
                        "</td><td>" . $row["taskID"] . "</td><td>" .$row["taskDetails"] . "</td><td>" .
                        $row["transCost"] . "</td><td>" . $row["transDate"]. "</td></tr>";
                }
                echo "</table>";


            } else {
                echo "0 results";
            }
            echo "<hr>";
            $sql = "select transID, PROJECT.projID, PROJECT.projName, EMPLOYEE.empID, empName,
TEAM.teamID, teamName, transCost, transDate
      from TRANSACTION_SALARY
       join PROJECT on TRANSACTION_SALARY.projID=PROJECT.projID
       join EMPLOYEE on TRANSACTION_SALARY.empID=EMPLOYEE.empID
       join TEAM on TRANSACTION_SALARY.teamID=TEAM.teamID
       order by transDate";
            echo "<h2>Salary Transactions</h2>";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>ID</th><th>Project ID</th><th>Project Name</th><th>Employee ID</th><th>Employee Name</th>
              <th>Team ID</th><th>Team Name</th><th>Transaction Costs($)</th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["transID"] . "</td><td>" . $row["projID"] . "</td>
              <td><a href=\"proj_index.php?projID=$row[projID]\">" . $row["projName"] .
                        "</td><td>" . $row["empID"] . "</td><td>$row[empName]</td><td>$row[teamID]</td>
                  <td><a href=\"team_emp.php?teamID=$row[teamID]\">$row[teamName]</td><td>" .
                        $row["transCost"] . "</td><td>" . $row["transDate"] . "</td></tr>";
                }
                echo "</table>";

            } else {
                echo "0 results";
            }

            $mysqli->close();
            ?>

        </div>
        <?php
        if(isset($_SESSION['custid']))
        {
            echo"<div class='jumbotron jumbotron-fluid'>
    <div class='container'>
        <h1 class='display-3'>Access Denied</h1>
        <p class='lead'>You are not allowed to be on this page!</p>
        <hr>
        <form action=\"cust_index.php\">
            <button type=\"submit\" class=\"btn btn-default\">Back</button>
        </form>
    </div>
</div>";

            echo "<script>$('#restricted').hide();</script>";
        }?>
    </div>
</div>
</body>
</html>
