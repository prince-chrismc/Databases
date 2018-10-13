<?php
session_start();
?>
<html>
<head>
    <title>Customers List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap_main.css">
</head>
<body>
<?php

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
        <div id='restricted' class="col-sm-8">
            <h1>Customer List</h1>
            <hr>
            <?php
            require_once './db_login.php';
            $sql = "SELECT * FROM CUSTOMER";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {

                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>ID</th><th>Name</th><th>Address</th><th>Phone Number</th><th></th></tr></thead>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $custID = $row["custID"];
                    echo "<tr><td>" . $custID . "</td><td>" . $row["custName"] . "</td><td>" . $row["custAddress"] .
                        "</td><td>" . $row["custPhoneNum"] . "</td>
                         <td><a href=\"update_cust.php?custID=$custID\">Update</a></td></tr>";
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
