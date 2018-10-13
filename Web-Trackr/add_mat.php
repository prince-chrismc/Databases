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
    $time = $_POST['time'];
    $cost = floatval($_POST['cost']);
    $qty = $_POST['qty'];

    preg_match('/^[0-9]+/', $_POST['supid'],$matches);
    $supid = $matches[0];

    preg_match('/^[0-9]+/', $_POST['phaseid'],$phasematches);
    $phaseid = $phasematches[0];

    $sql = "INSERT INTO MATERIAL(matName) VALUES".
        "('$matname')";

    if ($mysqli->query($sql) === TRUE)
    {
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
        $newID = $mysqli->insert_id;

        if(!empty($supid) && !empty($cost) && !empty($time))
        {
            $insert = "INSERT INTO MATERIAL_SUPPLIER VALUES".
                "($newID,$supid,$cost,$time)";
            if($mysqli->query($insert))
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
        else{
            echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-info alert-dismissable\">
                            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                            <strong>Info!</strong> Indicates a neutral informative change or action. Missing information such as a supplier and/or a cost.
                          </div></div>";
        }

        if(!empty($phaseid) && !empty(qty))
        {
            $insert = "INSERT INTO PHASE_MATERIAL VALUES".
                "($phaseid,$newID,$qty)";
            if($mysqli->query($insert))
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
        else{
            echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-info alert-dismissable\">
                            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                            <strong>Info!</strong> Indicates a neutral informative change or action. Missing information such as a phase and/or a quantity.
                          </div></div>";
        }

    }

    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information.  ". mysqli_error($mysqli) ."
              </div></div>";

    $mysqli->close();
}
?>

<div id='restricted' class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Log New Material</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="name">*Name:</label>
            <input type="text" class="form-control" id="name" name="matname" placeholder="Enter the material's name" required>
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
            <input type="number" class="form-control" id="cost" name="cost" min="0" max="4294967295" placeholder="Enter the cost">
        </div>
        <div class="form-group">
            <label for="time">Delivery Time:</label>
            <input type="number" class="form-control" id="time" name="time" min="0" max="4294967295" placeholder="Enter the delivery time">
        </div>
        <div class="form-group">
            <label for="supID">Phase:</label>
            <select class="form-control" id="supID" name="phaseid">
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
            <input type="number" class="form-control" id="qty" min="1" name="qty" max="4294967295" placeholder="Enter the quantity">
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
