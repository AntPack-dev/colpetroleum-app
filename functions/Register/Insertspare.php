<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$tokenw = $mysqli->real_escape_string($_GET['warehouse']);

$mtto = new mtto();

$errors = array();

if(!empty($_POST))
{
    $actives = $mysqli->real_escape_string($_POST['active']);
    $cant = $mysqli->real_escape_string($_POST['cant']);
    $token = $mtto->GenerateTokenMtto();
    $id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $tokenw);
    $concept = $mtto->getValueMtto('concept_warehouse', 'spares_parts', 'id_spares', $actives);
    $numcon = $mtto->getValueMtto('num_concept_warehouse', 'spares_parts', 'id_spares', $actives);
    $reference = $concept."-".$numcon;
    $description = $mtto->getValueMtto('description_element_spares', 'spares_parts', 'id_spares', $actives);
    $unityspares = $mtto->getValueMtto('unity_spares', 'spares_parts', 'id_spares', $actives);
    $unityvalue = $mtto->getValueMtto('unity_value_spares', 'spares_parts', 'id_spares', $actives);
    $partial = $unityvalue * $cant;
    $state = 0;

    if($mtto->ValideValue($actives) <= 0)
    {
        $errors[] = "ERROR";
    }
    if($mtto->ValideValue($cant) <= 0)
    {
        $errors[] = "ERROR";
    }

    if(count($errors) == 0)
    {
        $insertspare = $mtto->InsertSpare($actives, $token, $description, $id_warehouse, $reference, $unityspares, $cant, $unityvalue, $partial, $state);

        $minusstock = $mtto->SubtractStockActive($actives, $cant);

        if($insertspare > 0)
        {
            header('Location: ../../pages/analysis?warehouse='.$tokenw);
        }
        else
        {
            header('Location: ../../pages/analysis?warehouse='.$tokenw);
        }
    }
    
}