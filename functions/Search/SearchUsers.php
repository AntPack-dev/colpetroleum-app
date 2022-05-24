<?php

include('../../db/ConnectDB.php');
include('../OperationsUser.php');

$consuluser = new Admin();

$users = $consuluser->SearchUsers();

echo $users;
