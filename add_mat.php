<?php
session_start();
?>
<html>
<head>
    <title>Add Material</title>
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

$pha_query = "SELECT phaseID, phaseName FROM PHASE";
$pha_result = $mysqli->query($pha_query);

if(isset($_POST['submit'])) {
    require_once './db_login.php';

    $matname = $_POST['matname'];

    $sql = "INSERT INTO MATERIAL(matName) VALUES".
        "('$matname')";

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

    <h2>Log New Material</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="matname" placeholder="Enter the material's name">
        </div>
        <div class="form-group">
            <label for="supID">Supplier:</label>
            <select class="form-control" id="supID" name="supid">
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
            <input type="number" class="form-control" id="cost" step="5" name="cost" placeholder="Enter the cost">
        </div>
        <div class="form-group">
            <label for="time">Delivery Time:</label>
            <input type="number" class="form-control" id="time" name="time" placeholder="Enter the delivery time">
        </div>
        <div class="form-group">
            <label for="supID">Phase:</label>
            <select class="form-control" id="supID" name="supid">
                <option></option>
                <?php
                if ($pha_result->num_rows > 0) {
                    while ($pa_row = $pha_result->fetch_assoc()) {
                        echo "<option>" . $pa_row['phaseID'] . " - " . $pa_row['phaseName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <div class="form-group">
            <label for="qty">Quantity:</label>
            <input type="number" class="form-control" id="qty" step="5" name="qty" placeholder="Enter the quantity">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>
</body>
