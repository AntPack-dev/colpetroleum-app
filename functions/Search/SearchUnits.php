<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$units = $cons->SerahcUnits();

echo $units;