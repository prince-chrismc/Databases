<!-- if the session variable has not been set yet, we show the form. 
if a session exists, the user is logged in so he doesnt have to see the form -->
<?php
if (!isset($_SESSION['empid']) && !isset($_SESSION['custid'])) echo "
            <form class='loginform' action='verify_emp.php' method='POST'>
                <h2>Employee Login</h2>
                <div class='form-group'>
                    <label>Account ID</label>
                    <input class='form-control' type='text' name='empid' required>
                </div>
                <div class='form-group'>
                <label>Password</label>
                <input class='form-control' type='password' name='emppwd' required>
                </div>
                <button type='submit'>Login</button>
            </form>
            <hr>
            <form class='loginform' action='verify_cust.php' method='POST'>
                <h2>Customer Login</h2>
                <div class='form-group'>
                    <label>Account ID</label>
                    <input class='form-control' name='custid' required>
                </div>
                <div class='form-group'>
                    <label>Password</label>
                    <input class='form-control' type='password' name='custpwd' required>
                </div>
                <button type='submit'>Login</button>
            </form>
            <hr>";
?>

