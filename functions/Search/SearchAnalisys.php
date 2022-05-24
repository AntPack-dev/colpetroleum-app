<?php
include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$warehouse = $mysqli->real_escape_string($_POST['ware']);

$analysis = $cons->SearchAnalisysLast($warehouse);

echo $analysis;