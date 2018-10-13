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

if(!isset($_GET['projID']) || empty($_GET['projID']))
    echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-danger alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. There is not customer selected to update!
              </div></div>";

$projID = $_GET['projID'];
$sql = "select * from PROJECT where projID=$projID";
$result = $mysqli->query($sql);
$proj = $result->fetch_assoc();
$bgd = $proj['budgetAmount'];

if(isset($_POST['submit']))
{
    $new_cust = $_POST['custID'];
    $new_name = $_POST['name'];
    $new_dtl = $_POST['details'];
    $new_bgt = ($_POST['budget'] + $bgd);
    $new_est = $_POST['estimate'];
    $new_str = (strlen($_POST['startdate']) == 0 ? "NULL" : "'".$_POST['startdate']."'");
    $new_end = (strlen($_POST['enddate']) == 0 ? "NULL" : "'".$_POST['enddate']."'");

    $update = "UPDATE PROJECT SET custID='$new_cust', projName = \"$new_name\", projDetails= '$new_dtl',budgetAmount = $new_bgt, estimatedCost = $new_est, projDateStart = $new_str,
                  projDateEnd = $new_end WHERE projID = $projID";
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

$sql = "select * from PROJECT where projID=$projID";
$result = $mysqli->query($sql);
$proj = $result->fetch_assoc();
$bgd = $proj['budgetAmount'];
$cust = $proj['custID'];
$name = $proj['projName'];
$dtl = $proj['projDetails'];
$cost = $proj['estimatedCost'];
$start = $proj['projDateStart'];
$nend = $proj['projDateEnd'];
?>

<div class="container">
    <form action="<? if(isset($_GET['custID'])) echo "cust_index.php"; else echo"proj_list.php";?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Update Project</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="name">*Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?echo $name?>" placeholder="Enter the project name" required>
        </div>
        <div class="form-group">
            <label for="dtl">Details:</label>
            <input type="text" class="form-control" id="dtl" name="details" value="<?echo $dtl?>" placeholder="Enter project details">
        </div>
        <div id='custform' class="form-group">
            <label for="custID">Customer:</label>
            <select class="form-control" id="custID" name="custID">
                <option></option>
                <?php
                $selectsql = "select * from CUSTOMER";
                $customer = $mysqli->query($selectsql);
                if ($customer->num_rows > 0) {
                    while ($row = $customer->fetch_assoc()) {
                        $custID = $row['custID'];
                        echo "<option value='$custID'>" . $custID . " - " . $row['custName'] . "</option>";
                    }
                }?>
            </select>
            <script type="text/javascript">
                <? if(isset($_GET['custID'])) echo "$('#custform').hide();"?>
                $('#custID').val('<?echo $cust;?>');
            </script>
        </div>
        <div class="form-group">
            <label for="bug">Increase Budget: current value is <?echo $bgd?></label>
            <input type="number" class="form-control" id="bug" name="budget" min="0" max="4294967295"placeholder="Enter the amount to increase budget by">
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
