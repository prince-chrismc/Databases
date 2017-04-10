<?php
session_start();
?>
<html>
<head>
    <title>Update Project</title>
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

if(!isset($_GET['taskID']) || empty($_GET['taskID']))
    echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-danger alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. There is not customer selected to update!
              </div></div>";

$taskID = $_GET['taskID'];


if(isset($_POST['submit']))
{
    $new_phase = $_POST['phaseID'];
    $new_dtl = $_POST['details'];
    $new_bgt = $_POST['budget'];
    $new_est = $_POST['estimate'];
    $new_str = $_POST['startdate'];
    $new_end = $_POST['enddate'];


    $update = "UPDATE TASK SET  phaseID='$new_phase', taskDetails= '$new_dtl',taskCost = $new_bgt, taskEstimateHours = $new_est, taskDateStart = '$new_str',
                  taskDateEnd = '$new_end' WHERE taskID = $taskID";
    if($mysqli->query($update))
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information. ". mysqli_error($mysqli) ."
              </div></div>";
}


$sql = "select * from TASK where taskID=$taskID";
$result = $mysqli->query($sql);
$task = $result->fetch_assoc();

$phase = $task['phaseID'];
$dtl = $task['taskDetails'];
$bgd = $task['taskCost'];
$cost = $task['taskEstimateHours'];
$start = $task['taskDateStart'];
$nend = $task['taskDateEnd'];
?>

<div class="container">
    <form action="<? if(isset($_SESSION['custid'])) echo 'cust_index.php'; else echo 'task_list.php'?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Update Task</h2>
    <form id="myform" method='POST'>
        <div id="phaseform" class="form-group">
            <label for="phaseID">*Phase:</label>
            <select class="form-control" id="phaseID" name="phaseID" required>
                <option></option>
                <?php
                $sql = "select phaseID, phaseName from PHASE";
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $phaseID = $row['phaseID'];
                        echo "<option value='$phaseID'>" . $phaseID . " - " . $row['phaseName'] . "</option>";
                    }
                }?>
            </select>
            <script type="text/javascript">
                <? if(isset($_SESSION['custid'])) echo "$('#phaseform').hide();";?>
                $('#phaseID').val('<?echo $phase;?>');
            </script>
        </div>
        <div class="form-group">
            <label for="dtl">Details:</label>
            <input type="text" class="form-control" id="dtl" name="details" value="<?echo $dtl?>" placeholder="Enter project details">
        </div>
        <div class="form-group">
            <label for="bug">Budget:</label>
            <input type="number" class="form-control" id="bug" name="budget" min="0" max="4294967295" value="<?echo $bgd?>" placeholder="Enter the project's budget">
        </div>
        <div class="form-group">
            <label for="est">Estimated:</label>
            <input type="number" class="form-control" id="est" name="estimate" min="0" max="4294967295" value="<?echo $cost?>" placeholder="Enter the project's estimated cost">
        </div>
        <div class="form-group">
            <label for="start">*Start Date:</label>
            <input type="date" class="form-control" id="start" name="startdate" min="2000-01-01" value="<?echo $start?>" required>
        </div>
        <div class="form-group">
            <label for="end">End Date:</label>
            <input type="date" class="form-control" id="end" name="enddate" min="2000-01-01" value="<?echo $nend?>">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>
</body>
