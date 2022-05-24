<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();


$analysis = $cons->SearchHistoryIndicators();

echo $analysis;