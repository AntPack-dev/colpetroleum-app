<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

$id_value = $mysqli->real_escape_string($_GET['idvalue']);
$month = $mysqli->real_escape_string($_GET['month']);
$tkschedule = $mysqli->real_escape_string($_GET['tkschedule']);


$mtto->UpdateValueInd($month, $id_value);

header("Location: ../../pages/viewschedule?schedule=".$tkschedule);