<?php

include('../db/ConnectDB.php');
include('../functions/FunctionsMtto.php');

$active = $mysqli->real_escape_string($_GET['active']);

$mtto = new mtto();

$delete = $mtto->DeleteActive($active);

header("Location:../pages/concepts");