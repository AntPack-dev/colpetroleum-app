<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$tokenw = $mysqli->real_escape_string($_GET['warehouse']);

$mtto = new mtto();


$errors = array();

if(!empty($_POST))
{
    $token = $mtto->GenerateTokenMtto();
    $id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $tokenw);
    $person = $mysqli->real_escape_string($_POST['personanalysis']);
    $hours = $mysqli->real_escape_string($_POST['canthoursperson']);
    $cost = $mtto->getValueMtto('cost_hour_workforce', 'workforce_analysis', 'id_workforce', $person);
    $descriptionwork = $mtto->getValueMtto('name_workforce', 'workforce_analysis', 'id_workforce', $person);
    $unity = "HORAS";
    $total = $hours * $cost;
    $state = 0;

    if($mtto->ValideValue($person) <= 0)
    {
        $errors[] = "Debe validar el registro de mano de obra.";
    }
    if($mtto->ValideValue($hours) <= 0)
    {
        $errors[] = "La hora no debe ser menor de 1.";
    }

    if(count($errors) == 0)
    {
        $insertperson = $mtto->InsertPerson($token, $id_warehouse, $descriptionwork, $hours, $cost, $unity, $total, $state);

        if($insertperson > 0)
        {
            header('Location: ../../pages/analysis?warehouse='.$tokenw);
        }
        else
        {
            header('Location: ../../pages/analysis?warehouse='.$tokenw);
        }

    }

}