<?php
session_start();
?>
<html lang="en">
<head>
    <title>Home Page</title>
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
        <div id='restricted' class="col-sm-8">
            <h1>Welcome <?php $name = "SELECT * FROM EMPLOYEE where empID=$_SESSION[empid]";$name2 = $mysqli->query($name);
                if ($name2->num_rows > 0) {$name3 = $name2->fetch_assoc(); echo $name3["empName"]; }?></h1>
            <hr>
            <ul>
                <li><a href="add_emp.php">Register New Employee</a></li>
                <li><a href="add_team.php">Register New Team</a></li>
                <li><a href="add_cust.php">Register New Customer</a></li>
                <li><a href="add_proj.php">Add a New Project</a></li>
                <li><a href="add_phase.php">Add a New Phase</a></li>
                <li><a href="add_task.php">Add a New Task</a></li>
                <li><a href="add_sup.php">Add a New Supplier</a></li>
                <li><a href="add_mat.php">Add a New Material</a></li>
                <li><a href="add_tran.php">Add a New Transaction</a></li>
                <li><a href="add_team_proj.php">Add Team to a Project</a></li>
                <li><a href="add_emp_team.php">Add Employee to a Team</a></li>
                <li><a href="add_sup_mat.php">Add Supplier to a Material</a></li>
                <li><a href="add_mat_phase.php">Add Material to a Phase</a></li>
                <li><a href="remove_many_many.php">Deletion Page</a></li>
            </ul>
            <hr>
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
<?php
$mysqli->close();
?>
</body>
</html>