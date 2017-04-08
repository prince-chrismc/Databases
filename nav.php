<?php
if(isset($_SESSION['empid']))
{
    echo "<p><a href='emp_index.php'> Home</a></p>
            <p><a href='proj_list.php'> Project List</a></p>
            <p><a href='cust_list.php'> Customer List</a></p>
            <p><a href='emp_list.php'> Employee List</a></p>
            <p><a href='mat_list.php'> Material List</a></p>
            <p><a href='sup_list.php'> Supplier List</a></p>
            <p><a href='team_list.php'> Team List</a></p>
            <p><a href='phase_list.php'> Phase List</a></p>
            <p><a href='task_list.php'> Task List</a></p>
            <p><a href='trans_list.php'> Transaction List</a></p>
            <p><a href='logout.php'> Log out </a></li>";
}
else if(isset($_SESSION['custid']))
{
    echo "<p><a href='cust_index.php'> Home</a></p>
            <p><a href='logout.php'> Log out </a></p>";
}
else
{
    echo "<li><a href='index.php'> Home</a></li>";
}
?>