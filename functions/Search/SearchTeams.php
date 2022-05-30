<?php


include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$ware = $mysqli->real_escape_string($_GET['warehouse']);
$teams = $cons->SearchTeams($ware);

echo $teams;
