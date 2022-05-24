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

if(isset($_POST['btngeneratereport']))
{
  $mtto = new mtto();

  $date_generate = $mtto->DateMtto();

  $token_user = $_SESSION['token'];
  $username = $mtto->getValueMtto('first_name', 'users', 'token', $token_user);
  $usernames = $mtto->getValueMtto('second_name', 'users', 'token', $token_user);

  $date_generate = $mtto->DateMtto();
  $date_ini = $mysqli->real_escape_string($_POST['date_ini']);  
  $date_end = $mysqli->real_escape_string($_POST['date_end']);
  $unity = $mysqli->real_escape_string($_POST['unity_rsu']);

  $unity_name = $mtto->getValueMtto('reference_units_rsu', 'father_units_rsu', 'id_units_rsu', $unity);

}
else
{
    header('Location: ../pages/');
}
?>



<page backtop="25mm" backbottom="15mm" backleft="0mm" backright="0mm">
  <page_header>

    <table style="background-color: #FDFEFE; border-radius: 5px; border: 0.5px solid #273746;">
      <tr style="border: 1px solid black;">      
      <td style="width:960px; font-size: 14px; color: #A93226; font-weight: bold;">COL PETROLEUM SERVICES S.A.S.</td>    
      <td rowspan="4"><img src="img/Logodos.png" style="position: relative; width: 110px; height: 60px;"></td>  
        
      </tr>
      <tr><td ><b style="color: #212F3D;">Fecha y Hora de Generación:</b> <?php echo $date_generate; ?></td></tr>      
      <tr><td style=" margin-bottom: 50px;"><b style="color: #212F3D;">Generado por:</b> <?php echo $username." ".$usernames ?></td></tr>

    </table>

  </page_header>
    
  <page_footer>

    <table style="background-color: #EAECEE; border-radius: 5px; border: 0.5px solid #273746;">
      <tr >
        <td style="width:355px; "><img src="img/Logodos.png" style="position: relative; width: 70px; height: 40px;"> </td>
        <td style="width:355px; text-align: center; color: #A93226;"><b>Col Petroleum Services S.A.S. ® <br> 2022</b></td>
        <td style="width:355px; text-align: right; color: #273746;">1.0</td>
      </tr>
      
    </table>

  </page_footer>

  <table style="color: white; text-align:center; font-size: 20px; border: 0.5px solid #273746; border-radius: 5px; background-color: #B03A2E ;">
    <tr>
      <th style="width: 1080px;">LISTADO DE MANTENIMIENTOS - <?php echo $unity_name; ?></th>
    </tr>
  </table>

  <table style="border-radius: 5px; border: 0.5px solid black; margin-top: 10px; font-size: 11px;">
  <tr style="background-color: #273746; text-align:center; ">
    <th colspan="4" style="color: white; border-radius: 2px; border: 0.5px solid black; height: 20px;">FECHA DESDE: <?php echo $date_ini; ?> FECHA HASTA: <?php echo $date_end; ?></th>
    <th colspan="4" style="color: white; border-radius: 2px; border: 0.5px solid black; height: 20px;">MANTENIMIENTOS REGISTRADOS</th>
  </tr>
  
  <?php echo $mtto->RMTTOMantsUnity($date_ini, $date_end, $unity); ?>

  </table>

  

  <table style="border-radius: 5px; border: 0.5px solid black; margin-top: 10px; font-size: 11px;">
  
  <tr style="background-color: #273746; text-align:center;">
    <th style="border-radius: 2px; border: 0.5px solid black; width: 700px; color: white;">COMENTARIO</th>
  
  </tr>

  <tr>
    <td style="border-radius: 2px; border: 0.5px solid black;">
    <textarea name="comment" rows="3" cols="100" >-</textarea>
       
    </td>
         
  </tr>

</table>

</page>



