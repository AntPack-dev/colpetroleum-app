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

$tkreport = $mysqli->real_escape_string($_GET['report']);

$num_report = $mtto->getValueMtto('num_report_fails','report_fails','token_report_fails',$tkreport);
$reference_teams = $mtto->getValueMtto('reference_teams_report_fails','report_fails','token_report_fails',$tkreport);
$no_analysis = $mtto->getValueMtto('no_analysis_report_fails','report_fails','token_report_fails',$tkreport);
$name_teams = $mtto->getValueMtto('name_teams_report_fails','report_fails','token_report_fails',$tkreport);
$name_report = $mtto->getValueMtto('name_report_fails','report_fails','token_report_fails',$tkreport);
$description_report = $mtto->getValueMtto('description_report_fails','report_fails','token_report_fails',$tkreport);
$time_stop = $mtto->getValueMtto('time_stop_report_fails','report_fails','token_report_fails',$tkreport);
$date = $mtto->getValueMtto('datereg_report_fails','report_fails','token_report_fails',$tkreport);
$costnpt = $mtto->getValueMtto('costnpt_report_fails','report_fails','token_report_fails',$tkreport);
$warning_person = $mtto->getValueMtto('warning_person_report_fails','report_fails','token_report_fails',$tkreport);
$warning_ambiental = $mtto->getValueMtto('warning_ambiental_report_fails','report_fails','token_report_fails',$tkreport);

$newDate = date("d-m-Y", strtotime($date));


?>

<page backtop="5mm" backbottom="0mm" backleft="10mm" backright="10mm">

    <table style="border: 1px solid black;">
        <tr>
            <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 200px; height: 50px;"></td>
            <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 350px;">FORMATO REPORTE DE FALLAS O AVERIAS HERRAMIENTAS - MANTENIMIENTO CORRECTIVO</td>
            <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-267</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-size: 11px;">Versión: 01</td>           

        </tr>

        <tr>
            <td style="border: 1px solid black; font-size: 11px;">Fecha: Enero 2021</td>          

        </tr>       
        
    </table>

    <table style="border: 1px solid white; margin-top: 40px;">
      <tr style="text-align: center;">
        <td style="border: 1px solid white; width: 220px;"></td>
        <th style="border: 1px solid black; width: 280px; background-color: #F2DCDB;">CÓDIGO DE REPORTE DE FALLA O AVERÍA</th>
        <td style="border: 1px solid black; width: 150px;"><?php echo $num_report; ?></td>
      </tr>
    </table>

    <table style="border: 1px solid white; margin-top: 20px;">
      <tr style="margin-top: 20px; text-align: center;">
        <td style="border: 1px solid white; width: 220px;"></td>
        <th style="border: 1px solid black; width: 280px; background-color: #F2DCDB; height: 30px;">REFERENCIA DEL EQUIPO</th>
        <td style="border: 1px solid black; width: 150px;"><?php echo $reference_teams; ?></td>
      </tr>
    </table>

    <table style="border: 1px solid white; margin-top: 20px;">
      <tr>
        <th style="border: 1px solid black; width: 350px; background-color: #F2DCDB; height: 30px;">EQUIPO QUE PRESENTÓ LA FALLA O AVERÍA</th>
        <td style="border: 1px solid black; width: 310px;"><?php echo $name_teams; ?></td>
      </tr>
    </table>

    <table style="border: 1px solid white; margin-top: 20px;">
      <tr>
        <th style="border: 1px solid black; width:350px; background-color: #F2DCDB; height: 30px;">COMPONENTE QUE FALLÓ O SE AVERIÓ</th>
        <td style="border: 1px solid black; width: 310px;"><?php echo $name_report; ?></td>
      </tr>
    </table>

    <table style="border: 1px solid white; margin-top: 20px;">
      <tr>
        <th style="border: 1px solid black; width:670px; text-align: center; background-color: #F2DCDB;">DESCRIPCIÓN DE FALLA O AVERIA </th>
      </tr>
      <tr>
        <td style="border: 1px solid black; height: 40px;"><?php echo $description_report; ?></td>
      </tr>
    </table>

    <table style="border: 1px solid white; margin-top: 20px;">
      <tr style="font-size: 10px; ">
        <th style="border: 1px solid black; width:300px; height: 30px; background-color: #F2DCDB;">TIEMPO PROMEDIO DE PARADA PARA MANTENIMIENTO POR FALLA (Hrs)</th>
        <td style="border: 1px solid black; width: 150px; text-align: center;"><?php echo $time_stop; ?></td>
        <th rowspan="5" style="border: 1px solid black; width: 100px; text-align: center; background-color: #F2DCDB;">CONSECUTIVO DE COSTOS No</th>
        <td rowspan="5" style="border: 1px solid black; width: 94px; text-align: center;"><?php echo $no_analysis; ?></td>
      </tr>

      <tr style="font-size: 10px;">
      <th style="border: 1px solid black; width:300px; height: 30px; background-color: #F2DCDB;">LA FALLA O AVERIA PONE EN RIESGO DE ACCIDENTALIDAD GRAVE A LOS TRABAJADORES </th>
        <td style="border: 1px solid black; width: 120px; text-align: center;"><?php echo $warning_person; ?></td>        
      </tr>

      <tr style="font-size: 10px;">
      <th style="border: 1px solid black; width:300px; height: 30px; background-color: #F2DCDB;">LA FALLA O AVERIA PUEDE GENERAR IMPACTO NEGATIVO AL MEDIO AMBIENTE</th>
        <td style="border: 1px solid black; width: 120px; text-align: center;"><?php echo $warning_ambiental; ?></td>        
      </tr>

      <tr style="font-size: 10px;">
      <th style="border: 1px solid black; width:300px; height: 30px; background-color: #F2DCDB;">COSTO PROMEDIO NPT</th>
        <td style="border: 1px solid black; width: 120px; text-align: center;">$<?php echo number_format($costnpt); ?></td>        
      </tr>

      <tr style="font-size: 10px;">
      <th style="border: 1px solid black; width:300px; height: 30px; background-color: #F2DCDB;">FECHA EN LA FALLA O AVERIA GENERA PARADAS O TIEMPO MUERTOS</th>
        <td style="border: 1px solid black; width: 120px; text-align: center;"><?php echo $newDate; ?></td>        
      </tr>
      

    </table>


</page>

