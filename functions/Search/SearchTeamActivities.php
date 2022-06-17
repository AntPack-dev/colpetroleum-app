<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$procedures = $cons->SearchTeamActivities();
echo $procedures;
