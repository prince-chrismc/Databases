<?php
session_start();
?>
<html>
<head>
    <title>Removal Pagee</title>
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


if(isset($_POST['submit']))
{
    if(isset($_POST['_emp_team']))
    {
        if(!empty($_POST['emp_team']))
        {
            preg_match_all('!\d+!', $_POST['emp_team'],$matching);
            //print_r($matching);
            $empID = $matching[0][0];
            $teamID = $matching[0][1];
            $query = "DELETE FROM TEAM_EMPLOYEE WHERE empID=$empID AND teamID=$teamID";

            if($mysqli->query($query))
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
    }
    else if(isset($_POST['_team_proj']))
    {
        if(!empty($_POST['team_proj']))
        {
            preg_match_all('!\d+!', $_POST['team_proj'],$matching);
            //print_r($matching);
            $projID = $matching[0][0];
            $teamID = $matching[0][1];
            $query = "DELETE FROM PROJECT_TEAM WHERE projID=$projID AND teamID=$teamID";

            if($mysqli->query($query))
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
    }
    else if(isset($_POST['_mat_sup']))
    {
        if(!empty($_POST['mat_sup']))
        {
            preg_match_all('!\d+!', $_POST['mat_sup'],$matching);
            //print_r($matching);
            $supID = $matching[0][0];
            $matID = $matching[0][1];
            $query = "DELETE FROM MATERIAL_SUPPLIER WHERE supID=$supID AND matID=$matID";

            if($mysqli->query($query))
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
    }
    else if(isset($_POST['_mat_phase']))
    {
        if(!empty($_POST['mat_phase']))
        {
            preg_match_all('!\d+!', $_POST['mat_phase'],$matching);
            //print_r($matching);
            $phaseID = $matching[0][0];
            $matID = $matching[0][1];
            $query = "DELETE FROM PHASE_MATERIAL WHERE phaseID=$phaseID AND matID=$matID";

            if($mysqli->query($query))
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
    }
}
?>

<div class="container">
    <form action="emp_index.php">
        <button type="submit" class="btn btn-default">Back</button>
    </form>

    <h2>Delete</h2>
    <form>
        <div class="form-group">
            <label for="type">Remove:</label>
            <label class="radio-inline"><input type="radio" value="1" name="type" checked="checked">Employ from a Team</label>
            <label class="radio-inline"><input type="radio" value="2" name="type">Team from a Project</label>
            <label class="radio-inline"><input type="radio" value="3" name="type">Material of a Supplier</label>
            <label class="radio-inline"><input type="radio" value="4" name="type">Material from a Phase</label>
        </div>
    </form>


    <form id="emp_team" method='POST'>
        <input name="_emp_team" value="1" type="hidden"/>

        <div class="form-group">
            <label for="emp_team">Entry:</label>
            <select class="form-control" id="empteam" name="emp_team" required>
                <option></option>
                <?php
                $emp_team_query = "SELECT TEAM_EMPLOYEE.empID, empName, TEAM_EMPLOYEE.teamID, teamName
                    FROM TEAM_EMPLOYEE natural left join EMPLOYEE natural left join TEAM";
                $emp_team_result = $mysqli->query($emp_team_query);
                if ($emp_team_result->num_rows > 0) {
                    while ($emp_team_row = $emp_team_result->fetch_assoc()) {
                        $empID = $emp_team_row['empID'];
                        $teamID = $emp_team_row['teamID'];
                        echo "<option value='$empID,$teamID'>" . $emp_team_row['empName'] . " on " . $emp_team_row['teamName'] . "</option>";
                    }
                }?>
            </select>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Delete</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>

    <form id="team_proj" method='POST' style="display: none">
        <input name="_team_proj" value="1" type="hidden"/>
        <div class="form-group">
            <label for="team_proj">Entry:</label>
            <select class="form-control" id="teamproj" name="team_proj">
                <option></option>
                <?php
                $proj_team_query = "SELECT PROJECT_TEAM.teamID, TEAM.teamName, PROJECT_TEAM.projID, projName
                    FROM PROJECT_TEAM natural left join PROJECT natural left join TEAM";
                $proj_team_result = $mysqli->query($proj_team_query);
                if ($proj_team_result->num_rows > 0) {
                    while ($proj_team_row = $proj_team_result->fetch_assoc()) {
                        $projID = $proj_team_row['projID'];
                        $teamID = $proj_team_row['teamID'];
                        echo "<option value='$projID,$teamID'>" . $proj_team_row['teamName'] . " working on " . $proj_team_row['projName']. "</option>";
                    }
                }?>
            </select>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Delete</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>

    <form id="mat_sup" method='POST' style="display: none">
        <input name="_mat_sup" value="1" type="hidden"/>
        <div class="form-group">
            <label for="mat_sup">Entry:</label>
            <select class="form-control" id="matsup" name="mat_sup">
                <option></option>
                <?php
                $mat_sup_query = "select MATERIAL_SUPPLIER.supID, SUPPLIER.supName, MATERIAL_SUPPLIER.matID, MATERIAL.matName, 
                    MATERIAL_SUPPLIER.matCost from MATERIAL_SUPPLIER natural left join MATERIAL natural left join SUPPLIER";
                $mat_sup_result = $mysqli->query($mat_sup_query);
                if ($mat_sup_result->num_rows > 0) {
                    while ($mat_sup_row = $mat_sup_result->fetch_assoc()) {
                        $supID = $mat_sup_row['supID'];
                        $matID = $mat_sup_row['matID'];
                        echo "<option value='$supID,$matID'>" . $mat_sup_row['matName'] . " from " . $mat_sup_row['supName'] . " at a cost of $" . $mat_sup_row['matCost'] ."</option>";
                    }
                }?>
            </select>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Delete</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>

    <form id="mat_phase" method='POST' style="display: none">
        <input name="_mat_phase" value="1" type="hidden"/>
        <div class="form-group">
            <label for="mat_phase">Entry:</label>
            <select class="form-control" id="matphase" name="mat_phase">
                <option></option>
                <?php
                $mat_phase_query = "SELECT projName, phaseID, phaseName, matID, matName, qty
                    FROM PHASE_MATERIAL natural left join PHASE natural left join MATERIAL natural left join PROJECT";
                $mat_phase_result = $mysqli->query($mat_phase_query);
                if ($mat_phase_result->num_rows > 0) {
                    while ($mat_phase_row = $mat_phase_result->fetch_assoc()) {
                        $phaseID = $mat_phase_row['phaseID'];
                        $matID = $mat_phase_row['matID'];
                        echo "<option value='$phaseID,$matID'>" . $mat_phase_row['projName'] . ": " . $mat_phase_row['phaseName'] .
                            " having " . $mat_phase_row['qty'] . " " . $mat_phase_row['matName']."</option>";
                    }
                }?>
            </select>
        </div>
        <button type="submit" class="btn btn-default" name="submit">Delete</button>
        <button type="reset" class="btn btn-warning" onclick="clearForm()">Reset</button>
    </form>

</div>

<?php
$mysqli->close();
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=type]').on('change', function(){
            var n = $(this).val();
            switch(n)
            {
                case '1':
                    $('#emp_team').show();
                    $('#team_proj').hide();
                    $('#mat_sup').hide();
                    $('#mat_phase').hide();
                    break;
                case '2':
                    $('#emp_team').hide();
                    $('#team_proj').show();
                    $('#mat_sup').hide();
                    $('#mat_phase').hide();
                    break;
                case '3':
                    $('#emp_team').hide();
                    $('#team_proj').hide();
                    $('#mat_sup').show();
                    $('#mat_phase').hide();
                    break;
                case '4':
                    $('#emp_team').hide();
                    $('#team_proj').hide();
                    $('#mat_sup').hide();
                    $('#mat_phase').show();
                    break;
            }
        });
    });
</script>
    </body>