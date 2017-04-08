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
        <div class="col-sm-8">
            <h1>Transaction List</h1>
            <hr>
            <?php
            require_once './db_login.php';
            $sql = "select transID, transType, PROJECT.projID, PROJECT.projName, typeID,
            transCost, transBalanceDue, transDate
            from TRANSACTION
             join PROJECT on TRANSACTION.projID=PROJECT.projID
             order by transDate";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>ID</th><th>Transaction Type</th><th>Type ID</th><th>Project ID</th><th>Project Name</th>
                    <th>Transaction Costs($)</th><th>Balance Due($)</th><th>Transaction Date</th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["transID"] . "</td><td>" . $row["transType"] . "</td><td>" . $row["projID"] .
                        "</td><td>" . $row["projName"] . "</td><td>" .$row["typeID"] . "</td><td>" .
                        $row["transCost"] . "</td><td>" .$row["transBalanceDue"] . "</td><td>" . $row["transDate"]. "</td></tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "0 results";
            }
            $mysqli->close();
            ?>
            <hr>
        </div>
    </div>
</div>
</body>
</html>
