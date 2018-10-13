<?php
session_start();
?>
<html>
<head>
    <title>Update Team</title>
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

if(!isset($_GET['teamID']) || empty($_GET['teamID']))
    echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-danger alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. There is not customer selected to update!
              </div></div>";

$teamID = $_GET['teamID'];


if(isset($_POST['submit']))
{

    $new_name = $_POST['name'];

    $update = "UPDATE TEAM SET teamName = '$new_name' WHERE teamID = $teamID";
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

if(isset($_GET['teamID']))
{
    $sql = "select * from TEAM where teamID=$teamID";
    $result = $mysqli->query($sql);
    $mat = $result->fetch_assoc();

    $name = $mat['teamName'];
}

?>

<div id='restricted' class="container">
    <form action="team_list.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Update Team</h2>
    <form id="myform" method='POST'>
        <div class="form-group">
            <label for="name">*Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?echo $name?>" placeholder="Enter the full name" required>
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
