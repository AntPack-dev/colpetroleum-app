<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$countmant = $cons->CountActivesRSU();

echo $countmant;
