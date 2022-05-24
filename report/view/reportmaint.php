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

$tkreportmant = $mysqli->real_escape_string($_GET['reportm']);

$date = $mtto->getValueMtto('date_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$location = $mtto->getValueMtto('location_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$number = $mtto->getValueMtto('number_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$typemant = $mtto->getValueMtto('type_activity_report_maint', 'report_maint', 'token_report_maint', $tkreportmant);
$codfails = $mtto->getValueMtto('cod_report_fails_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$nameteams = $mtto->getValueMtto('name_teams_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$referenceteams = $mtto->getValueMtto('reference_teams_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$actormant = $mtto->getValueMtto('actor_execution_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$description = $mtto->getValueMtto('description_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);
$analysis = $mtto->getValueMtto('analysis_data_report_mant', 'report_maint', 'token_report_maint', $tkreportmant);


if($codfails == "")
{
    $st = "NO APLICA";
}
else
{
    $st = $codfails;
}


$newDate = date("d-m-Y", strtotime($date));

?>


<page backtop="5mm" backbottom="0mm" backleft="10mm" backright="10mm">

    <table style="border: 1px solid black;">
        <tr>
            <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 200px; height: 50px;"></td>
            <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 350px;">FORMATO HOJA DE MANTENIMIENTO</td>
            <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-269</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-size: 11px;">Versión: 0</td>           

        </tr>

        <tr>
            <td style="border: 1px solid black; font-size: 11px;">Fecha: Enero 2021</td>          

        </tr>       
        
    </table>

    <table style="border: 1px solid #F2F3F4; font-size: 11px; margin-top: 20px;">
        <tr style="text-align: center;">
            <th style="border: 1px solid black; width: 150px; height: 20px; text-align: center; background-color: #F2DCDB;">FECHA (DD/MM/AAAA)</th>
            <td style="border: 1px solid black; width: 200px;"><?php echo $newDate; ?></td>
            <th rowspan="2" style="border: 1px solid black; width: 194px; text-align:center; background-color: #F2DCDB;">CONSECUTIVO DE MANTENIMIENTO No.</th>
            <td rowspan="2" style="border: 1px solid black; width: 100px;"><?php echo $number; ?></td>
        </tr>
        <tr style="text-align: center;">
            <th style="border: 1px solid black; width: 150px; height: 20px; text-align: center; background-color: #F2DCDB;">LUGAR</th>
            <td style="border: 1px solid black; width: 200px;"><?php echo $location; ?></td>
        </tr>
    </table>


    <table style="border: 1px solid #F2F3F4; font-size: 11; margin-top: 20px;">
        
        <tr style="text-align: center;">
            <th colspan="4" style="border: 1px solid black; text-align: center; height:20px; background-color: #F2DCDB;">DETALLE</th>
            
        </tr>
        <tr style="text-align: center;">
            <th style="border: 1px solid black; width: 150px; text-align: center; height:20px; background-color: #F2DCDB;">TIPO DE MANTENIMIENTO</th>
            <td style="border: 1px solid black; width: 200px;"><?php echo $typemant; ?></td>
            <th style="border: 1px solid black; width: 150px; text-align: center; height:20px; background-color: #F2DCDB;">CODIGO DE REPORTE DE FALLA O AVERÍA SI APLICA</th>
            <td style="border: 1px solid black; width: 144px;"><?php echo $st; ?></td>
        </tr>

        <tr style="text-align: center;">
            <th style="border: 1px solid black; width: 150px; text-align: center; height:20px; background-color: #F2DCDB;">NOMBRE DEL EQUIPO</th>
            <td style="border: 1px solid black;"><?php echo $nameteams; ?></td>
            <th style="border: 1px solid black; width: 150px; text-align: center; height:20px; background-color: #F2DCDB;">REFERENCIA DEL EQUIPO</th>
            <td style="border: 1px solid black;"><?php echo $referenceteams; ?></td>
        </tr>

    </table>


    <table style="border: 1px solid #F2F3F4; font-size: 11px; margin-top: 20px;">
        <tr>
            <th style="border: 1px solid black; width: 350px; background-color: #F2DCDB;">NOMBRE DEL MECÁNICO O PERSONA QUE REALIZA EL MANTENIMIENTO</th>
            <td style="border: 1px solid black; width: 310px; text-align: center;"><?php echo $actormant; ?></td>
        </tr>

    </table>


    <table style="border: 1px solid #F2F3F4; font-size: 11px; margin-top: 20px;">
        <tr>
            <th style="width: 300px; height: 100px; border: 1px solid black; text-align: center; background-color: #F2DCDB;">DESCRIPCIÓN DEL MANTENIMIENTO</th>
            <td style="width: 360px; height: 100px; border: 1px solid black; text-align: center;"><?php echo $description; ?></td>
        </tr>

    </table>

    <table style="border: 1px solid #F2F3F4; font-size: 11px; margin-top: 20px; text-align:center;">

        <tr>
            <th style="border: 1px solid black; width: 300px; height: 20px; background-color: #F2DCDB;">CONSECUTIVO DE COSTO No.</th>
            <td style="border: 1px solid black; width: 100px; height: 20px;"><?php echo $analysis; ?></td>
        </tr>

    </table>

    <table style="border: 1px solid #F2F3F4; font-size: 11px; margin-top: 20px;">
        <tr style="text-align: center;">
            <th colspan="2" style="border:1px solid black; width: 332px; background-color: #F2DCDB; height: 15px;">DATOS DE QUIEN REALIZÓ EL MANTENIMIENTO</th>
            <th colspan="2" style="border:1px solid black; width: 332px; background-color: #F2DCDB; height: 15px;">DATOS DE QUIEN RECIBIÓ EL MANTENIMIENTO</th>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid black; height: 100px;"></td>
            <td colspan="2" style="border:1px solid black; height: 100px;"></td>
            
        </tr>

        <tr>
            <th style="border:1px solid black; width: 50px; background-color: #F2DCDB;">NOMBRE</th>
            <td style="border:1px solid black; width: 150px;"></td>
            <th style="border:1px solid black; width: 50px; background-color: #F2DCDB;">NOMBRE</th>
            <td style="border:1px solid black; width: 150px;"></td>
        </tr>
        <tr>
            <th style="border:1px solid black; width: 50px; background-color: #F2DCDB;">CARGO</th>
            <td style="border:1px solid black; width: 150px;" ></td>
            <th style="border:1px solid black; width: 50px; background-color: #F2DCDB;">CARGO</th>
            <td style="border:1px solid black; width: 150px;"></td>
        </tr>
    </table>


</page>