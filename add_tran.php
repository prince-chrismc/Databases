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

$proj_query = isset($_SESSION['custid']) ? "SELECT a.projID, a.projName FROM (SELECT * FROM PROJECT WHERE custID=$_SESSION[custid]) as a" : "select PROJECT.projID, projName from PROJECT";
$proj_result = $mysqli->query($proj_query);

$task_query = "SELECT taskID, taskDetails FROM TASK";
$task_result = $mysqli->query($task_query);

$mat_query = "SELECT * FROM wsc353_4.MATERIAL";
$mat_result = $mysqli->query($mat_query);

$sup_query = "SELECT supID, supName FROM SUPPLIER";
$sup_result = $mysqli->query($sup_query);

$sal_query = "select  EMPLOYEE.empID, empName, TEAM.teamID, TEAM.teamName, salary from TEAM
left join TEAM_EMPLOYEE on TEAM.teamID=TEAM_EMPLOYEE.teamID
left join EMPLOYEE on TEAM_EMPLOYEE.empID=EMPLOYEE.empID
order by EMPLOYEE.empID";
$sal_result = $mysqli->query($sal_query);

if(isset($_POST['submit'])) {

    preg_match('/^[0-9]+/', $_POST['projID'],$matches);
    $projID = $matches[0];
    $transdate = (strlen($_POST['transdate']) == 0 ? "NULL" : "'".$_POST['transdate']."'");
    #preg_match('/^[0-9]+/', $_POST['phaseid'],$matches);

    if(isset($_POST['_task']))
    {
        $cost = floatval($_POST['cost']);
        preg_match('/^[0-9]+/', $_POST['taskID'],$matches);
        $taskID = $matches[0];

        $sql2 = "insert into TRANSACTION_TASK(projID,taskID,transCost,transDate) value($projID, $taskID, $cost, $transdate)";
    }
    else if(isset($_POST['_material']))
    {
        $cost = floatval($_POST['cost']);
        preg_match('/^[0-9]+/', $_POST['matID'],$matches);
        $matID = $matches[0];
        preg_match('/^[0-9]+/', $_POST['supID'],$matches);
        $supID = $matches[0];

        $sql2 = "insert into TRANSACTION_MATERIAL(projID,matID,supID, transCost,transDate) value($projID, $matID, $supID, $cost, $transdate)";
    }
    else if(isset($_POST['_salary']))
    {
        preg_match_all('!\d+!', $_POST['salID'],$matching);
        $empID = $matching[0][0];
        $teamID = $matching[0][1];
        $cost = $matching[0][2];

        $sql2 = "insert into TRANSACTION_SALARY(projID,empID,teamID, transCost,transDate) value($projID, $empID, $teamID, $cost, $transdate)";

    }

    if ($mysqli->query($sql2) === TRUE)
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
?>

<div class="container">
    <form action="<? if(isset($_SESSION['custid'])) echo 'cust_index.php'; else echo 'emp_index.php';?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Log New Transaction</h2>
    <form>
        <div class="form-group">
            <label for="type">Transaction Type:</label>
            <label class="radio-inline"><input type="radio" value="1" name="transtype" checked="checked">Task</label>
            <label class="radio-inline"><input type="radio" value="2" name="transtype">Material</label>
            <label class="radio-inline"><input type="radio" value="3" name="transtype">Salary</label>
        </div>
    </form>

    <!-- ================================================ Task ================================================ -->
    <form id="task" method='POST'>
        <input name="_task" value="1" type="hidden"/>
        <div class="form-group">
            <label for="projID">*Project:</label>
            <select class="form-control" id="projID" name="projID" required>
                <option></option>
                <?php
                $proj_result = $mysqli->query($proj_query);
                if ($proj_result->num_rows > 0) {
                    while ($proj_row = $proj_result->fetch_assoc()) {
                        echo "<option>" . $proj_row['projID'] . " - " . $proj_row['projName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div id="task" class="form-group">
            <label for="taskID">*Task:</label>
            <select class="form-control" id="taskID" name="taskID" required>
                <option></option>
                <?php
                if ($task_result->num_rows > 0) {
                    while ($task_row = $task_result->fetch_assoc()) {
                        echo "<option>" . $task_row['taskID'] . " - " . $task_row['taskDetails'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="cost">*Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" min="0" max="4294967295" placeholder="Enter the total cost" required>
        </div>
        <div class="form-group">
            <label for="start">*Transaction Date:</label>
            <input type="date" class="form-control" id="start" name ="transdate" min="2000-01-01" required>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>

    <!-- ================================================ Material ================================================ -->
    <form id="material" method='POST'  style="display: none">
        <input name="_material" value="1" type="hidden"/>
        <div class="form-group">
            <label for="projID">*Project:</label>
            <select class="form-control" id="projID" name="projID" required>
                <option></option>
                <?php
                $proj_result = $mysqli->query($proj_query);
                if ($proj_result->num_rows > 0) {
                    while ($proj_row = $proj_result->fetch_assoc()) {
                        echo "<option>" . $proj_row['projID'] . " - " . $proj_row['projName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div id="material" class="form-group" >
            <label for="matID">*Material:</label>
            <select class="form-control" id="matID" name="matID" required>
                <option></option>
                <?php
                if ($mat_result->num_rows > 0) {
                    while ($mat_row = $mat_result->fetch_assoc()) {
                        echo "<option>" . $mat_row['matID'] . " - " . $mat_row['matName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="supID">*Supplier:</label>
            <select class="form-control" id="supID" name="supID" required>
                <option></option>
                <?php
                if ($sup_result->num_rows > 0) {
                    while ($sup_row = $sup_result->fetch_assoc()) {
                        echo "<option>" . $sup_row['supID'] . " - " . $sup_row['supName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="cost">*Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" min="0" max="4294967295"placeholder="Enter the total cost" required>
        </div>
        <div class="form-group">
            <label for="start">*Transaction Date:</label>
            <input type="date" class="form-control" id="start" name ="transdate" min="2000-01-01" required>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>

    <!-- ================================================ Salary ================================================ -->
    <form id="salary" method='POST' style="display: none">
        <input name="_salary" value="1" type="hidden"/>
        <div class="form-group">
            <label for="projID">*Project:</label>
            <select class="form-control" id="projID" name="projID" required>
                <option></option>
                <?php
                $proj_result = $mysqli->query($proj_query);
                if ($proj_result->num_rows > 0) {
                    while ($proj_row = $proj_result->fetch_assoc()) {
                        echo "<option>" . $proj_row['projID'] . " - " . $proj_row['projName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div id="salary" class="form-group">
            <label for="salID">*Salary:</label>
            <select class="form-control" id="salID" name="salID" required>
                <option></option>
                <?php
                if ($sal_result->num_rows > 0) {
                    while ($sal_row = $sal_result->fetch_assoc()) {
                        echo "<option>" . $sal_row['empID'] . " - " . $sal_row['empName'] . "on " . $sal_row['teamID']. " : ". $sal_row['teamName'] . " earning $ " . $sal_row['salary'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="start">*Transaction Date:</label>
            <input type="date" class="form-control" id="start" name ="transdate" min="2000-01-01" required>
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

