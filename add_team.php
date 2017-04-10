<?php
session_start();
?>
<html>
<head>
    <title>Add New Team</title>
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

$sql = "select PROJECT.projID, projName from PROJECT";
$result = $mysqli->query($sql);

if(isset($_POST['submit'])) {
    require_once './db_login.php';

    $name = $_POST['name'];
    preg_match('/^[0-9]+/', $_POST['projID'],$matches);
    $projID = $matches[0];

    $sql2 = "INSERT INTO TEAM(teamname) VALUES".
        "('$name')";

    if ($mysqli->query($sql2) === TRUE)
    {
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-success alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> This alert box could indicate a successful or positive action.
              </div></div>";
        if(!empty($_POST['projID']))
        {
            $newID = $mysqli->insert_id;

            $insert = "INSERT INTO PROJECT_TEAM VALUES".
                "($projID,$newID)";

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
                            <strong>Info!</strong> Indicates a neutral informative change or action. Missing information to add a team to a project.
                          </div></div>";
        }

    }

    else
        echo "<div class=\"container\"><div id=\"myAlert\" class=\"alert alert-warning alert-dismissable\">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Danger!</strong> Indicates a warning that might need attention. You have entered incorrect information.   ". mysqli_error($mysqli) ."
              </div></div>";

    $mysqli->close();
}
?>

<div id='restricted' class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

        <h2>Register New Team</h2>
        <form id="myform" method='POST'>
            <div class="form-group">
                <label for="name">*Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the team name" required>
            </div>
            <div class="form-group">
                <label for="projID">Project:</label>
                <select class="form-control" id="projID" name="projid">
                    <option></option>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option>" . $row['projID'] . " - " . $row['projName'] . "</option>";
                        }
                    }?>
                </select>
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
