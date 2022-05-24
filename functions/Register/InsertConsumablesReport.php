<?php

///REGISTRA EL REPORTE DE CONSUMIBLES

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();


if(isset($_POST['btnregister']))
{
    $token_consum = $mtto->GenerateTokenMtto();
    $date_register = $mtto->DateMtto();
    $initial_delivery = $mysqli->real_escape_string($_POST['initial_delivery']);
    $reposition_delivery = $mysqli->real_escape_string($_POST['reposition_delivery']);
    $description_consum = $mysqli->real_escape_string($_POST['description']);
    $site = $mysqli->real_escape_string($_POST['site']);
    $rsu = $mysqli->real_escape_string($_POST['rsu']);
    $team = $mysqli->real_escape_string($_POST['team']);
    $value_total = $mysqli->real_escape_string($_POST['value_total']);
    $contract = $mysqli->real_escape_string($_POST['contract']);
    $user_id = $mysqli->real_escape_string($_POST['user_id']);

    $reg_report = $mtto->RegisterReportConsumables($token_consum, $date_register, $initial_delivery, $reposition_delivery, $description_consum, $site, $team, $contract, $value_total, $user_id);
    $mod_report = $mtto->UpdateStateReportConsu($reg_report);
    
    if($reg_report > 0)
    {
        header("Location: ../../pages/consumables");
    }
    else
    {
        header("Location: ../../pages/consumables");
    }
}