<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$report = $cons->SearchNotifications();

echo $report;