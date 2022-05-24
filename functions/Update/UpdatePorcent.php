<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

if(isset($_POST['btnregistervalue']))
{
    $value_porcent = $mysqli->real_escape_string($_POST['value_porcent']);
    $frequency = $mysqli->real_escape_string($_POST['frequency']);
    $id_activies = $mysqli->real_escape_string($_POST['id_activies']);
    $indicator_tk = $mysqli->real_escape_string($_POST['indicator_tk']);
    $id_indicator = $mtto->getValueMtto('id_history_indicators', 'history_indicators', 'token_history_indicators', $indicator_tk);

    $mtto->UpdatePorcentIndicators($value_porcent, $frequency, $id_activies);
    header('Location: ../../pages/viewindicators?indicators='.$indicator_tk);

}