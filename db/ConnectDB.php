<?php

//CADENA MYSQL ORIGINAL
$mysqli = new mysqli("localhost","colpetro_dbmtto","792nQALpz)","colpetro_dbmtto");

//CADENA MYSQL PRUEBA
//$mysqli = new mysqli("localhost","colpetro_pruebamtto","f33r8CBNe&","colpetro_pruebamtto");

if(mysqli_connect_errno())
{
    echo 'Fallied Conection: ', mysqli_connect_error();
    exit();
}