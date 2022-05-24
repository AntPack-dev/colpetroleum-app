<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

if(isset($_POST['btnregistervalue'])){

    $tk_schedule = $mysqli->real_escape_string($_POST['tk_schedule']);
    $month_activies = $mysqli->real_escape_string($_POST['month_activies']);
    $category_activies = $mysqli->real_escape_string($_POST['category_activies']);
    $value_activies = $mysqli->real_escape_string($_POST['value_activies']);
    $id_activies = $mysqli->real_escape_string($_POST['fk_activies']);
    $id_schedule = $mysqli->real_escape_string($_POST['fk_schedule']);

    if($month_activies == 'all_ratings'){
        
        $mtto->InsertValueScheduleGen($id_activies, $category_activies, $id_schedule);
        header('Location: ../../pages/viewschedule?schedule='.$tk_schedule);
    }
    else{

        $mtto->InsertValueScheduleInd($month_activies, $value_activies, $id_activies, $category_activies, $id_schedule);
        header('Location: ../../pages/viewschedule?schedule='.$tk_schedule);
    }

    // echo "Id de la actividad a evaluar ".$id_activies;
    // echo "Del cronograma ".$id_schedule;
    // echo "Mes a evaluar ".$month_activies;
    // echo "Categoria a evaluar ".$category_activies;

}

