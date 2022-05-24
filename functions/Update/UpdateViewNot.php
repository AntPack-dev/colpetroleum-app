<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$tokenNot = $mysqli->real_escape_string($_GET['notification']);

$mtto = new mtto();

$not_stock = $mtto->getValueMtto('fk_stockactives_mtto', 'notifications_mtto', 'token_notification_mtto', $tokenNot);
$not_maint = $mtto->getValueMtto('fk_maintteams_mtto', 'notifications_mtto', 'token_notification_mtto', $tokenNot);

if(isset($not_stock))
{
    $mtto->ResetStateNot('spares_parts', 'state_notification_mtto_spares_parts', 0, 'id_spares', $not_stock);
    $mtto->UpdateViewNotifications($tokenNot);
    header('Location: ../../pages/notifications');
}

if(isset($not_maint))
{   
    $mtto->UpdateViewNotifications($tokenNot);
    header('Location: ../../pages/notifications');
}










