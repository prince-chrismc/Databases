<?php
session_start();
?>
<html>
<head>
    <title>Update Phase</title>
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

if(!isset($_GET['phaseID']) || empty($_GET['phaseID']))
    echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-danger alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. There is not customer selected to update!
              </div></div>";

$phaseID = $_GET['phaseID'];


if(isset($_POST['submit']))
{
    $new_phase = $_POST['projID'];
    $new_name = $_POST['name'];
    $new_dtl = $_POST['details'];
    $new_str = $_POST['startdate'];
    $new_end = $_POST['enddate'];

    $update = "UPDATE PHASE SET projID='$new_phase', phaseName='$new_name', phaseDetails='$new_dtl', phaseDateStart='$new_str', phaseDateEnd='$new_end' WHERE phaseID=$phaseID";
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


$sql = "select * from PHASE where phaseID=$phaseID";
$result = $mysqli->query($sql);
$phase = $result->fetch_assoc();

$proj = $phase['projID'];
$name = $phase['phaseName'];
$dtl = $phase['phaseDetails'];
$str = $phase['phaseDateStart'];
$end = $phase['phaseDateEnd'];
?>

<div class="container">
    <form action="<? if(isset($_SESSION['custid'])) echo 'cust_index.php'; else echo 'phase_list.php';?>">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Registar New Customer</h2>
    <form id="myform" method='POST'>
        <div id="projform" class="form-group">
            <label for="projID">*Project:</label>
            <select class="form-control" id="projID" name="projID" required>
                <option></option>
                <?php
                $sql = "select PROJECT.projID, projName from PROJECT";
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $projID = $row['projID'];
                        echo "<option value='$projID'>" . $projID . " - " . $row['projName'] . "</option>";
                    }
                }?>
            </select>
            <script type="text/javascript">
                <? if(isset($_SESSION['custid'])) echo "$('#projform').hide();";?>
                $('#projID').val('<?echo $proj;?>');
            </script>
        </div>
        <div class="form-group">
            <label for="name">*Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?echo $name?>" placeholder="Enter the phase name" required>
        </div>
        <div class="form-group">
            <label for="dtl">Details:</label>
            <input type="text" class="form-control" id="dtl" name="details" value="<?echo $dtl?>"placeholder="Enter the phase details">
        </div>
        <div class="form-group">
            <label for="start">Start Date:</label>
            <input type="date" class="form-control" id="start" name="startdate" min="2000-01-01" value="<?echo $str?>">
        </div>
        <div class="form-group">
            <label for="end">End Date:</label>
            <input type="date" class="form-control" id="end" name="enddate" min="2000-01-01" value="<?echo $end?>">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>
</div>
</body>
