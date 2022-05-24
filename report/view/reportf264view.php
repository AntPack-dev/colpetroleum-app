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
                    <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 380px;">SALIDA DE ACTIVOS AL ALMACÉN</td>
                    <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-264</td>
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
        <tr style="font-size: 12px; text-align: center;">
            <th style="border: 1px solid black; width:90px;">No FACTURA</th>
            <th style="border: 1px solid black; width:130px;">FECHA DE RETIRADA</th>
            <th style="border: 1px solid black; width:200px;">REFERENCIA ALMACÉN</th>
            <th style="border: 1px solid black; width:200px;">NOMBRE ELEMENTO</th>
            <th style="border: 1px solid black; width:90px;">CANTIDAD INGRESADA</th>

        </tr>

        <?php echo $mtto->getDatosF264($id_warehouse); ?>

       



 
    </table>

</page>