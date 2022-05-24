<?php

include('../db/ConnectDB.php');
include('../functions/OperationsUser.php');

$permitid = $mysqli->real_escape_string($_GET['permit']);
$token = $mysqli->real_escape_string($_GET['user']);


$delete = new UserFunctions();

$delete->DeletePermitUser($permitid);

header("Location:../pages/permitionsuser?user=".$token);



