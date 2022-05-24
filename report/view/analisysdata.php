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


$tkanalisys = $mysqli->real_escape_string($_GET['Analisys']);

?>

<page backtop="5mm" backbottom="0mm" backleft="10mm" backright="10mm">

    <table style="border: 1px solid black;">
        <tr>
            <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 200px; height: 50px;"></td>
            <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 350px;">FORMATO ANÁLISIS DE COSTOS</td>
            <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-268</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-size: 11px;">Versión: 0</td>           

        </tr>

        <tr>
            <td style="border: 1px solid black; font-size: 11px;">Fecha: Enero-2021</td>          

        </tr>       
        
    </table>

    <?php echo $mtto->NumberAnalysis($tkanalisys); ?>
    <?php echo $mtto->DescriptionAnalysis($tkanalisys); ?>    

    <?php echo $mtto->ReportPersonAnalysis($tkanalisys); ?>
    <?php echo $mtto->ReportSpareAnalysis($tkanalisys); ?>   
    <?php echo $mtto->ReportExpectAnalysis($tkanalisys);?>
    <?php echo $mtto->TotalAnalysis($tkanalisys);?>    

    <table style="border: 1px solid black; margin-top: 20px;">
        
    <tr style="font-size: 12px; text-align: center;">
        <th colspan="2" style="border: 1px solid black; width: 331px; height: 30px;">DATOS DE QUIEN REALIZÓ EL ANÁLISIS DE COSTOS</th>
        <th colspan="2" style="border: 1px solid black; width: 332px; height: 30px;">DATOS DE QUIEN REALIZÓ EL ANÁLISIS DE COSTOS</th>
    </tr>

    <tr style="font-size: 11px; text-align: center;">
        <th style="border: 1px solid black ; height: 100px; width: 125px; background-color: #F2DCDB ">FIRMA QUIEN REALIZÓ</th>   
        <td style="border: 1px solid black; width: 190px;"></td>
        <th style="border: 1px solid black ; height: 100px; width: 125px; background-color: #F2DCDB ">FIRMA QUIEN APROBÓ</th>
        <td style="border: 1px solid black; width: 190px;"></td>
    </tr>

    <tr style="font-size: 11px;">
        <th style="border: 1px solid black ; height: 15px; width: 125px; background-color: #F2DCDB ">NOMBRE</th>
        <td style="border: 1px solid black; height: 15px; width: 125px;"></td>
        <th style="border: 1px solid black ; height: 15px; width: 125px; background-color: #F2DCDB ">NOMBRE</th>
        <td style="border: 1px solid black; height: 15px; width: 125px;"></td>
    </tr>

    <tr style="font-size: 11px;">
        <th style="border: 1px solid black; height: 15px; width: 125px; background-color: #F2DCDB ">CARGO</th>
        <td style="border: 1px solid black; height: 15px; width: 125px;"></td>
        <th style="border: 1px solid black; height: 15px; width: 125px; background-color: #F2DCDB ">CARGO</th>
        <td style="border: 1px solid black; height: 15px; width: 125px;"></td>
    </tr>    

    </table>

    <table style="margin-top: 60px;">

    <tr style="font-size: 12px;">
        <td style="width: 315px;"></td>
        <td style="border-bottom: 1px solid black;">FECHA DE APROBACIÓN</td>
        <td style="width: 200px; border-bottom: 1px solid black;"></td>

    </tr>   

    </table>


</page>

