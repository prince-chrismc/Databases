<?php
session_start();
?>
<html>
<head>
    <title>Add New Transaction</title>
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

$proj_query = "select PROJECT.projID, projName from PROJECT";
$proj_result = $mysqli->query($proj_query);

$task_query = "SELECT taskID, taskDetails FROM TASK";
$task_result = $mysqli->query($task_query);

$mat_query = "SELECT * FROM wsc353_4.MATERIAL";
$mat_result = $mysqli->query($mat_query);

$sal_query = "select  EMPLOYEE.empID, empName, TEAM.teamName, salary from TEAM
left join TEAM_EMPLOYEE on TEAM.teamID=TEAM_EMPLOYEE.teamID
left join EMPLOYEE on TEAM_EMPLOYEE.empID=EMPLOYEE.empID
order by EMPLOYEE.empID";
$sal_result = $mysqli->query($sal_query);

if(isset($_POST['submit'])) {
    require_once './db_login.php';

    $projid = $_POST['projid'];
	$transtype = $_POST['transtype'];
	$typeid = $_POST['typeid'];
	$transcost = $_POST['transcost'];
	$transbalance = $_POST['transbalance'];
    $startdate = (strlen($_POST['startdate']) == 0 ? "NULL" : "'".$_POST['startdate']."'");
    #preg_match('/^[0-9]+/', $_POST['phaseid'],$matches);

    $sql2 = "INSERT INTO TRANSACTION(projID,transType,typeID,transCost,transBalanceDue,transDate)VALUES".
        "($phaseid,'$details',$cost,$estimate,$startdate,$enddate)";

    if ($mysqli->query($sql2) === TRUE)
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

    <h2>Log New Transaction</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="projID">Project:</label>
            <select class="form-control" id="projID" name="projid">
                <option></option>
                <?php
                if ($proj_result->num_rows > 0) {
                    while ($proj_row = $proj_result->fetch_assoc()) {
                        echo "<option>" . $proj_row['projID'] . " - " . $proj_row['projName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="type">Project:</label>
            <label class="radio-inline"><input type="radio" value="1" name="transtype" checked="checked">Task</label>
            <label class="radio-inline"><input type="radio" value="2" name="transtype">Material</label>
            <label class="radio-inline"><input type="radio" value="3" name="transtype">Salary</label>
        </div>
        <div id="task" class="form-group">
            <label for="taskID">Task:</label>
            <select class="form-control" id="taskID" name="taskid">
                <option></option>
                <?php
                if ($task_result->num_rows > 0) {
                    while ($task_row = $task_result->fetch_assoc()) {
                        echo "<option>" . $task_row['taskID'] . " - " . $task_row['taskDetails'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div id="material" class="form-group" style="display: none">
            <label for="materialID">Material:</label>
            <select class="form-control" id="materialID">
                <option></option>
                <?php
                if ($mat_result->num_rows > 0) {
                    while ($mat_row = $mat_result->fetch_assoc()) {
                        echo "<option>" . $mat_row['matID'] . " - " . $mat_row['matName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div id="salary" class="form-group" style="display: none">
            <label for="empID">Salary:</label>
            <select class="form-control" id="empID">
                <option></option>
                <?php
                if ($sal_result->num_rows > 0) {
                    while ($sal_row = $sal_result->fetch_assoc()) {
                        echo "<option>" . $sal_row['empID'] . " - " . $sal_row['empName'] . ": " . $sal_row['teamName'] . " earning $" . $sal_row['salary'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="tot">Total:</label>
            <input type="number" class="form-control" id="tot" placeholder="Enter the total cost">
        </div>
        <div class="form-group">
            <label for="due">Due:</label>
            <input type="number" class="form-control" id="due" name="due" placeholder="Enter the balance due">
        </div>
        <div class="form-group">
            <label for="start">Transaction Date:</label>
            <input type="date" class="form-control" id="start" name ="transdate" min="2000-01-01">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>

<?php
$mysqli->close();
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=transtype]').on('change', function(){
            var n = $(this).val();
            switch(n)
            {
                case '1':
                    $('#task').show();
                    $('#material').hide();
                    $('#salary').hide();
                    break;
                case '2':
                    $('#task').hide();
                    $('#material').show();
                    $('#salary').hide();
                    break;
                case '3':
                    $('#task').hide();
                    $('#material').hide();
                    $('#salary').show();
                    break;
            }
        });
    });
</script>

</body>

