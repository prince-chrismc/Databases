<?php

/**this file provides the database connection*/

$db_hostname = "wsc353_4.encs.concordia.ca";
$db_database = "wsc353_4";
$db_username = "wsc353_4";
$db_password = "Potat017";

$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($mysqli->connect_error)
    die("Failed to connect ot MySQL: " . $mysqli->connect_error);