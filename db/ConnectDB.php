<?php

//CADENA MYSQL ORIGINAL
//$mysqli = new mysqli("localhost","colpetro_dbmtto","792nQALpz)","colpetro_dbmtto");
$mysqli = new mysqli("database-dev.cfnlhhqifebn.us-east-2.rds.amazonaws.com","admin","6P*LQm1n34e","colpetro_dbmtto");
$mysqli->set_charset('utf8mb4');
//CADENA MYSQL PRUEBA
//$mysqli = new mysqli("localhost","colpetro_pruebamtto","f33r8CBNe&","colpetro_pruebamtto");

if(mysqli_connect_errno())
{
    echo 'Fallied Conection: ', mysqli_connect_error();
    exit();
}
