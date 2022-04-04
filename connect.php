<?php
require_once("data.php");

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    die (mysqli_connect_error());
}
?>