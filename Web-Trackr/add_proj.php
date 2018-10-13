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

$selectsql = "select * from CUSTOMER";
$customer = $mysqli->query($selectsql);

if(isset($_POST['submit'])) {

    $name = $_POST['name'];
    $details = $_POST['details'];
    $budget = floatval((empty($_POST['budget'])) == true ? 0 : $_POST['budget']);
    $estimate = floatval((empty($_POST['estimate'])) == true ? 0 : $_POST['estimate']);
    $startdate = (strlen($_POST['startdate']) == 0 ? "NULL" : "'".$_POST['startdate']."'");
    $enddate = (strlen($_POST['enddate']) == 0 ? "NULL" : "'".$_POST['enddate']."'");

    preg_match('/^[0-9]+/', $_POST['custID'],$matches);
    $custID = $matches[0];

    $sql = "INSERT INTO PROJECT(custID,projName,projDetails,budgetAmount,estimatedCost,projDateStart,projDateEnd) VALUES".
        "('$custID','$name','$details','$budget','$estimate',$startdate,$enddate)";

    if ($mysqli->query($sql))
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information.  ". mysqli_error($mysqli) ."
              </div></div>";

    $mysqli->close();
}
?>

<div class="container">
    <form action="<? if(isset($_SESSION['custid'])) echo 'cust_index.php'; else echo 'emp_index.php';?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

        <h2>Create New Project</h2>
        <form id="myform" method='POST'>
            <div class="form-group">
                <label for="name">*Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the project name" required>
            </div>
            <div class="form-group">
                <label for="dtl">Details:</label>
                <input type="text" class="form-control" id="dtl" name="details" placeholder="Enter project details">
            </div>
            <div class="form-group">
                <label for="bug">Budget:</label>
                <input type="number" class="form-control" id="bug" name="budget" min="0" max="4294967295" placeholder="Enter the project's budget">
            </div>
            <div class="form-group">
                <label for="est">Estimated:</label>
                <input type="number" class="form-control" id="est" name="estimate" min="0" max="4294967295" placeholder="Enter the project's estimated cost">
            </div>
            <div id="custform" class="form-group">
                <label for="custID">*Customer:</label>
                <select class="form-control" id="custID" name="custID" required>
                    <option></option>
                    <?php
                    if ($customer->num_rows > 0) {
                        while ($row = $customer->fetch_assoc()) {
                            echo "<option value='$row[custID]'>" . $row['custID'] . " - " . $row['custName'] . "</option>";
                        }
                    }?>
                </select>
                <script type="text/javascript">
                    <? if(isset($_SESSION['custid'])) echo "$('#custform').hide();";?>
                    $('#custID').val('<?echo $_SESSION['custid'];?>');
                </script>
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
