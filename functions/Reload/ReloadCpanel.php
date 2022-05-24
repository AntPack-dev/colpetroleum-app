<?php

include('../../db/ConnectDB.php');

$tokenw = $mysqli->real_escape_string($_GET['warehouse']);

header('Location: ../../pages/cpanel?warehouse='.$tokenw);
