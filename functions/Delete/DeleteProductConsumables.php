<?php

//ELIMINA EL PRODUCTO DE LOS CONSUMIBLES

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

$token_product = $mysqli->real_escape_string($_POST['tk_product']);

$delete = $mtto->DeleteProductCons($token_product);

header('Location: ../../pages/consumables');

