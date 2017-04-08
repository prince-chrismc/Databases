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

    $sql = "INSERT INTO TEAM(teamname) VALUES".
        "('$name')";

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

        <h2>Registar New Team</h2>
        <form id="myform" method='POST'>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the team name">
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
</body>
