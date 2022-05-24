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
                    <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 700px;">GESTIÓN DE INVENTARIOS DEL ALMACÉN</td>
                    <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-262</td>
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
            <th style="border: 1px solid black; width:80px;">REFERENCIA ALMACÉN</th>
            <th style="border: 1px solid black; width:160px;">NOMBRE ELEMENTO</th>
            <th style="border: 1px solid black; width:90px;">TIPO ELEMENTO</th>
            <th style="border: 1px solid black; width:60px;">UNIDAD</th>
            <th style="border: 1px solid black; width:88px;">ALARMA DE REQUISICIÓN</th>
            <th style="border: 1px solid black; width:100px;">VALOR UNITARIO</th>
            <th style="border: 1px solid black; width:100px;">FABRICANTE</th>
            <th style="border: 1px solid black; width:100px;">MODELO</th>
            <th style="border: 1px solid black; width:100px;">SERIE</th>
            <th style="border: 1px solid black; width:40px;">ALARMA</th>
            <th style="border: 1px solid black; width:50px;">STOCK</th>
        </tr>

        <?php echo $mtto->getDatosF262($id_warehouse); ?>



 
    </table>

</page>