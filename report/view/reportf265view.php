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
                    <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 700px;">BASE DE DATOS DE FALLAS O AVERÍAS DE HERRAMIENTAS - MANTENIMIENTO CORRECTIVO</td>
                    <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-265</td>
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
            <th style="border: 1px solid black; width:80px;">CÓDIGO FALLA O AVERÍA</th>
            <th style="border: 1px solid black; width:160px;">EQUIPO QUE PRESNTÓ LA FALLA O AVERÍA</th>
            <th style="border: 1px solid black; width:90px;">REFERENCIA DEL EQUIPO</th>
            <th style="border: 1px solid black; width:60px;">COMPONENTE QUE FALLÓ O SE AVERIÓ</th>
            <th style="border: 1px solid black; width:88px;">DESCRIPCIÓN DE FALLA O AVERÍA</th>
            <th style="border: 1px solid black; width:100px;">TIEMPO PROMEDIO DE PARADA PARA MANTENIMIENTO POR FALLA</th>
            <th style="border: 1px solid black; width:100px;">AVERÍA PONE RIESGO DE ACCIDENTALIDAD GRAVE A OS TRABAJADORES</th>
            <th style="border: 1px solid black; width:100px;">LA FALLA O AVERÍA PUEDE GENERAR IMPACTO NEGATIVO AL MEDIO AMBIENTE</th>
            <th style="border: 1px solid black; width:100px;">FECHA DE LA AVERÍA O FALLA</th>
            <th style="border: 1px solid black; width:80px;">CONSECUTIVO DE COSTOS No.</th>

        </tr>

        <?php echo $mtto->getDatosF265(); ?>

        <!-- <tr>
            <td style="border: 1px solif black; width: 80px;"></td>
            <td style="border: 1px solif black; width: 160px;"></td>
            <td style="border: 1px solif black; width: 90px;"></td>
            <td style="border: 1px solif black; width: 80px;"></td>
            <td style="border: 1px solif black; width: 88px;"></td>
            <td style="border: 1px solif black; width: 100px;"></td>
            <td style="border: 1px solif black; width: 100px;"></td>
            <td style="border: 1px solif black; width: 100px;"></td>
            <td style="border: 1px solif black; width: 100px;"></td>
            <td style="border: 1px solif black; width: 80px;"></td>

        </tr> -->




 
    </table>

</page>