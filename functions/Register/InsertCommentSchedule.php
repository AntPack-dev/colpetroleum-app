<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();


if(isset($_POST['btnregistercoment'])){

    $id_schedule = $mysqli->real_escape_string($_POST['id_schedule']);
    $id_activie = $mysqli->real_escape_string($_POST['id_activie']);
    $coment = $mysqli->real_escape_string($_POST['coment_schedule']);
    $tk_schedule = $mysqli->real_escape_string($_POST['tk_schedule']);

    $mtto->InsertComentSchedule($coment, $id_schedule, $id_activie);
    header('Location: ../../pages/viewschedule?schedule='.$tk_schedule);

}