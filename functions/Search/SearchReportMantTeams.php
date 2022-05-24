<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$ware = $mysqli->real_escape_string($_GET['teams']);

$cons = new mtto();

$sr = $cons->SearchReportTeamsMant($ware);

echo $sr;