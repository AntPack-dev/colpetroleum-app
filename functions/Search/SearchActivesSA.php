<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

$ware = $mysqli->real_escape_string($_GET['warehouse']);

echo $mtto->SearchActiveSA($ware);