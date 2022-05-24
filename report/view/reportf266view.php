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


?>

<page backtop="25mm" backttom="15mm" backleft="0m" backright="0mm">

    <page_header>
            <table style="border: 1px solid black;">
                <tr>
                    <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 250px; height: 50px;"></td>
                    <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 700px;">BASE DATOS - HOJA DE MANTENIMIENTO CORRECTIVO/PREVENTIVO</td>
                    <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-266</td>
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
        <tr style="font-size: 10px; text-align: center;">
            <th style="border: 1px solid black; width:80px;">CONSECUTIVO DE MANTENIMIENTO No.</th>
            <th style="border: 1px solid black; width:140px;">TIPO DE MANTENIMIENTO</th>
            <th style="border: 1px solid black; width:90px;">LUGAR</th>
            <th style="border: 1px solid black; width:60px;">REFERENCIA DEL EQUIPO</th>
            <th style="border: 1px solid black; width:80px;">NOMBRE DEL EQUIPO</th>
            <th style="border: 1px solid black; width:88px;">CÓDIGO DE REPORTE DE FALLA O AVERÍA SI APLICA</th>
            <th style="border: 1px solid black; width:100px;">DESCRIPCIÓN DEL MANTENIMIENTO</th>
            <th style="border: 1px solid black; width:100px;">NOMBRE DEL COORDINADOR DE MANTENIMIENTO O MECANICO QUE RELIZA EL MANTENIMIENTO</th>
            <th style="border: 1px solid black; width:100px;">CONSECUTIVO DE COSTOS </th>
            <th style="border: 1px solid black; width:100px;">FECHA (DD/MM/AAAA)</th>

        </tr>

        <?php echo $mtto->getDatosF266(); ?>

            

 
    </table>

</page>