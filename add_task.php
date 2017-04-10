<?php
session_start();
?>
<html>
<head>
    <title>Add New Task</title>
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

$sql = isset($_SESSION['custid']) ? "SELECT a.projName, a.phaseID, a.phaseName FROM ( SELECT * FROM PROJECT natural left join PHASE WHERE custID=1 )  as a"
    : "select phaseID, phaseName from PHASE";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])) {
    require_once './db_login.php';

    $cost = floatval($_POST['cost']);
    $startdate = (strlen($_POST['startdate']) == 0 ? "NULL" : "'".$_POST['startdate']."'");
    $enddate = (strlen($_POST['enddate']) == 0 ? "NULL" : "'".$_POST['enddate']."'");
    $estimate = $_POST['estimate'];
    preg_match('/^[0-9]+/', $_POST['phaseID'],$matches);
    $phaseid = $matches[0];
    $details = $_POST['details'];

    $sql2 = "INSERT INTO TASK(phaseid,taskdetails,taskcost,taskestimatehours,taskdatestart,taskdateend) VALUES".
        "($phaseid,'$details',$cost,$estimate,$startdate,$enddate)";

    if ($mysqli->query($sql2) === TRUE)
    {
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
    }

    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information. ". mysqli_error($mysqli) ."
              </div></div>";

    echo $mysqli->error;

    $mysqli->close();
}
?>

<div class="container">
    <form action="<? if(isset($_SESSION['custid'])) echo 'cust_index.php'; else echo 'emp_index.php';?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Create New Task</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="phaseID">*Phase:</label>
            <select class="form-control" id="phaseID" name="phaseID" required>
                <option></option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option>$row[phaseID] - $row[phaseName] for $row[projName]</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="dtl">*Details:</label>
            <input type="text" class="form-control" id="dtl" name="details" placeholder="Enter the task details" required>
        </div>
        <div class="form-group">
            <label for="bug">*Cost:</label>
            <input type="number" class="form-control" id="bug" name="cost" min="0" max="4294967295" placeholder="Enter the task's cost" required>
        </div>
        <div class="form-group">
            <label for="est">*Estimated Hours:</label>
            <input type="number" class="form-control" id="est" name="estimate" min="0" max="4294967295" placeholder="Enter the task's estimated hours" required>
        </div>
        <div class="form-group">
            <label for="start">*Start Date:</label>
            <input type="date" class="form-control" id="start" name="startdate" min="2000-01-01" required>
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

