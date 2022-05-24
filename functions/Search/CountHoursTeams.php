<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$year = $mysqli->real_escape_string($_POST['year']);

$countmant = $cons->CountHoursTeams($year);

echo $countmant;