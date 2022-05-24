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

$tkresumeteams = $mysqli->real_escape_string($_GET['teams']);

$imgone_url = $mtto->getValueMtto('teams_image_one','teams_units_rsu','token_teams_units',$tkresumeteams);
$imgtwo_url = $mtto->getValueMtto('teams_image_two','teams_units_rsu','token_teams_units',$tkresumeteams);

if($imgone_url == '')
{
  $imgone_url = "";
}

if($imgtwo_url == '')
{
  $imgtwo_url = "";
}



?>

<page backtop="5mm" backbottom="0mm" backleft="5mm" backright="5mm">

    

      <table style="border: 1px solid black;">
          <tr>
              <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 200px; height: 50px;"></td>
              <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 660px;">HOJA DE VIDA - FICHA TÉCNICA MANTENIMIENTO</td>
              <td style="border: 1px solid black; width: 150px; font-size: 11px;">Código: F-271</td>
          </tr>
          <tr>
              <td style="border: 1px solid black; font-size: 11px;">Versión: 1</td>           

          </tr>

          <tr>
              <td style="border: 1px solid black; font-size: 11px;">Fecha: Octubre-2021</td>          

          </tr>       
          
      </table>

      <?php echo $mtto->ReportOneteams($tkresumeteams); ?>

    

      <table style="border: 1px solid #EAECEE ; font-size: 12px;">
        

        <tr style="border: 1px solid black; text-align:center;">       
          <th colspan="3" style="border: 1px solid black; background-color: #F2DCDB;">FRECUENCIA DE INSPECCIÓN Y MANTENIMIENTO</th>
         
        </tr>
        

        <?php echo $mtto->ReportTwoTeams($tkresumeteams);?>
      </table>

      <table style="border: 1px solid #EAECEE ; font-size: 12px;">
        <tr style="border: 1px solid black; text-align:center; background-color: #F2DCDB;">
          <th style="border: 1px solid black; width:512px;">FOTOGRAFIA No. 1 </th>
          <th style="border: 1px solid black; width:512px;">FOTOGRAFIA No. 2</th>
        </tr>

        <?php echo $mtto->ReportThreeTeams($tkresumeteams); ?>
        
      </table>

      <table style="width: 100%; border: 1px solid #EAECEE ;">
        <tr>
          <th colspan="12" style="border: 1px solid black; text-align:center; background-color: #F2DCDB;">REGISTROS DE LOS MANTENIMIENTOS REALIZADOS</th>
        </tr>
        <tr style="font-size: 8px; justify-content: center; text-align: center; background-color: #F2DCDB;">
          <th style="border: 1px solid black; overflow:hidden; width: 30px;">CONSE CUTIVO DE ACTIVI DAD No.</th>
          <th style="border: 1px solid black; overflow:hidden; width: 30px;">CÓNSE CUTIVO DE MAN TENI MIENTO (SI APLICA)</th>
          <th style="border: 1px solid black; overflow:hidden; width: 70px;">TIPO DE ACTIVIDAD</th>
          <th style="border: 1px solid black; overflow:hidden; width: 200px;">DESCRIPCIÓN DEL MANTENIMIENTO</th>
          <th style="border: 1px solid black; overflow:hidden; width: 80px;">RESPONSABLE DE EJECUCIÓN</th>
          <th style="border: 1px solid black; overflow:hidden; width: 100px;">LUGAR DE EJECUCIÓN</th>
          <th style="border: 1px solid black; overflow:hidden; overflow:hidden; width: 30px;">CÓDIGO DE REPOR TE DE FALLA O AVERÍA (SI APLICA)</th>
          <th style="border: 1px solid black; overflow:hidden; width: 62px;">FECHA</th>
          <th style="border: 1px solid black; overflow:hidden; width: 100px;">ALARMA</th>
          <th style="border: 1px solid black; overflow:hidden; width: 84px;">PROXIMA FECHA DE EJECUCIÓN</th>
          <th style="border: 1px solid black; overflow:hidden; width: 40px;">SE EJECUTÓ CORRECTA MENTE LA ACTIVIDAD PROGRA MADA? SI/NO</th>
          <th style="border: 1px solid black; overflow:hidden; width: 90px;">COSTOS ASOCIADOS</th>
        </tr> 
        
        <?php echo $mtto->ReportFourthTeams($tkresumeteams); ?>

      </table>

      

</page>
