<?php

//REALIZA LA ACTUALZIACIÓN DE VALORES CUANTITATIVOS DE LOS INDICADORES

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

if(isset($_POST['btnaddquantity']))
{
    //VALORES DE LOS INDICADORES
    $id_month_indicator = $mysqli->real_escape_string($_POST['id_indicator_month']);
    $tk_indicator = $mysqli->real_escape_string($_POST['indicator_tk']);

    //VALORES DE LOS MESES DE LOS INDICADORES
    $v_ene = $mysqli->real_escape_string($_POST['v_ene']);
    $v_feb = $mysqli->real_escape_string($_POST['v_feb']);
    $v_mar = $mysqli->real_escape_string($_POST['v_mar']);
    $v_abr = $mysqli->real_escape_string($_POST['v_abr']);
    $v_may = $mysqli->real_escape_string($_POST['v_may']);
    $v_jun = $mysqli->real_escape_string($_POST['v_jun']);
    $v_jul = $mysqli->real_escape_string($_POST['v_jul']);
    $v_ago = $mysqli->real_escape_string($_POST['v_ago']);
    $v_sep = $mysqli->real_escape_string($_POST['v_sep']);
    $v_oct = $mysqli->real_escape_string($_POST['v_oct']);
    $v_nov = $mysqli->real_escape_string($_POST['v_nov']);
    $v_dic = $mysqli->real_escape_string($_POST['v_dic']);

    $updatequantity = $mtto->UpdateQuantityIndicator($v_ene, $v_feb, $v_mar, $v_abr, $v_may, $v_jun, $v_jul, $v_ago, $v_sep, $v_oct, $v_nov, $v_dic, $id_month_indicator);

    header('Location: ../../pages/viewindicators?indicators='.$tk_indicator);

}

