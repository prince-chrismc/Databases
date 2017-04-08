<?php
session_start();
?>
<html>
<head>
    <title>Material List</title>
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
            <?php require('nav.php'); ?>
        </div>
        <div class="col-sm-8">
            <h1>Material List</h1>
            <hr>
            <?php
            require_once './db_login.php';
            $sql = "select MATERIAL.matID, matName, MATERIAL_SUPPLIER.matCost, deliveryTime, supName
            from MATERIAL
            left join MATERIAL_SUPPLIER on MATERIAL.matID=MATERIAL_SUPPLIER.matID
            left join SUPPLIER on MATERIAL_SUPPLIER.supID=SUPPLIER.supID
            order by matName";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>ID</th><th>Name</th><th>Cost($)</th><th>Delivery Time(Days)</th><th>Supplier</th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["matID"] . "</td><td>" . $row["matName"] . "</td><td>" .
                        $row["matCost"] . "</td><td>" . $row["deliveryTime"] . "</td><td>" . $row["supName"] . "</td></tr>";
                }
                echo "</table>";
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
