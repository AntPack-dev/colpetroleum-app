<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();
date_default_timezone_set('America/Bogota'); 


    $year = date("Y");

    $token = $mtto->GenerateTokenMtto();
    $date = $mtto->DateMtto();

    $id_schedule = $mtto->RegisterScheduleNew($year, $date, $token);

    $registerplan = $mtto->ConsultPlanActivies($id_schedule, $date);

    header("Location: ../../pages/schedule");