<?php

session_start();

// FORMATO GESTIÓN DE INVENTARIOS DEL ALMACÉN F-262

include('../db/ConnectDB.php');

if(!isset($_SESSION['id_user']))
{
  header("Location: ../../");
}

if(!isset($_SESSION['token']))
{
  header("Location: ../../");
}

require '../bookstores/Vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();
require_once 'view/reportf265view.php';

$html = ob_get_clean();
$html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
$html2pdf->writeHTML($html);
$html2pdf->output();

?>