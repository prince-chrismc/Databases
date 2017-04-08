<?php
session_start();
?>
<html>
<head>
    <title>Add New Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        function clearForm(){ document.getElementById("myform").reset(); }
    </script>
</head>
<body style="margin-top: 3em">

<?php
require_once 'db_login.php';
$empID = $_SESSION['empid'];

if(isset($_POST['submit'])) {
    require_once './db_login.php';

    $name = $_POST['name'];
    $details = $_POST['details'];
    $budget = $_POST['budget'];
    $estimate = floatval($_POST['estimate']);
    $startdate = (strlen($_POST['startdate']) == 0 ? "NULL" : "'".$_POST['startdate']."'");
    $enddate = (strlen($_POST['enddate']) == 0 ? "NULL" : "'".$_POST['enddate']."'");

    $sql = "INSERT INTO PROJECT(projName,projDetails,budgetAmount,estimatedCost,projDateStart,projDateEnd) VALUES".
        "('$name','$details','$budget','$estimate',$startdate,$enddate)";

    if ($mysqli->query($sql) === TRUE)
        echo "<script>alert('Success')</script>";
    else
        echo "<script>alert('Please verify that your entry is correct')</script>";

    $mysqli->close();
}
?>

<div class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

        <h2>Create New Project</h2>
        <form id="myform" method='POST'>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the project name">
            </div>
            <div class="form-group">
                <label for="dtl">Details:</label>
                <input type="text" class="form-control" id="dtl" name="details" placeholder="Enter project details">
            </div>
            <div class="form-group">
                <label for="bug">Budget:</label>
                <input type="number" class="form-control" id="bug" name="budget" step="1" placeholder="Enter the project's budget">
            </div>
            <div class="form-group">
                <label for="est">Estimated:</label>
                <input type="number" class="form-control" id="est" step="1" name="estimate" placeholder="Enter the project's estimated cost">
            </div>
            <div class="form-group">
                <label for="start">Start Date:</label>
                <input type="date" class="form-control" id="start" name="startdate" min="2000-01-01">
            </div>
            <div class="form-group">
                <label for="end">End Date:</label>
                <input type="date" class="form-control" id="end" name="enddate" min="2000-01-01">
            </div>
            <button type="submit" class="btn btn-default" name="submit">Submit</button>
            <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
        </form>
</div>
</body>
