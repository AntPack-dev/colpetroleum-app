<?php

//INSERTA LOS PRODUCTOS DEL CONSUMIBLE A REGISTRAR (FORMATO)

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

if(isset($_POST['btnproductscons']))
{
    $token = $mtto->GenerateTokenMtto();

    $description = $mysqli->real_escape_string($_POST['description_consumables']);
    $quantity = $mysqli->real_escape_string($_POST['quantity_consumables']);
    $price = $mysqli->real_escape_string($_POST['price_consumables']);
    $observation = $mysqli->real_escape_string($_POST['observation_consumables']);

    $reg_products_consumables = $mtto->RegisterArticlesConsumables($token, $description, $quantity, $price, $observation);

    header("Location: ../../pages/consumables");


}

