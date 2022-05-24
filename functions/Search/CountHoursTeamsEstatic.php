<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$countmant = $cons->CountHoursTeamsStatic();

echo $countmant;