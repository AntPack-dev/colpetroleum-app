<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$warehouse = $cons->SearchwarehouseAnalisys();

echo $warehouse;