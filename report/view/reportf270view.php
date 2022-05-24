<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/FunctionsMtto.php');

if(!isset($_SESSION['id_user']))
{
  header("Location: ../../");
}

if(!isset($_SESSION['token']))
{
  header("Location: ../../");
}

$mtto = new mtto();

$tk_ware = $mysqli->real_escape_string($_GET['warehouse']);

$id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $tk_ware);


?>

<page backtop="25mm" backttom="15mm" backleft="0m" backright="0mm">

    <page_header>
            <table style="border: 1px solid black;">
                <tr>
                    <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 250px; height: 50px;"></td>
                    <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 830px;">BASE DE DATOS DE UNIDADES RSU</td>
                    <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-270</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Versión: 0</td>
                </tr>

                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Fecha: Enero-2021</td>
                </tr>       
                
            </table>
    </page_header>

    <table>
        <tr style="font-size: 9px; text-align: center;">
            <th style="border: 1px solid black; width:100px;">REFERENCIA DEL EQUIPO</th>
            <th style="border: 1px solid black; width:100px;">ESTADO ACTUAL</th>
            <th style="border: 1px solid black; width:100px;">UBICACIÓN ACTUAL</th>
            <th style="border: 1px solid black; width:100px;">ASIGNACIÓN CONTRACTUAL VIGENTE</th>
            <th style="border: 1px solid black; width:100px;">CLIENTE</th>
            <th style="border: 1px solid black; width:100px;">TIEMPO OPERATIVO ASIGNACIÓN ACTUAL (Días)</th>
            <th style="border: 1px solid black; width:100px;">COSTO ASOCIADO A MANTENIMIENTOS</th>
            <th style="border: 1px solid black; width:100px;">COSTO ASOCIADO A FALLAS, DAÑO AVERÍAS</th>
            <th style="border: 1px solid black; width:100px;">COSTO DE MANTENIMIENTOS PREVENTIVOS</th>
            <th style="border: 1px solid black; width:100px;">COSTO POR NPT</th>
            <th style="border: 1px solid black; width:100px;">ALERTAS DE MANTENIMIENTO</th>
        </tr>
       
        <?php echo $mtto->getDatosF270(); ?>
 
    </table>


</page>