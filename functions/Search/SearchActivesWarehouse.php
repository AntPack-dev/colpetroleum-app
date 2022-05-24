<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$ware = $mysqli->real_escape_string($_GET['warehouse']);

$active = $cons->SearchActivesWarehouse($ware);

echo $active;



// echo $ware;
