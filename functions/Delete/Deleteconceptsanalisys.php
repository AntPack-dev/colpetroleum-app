<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

$table = $mysqli->real_escape_string($_GET['tab']);
$token = $mysqli->real_escape_string($_GET['tk']);
$condition = $mysqli->real_escape_string($_GET['cond']);
$tkw = $mysqli->real_escape_string($_GET['tkw']);

$tokenw = $mtto->getValueMtto('token_warehouse', 'warehouse', 'id_warehouse', $tkw);

$deleteconcept = $mtto->DeleteConcept($table, $condition, $token);

if($deleteconcept >= 0)
{
    //Se va a poner Headers y evitar el uso de avisos
    header("Location: ../../pages/analysis?warehouse=$tokenw");
    
}
else
{
    //Se va a poner Headers y evitar el uso de avisos
    header("Location: ../../pages/analisys?warehouse=$tokenw");
}
