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

$sup_query = "SELECT supID, supName FROM SUPPLIER";
$sup_result = $mysqli->query($sup_query);

$sql = "select matID, matName from MATERIAL";
$mat_result = $mysqli->query($sql);


if(isset($_POST['submit'])) {
    $time = $_POST['time'];
    $cost = $_POST['cost'];
    preg_match('/^[0-9]+/', $_POST['matID'],$matches);
    $matID = $matches[0];
    preg_match('/^[0-9]+/', $_POST['supID'],$matches2);
    $supID = $matches2[0];

    $insert = "INSERT INTO MATERIAL_SUPPLIER VALUES($matID,$supID,$cost,$time)";

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

    <h2>Register a Supplier to a Material</h2>
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
            <label for="cost">Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" min="0" max="4294967295" placeholder="Enter the cost">
        </div>
        <div class="form-group">
            <label for="time">Delivery Time:</label>
            <input type="number" class="form-control" id="time" name="time" min="0" max="4294967295" placeholder="Enter the delivery time">
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
