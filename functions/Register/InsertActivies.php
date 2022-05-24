<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

if(isset($_POST['btnregisteractivies']))
{
    $token = $mtto->GenerateTokenMtto();
    $date = $mtto->DateMtto();

    

    $schedule = $mysqli->real_escape_string($_POST['id_schedule']);
    $tk_schedule = $mysqli->real_escape_string($_POST['tk_schedule']);
    $description_activies = $mysqli->real_escape_string($_POST['description_activies']);
    $resources_activies = $mysqli->real_escape_string($_POST['resources_activies']);
    $responsable_activies = $mysqli->real_escape_string($_POST['responsable_activies']);
    $where_activies = $mysqli->real_escape_string($_POST['where_activies']);
    $category_activies = $mysqli->real_escape_string($_POST['category_activies']);
        

    $type_calf_p = 1;
    $type_calf_e = 2;

    $reg_act = $mtto->RegisterActivies($token, $schedule, $category_activies, $description_activies, $resources_activies, $responsable_activies, $where_activies);
    $mtto->RegisterCuantiActivies($token, $schedule, $reg_act, $type_calf_e, $date);
    $mtto->RegisterCuantiActivies($token, $schedule, $reg_act, $type_calf_p, $date);

    header("Location: ../../pages/viewindicators?schedule=".$tk_schedule);
            
    
}