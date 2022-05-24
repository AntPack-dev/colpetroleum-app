<?php

//REALIZA LA BUSQUEDA DE REPORTES DE CONSUMIBLES

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$cons = new mtto();

$report = $cons->SearchReportConsum();

echo $report;