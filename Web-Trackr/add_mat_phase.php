<?php
session_start();
?>
<html>
<head>
    <title>Add Supplier to a Material</title>
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

$sql = "select phaseID, phaseName from PHASE";
$result = $mysqli->query($sql);

$sql = "select matID, matName from MATERIAL";
$mat_result = $mysqli->query($sql);


if(isset($_POST['submit'])) {
    $qty = $_POST['qty'];
    preg_match('/^[0-9]+/', $_POST['matID'],$matches);
    $matID = $matches[0];
    preg_match('/^[0-9]+/', $_POST['phaseID'],$matches2);
    $phaseID = $matches2[0];

    $insert = "INSERT INTO PHASE_MATERIAL VALUES($phaseID,$matID,$qty)";

    if ($mysqli->query($insert))
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

    $mysqli->close();
}
?>

<div id='restricted' class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Register a Material to a Phase</h2>
    <form id="myform"  method='POST'>
        <div class="form-group">
            <label for="supID">*Material:</label>
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
            <label for="phaseID">*Phase:</label>
            <select class="form-control" id="phaseID" name="phaseID" required>
                <option></option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option>" . $row['phaseID'] . " - " . $row['phaseName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="cost">*Quantity:</label>
            <input type="number" class="form-control" id="qty"  name="qty" min="1" max="4294967295" placeholder="Enter the quantity" required>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
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
</body>
