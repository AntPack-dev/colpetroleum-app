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
    $description = $mysqli->real_escape_string($_POST['description']);
    $unitymed = $mysqli->real_escape_string($_POST['unitymed']);
    $quantity = $mysqli->real_escape_string($_POST['quantity']);
    $unityprice = $mysqli->real_escape_string($_POST['unityprice']);
    $totalpartial = $unityprice * $quantity;
    $state = 0;

    if($mtto->ValideValue($description) && $mtto->ValideValue($unitymed) && 
    $mtto->ValideValue($quantity) && $mtto->ValideValue($unityprice) <= 0)
    {
        $errors[] = "ERROR";   
        
        echo $errors;

    }

    if(count($errors) == 0)
    {
        $insertcost = $mtto->InsertExpect($token, $id_warehouse, $description, $unitymed, $quantity, $unityprice, $totalpartial, $state);

        if($insertcost > 0)
        {
            header('Location: ../../pages/analysis?warehouse='.$tokenw);
        }
        else
        {
            header('Location: ../../pages/analysis?warehouse='.$tokenw);
        }
    }
    
}