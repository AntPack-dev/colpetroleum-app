<?php

//Clase de las funciones primordiales del aplicativo
class mtto{

    //Devuelve la fecha para registrar.
    function DateMtto()
    {
        date_default_timezone_set('America/Bogota');

        $date = date("Y-m-d H:i:s");

        return $date;
    }

    //Genera fecha actual
	function DatetoDay()
	{
		date_default_timezone_set('America/Bogota');

		$dia = date('j');
		$mes = date('F');
		$year = date('Y');

		if ($mes == "January") $mes = "Enero";
		if ($mes == "February") $mes = "Febrero";
		if ($mes == "March") $mes = "Marzo";
		if ($mes == "April") $mes = "Abril";
		if ($mes == "May") $mes = "Mayo";
		if ($mes == "June") $mes = "Junio";
		if ($mes == "July") $mes = "Julio";
		if ($mes == "August") $mes = "Agosto";
		if ($mes == "September") $mes = "Setiembre";
		if ($mes == "October") $mes = "Octubre";
		if ($mes == "November") $mes = "Noviembre";
		if ($mes == "December") $mes = "Diciembre";

		$fecha = $dia.' de '.$mes.' de '.$year;

		return $fecha;
	}

    //Devuelve la primera letra del almacén
    function LetterWarehouse($value)
    {
        $letterA = $value[11];
        $letterB = $value[12];
        $letter = $letterA."".$letterB;
        $letter = strtoupper($letter);

        return $letter;
    }

    //Trae el ultimo número de inventario según el almacén
    function AfterNumWarehouse($value)
    {
        global $mysqli;

        $top = 0;

        $stmt = $mysqli->prepare("SELECT num_concept_warehouse FROM spares_parts WHERE 	warehouse_reference_spares = ? ORDER BY id_spares DESC LIMIT 1");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($num_ware);
        $stmt->fetch();

        $top = $num_ware + 1;

        return $top;
    }

    //Muestra los avisos de los errores
    function ResultBlockError($errors)
    {
        if(count($errors) > 0)
        {
            echo "<div class='alert alert-warning alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-exclamation-triangle'></i>Espera</h5>";
            foreach($errors as $error)
            {
                echo $error. "<br>";
            }
            echo "</div>";
        }
    }

    //Genera Token
    function GenerateTokenMtto()
    {
        $gen = md5(uniqid(mt_rand(), false));
        return $gen;
    }

    //Valida el dato del almacén
    function IsNullWarehouse($namewarehouse)
    {
        if(strlen(trim($namewarehouse) < 1))
        {
            return true;
            }else{
            return false;
        }

    }

    //Registrar Almacén
    function RegisterWarehouse($tokenwarehouse, $namewarehouse, $datewarehouse, $statewarehouse)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO warehouse (token_warehouse, description_warehouse, dateregister_warehouse, state_warehouse) VALUES (?,?,?,?)");
        $stmt->bind_param('sssi', $tokenwarehouse, $namewarehouse, $datewarehouse, $statewarehouse);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }


    }

    //Valida los campos concepto
    function IsNullWorforce($nameconcept, $value)
    {
        if(strlen(trim($nameconcept)) < 1 || strlen(trim($value)) < 1)
        {
            return true;
            }else{
            return false;
        }
    }

    //Registrar concepto de mano de obra
    function RegisterWorkforce($token, $nameconcept, $state, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO workforce_analysis (token_workforce, name_workforce, state_workforce, cost_hour_workforce) VALUES (?,?,?,?)");
        $stmt->bind_param('ssis', $token, $nameconcept, $state, $value);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Registrar concepto activo
    function RegisterActive($tokens, $nameactive, $states)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO type_active (token_active_type, name_active_type, state_active_type) VALUES (?,?,?)");
        $stmt->bind_param('ssi', $tokens, $nameactive, $states);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Elimina el concepto activo
    function DeleteActive($active)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("DELETE FROM type_active WHERE token_active_type = ?");
        $stmt->bind_param('s', $active);

        if($stmt->execute())
        {
            return true;
            }else{
            return false;
        }
    }

    //Realozau consulta de almacenes
    function Searchwarehouse()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT * FROM warehouse";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        //Search

        $sql = "SELECT * FROM warehouse ";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_warehouse LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();


        while($row = $query->fetch_array())
        {
            if($row[4] == 0)
            {
                $state = "<span class='badge bg-success'>Habilitado</span>";
            }
            else
            {
                $state = "<span class='badge bg-danger'>Deshabilitado</span>";
            }

            $subdata = array();
            $subdata[] = $row[2];//Descripción del almacén
            $subdata[] = $row[3];//Fecha de registro
            $subdata[] = $state; //Estado del almacén
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='cpanel?warehouse=".$row[1]."'><i class='fas fa-grip-horizontal'></i></a>
                             
            </div>";

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);

    }

    //Realiza la consulta de analisis de costos
    function SearchAnalisysLast($value)
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT * FROM analysis_data WHERE fk_warehouse_analysis = '".$value."'";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT * FROM analysis_data WHERE fk_warehouse_analysis = '".$value."'";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "num_analysis_data LIKE '".$resquest['search']['value']."%'";
            $sql.="OR concept_analysis_data LIKE '".$resquest['search']['value']."%'";
        }

        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();

            $subdata[] = $row[4]."".$row[5];
            $subdata[] = $row[2];
            $subdata[] = $row[6];
            $subdata[] = "$".number_format($row[7]);
            $subdata[] = "<div class='btn-group'>
			<a class='btn btn-default btn-sm' title='Ver Formato' href='../report/AnalisysData?Analisys=".$row[1]."'target='_blank'><i class='fas fa-eye'></i></a>                    
            </div>";

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsToral"       => intval($totalData),
            "recordsFiltered"    => intval($totalFilter),
            "data"               => $data
         );

         return json_encode($json_data);
    }
    //Realiza consulta de almacenes para registrar analisis de costos
    function SearchwarehouseAnalisys()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT * FROM warehouse";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        //Search

        $sql = "SELECT * FROM warehouse ";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_warehouse LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();


        while($row = $query->fetch_array())
        {
            if($row[4] == 0)
            {
                $state = "<span class='badge bg-success'>Habilitado</span>";
            }
            else
            {
                $state = "<span class='badge bg-danger'>Deshabilitado</span>";
            }

            $subdata = array();
            $subdata[] = $row[2];//Descripción del almacén
            $subdata[] = $row[3];//Fecha de registro
            $subdata[] = $state; //Estado del almacén
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Seleccionar' href='analysis?warehouse=".$row[1]."'><i class='fas fa-edit'></i></a>
                             
            </div>";

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);

    }

    //Consulta concepto de mano de obra
    function SearchManConcept()
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT id_workforce, token_workforce, name_workforce, cost_hour_workforce FROM workforce_analysis");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_workforce, $token_workforce, $name_workforce, $cost_workforce);

            $table = " <table id='id_table_concept' class='display' style='width: 100%;'>
                <thead>
                  <tr style='text-align: center;'>
                    <th>Concepto</th>
                    <th>Valor</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody style='text-align: center;'> ";

            while($stmt->fetch())
            {
                $table.= "<tr>
                <td>".$name_workforce."</td>
                <td>$ ".number_format($cost_workforce)."</td>
                <td style='text-align: center;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-deleteuno".$id_workforce."'>Editar</td>
            </tr> 
            
            <div class='modal fade' id='modal-deleteuno".$id_workforce."'>
						<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
							<h4 class='modal-title'>Editar concepto</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>
							<div class='modal-body'>
                            <form action='".$_SERVER['PHP_SELF']."' method='POST'>
							<p>¿Desea editar este concepto? <br><br>		
                            <input type='hidden' class='form-control' name='tkconcept' value='".$token_workforce."'>				
							<b>Descripción: </b><input type='text' class='form-control' name='concept' value='".$name_workforce."'>
                            
							<b>Valor por hora: </b><input type='text' class='form-control' name='value' value='".$cost_workforce."'>
                            
							</div>
							<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                            <button type='submit' name='btnupdateconcept' class='btn btn-success' >Aceptar</button>
                            </form>													
							</div>
						</div>
						<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>";


            }

            $table.="</tbody>

              </table>
              
              ";


        }
        else
        {
            $table = " <table id='id_table_concept' class='display' style='width: 100%;'>
                <thead>
                  <tr style='text-align: center;'>
                    <th>Concepto</th>
                    <th>Valor</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody style='text-align: center;'>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>           
                  
                </tbody>

              </table>";

        }

        return $table;
    }

    //Actualiza los datos del concepto
    function UpdateConceptMan($concept, $value, $tkconcept)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE workforce_analysis SET name_workforce = ?, cost_hour_workforce	= ? WHERE token_workforce = ?");
        $stmt->bind_param('sis', $concept, $value, $tkconcept);
        $stmt->execute();
        $stmt->close();

    }

    //Realiza la consulta del tipo de activo
    function SearchActive()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT * FROM type_active";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $data = array();


        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[2]; //Nombre del tipo de activo
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-danger btn-sm' title='Editar' href='../functions/DeleteActive?active=".$row[1]."'>Eliminar</a>
                             
            </div>";

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data,
        );

        return json_encode($json_data);


    }

    //Realiza consulta editada
    function getValueMtto($campo, $table, $campowhere, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT $campo FROM $table WHERE $campowhere = ? LIMIT 1");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($_campo);
            $stmt->fetch();
            return $_campo;
        }
        else
        {
            return null;
        }

    }

    //Opciones de activos
    function OptionActive()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_type_active, name_active_type FROM type_active WHERE state_active_type = 0");
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($id_type_active, $name_active_type);

        while($stmt->fetch())
        {
            echo "<option value=".$id_type_active.">".$name_active_type."</option>";
        }

    }

    //Opciones de activos registrados
    function OptionActiveA($value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_spares, concept_warehouse, num_concept_warehouse, description_element_spares, unity_spares, stock_spares FROM spares_parts WHERE warehouse_reference_spares = ?");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($idspares, $concept, $num, $description, $unity, $stock);

        while($stmt->fetch())
        {
            $ref = $concept."-".$num;

            echo "<option value=".$idspares.">".$ref." (".$description.") (".$unity.") Stock: ".$stock."</option>";
        }
    }

    //Opciones de mano de obra.
    function OptionWorforce()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_workforce, name_workforce, cost_hour_workforce FROM workforce_analysis WHERE state_workforce = 0");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_work, $name_work, $cost_work);

        while($stmt->fetch())
        {
            echo "<option value=".$id_work.">".$name_work."- $ ".$cost_work."</option>";
        }
    }

    //Opciones de almacenes
    function OptionWarehouses()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_warehouse, description_warehouse FROM warehouse WHERE state_warehouse = 0");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_warehouse, $description_warehouse);

        while($stmt->fetch())
        {
            echo "<option value=".$id_warehouse.">".$description_warehouse."</option>";
        }

    }

    //Opciones de Unidades RSU
    function OptionTeamsRSU($id_rsu)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_teams_units, letter_units_teams, number_teams_units, name_teams_units, mark_teams_units FROM teams_units_rsu WHERE fk_id_father_teams_units = ?");
        $stmt->bind_param('i', $id_rsu);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_teams, $letter_teams, $number_teams, $name_teams_units, $mark_teams);

        $opt = "<option value=''>Seleccione equipo o maquinaria</option>";

        while($stmt->fetch())
        {
            $opt.="<option value=".$id_teams.">| REF: ".$letter_teams."-".$number_teams." | NOMBRE: ".$name_teams_units." | MARCA: ".$mark_teams." |</option>";
        }

        return $opt;
    }

    //Valida los datos del activo
    function IsNullActive($facturer, $serie, $unity, $nameactive, $model, $alarm, $unityvalue, $typeactive)
    {
        if(strlen(trim($facturer)) < 1 ||
        strlen(trim($serie)) < 1 ||
        strlen(trim($unity)) < 1 ||
        strlen(trim($nameactive)) < 1 ||
        strlen(trim($model)) < 1 ||
        strlen(trim($alarm)) < 0 ||
        strlen(trim($unityvalue)) < 1 ||
        strlen(trim($typeactive)) == null)
        {
            return true;
            }else{
            return false;
        }
    }

    //Registra el activo según su almacén
    function RegisterActivesWarehouse($token, $datereg, $simbol, $num, $id_warehouse, $nameactive, $typeactive, $unity, $unityvalue, $facturer, $model, $serie, $alarm, $stock)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO spares_parts (token_spares, date_register_spares, concept_warehouse, num_concept_warehouse, 
        warehouse_reference_spares, description_element_spares, type_element_spares, unity_spares, 
        unity_value_spares, maker_spares, model_spares, serie_spares, alarm_spares_stock, stock_spares) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssiisisisssii', $token, $datereg, $simbol, $num, $id_warehouse, $nameactive, $typeactive, $unity, $unityvalue, $facturer, $model, $serie, $alarm, $stock);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Consulta activos según el almacén
    function SearchActivesWarehouse($ware)
    {
        global $mysqli;


        $resquest = $_REQUEST;

        $sql = "SELECT * FROM spares_parts INNER JOIN type_active ON spares_parts.type_element_spares = type_active.id_type_active WHERE warehouse_reference_spares = '".$ware."'";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        //Search

        $sql = "SELECT * FROM spares_parts INNER JOIN type_active ON spares_parts.type_element_spares = type_active.id_type_active WHERE warehouse_reference_spares = '".$ware."'";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_element_spares LIKE '".$resquest['search']['value']."%'";
            $sql.= "OR maker_spares LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();


        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[3]."-".$row[4];//Número del registro
            $subdata[] = $row[6];//Descripción del activo
            $subdata[] = $row[19];//Tipo de activo
            $subdata[] = $row[8];//Unidad del activo
            $subdata[] = $row[14];//Alarma de requisición
            $subdata[] = "$ ".number_format($row[10]);//Valor unitario
            $subdata[] = $row[11];//Fabricante activo
            $subdata[] = $row[12];//Modelo activo
            $subdata[] = $row[13];//Serie activo
            $subdata[] = $row[15];//stock activo

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);
    }

    //Consulta tres tablas y el formulario para completar el analisis de costos
    function SearchAnalysisCosts($value)
    {
        global $mysqli;

        $mtto = new mtto();

        $tokenw = $mtto->getValueMtto('token_warehouse', 'warehouse', 'id_warehouse', $value);
        $description = $mtto->getValueMtto('description_warehouse', 'warehouse', 'id_warehouse', $value);
        $letters = $mtto->LetterWarehouse($description);//
        $op = "AN";
        $letter = $op."-".$letters;
        $number = $mtto->AfterNumAnalisys($value);//
        $date = $mtto->DatetoDay();

        $top = $letter."".$number;

        $form = "";

        $form = "

            <table class='table table-bordered'>
            <tr style='background-color: #F0F3F4 ;'>
                <td style='width:500px;'><div class='input-group'>
                <div class='input-group-prepend'>
                            <div class='input-group-text'>
                            <span class='fas fa-warehouse'></span>
                            </div>
                        </div>
                    <input style='background-color: #FCF3CF' type='text' class='form-control' value='".$description."' disabled>
                        
                </td>

                <td><div class='input-group'>
                <div class='input-group-prepend'>
                            <div class='input-group-text'>
                            <span class='fas fa-calendar-alt'></span>
                            </div>
                        </div>
                    <input style='background-color: #FCF3CF' type='text' class='form-control' value='".$date."' disabled>
                        
                </td>
                

                <td>
                <div class='input-group'>
                <div class='input-group-prepend'>
                        <div class='input-group-text'>
                        <span class='fas fa-paste'></span>
                        </div>
                    </div>
                    <input style='background-color: #FCF3CF' type='text' class='form-control' value='".$top."' disabled>
                    
                </td>
            </tr>
            </table>

        
        ";



        //CONSULTA DEL PERSONAL

        $subsuma = 0;
        $subsumas = 0;
        $subsum = 0;
        $nums = 0;
        $nus = 0;

        $stmtp = $mysqli->prepare("SELECT id_person, token_person, fk_id_workforce, unit_measure, 
        cant_hours_person, unity_value_person, partial_value_person FROM person_analysis WHERE fk_warehouse_person_analysis = ? AND state_person_analysis = 0");

        $stmtp->bind_param('i', $value);
        $stmtp->execute();
        $stmtp->store_result();
        $nump = $stmtp->num_rows;

        //CONSULTA DE REPUESTO O MAQUINARIA


        $stmtr = $mysqli->prepare("SELECT id_spare_parts, token_spare_parts, fk_id_spare_parts, reference_warehouse_spares, unity_measure_spare_parts, cant_spare_analysis, 
        unity_value_spare_analysis, partial_value_spare_analysis, spare_id_analysis FROM spare_parts_analysis
        WHERE fk_warehouse_spare_analysis = ? AND state_spare_analysis = 0");

        $stmtr->bind_param('i', $value);
        $stmtr->execute();
        $stmtr->store_result();
        $numr = $stmtr->num_rows;

        //CONSULTA DE GASTOS NO PREVISTOS

        $stmtg = $mysqli->prepare("SELECT id_expected_analysis, token_expected_analysis, description_expected_analysis, unity_expected_analysis, cant_expected_analysis,
        unity_value_expected_analysis, partial_value_expected_analysis FROM expected_analysis WHERE fk_warehouse_expected_analysis = ? AND state_expected_analysis = 0");

        $stmtg->bind_param('i', $value);
        $stmtg->execute();
        $stmtg->store_result();
        $numg = $stmtg->num_rows;

        if($nump || $numr || $numg > 0)
        {

            if($nump > 0)
            {
                $tableper = "person_analysis";
                $condip = "token_person";
                $stmtp->bind_result($idperson, $tokenper, $namework, $unitmea, $canthours, $unityvaleperson, $partialvalueper);

                $form.= "
              
                <div class='row'>
                <div class='col-12 table-responsive'>
                <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaultone'><i class='fas fa-plus'></i></button></td>
                    <th colspan='6' style='background-color: #F0F3F4 ; color: black;'>1. PERSONAL</th>                
                    </tr>
                    <tr style='background-color: #F7F9F9;'>
                        <th>Acción</th>
                        <th>Item</th>
                        <th>Cargo del empleado</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad / horas</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody style='text-align: center;'>
                    ";

                    while($stmtp->fetch())
                    {
                        $nums = $nums + 1;

                        $subsuma +=$partialvalueper;

                            $form.= "<tr>
                            <td style='text-align: center;'><button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#modal-deleteuno".$idperson."'><i class='fas fa-trash-alt'></i></button></td>
                            <td>".$nums."</td>
                            <td>".$namework."</td>
                            <td>".$unitmea."</td>
                            <td>".$canthours."</td>
                            <td>$ ".number_format($unityvaleperson)."</td>
                            <td style='text-align: center; '><input type='text' class='form-control form-control-sm' style='text-align: right; background-color: #D6EAF8; font-weight: bold;' value='$ ".number_format($partialvalueper)."' disabled=disabled></td>
                        </tr>
                        
                        <div class='modal fade' id='modal-deleteuno".$idperson."'>
						<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
							<h4 class='modal-title'>Confirmación de eliminación</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>
							<div class='modal-body'>
							<p>¿Desea confirma la eliminación del concepto? <br><br>						
							<b>Descripción: </b> ".$namework."<br>
							<b>Cantidad de horas: </b> ".$canthours."<br>
							<b>Valor unitario: </b>$ ".number_format($unityvaleperson)."</b></p>
							</div>
							<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
							<a class='btn btn-success' href='../functions/Delete/Deleteconceptsanalisys?tk=".$tokenper."&tab=".$tableper."&cond=".$condip."&tkw=".$value."'>Aceptar</a>							
							</div>
						</div>
						<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>";

                    }
                    $form.= "
                            </tbody>
                            <tr>
                            <td colspan='5' style='background-color: #F0F3F4 ;'></td>
                            

                            <th style='text-align: center; background-color: #F0F3F4 ; color: black;'>Sub-Total</th>
                            <td style='text-align: center; background-color: #F0F3F4 ;'><input type='text' class='form-control form-control-sm' style='text-align: right; background-color: #D5F5E3; font-weight: bold;' value='$ ".number_format($subsuma)."' disabled=disabled></td>
                        </tr>
                        

                        </table>
                        
                        
                        
                    </div>
                
                </div>
                
                ";
            }
            else
            {
                $form.="<div class='row'>
                <div class='col-12 table-responsive'>
                <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaultone'><i class='fas fa-plus'></i></button></td>

                    <th colspan='6' style='background-color: #F0F3F4; color: black;'>1. PERSONAL</th>                
                    </tr>
                    <tr style='background-color: #F7F9F9'>
                        <th>Item</th>
                        <th>Cargo del empleado</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad / horas</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan='6' style='color: #B2BABB; text-align:center; font-weight: bold; '>NO EXISTEN DATOS REGISTRADOS</td>
                        
                    </tr>                  
                
                
                </tbody>
                </table>
                </div>
                </div>";
            }

            if($numr > 0)
            {
                $tablepart = "spare_parts_analysis";
                $condir = "token_spare_parts";
                $stmtr->bind_result($idspare, $tokenpart, $description, $reference, $unitymesaure, $cantspare, $unityvaluespare, $partialvaluer, $spareid);

                $form.="<div class='row'>
                <div class='col-12 table-responsive'>
                <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaulttwo'><i class='fas fa-plus'></i></button></td>
                    <th colspan='6' style='background-color: #F0F3F4; color: black;'>2. REPUESTOS DE MAQUINARIA, EQUIPOS O HERRAMIENTAS</th>                
                    </tr>
                    <tr style='background-color: #F7F9F9'>
                        <th>Acción</th>
                        <th>Referencia almacén</th>
                        <th>Descripción</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody style='text-align: center;'>";

                    while($stmtr->fetch())
                    {
                        $subsum +=$partialvaluer;

                        $form.= "<tr>
                        <td style='text-align: center;'><button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#modal-deletedos".$idspare."'><i class='fas fa-trash-alt'></i></button></td>
                        <td>".$reference."</td>
                        <td>".$description."</td>
                        <td>".$unitymesaure."</td>
                        <td>".$cantspare."</td>
                        <td>$ ".number_format($unityvaluespare)."</td>
                        <td style='text-align: center;'><input type='text' class='form-control form-control-sm' style='text-align: right; background-color: #D6EAF8; font-weight: bold;' value='$ ".number_format($partialvaluer)."' disabled=disabled></td>
                    </tr>
                    
                    <div class='modal fade' id='modal-deletedos".$idspare."'>
						<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
							<h4 class='modal-title'>Confirmación de eliminación</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>
							<div class='modal-body'>
							<p>¿Desea confirma la eliminación del concepto? <br><br>										
							<b>Descripción: </b>".$description."<br>
							<b>Referencia: </b>".$reference."<br>
                            <b>Cantidad: </b>".$cantspare."
							</div>
							<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
							<a class='btn btn-success' href='../functions/Delete/DeleteSpareAnalysis?tk=".$tokenpart."&tab=".$tablepart."&cond=".$condir."&tkw=".$value."&cant=".$cantspare."&idspare=".$spareid."'>Aceptar</a>							
							</div>
						</div>
						<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>";


                    }

                    $form.="               
                    
                        </tbody>
                        <tr>
                            <td colspan='5' style='background-color: #F0F3F4;'></td>
                            <th style='text-align: center; background-color: #F0F3F4; color: black;'>Sub-Total</th>
                            <td style='text-align: center; background-color: #F0F3F4'><input type='text' class='form-control form-control-sm' style='text-align: right; background-color: #D5F5E3; font-weight: bold;' value='$ ".number_format($subsum)."' disabled=disabled></td>
                        </tr>
                    </table>
                    </div>
                

                
                </div>";
            }
            else
            {
                $form.="  <div class='row'>
                <div class='col-12 table-responsive'>
                  <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaulttwo'><i class='fas fa-plus'></i></button></td>

                    <th colspan='6' style='background-color: #F0F3F4; color: black;'>2. REPUESTOS DE MAQUINARIA, EQUIPOS O HERRAMIENTAS</th>                
                      
                    </tr>
                    <tr style='background-color: #F7F9F9'>
                        <th>Referencia almacén</th>
                        <th>Descripción</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan='6' style='color: #B2BABB; text-align:center; font-weight: bold; '>NO EXISTEN DATOS REGISTRADOS</td>
                        
                    </tr>                  
                  
                  
                  </tbody>
                </table>
              </div>
              </div>";
            }

            if($numg > 0)
            {
                $tableexp = "expected_analysis";
                $condicg = "token_expected_analysis";

                $stmtg->bind_result($idexpect, $tokenexpect, $descriptionexp, $unityexp, $cantexp, $unityvalueexp, $partialvalueexp);

                $form.= "
                <div class='row'>
                    <div class='col-12 table-responsive'>
                    <table class='table table-bordered'>
                        <thead style='text-align: center;'>
                        <tr>
                        <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaultthree'><i class='fas fa-plus'></i></button></td>
                        <th colspan='6' style='background-color: #F0F3F4; color: black;'>3. COSTOS NO PREVISTOS</th>
                                    
                        </tr>
                        <tr style='background-color: #F7F9F9'>
                            <th>Acción</th>
                            <th>Item</th>
                            <th>Descripción</th>
                            <th>Unidad de medida</th>
                            <th>Cantidad</th>
                            <th>Valor unitario</th>
                            <th>Valor parcial</th>
                        </tr>
                        </thead>
                        <tbody style='text-align: center;'>
                ";

                while($stmtg->fetch())
                {
                    $nus = $nus +1;
                    $subsumas +=$partialvalueexp;

                    $form.= "<tr>
                    <td style='text-align: center;'><button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#modal-deletetres".$idexpect."'><i class='fas fa-trash-alt'></i></button></td>
                        <td>".$nus."</td>
                        <td>".$descriptionexp."</td>
                        <td>".$unityexp."</td>
                        <td>".$cantexp."</td>
                        <td>$ ".number_format($unityvalueexp)."</td>
                        <td style='text-align: center;'><input type='text' class='form-control form-control-sm' style='text-align: right; background-color: #D6EAF8; font-weight: bold;' value='$ ".number_format($partialvalueexp)."' disabled=disabled></td>
                    </tr>

                    <div class='modal fade' id='modal-deletetres".$idexpect."'>
						<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
							<h4 class='modal-title'>Confirmación de eliminación</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>
							<div class='modal-body'>
							<p>¿Desea confirma la eliminación de la compra? <br><br>						
							<b>Descripción: </b> ".$descriptionexp." <br>
							<b>Cantidad: </b>".$cantexp."<br>
							<b>Valor: </b>$".number_format($unityvalueexp)."</p>
							</div>
							<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
							<a class='btn btn-success' href='../functions/Delete/Deleteconceptsanalisys?tk=".$tokenexpect."&tab=".$tableexp."&cond=".$condicg."&tkw=".$value."'>Aceptar</a>							
							</div>
						</div>
						<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
                    
                    ";
                }
                                $form.= "
                                </tbody>
                                <tr>
                                <td colspan='5' style='background-color: #F0F3F4;'></td>
                                <th style='text-align: center; background-color: #F0F3F4; color: black;'>Sub-Total</th>
                                <td style='text-align: center; background-color: #F0F3F4;'><input type='text' class='form-control form-control-sm' style='text-align: right; background-color: #D5F5E3; font-weight: bold;' value='$ ".number_format($subsumas)."' disabled=disabled></td>
                            </tr>
                            </table>
                    </div>
                    

                    
                </div>

                    ";

            }
            else
            {
                $form.="<div class='row'>
                <div class='col-12 table-responsive'>
                <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaultthree'><i class='fas fa-plus'></i></button></td>

                    <th colspan='6' style='background-color: #F0F3F4; color: black;'>3. COSTOS NO PREVISTOS</th>                
                    </tr>
                    <tr style='background-color: #F7F9F9'>
                        <th>Item</th>
                        <th>Descripición</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan='6' style='color: #B2BABB; text-align:center; font-weight: bold; '>NO EXISTEN DATOS REGISTRADOS</td>
                        
                    </tr>              
                
                
                </tbody>
                </table>
                </div>
                </div>";
            }

            $total = $subsuma + $subsumas + $subsum;

            $form.= "

                        <div class='row no-print'>
                        <div class='col-12'>
                        
                        <button type='button' class='btn btn-success float-right' data-toggle='modal' data-target='#modal-confirm'><i class='fas fa-edit'></i>
                        Generar Analisis de Costos
                        </button>
                        
                        </div>
                    </div>

                    <div class='modal fade' id='modal-confirm'>
                    <form action='../functions/Register/InsertAnalisys?warehouse=".$tokenw."' method='POST'>
						<div class='modal-dialog modal-lg'>
                        <input type='hidden' name='totalvalue' value='".$total."'>
						<div class='modal-content'>
							<div class='modal-header'>
							<h4 class='modal-title'>Confirmación de registro</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>
							<div class='modal-body'>
							<p>¿Desea continuar con el registro? <br><br>	

                            <div class='col-12'>					
							<b>Descripción:</b>  <input type='text' name='description' class='form-control'><br>
                            </div>

                            <div class='col-3'>
							<b>Costo Total:</b> <input type='text' value='$".number_format($total)."' class='form-control' style='text-align: left; background-color: #D5F5E3; font-weight: bold;' readonly=readonly><br>
                            </div>
							
							</div>
							<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
							<button type='submit' class='btn btn-success' name='btnregister'>Aceptar</button>							
							</div>
						</div>
						
						</div>
						</form>
					</div>
                    ";
        }
        else
        {
            $form.="
                <div class='row'>
                <div class='col-12 table-responsive'>
                <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaultone'><i class='fas fa-plus'></i></button></td>

                    <th colspan='6' style='background-color: #F0F3F4; color: black;'>1. PERSONAL</th>                
                    </tr>
                    <tr style='background-color: #F7F9F9'>
                        <th>Item</th>
                        <th>Cargo del empleado</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad / horas</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan='6' style='color: #B2BABB; text-align:center; font-weight: bold; '>NO EXISTEN DATOS REGISTRADOS</td>
                        
                    </tr>                  
                
                
                </tbody>
                </table>
                </div>
                </div>


                <div class='row'>
                <div class='col-12 table-responsive'>
                  <table class='table table-bordered'>
                    <thead style='text-align: center;'>
                    <tr>
                    <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaulttwo'><i class='fas fa-plus'></i></button></td>

                    <th colspan='6' style='background-color: #F0F3F4; color: black;'>2. REPUESTOS DE MAQUINARIA, EQUIPOS O HERRAMIENTAS</th>                
                      
                    </tr>
                    <tr style='background-color: #F7F9F9'>
                        <th>Referencia almacén</th>
                        <th>Descripción</th>
                        <th>Unidad de medida</th>
                        <th>Cantidad</th>
                        <th>Valor unitario</th>
                        <th>Valor parcial</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan='6' style='color: #B2BABB; text-align:center; font-weight: bold; '>NO EXISTEN DATOS REGISTRADOS</td>
                        
                    </tr>                  
                  
                  
                  </tbody>
                </table>
              </div>
              </div> 


              <div class='row'>
              <div class='col-12 table-responsive'>
              <table class='table table-bordered'>
                  <thead style='text-align: center;'>
                  <tr>
                  <td style='background-color: #F0F3F4; text-align: left;'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#modal-defaultthree'><i class='fas fa-plus'></i></button></td>

                  <th colspan='6' style='background-color: #F0F3F4; color: black;'>3. COSTOS NO PREVISTOS</th>                
                  </tr>
                  <tr style='background-color: #F7F9F9'>
                      <th>Item</th>
                      <th>Descripición</th>
                      <th>Unidad de medida</th>
                      <th>Cantidad</th>
                      <th>Valor unitario</th>
                      <th>Valor parcial</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <td colspan='6' style='color: #B2BABB; text-align:center; font-weight: bold; '>NO EXISTEN DATOS REGISTRADOS</td>
                      
                  </tr>              
              
              
              </tbody>
              </table>
              </div>
              </div>         
          
          ";
        }
        return $form;

    }

    //Valida un valor
    function ValideValue($value)
    {
        $valide = strlen(trim($value));

        return $valide;
    }

    //Registra el persona en analisis de costos
    function InsertPerson($token, $id_warehouse, $descriptionwork, $hours, $cost, $unity, $total, $state)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO person_analysis (token_person, fk_id_workforce, fk_warehouse_person_analysis, unit_measure, 
        cant_hours_person, unity_value_person, partial_value_person, state_person_analysis) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssisiiii', $token, $descriptionwork, $id_warehouse, $unity, $hours, $cost, $total, $state);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Registra el activo en el analisis de costos
    function InsertSpare($actives, $token, $description, $id_warehouse, $reference, $unityspares, $cant, $unityvalue, $partial, $state)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO spare_parts_analysis (spare_id_analysis, token_spare_parts, fk_id_spare_parts, fk_warehouse_spare_analysis, reference_warehouse_spares,
        unity_measure_spare_parts, cant_spare_analysis, unity_value_spare_analysis, partial_value_spare_analysis, state_spare_analysis) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ississiiii',$actives, $token, $description, $id_warehouse, $reference, $unityspares, $cant, $unityvalue, $partial, $state);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Registrar costos indirectos en el analisis de costos
    function InsertExpect($token, $id_warehouse, $description, $unitymed, $quantity, $unityprice, $totalpartial, $state)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO expected_analysis (token_expected_analysis, fk_warehouse_expected_analysis, description_expected_analysis, unity_expected_analysis,
        cant_expected_analysis, unity_value_expected_analysis, partial_value_expected_analysis, state_expected_analysis) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sissiiii', $token, $id_warehouse, $description, $unitymed, $quantity, $unityprice, $totalpartial, $state);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Eliminar los conceptos del analisis de costos.
    function DeleteConcept($table, $condition, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("DELETE FROM $table WHERE $condition = ?");
        $stmt->bind_param('s', $value);

        if($stmt->execute())
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}

    }

    //Devuelve el ultimo número del analisis de costos
    function AfterNumAnalisys($value)
    {
        global $mysqli;

        $top = 0;

        $stmt = $mysqli->prepare("SELECT num_analysis_data FROM analysis_data WHERE fk_warehouse_analysis = ? ORDER BY 	id_analysis_data DESC LIMIT 1");

        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($num_ware);
        $stmt->fetch();

        $top = $num_ware + 1;

        return $top;
    }

    //Inserta el analisis de costos
    function InsertAnalisysData($token, $date, $id_warehouse, $letter, $number, $description, $totalvalue, $state)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO analysis_data (token_analysis_data, dateregister_analysis, fk_warehouse_analysis, 
        letter_analysis_warehouse, num_analysis_data, concept_analysis_data, total_analysis_data, state_assign_analysis_data) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssisisii', $token, $date, $id_warehouse, $letter, $number, $description, $totalvalue, $state);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Actualiza los estado de los activos ya registrados
    function UpdateStateAnalysis($table, $column1, $value, $column2, $condition1, $value2, $condition2)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE $table SET $column1 = ?, $column2 = 1 WHERE $condition1 = ? AND $condition2 = 0");
        $stmt->bind_param('ii', $value, $value2);
        $stmt->execute();
        $stmt->close();

    }

    // APARTADO DE REPORTE (Analísis de costos)

    //Consulta la tabla 1 del análisis de costos
    function ReportPersonAnalysis($value)
    {
        global $mysqli;

        $mtto = new mtto();

        $tablep = "";

        $nums = 0;
        $subsuma = 0;

        $id_analisys = $mtto->getValueMtto('id_analysis_data','analysis_data','token_analysis_data',$value);

        $stmt = $mysqli->prepare("SELECT fk_id_workforce, unit_measure, cant_hours_person, unity_value_person, partial_value_person FROM person_analysis WHERE fk_analysis_data_person = ?");
        $stmt->bind_param('i', $id_analisys);

        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {

            $stmt->bind_result($namework, $unitmea, $canthours, $unityvaleperson, $partialvalueper);

            $tablep = "<table style='border: 1px solid black; margin-top: 10px;'>

        
            <tr> 
                <th colspan='6' style='border: 1px solid black; text-align: center; background-color: #F2DCDB;'>1. PERSONAL</th>            
            </tr>
            <tr style='font-size: 11px; text-align: center; background-color:#F2DCDB;'>
                <th style='border: 1px solid black; width: 45px;'>ÍTEM</th>
                <th style='border: 1px solid black; width: 190px;'>CARGO DEL EMPLEADO</th>
                <th style='border: 1px solid black; width: 100px;'>UNIDAD DE MEDIDA</th>
                <th style='border: 1px solid black; width: 80px;'>CANTIDAD HORAS</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR UNITARIO</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR PARCIAL</th>
            </tr>
            ";

            while($stmt->fetch())
            {
                $nums = $nums + 1;
                $subsuma +=$partialvalueper;

                $tablep.= "<tr style='text-align: center; font-size: 10px;'>
                    <td style='border: 1px solid black; width: 45px; height: 20px;'>".$nums."</td>
                    <td style='border: 1px solid black; width: 190px; height: 20px;'>".$namework."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>".$unitmea."</td>
                    <td style='border: 1px solid black; width: 80px; height: 20px;'>".$canthours."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$".number_format($unityvaleperson)."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$".number_format($partialvalueper)."</td>
                </tr>";

            }

            $tablep.="
            
                    
            </table>  
            
            <table style='border: 1px solid black; margin-top: 10px;'>
                <tr style='font-size: 12px; text-align: center;'>
                    <th style='border: 1px solid black; width: 559px; text-align:right; background-color: #F2DCDB'>SUB TOTAL ACTIVIDAD</th>
                    <td style='border: 1px solid black; width: 100px; text-align: center;'>$".number_format($subsuma)."</td>
                </tr>
            </table>";
        }
        else
        {
            $tablep.= "<table style='border: 1px solid black; margin-top: 10px;'>

        
            <tr> 
                <th colspan='6' style='border: 1px solid black; text-align: center; background-color: #F2DCDB;'>1. PERSONAL</th>            
            </tr>
            <tr style='font-size: 11px; text-align: center; background-color: #F2DCDB;'>
                <th style='border: 1px solid black; width: 45px;'>ÍTEM</th>
                <th style='border: 1px solid black; width: 190px;'>CARGO DEL EMPLEADO</th>
                <th style='border: 1px solid black; width: 100px;'>UNIDAD DE MEDIDA</th>
                <th style='border: 1px solid black; width: 80px;'>CANTIDAD HORAS</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR UNITARIO</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR PARCIAL</th>
            </tr> 

            

            <tr style='text-align: center; font-size: 10px;'>
                <td style='border: 1px solid black; width: 45px; height: 20px;'></td>
                <td style='border: 1px solid black;  width: 190px; height: 20px;'></td>
                <td style='border: 1px solid black; width: 100px; height: 20px;'></td>
                <td style='border: 1px solid black; width: 80px; height: 20px;'></td>
                <td style='border: 1px solid black; width: 100px; height: 20px;'>$0</td>
                <td style='border: 1px solid black; width: 100px; height: 20px;'>$0</td>
            </tr>        
            
            
                    
            </table>  
            
            <table style='border: 1px solid black; margin-top: 10px;'>
                <tr style='font-size: 12px; text-align: center;'>
                    <th style='border: 1px solid black; width: 559px; text-align:right; background-color: #F2DCDB'>SUB TOTAL ACTIVIDAD</th>
                    <td style='border: 1px solid black; width: 100px; text-align: center;'>$".number_format($subsuma)."</td>
                </tr>
            </table>";
        }

        return $tablep;

    }

    //Consulta la tabla 2 del análisis de costos
    function ReportSpareAnalysis($value)
    {
        global $mysqli;

        $mtto = new mtto();

        $tablep = "";


        $subsuma = 0;

        $id_analisys = $mtto->getValueMtto('id_analysis_data','analysis_data','token_analysis_data',$value);

        $stmt = $mysqli->prepare("SELECT reference_warehouse_spares, fk_id_spare_parts, unity_measure_spare_parts, cant_spare_analysis, 
        unity_value_spare_analysis, partial_value_spare_analysis FROM spare_parts_analysis WHERE fk_analysis_data_spare = ?");
        $stmt->bind_param('i', $id_analisys);

        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($reference, $description, $unitymesaure, $cantspare, $unityvaluespare, $partialvaluer);

            $tablep = "<table style='border: 1px solid black; margin-top: 10px;'>
        
            <tr> 
                <th colspan='6' style='border: 1px solid black; text-align: center; background-color: #F2DCDB;'>2. REPUESTOS DE MAQUINARIA, EQUIPOS O HERRAMIENTAS</th>            
            </tr>
            <tr style='font-size: 11px; text-align: center; background-color: #F2DCDB;'>
                <th style='border: 1px solid black; width: 70px;'>REFERENCIA ALAMCÉN</th>
                <th style='border: 1px solid black; width: 190px;'>DESCRIPCIÓN</th>
                <th style='border: 1px solid black; width: 90px;'>UNIDAD DE MEDIDA</th>
                <th style='border: 1px solid black; width: 65px;'>CANTIDAD</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR UNITARIO</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR PARCIAL</th>
            </tr>";

            while($stmt->fetch())
            {
                $subsuma +=$partialvaluer;

                $tablep.= "
                <tr style='text-align: center; font-size: 10px;'>
                    <td style='border: 1px solid black; width: 70px; height: 20px;'>".$reference."</td>
                    <td style='border: 1px solid black; width: 190px; height: 20px;'>".$description."</td>
                    <td style='border: 1px solid black; width: 90px; height: 20px;'>".$unitymesaure."</td>
                    <td style='border: 1px solid black; width: 65px; height: 20px;'>".$cantspare."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$".number_format($unityvaluespare)."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$".number_format($partialvaluer)."</td>
                </tr> 
                ";
            }

            $tablep.= "</table>

            <table style='border: 1px solid black; margin-top: 10px;'>
            <tr style='font-size: 12px; text-align: center;'>
                <th style='border: 1px solid black; width: 559px; text-align: right; background-color: #F2DCDB'>SUB TOTAL ACTIVIDAD</th>
                <td style='border: 1px solid black; width: 100px; text-align: center;'>$".number_format($subsuma)."</td>
            </tr>
            </table>

        ";
        }
        else
        {
            $tablep.="
            <table style='border: 1px solid black; margin-top: 10px;'>
        
                <tr> 
                    <th colspan='6' style='border: 1px solid black; text-align: center; background-color: #F2DCDB;'>2. REPUESTOS DE MAQUINARIA, EQUIPOS O HERRAMIENTAS</th>            
                </tr>
                <tr style='font-size: 11px; text-align: center; background-color: #F2DCDB;'>
                    <th style='border: 1px solid black; width: 70px;'>REFERENCIA ALAMCÉN</th>
                    <th style='border: 1px solid black; width: 190px;'>DESCRIPCIÓN</th>
                    <th style='border: 1px solid black; width: 90px;'>UNIDAD DE MEDIDA</th>
                    <th style='border: 1px solid black; width: 65px;'>CANTIDAD</th>
                    <th style='border: 1px solid black; width: 100px;'>VALOR UNITARIO</th>
                    <th style='border: 1px solid black; width: 100px;'>VALOR PARCIAL</th>
                </tr>

                <tr style='text-align: center; font-size: 10px;'>
                    <td style='border: 1px solid black; width: 70px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 190px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 90px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 65px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$0</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$0</td>
                </tr> 

                </table>

            <table style='border: 1px solid black; margin-top: 10px;'>
            <tr style='font-size: 12px; text-align: center;'>
                <th style='border: 1px solid black; width: 559px; text-align:right; background-color: #F2DCDB'>SUB TOTAL ACTIVIDAD</th>
                <td style='border: 1px solid black; width: 100px; text-align: center;'>$".$subsuma."</td>
            </tr>
            </table>
            ";
        }

        return $tablep;
    }

    //Consulta la tabla 3 de análisis de costos
    function ReportExpectAnalysis($value)
    {
        global $mysqli;

        $mtto = new mtto();

        $tablep = "";

        $nums = 0;
        $subsuma = 0;

        $id_analisys = $mtto->getValueMtto('id_analysis_data','analysis_data','token_analysis_data',$value);

        $stmt = $mysqli->prepare("SELECT description_expected_analysis, unity_expected_analysis, cant_expected_analysis,
        unity_value_expected_analysis, partial_value_expected_analysis FROM expected_analysis WHERE fk_analysis_data_expected = ?");
        $stmt->bind_param('i', $id_analisys);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($descriptionexp, $unityexp, $cantexp, $unityvalueexp, $partialvalueexp);

            $tablep = "<table style='border: 1px solid black; margin-top: 10px;'>

        
            <tr> 
                <th colspan='6' style='border: 1px solid black; text-align: center; background-color: #F2DCDB;'>3. OTROS COSTOS NO PREVISTOS</th>            
            </tr>
            <tr style='font-size: 12px; text-align: center; background-color: #F2DCDB;'>
                <th style='border: 1px solid black; width: 45px;'>ÍTEM</th>
                <th style='border: 1px solid black; width: 190px;'>DESCRIPCIÓN</th>
                <th style='border: 1px solid black; width: 100px;'>UNIDAD</th>
                <th style='border: 1px solid black; width: 70px;'>CANTIDAD</th>
                <th style='border: 1px solid black; width: 110px;'>VALOR UNITARIO</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR PARCIAL</th>
            </tr>";

            while($stmt->fetch())
            {
                $nums = $nums + 1;
                $subsuma +=$partialvalueexp;
                $tablep.="
                <tr style='text-align: center; font-size: 10px;'>
                    <td style='border: 1px solid black; width: 45px; height: 20px;'>".$nums."</td>
                    <td style='border: 1px solid black; width: 190px; height: 20px;'>".$descriptionexp."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>".$unityexp."</td>
                    <td style='border: 1px solid black; width: 70px; height: 20px;'>".$cantexp."</td>
                    <td style='border: 1px solid black; width: 110px; height: 20px;'>$".number_format($unityvalueexp)."</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$".number_format($partialvalueexp)."</td>
                </tr> 
            
                ";
            }

            $tablep.= "

            </table>

            <table style='border: 1px solid black; margin-top: 10px;'>
                <tr style='font-size: 12px; text-align: center;'>
                    <th style='border: 1px solid black; width: 559px; text-align:right; background-color: #F2DCDB'>SUB TOTAL ACTIVIDAD</th>
                    <td style='border: 1px solid black; width: 100px; text-align: center;'>$".number_format($subsuma)."</td>
                </tr>
            </table>
            ";
        }
        else
        {
            $tablep.="<table style='border: 1px solid black; margin-top: 10px;'>

        
            <tr> 
                <th colspan='6' style='border: 1px solid #D5D8DC; text-align: center; background-color: #F2DCDB;'>3. OTROS COSTOS NO PREVISTOS</th>            
            </tr>
            <tr style='font-size: 12px; text-align: center; background-color: #F2DCDB;'>
                <th style='border: 1px solid black; width: 45px;'>ÍTEM</th>
                <th style='border: 1px solid black; width: 190px;'>DESCRIPCIÓN</th>
                <th style='border: 1px solid black; width: 100px;'>UNIDAD</th>
                <th style='border: 1px solid black; width: 70px;'>CANTIDAD</th>
                <th style='border: 1px solid black; width: 110px;'>VALOR UNITARIO</th>
                <th style='border: 1px solid black; width: 100px;'>VALOR PARCIAL</th>
            </tr>

            <tr style='text-align: center; font-size: 10px;'>
                    <td style='border: 1px solid black; width: 45px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 190px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 70px; height: 20px;'></td>
                    <td style='border: 1px solid black; width: 110px; height: 20px;'>$0</td>
                    <td style='border: 1px solid black; width: 100px; height: 20px;'>$0</td>
                </tr> 

                </table>

            <table style='border: 1px solid black; margin-top: 10px;'>
                <tr style='font-size: 12px; text-align: center;'>
                    <th style='border: 1px solid black; width: 559px; text-align:right; background-color: #F2DCDB'>SUB TOTAL ACTIVIDAD</th>
                    <td style='border: 1px solid black; width: 100px; text-align: center;'>$".number_format($subsuma)."</td>
                </tr>
            </table>


            ";

        }

        return $tablep;


    }

    //Consulta el total del análisis de costos
    function TotalAnalysis($value)
    {
        global $mysqli;

        $tablep = "";

        $stmt = $mysqli->prepare("SELECT total_analysis_data FROM analysis_data WHERE token_analysis_data = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total);
        $stmt->fetch();

        $tablep = "<table style='border: 1px solid black; margin-top: 10px;'>
        <tr style='font-size: 12px; text-align: center;'>
            <th style='border: 1px solid black; width: 559px; text-align:right; background-color: #F2DCDB'>TOTAL ACTIVIDAD MANTENIMIENTO</th>
            <td style='border: 1px solid black; width: 100px; text-align: center;'>$".number_format($total)."</td>
        </tr>
        </table>";

        return $tablep;

    }

    //Consulta el número de análisis de costos correspondiente
    function NumberAnalysis($value)
    {
        global $mysqli;

        $num = "";

        $stmt = $mysqli->prepare("SELECT letter_analysis_warehouse, num_analysis_data FROM analysis_data WHERE token_analysis_data = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($letter, $number);
        $stmt->fetch();

        $num="<table style='border: 1px solid black; margin-top: 10px;'>
        <tr>
            <td style='border: 1px solid white; width: 450px;'></td>
            <td style='border: 1px solid black; width: 120px; text-align: right; font-size: 12px; font-weight: bold; background-color: #F2DCDB;'>CONSECUTIVO DE COSTOS No.</td>
            <td style='border: 1px solid black; width: 75px; text-align: center;'>".$letter.$number."</td>

        </tr>

        </table>";

        return $num;

    }

    //Consulta la descripción del análisis de costos
    function DescriptionAnalysis($value)
    {
        global $mysqli;

        $desc = "";

        $stmt = $mysqli->prepare("SELECT concept_analysis_data FROM analysis_data WHERE token_analysis_data = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($concept);
        $stmt->fetch();

        $desc = "<table style='border: 1px solid black; margin-top: 10px;'>

        <tr>
            <td style='vertical-align: middle; border: 1px solid black; width: 190px; font-size: 12px; font-weight: bold; background-color: #F2DCDB;'>CONCEPTO ANÁLISIS DE COSTOS</td>
            <td style='border: 1px solid white; width: 465px;'>".$concept."</td>

        </tr>

        </table>";

        return $desc;
    }

    //APARTADO DE GESTIÓN ACTIVOS

    //Registra entrada de almacén
    function RegisterActiveEA($pay_ea, $date_ea, $id_warehouse, $active_ea, $cant_ea)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO input_actives (number_remission_input, date_input, fk_warehouse_input, fk_active_input, quantity_add) 
        VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssiii', $pay_ea, $date_ea, $id_warehouse, $active_ea, $cant_ea);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Registrar salida de almacén
    function RegisterActiveSA($pay_sa, $date_sa, $id_warehouse, $active_sa, $cant_sa)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO output_actives (number_remission_output, date_output, fk_warehouse_output, fk_active_output, quantity_remove)
        VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssiii', $pay_sa, $date_sa, $id_warehouse, $active_sa, $cant_sa);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Realiza la actualización de Stock aumentando n de cantidad
    function SumStockActive($active_ea, $cant_ea)
    {
        $sub = 0;

        global $mysqli;

        $mysqli->begin_transaction();
        $consult = $mysqli->prepare("SELECT stock_spares FROM spares_parts WHERE id_spares = ?");
        $consult->bind_param('i', $active_ea);
        $mysqli->commit();
        $consult->execute();
        $consult->store_result();
        $consult->bind_result($stock);
        $consult->fetch();

        $sub = $stock + $cant_ea;

        $mysqli->begin_transaction();
        $stmt = $mysqli->prepare("UPDATE spares_parts SET stock_spares = ? WHERE id_spares = ?");
        $stmt->bind_param('ii', $sub, $active_ea);
        $mysqli->commit();
        $stmt->execute();
        $stmt->close();
    }

    //Realiza la actualización de stock restando n de cantidad
    function SubtractStockActive($active_sa, $cant_sa)
    {
        $sub = 0;

        $message = "";

        global $mysqli;

        $mysqli->begin_transaction();
        $consult = $mysqli->prepare("SELECT stock_spares FROM spares_parts WHERE id_spares = ?");
        $consult->bind_param('i', $active_sa);
        $mysqli->commit();
        $consult->execute();
        $consult->store_result();
        $consult->bind_result($stock);
        $consult->fetch();


        $sub = $stock - $cant_sa;

        $mysqli->begin_transaction();
        $stmt = $mysqli->prepare("UPDATE spares_parts SET stock_spares = ? WHERE id_spares = ?");
        $stmt->bind_param('ii', $sub, $active_sa);
        $mysqli->commit();
        $stmt->execute();
        $stmt->close();



    }

    //Consulta los registros de entrada almacén según el almacén
    function SearchActiveEA($warehouse)
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT number_remission_input, date_input, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_add 
        FROM input_actives INNER JOIN spares_parts ON input_actives.fk_active_input = spares_parts.id_spares WHERE fk_warehouse_input = '".$warehouse."'";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT number_remission_input, date_input, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_add 
        FROM input_actives INNER JOIN spares_parts ON input_actives.fk_active_input = spares_parts.id_spares WHERE fk_warehouse_input = '".$warehouse."'";


        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_element_spares LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $row[2]."-".$row[3];
            $subdata[] = $row[4];
            $subdata[] = $row[5];

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);

    }

    //Consulta los registros de salida almacén según el almacén
    function SearchActiveSA($warehouse)
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT number_remission_output, date_output, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_remove 
        FROM output_actives INNER JOIN spares_parts ON output_actives.fk_active_output = spares_parts.id_spares WHERE fk_warehouse_output = '".$warehouse."'";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT number_remission_output, date_output, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_remove 
        FROM output_actives INNER JOIN spares_parts ON output_actives.fk_active_output = spares_parts.id_spares WHERE fk_warehouse_output = '".$warehouse."'";


        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_element_spares LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $row[2]."-".$row[3];
            $subdata[] = $row[4];
            $subdata[] = $row[5];

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);

    }

    //Muestra los widgets de cpanel
    function WidgetCpanel($warehouse)
    {
        $widget = "";

        global $mysqli;

        $stmt_one = $mysqli->prepare("SELECT COUNT(*) FROM spares_parts WHERE warehouse_reference_spares = ?");
        $stmt_one->bind_param('i',$warehouse);
        $stmt_one->execute();
        $stmt_one->store_result();
        $stmt_one->bind_result($countactives);
        $stmt_one->fetch();

        $stmt_two = $mysqli->prepare("SELECT COUNT(*) FROM output_actives WHERE fk_warehouse_output = ?");
        $stmt_two->bind_param('i', $warehouse);
        $stmt_two->execute();
        $stmt_two->store_result();
        $stmt_two->bind_result($countactivessa);
        $stmt_two->fetch();

        $stmt_three = $mysqli->prepare("SELECT COUNT(*) FROM input_actives WHERE fk_warehouse_input = ?");
        $stmt_three->bind_param('i', $warehouse);
        $stmt_three->execute();
        $stmt_three->store_result();
        $stmt_three->bind_result($countactivesea);
        $stmt_three->fetch();


        $widget = "<div class='row'>
        <div class='col-12 col-sm-6 col-md-4'>
          <div class='info-box'>
            <span class='info-box-icon bg-info elevation-1'><i class='fas fa-boxes'></i></span>

            <div class='info-box-content'>
              <span class='info-box-text'>ACTIVOS REGISTRADOS</span>
              <h3 class='info-box-number'>".$countactives."</h3>
            </div>
          
          </div>
          
        </div>
        
        <div class='col-12 col-sm-6 col-md-4'>
          <div class='info-box mb-3'>
            <span class='info-box-icon bg-danger elevation-1'><i class='fas fa-long-arrow-alt-down'></i></span>

            <div class='info-box-content'>
              <span class='info-box-text'>SALIDA ALMACÉN</span>
              <h3 class='info-box-number'>".$countactivessa."</h3>
            </div>
          
          </div>
     
        </div>          

          <div class='col-12 col-sm-6 col-md-4'>
            <div class='info-box mb-3'>
              <span class='info-box-icon bg-success elevation-1'><i class='fas fa-long-arrow-alt-up'></i></span>

              <div class='info-box-content'>
                <span class='info-box-text'>ENTRADA ALMACÉN</span>
                <h3 class='info-box-number'>".$countactivesea."</h3>
              </div>
            
            </div>
        
          </div>        
    
      
      </div>";


      return $widget;

    }


    //UNIDADES RSU - GESTIÓN

    //Registra Unidad RSU
    function InsertUnits($token_units, $date_units, $reference_units, $state_units, $costmant_units, $costnpt_units, $id_user)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO father_units_rsu (token_units_rsu, date_register_units, reference_units_rsu, state_units_rsu, costmaint_units_rsu, costnpt_units_rsu, fk_id_users_units_rsu) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssiii', $token_units, $date_units, $reference_units, $state_units, $costmant_units, $costnpt_units, $id_user);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Genera el listado de unidades RSU
    function SerahcUnits()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT date_register_units, reference_units_rsu, state_units_rsu, costmaint_units_rsu, costnpt_units_rsu, location_contract_units_rsu, client_contract_units_rsu, token_units_rsu FROM father_units_rsu INNER JOIN contract_units_rsu ON father_units_rsu.id_units_rsu = contract_units_rsu.fk_id_father_units_rsu";

        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT date_register_units, reference_units_rsu, state_units_rsu, costmaint_units_rsu, costnpt_units_rsu, location_contract_units_rsu, client_contract_units_rsu, token_units_rsu FROM father_units_rsu INNER JOIN contract_units_rsu ON father_units_rsu.id_units_rsu = contract_units_rsu.fk_id_father_units_rsu";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "reference_units_rsu LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[1];
            $subdata[] = $row[0];
            $subdata[] = $row[2];
            $subdata[] = $row[5];
            $subdata[] = $row[6];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Detalles' href='cpanelunits?units=".$row[7]."'><i class='fas fa-info-circle'></i></a>
                             
            </div>";

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);
    }

    function insertProcedure($data)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO procedures (title, date, objective, scope, definitions, position_1, number_workers_1, responsibilities_1, position_2, number_workers_2, responsibilities_2, recommendations, planning, monthly_maintenance, semi_annual_maintenance, maintenance_2_years, equipment_tools, records, confidentiality_note, version, change_reason) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssssssssssssssssss', $data['title'], $data['date'], $data['objective'], $data['scope'], $data['definitions'], $data['position_1'], $data['number_workers_1'], $data['responsibilities_1'], $data['position_2'], $data['number_workers_2'], $data['responsibilities_2'], $data['recommendations'], $data['planning'], $data['monthly_maintenance'], $data['semi_annual_maintenance'], $data['maintenance_2_years'], $data['equipment_tools'], $data['records'], $data['confidentiality_note'], $data['version'], $data['change_reason']);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
        }else{
            return 0;
        }
    }

    function updateProcedure($id, $data)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE procedures SET title=?, date=?, objective=?, scope=?, definitions=?, position_1=?, number_workers_1=?, responsibilities_1=?, position_2=?, number_workers_2=?, responsibilities_2=?, recommendations=?, planning=?, monthly_maintenance=?, semi_annual_maintenance=?, maintenance_2_years=?, equipment_tools=?, records=?, confidentiality_note=?, version=?, change_reason=? WHERE id_procedure=?");
        $stmt->bind_param('ssssssssssssssssssssss', $data['title'], $data['date'], $data['objective'], $data['scope'], $data['definitions'], $data['position_1'], $data['number_workers_1'], $data['responsibilities_1'], $data['position_2'], $data['number_workers_2'], $data['responsibilities_2'], $data['recommendations'], $data['planning'], $data['monthly_maintenance'], $data['semi_annual_maintenance'], $data['maintenance_2_years'], $data['equipment_tools'], $data['records'], $data['confidentiality_note'], $data['version'], $data['change_reason'], $id);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
        }else{
            return 0;
        }
    }

    function findProcedure($id)
    {
        global $mysqli;
        $stmt = $mysqli->prepare('SELECT id_procedure, title, date, objective, scope, definitions, position_1, number_workers_1, responsibilities_1, position_2, number_workers_2, responsibilities_2, recommendations, planning, monthly_maintenance, semi_annual_maintenance, maintenance_2_years, equipment_tools, records, confidentiality_note, version, change_reason FROM procedures WHERE id_procedure=?');
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    function SearchProcedures()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT id_procedure, title, date, objective, scope, definitions, position_1, number_workers_1, responsibilities_1, position_2, number_workers_2, responsibilities_2, recommendations, planning, monthly_maintenance, semi_annual_maintenance, maintenance_2_years, equipment_tools, records, confidentiality_note, version, change_reason FROM procedures";

        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

//        $sql = "SELECT id_procedure, name, description FROM procedures ";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "title LIKE '%".$resquest['search']['value']."%' OR objective LIKE '%" . $resquest['search']['value'] ."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[1];
            $subdata[] = $row[3];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-danger btn-sm mr-1' target='_blank' href='/report/Procedure.php?id=". $row[0] ."' title='Exportar'>Exportar</a>
            <a class='btn btn-primary btn-sm mr-1' href='/pages/adminprocedurescreateedit.php?action=edit&id=". $row[0] ."' title='Eliminar'>Editar</a>
            <button class='btn btn-danger btn-sm' onclick='deleteItem(".$row[0].")' title='Eliminar'>Eliminar</button>
            </div>";

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);
    }

    function SearchTeamActivities()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT id_team_activity, hours_worked, date, comment, IF(`type` = 1, 'Horas de trabajo', 'Cambio de aceite') AS type_text FROM team_activities";

        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        if(!empty($resquest['search']['value']))
        {
            $sql.= "hours_worked LIKE '%".$resquest['search']['value']."%' OR date LIKE '%" . $resquest['search']['value'] ."%' OR comment LIKE '%" . $resquest['search']['value'] . "%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[2];
            $subdata[] = $row[4];
            $subdata[] = $row[1];
            $subdata[] = $row[3];
//            $subdata[] = "<div class='btn-group'>
//            </div>";
            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);
    }

    function insertTeamActivities($data)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO team_activities (fk_teams_units, fk_user_id, hours_worked, date, comment, type) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssssss', $data['fk_teams_units'], $data['fk_user_id'], $data['hours_worked'], $data['date'], $data['comment'], $data['type']);
        $id = $mysqli->insert_id;
        if($stmt->execute())
        {
            if (!empty($data['hours_worked'])) {
                $hourWorked = $data['hours_worked'];
                $stmt = $mysqli->prepare("UPDATE teams_units_rsu SET accumulated_hours_worked = (accumulated_hours_worked + $hourWorked) WHERE id_teams_units = " . $data['fk_teams_units']);
                $stmt->execute();
                $stmt->close();

                //validar horas trabajadas
                $sql = "SELECT id_teams_units, token_teams_units, letter_units_teams, number_teams_units, plate_teams_units, accumulated_hours_worked FROM teams_units_rsu WHERE id_teams_units = ".$data['fk_teams_units'];
                $result = $mysqli->query($sql);
                $row   = $result->fetch_array();

                if($row['accumulated_hours_worked'] >= 190 && $row['accumulated_hours_worked'] < 200) {
                    //enviar correo
                    $this->envioDeNotificacionCambioAceite($row);
                } elseif($row['accumulated_hours_worked'] >= 200) {
                    $this->envioDeNotificacionCambioAceite($row, true);
                }
            }

            if (!empty($data['type']) && $data['type'] == 2) {
                $stmt = $mysqli->prepare("UPDATE teams_units_rsu SET accumulated_hours_worked = 0 WHERE id_teams_units = " .$data['fk_teams_units']);
                $stmt->execute();
                $stmt->close();
            }

            return $id;
        }else{
            return 0;
        }
    }

    function envioDeNotificacionCambioAceite($equipo, $flagUrgente = false)
    {
        global $mysqli;

        //obtener administradores
        $result = $mysqli->query('select first_name, second_name, email_user from asign_permits inner join users on users.id_user = asign_permits.user_id_asign where id_module_permit = 1 group by user_id_asign');
        $usersEmails = [];
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $usersEmails[] = $row;
            }

            require_once '../bookstores/PHPMailer/PHPMailerAutoload.php';
            $template = file_get_contents('../report/view/notificacionCambioAceite.php');
            $template = str_replace("{{indicadorUrgente}}", $flagUrgente ? 'URGENTE!' : '', $template);
            $template = str_replace("{{nombreDeEquipo}}", $equipo['letter_units_teams'] . ' con placa '. $equipo['plate_teams_units'], $template);

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.mailtrap.io';
            $mail->Port = 2525;
            $mail->Username = '1355a47bcecae7';
            $mail->Password = '515048fe7327d4';


            $mail->setFrom('cpsmtto@colpetroleumservices.com','CPS MTTO');
            $mail->addAddress($usersEmails[0]['email_user'], $usersEmails[0]['first_name'] . ' ' . $usersEmails[0]['second_name']);
            foreach ($usersEmails as $key => $usersEmail) {
                if($key > 0) {
                    $mail->addCC($usersEmail['email_user'], $usersEmail['first_name'] . ' ' . $usersEmail['second_name']);
                }
            }
            $mail->wordwrap = 50;

            $mail->Subject = 'Cambio de Aceite';
            $mail->Body = $template;
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            if($mail->send())
                return true;
            else
                return false;
        }
    }

    //Eliminar productos de los consumibles
    function deleteProcedure($id)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("DELETE FROM procedures WHERE id_procedure = ?");
        $stmt->bind_param('s', $id);
        if($stmt->execute())
        {
            return true;
        }else{
            return false;
        }
    }

    //Listado de opciones de las unidades RSU
    function OptionsUnits()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_units_rsu, reference_units_rsu, state_units_rsu FROM father_units_rsu");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_units, $reference_units, $state_units);

        while($stmt->fetch())
        {


            echo "<option value=".$id_units."> Unidad: ".$reference_units."</option>";
        }
    }

    //Listado de opciones de reporte de fallas
    function OptionsReportFails()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_report_fails, num_report_fails, reference_teams_report_fails, name_teams_report_fails FROM report_fails");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_report, $num_report, $reference_report, $teams_report);

        while($stmt->fetch())
        {

            echo "<option value=".$id_report.">".$num_report."-".$reference_report."-".$teams_report."</option>";
        }
    }

    //Registrar la asignación de contrato a la unidad
    function AssignContract($token_units_contract, $id_units_rsu, $date_units_contract, $no_contract, $ubication_contract, $client_contract, $dateini_contract, $datefini_contract, $id_user)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO contract_units_rsu (token_contract_units_rsu, fk_id_father_units_rsu, date_register_contract_units_rsu, contract_units_rsu, 
        location_contract_units_rsu, client_contract_units_rsu, dateini_contract_units_rsu, datefin_contract_units_rsu, fk_user_id_contract) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sissssssi', $token_units_contract, $id_units_rsu, $date_units_contract, $no_contract, $ubication_contract, $client_contract, $dateini_contract, $datefini_contract, $id_user);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Trae el ultimo número de los equipos registrados en la RSU
    function TopNumberTeams($value)
    {
        global $mysqli;

        $top = 0;

        $stmt = $mysqli->prepare("SELECT number_teams_units FROM teams_units_rsu WHERE 	fk_id_father_teams_units = ? ORDER BY id_teams_units DESC LIMIT 1");

        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($num_teams);
        $stmt->fetch();

        $top = $num_teams + 1;

        return $top;
    }

    //Trae el ultimo numero de los reporte de fallas
    function TopReportFails()
    {
        global $mysqli;

        $top = 0;

        $stmt = $mysqli->prepare("SELECT num_report_fails FROM report_fails ORDER BY id_report_fails DESC LIMIT 1");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($num_teams);
        $stmt->fetch();

        $top = $num_teams + 1;

        return $top;
    }

    //Registrar equipo en la unidad RSU
    function InsertTeamsUnits($token_teams, $date_teams, $id_father_units, $letter_teams, $number_teams, $type_teams, $model_teams, $mark_teams, $name_teams, $serie_teams, $capacity_teams, $plate_teams, $description_teams, $id_user)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO teams_units_rsu (token_teams_units, dateregister_teams_units, fk_id_father_teams_units, letter_units_teams, number_teams_units, 
        type_teams_units, model_teams_units, mark_teams_units, name_teams_units, serie_teams_units, capacity_teams_units, plate_teams_units, description_teams_units, fk_id_user_teams_units) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param('ssisissssssssi',$token_teams, $date_teams, $id_father_units, $letter_teams, $number_teams, $type_teams, $model_teams, $mark_teams, $name_teams, $serie_teams, $capacity_teams, $plate_teams, $description_teams, $id_user);


        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }


    }

    //Realiza el listado de los equipos registrados en cada RSU
    function SearchTeams($warehouse)
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT letter_units_teams, number_teams_units, name_teams_units, type_teams_units, model_teams_units, serie_teams_units, capacity_teams_units, mark_teams_units, plate_teams_units, dateregister_teams_units, description_teams_units, token_teams_units
        FROM teams_units_rsu WHERE fk_id_father_teams_units = '".$warehouse."'";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT letter_units_teams, number_teams_units, name_teams_units, type_teams_units, model_teams_units, serie_teams_units, capacity_teams_units, mark_teams_units, plate_teams_units, dateregister_teams_units, description_teams_units, token_teams_units
        FROM teams_units_rsu WHERE fk_id_father_teams_units = '".$warehouse."'";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "name_teams_units LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[0]."-".$row[1];
            $subdata[] = $row[2];
            $subdata[] = $row[3];
            $subdata[] = $row[4];
            $subdata[] = $row[5];
            $subdata[] = $row[6];
            $subdata[] = $row[7];
            $subdata[] = $row[8];
            $subdata[] = $row[9];
            $subdata[] = $row[10];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='resumeteams?teams=".$row[11]."'><i class='fas fa-file'></i></a>
                             
            </div>";


            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );
        return  json_encode($json_data);
    }

    //Registra inspecciones y mantenimientos
    function InsertInspection($date_reg, $tk_inspection, $maint, $frequency, $id_teams, $id_user, $frequency_type, $frequency_type_text, $frequency_value_hours, $frequency_value_date)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO inspection_of_mant_teams (date_reguster_inspection, token_inspection_mant_teams, maintenance_carried, frequency_inspection_teams, fk_teams_units, fk_user_id, frequency_type, frequency_type_text, frequency_value_hours, frequency_value_date) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssiiisds',$date_reg, $tk_inspection, $maint, $frequency, $id_teams, $id_user, $frequency_type, $frequency_type_text, $frequency_value_hours, $frequency_value_date);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Consulta los registros de inspección y mantenimiento
    function SearchInspection($tk_teams)
    {
        global $mysqli;

        $mtto = new mtto();

        $id_teams = $mtto->getValueMtto('id_teams_units','teams_units_rsu','token_teams_units', $tk_teams);

        $table = "";

        $nums = 0;


        $stmt = $mysqli->prepare("SELECT id_inspection_mant_teams, maintenance_carried, frequency_inspection_teams, frequency_type, frequency_type_text, frequency_value_hours, frequency_value_date FROM inspection_of_mant_teams WHERE fk_teams_units = ?");
        $stmt->bind_param('i', $id_teams);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {


            $stmt->bind_result($id_inspection_mant_teams, $maintenance, $frequency, $frequency_type, $frequency_type_text, $frequency_value_hours, $frequency_value_date);

            $table = "
                <tr style='background-color: #FBFCFC;'>
                    <th style='text-align: center;'>ITEM</th>
                    <th style='text-align: center;'>MANTENIMIENTO A REALIZAR</th>
                    <th style='text-align: center;'>FRECUENCIA</th>
                    <th style='text-align: center;'>TIPO DE FRECUENCIA</th>
                    <th style='text-align: center;'>VALOR DE FRECUENCIA</th>
                    <th style='text-align: center;'>ACCIONES</th>
                </tr>

            </thead>

            <tbody>";

            while($stmt->fetch())
            {
                $nums = $nums + 1;
                $table.= "<tr style='text-align: center;'>
                <td>".$nums."</td>
                <td>".$maintenance."</td>
                <td>".$frequency."</td>
                <td>".$frequency_type_text."</td>
                <td>".($frequency_type == 1 ? $frequency_value_hours : $frequency_value_date)."</td>
                <td>
                    <button class='btn btn-danger btn-sm' type='button' onclick='deleteInspectionFrequency(".$id_inspection_mant_teams.")'>Eliminar</button>
                    <button class='btn btn-primary btn-sm' type='button' onclick='editInspectionFrequency(".$id_inspection_mant_teams.",\"".$maintenance."\",\"".$frequency."\",\"".$frequency_type."\",\"".$frequency_value_hours."\",\"".$frequency_value_date."\")'>Editar</button>
                </td>
            </tr>";
            }

            $table.= "
                </tbody>
            </table>
            ";


        }
        else
        {
            $table.= "
            <tr style='background-color: #FBFCFC;'>
                    <th style='text-align: center;'>ITEM</th>
                    <th style='text-align: center;'>MANTENIMIENTO A REALIZAR</th>
                    <th style='text-align: center;'>FRECUENCIA</th>
                    <th style='text-align: center;'>ACCIONES</th>
                </tr>

            </thead>

            <tbody>
            
            <tr><td colspan='4' style='text-align: center;'>NO EXISTEN REGISTROS</td></tr>
            
            </tbody>
            </table>
            ";
        }

        return $table;
    }

    function getInspection_of_mant_teamsForCalendar($tk_teams)
    {
        global $mysqli;
        $mtto = new mtto();
        $id_teams = $mtto->getValueMtto('id_teams_units','teams_units_rsu','token_teams_units', $tk_teams);

        $rows = [];
        $result = $mysqli->query("SELECT id_inspection_mant_teams, maintenance_carried, frequency_inspection_teams, frequency_type, frequency_type_text, frequency_value_hours, frequency_value_date FROM inspection_of_mant_teams WHERE fk_teams_units = $id_teams");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['type_row'] = 'frecuencia';

                $row['next_date'] = $row['frequency_value_date'];
                $rows[] = $row;

                $date = date_create($row['frequency_value_date']);
                $date->modify('+1 month');
                $row['next_date'] = $date->format('Y-m-d');
                $rows[] = $row;
            }
        }

        $result = $mysqli->query("SELECT hours_worked, date, comment FROM team_activities WHERE fk_teams_units = $id_teams");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['type_row'] = 'activity';
                $rows[] = $row;
            }
        }

        return $rows;
    }


    function getInspection_of_mant_teamsForGeneralCalendar()
    {
        global $mysqli;
        $rows = [];
        $result = $mysqli->query('select id_inspection_mant_teams, maintenance_carried, frequency_inspection_teams, frequency_type, frequency_type_text, frequency_value_hours, frequency_value_date, type_teams_units, plate_teams_units, name_teams_units, model_teams_units, serie_teams_units, CONCAT(letter_units_teams, "-", number_teams_units) AS reference, capacity_teams_units, mark_teams_units, description_teams_units, id_teams_units from teams_units_rsu inner join inspection_of_mant_teams on inspection_of_mant_teams.fk_teams_units = teams_units_rsu.id_teams_units');
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['type_row'] = 'frecuencia';

                $row['next_date'] = $row['frequency_value_date'];
                $rows[] = $row;

                $date = date_create($row['frequency_value_date']);
                $date->modify('+1 month');
                $row['next_date'] = $date->format('Y-m-d');
                $rows[] = $row;


                $resultActivities = $mysqli->query("SELECT hours_worked, date, comment FROM team_activities WHERE fk_teams_units = " . $row['id_teams_units']);
                if ($resultActivities->num_rows > 0) {
                    while($rowActivity = $resultActivities->fetch_assoc()) {
                        $rowActivity['type_teams_units'] = $row['type_teams_units'];
                        $rowActivity['name_teams_units'] = $row['name_teams_units'];
                        $rowActivity['type_row'] = 'activity';
                        $rows[] = $rowActivity;
                    }
                }
            }
        }
        return $rows;
    }


    //Actualiza los datos del equipo
    function UpdateDetailsTeams($type_up, $model_up, $mark_up, $name_up, $serie_up, $capacity_up, $plate_up, $description_up, $tk_teams)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE teams_units_rsu SET type_teams_units = ?, model_teams_units = ?, mark_teams_units = ?, name_teams_units = ?, serie_teams_units = ?, capacity_teams_units = ?, plate_teams_units = ?, description_teams_units = ? WHERE token_teams_units = ?");
        $stmt->bind_param('sssssssss', $type_up, $model_up, $mark_up, $name_up, $serie_up, $capacity_up, $plate_up, $description_up, $tk_teams);
        $stmt->execute();
        $stmt->close();

    }

    //Agrega la imagen uno
    function AddImagenOne($imagenuno, $tk_teams)
    {
        global $mysqli;

        $ruta = '../images/'.$imagenuno['imagenuno']['name'];
        move_uploaded_file($imagenuno['imagenuno']['tmp_name'],$ruta);

        $stmt = $mysqli->prepare("UPDATE teams_units_rsu SET teams_image_one = ? WHERE 	token_teams_units = ?");
        $stmt->bind_param('ss', $ruta, $tk_teams);
        $stmt->execute();
        $stmt->close();
    }

    //Agrega la imagen uno
    function AddImagenDos($imagendos, $tk_teams)
    {
        global $mysqli;

        $ruta = '../images/'.$imagendos['imagendos']['name'];
        move_uploaded_file($imagendos['imagendos']['tmp_name'],$ruta);

        $stmt = $mysqli->prepare("UPDATE teams_units_rsu SET teams_image_two = ? WHERE 	token_teams_units = ?");
        $stmt->bind_param('ss', $ruta, $tk_teams);
        $stmt->execute();
        $stmt->close();
    }

    //Opciones de los equipos registrados
    function OptionsTeams()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_teams_units, letter_units_teams, number_teams_units, name_teams_units FROM teams_units_rsu");
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($id_teams_units, $letter, $number, $name);

        while($stmt->fetch())
        {
            echo "<option value=".$id_teams_units.">".$letter."-".$number."-".$name."</option>";
        }
    }

    //Opciones de los equipos registrados
    function OptionsAnalysis()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_analysis_data, letter_analysis_warehouse, num_analysis_data, concept_analysis_data FROM analysis_data");
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($id_analysis_data, $letter, $number, $concept);

        while($stmt->fetch())
        {
            echo "<option value=".$id_analysis_data.">".$letter.$number."-".$concept."</option>";
        }
    }

    //Registrar reporte de fallas o mantenimiento
    function InsertReportFails($num_report, $reference_teams_end, $num_analysis, $name_teams, $name_fails, $description_fails, $date_fails, $cost_npt, $time_fails, $impact_trab, $impact_ambiental, $id_user, $token_report, $id_unity)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO report_fails (num_report_fails, reference_teams_report_fails, no_analysis_report_fails, name_teams_report_fails, name_report_fails, description_report_fails, datereg_report_fails,
        costnpt_report_fails, time_stop_report_fails, warning_person_report_fails, warning_ambiental_report_fails, user_id_report_fails, token_report_fails, fk_units_report_fail) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('issssssiissisi',$num_report, $reference_teams_end, $num_analysis, $name_teams, $name_fails, $description_fails, $date_fails, $cost_npt, $time_fails, $impact_trab, $impact_ambiental, $id_user, $token_report, $id_unity);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Busca los reporte de fallas y mantenimientos
    function SearchReportFails()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT num_report_fails, reference_teams_report_fails, no_analysis_report_fails, name_teams_report_fails, name_report_fails, description_report_fails,
        datereg_report_fails, costnpt_report_fails, time_stop_report_fails, warning_person_report_fails, 
        warning_ambiental_report_fails, token_report_fails FROM report_fails";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT num_report_fails, reference_teams_report_fails, no_analysis_report_fails, name_teams_report_fails, name_report_fails, description_report_fails,
        datereg_report_fails, costnpt_report_fails, time_stop_report_fails, warning_person_report_fails, 
        warning_ambiental_report_fails, token_report_fails FROM report_fails";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "name_report_fails LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();

        while($row = $query->fetch_array())
        {

            if($row[9] == "NO")
            {
                $ct = "<small class='badge badge-danger'>NO</small>";
            }
            else
            {
                $ct = "<small class='badge badge-success'>SI</small>";
            }

            if($row[10] == "NO")
            {
                $ca = "<small class='badge badge-danger'>NO</small>";;
            }
            else
            {
                $ca = "<small class='badge badge-success'>SI</small>";
            }

            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[3];
            $subdata[] = $row[1];
            $subdata[] = $row[5];
            $subdata[] = $row[8];
            $subdata[] = $ct;
            $subdata[] = $ca;
            $subdata[] = $row[6];
            $subdata[] = $row[2];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='../report/ReportFails?report=".$row[11]."' target='_blank'><i class='fas fa-file'></i></a>
                             
            </div>";


            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);
    }

    //Consulta el total de costos realizados en cada unidad
    function TotalMantsRsu($value)
    {
        global $mysqli;

        $widget = "";

        $stmt = $mysqli->prepare("SELECT SUM(totalcost_teams_maint) AS TOTAL FROM report_teams_maint AS a INNER JOIN 
        teams_units_rsu AS b ON a.fk_teams_report_maint = b.id_teams_units INNER JOIN 
        father_units_rsu AS c ON b.fk_id_father_teams_units = c.id_units_rsu WHERE c.id_units_rsu = ?");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total);
        $stmt->fetch();

        $widget = "<div class='col-12 col-sm-6 col-md-3'>
            <div class='info-box mb-3'>
            <span class='info-box-icon bg-danger elevation-1'><i class='fas fa-dollar-sign'></i></span>

            <div class='info-box-content'>
                <span class='info-box-text'>Costo mantenimientos</span>
                <span class='info-box-number'>".number_format($total)."</span>
            </div>
    
            </div>

        </div>";

        return $widget;

    }

    //Consulta el total de costos NPT
    function TotalNPTRsu($value)
    {
        global $mysqli;

        $widget = "";

        $stmt = $mysqli->prepare("SELECT SUM(a.costnpt_report_fails) AS TOTAL FROM report_fails AS a INNER JOIN 
        father_units_rsu AS b ON a.fk_units_report_fail = b.id_units_rsu WHERE b.id_units_rsu = ?");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total);
        $stmt->fetch();

        $widget = "<div class='col-12 col-sm-6 col-md-3'>
        <div class='info-box mb-3'>
        <span class='info-box-icon bg-danger elevation-1'><i class='fas fa-dollar-sign'></i></span>

                <div class='info-box-content'>
                    <span class='info-box-text'>Costo por NPT</span>
                    <span class='info-box-number'>".number_format($total)."</span>
                </div>

            </div>

        </div>";

        return $widget;
    }

    //Consulta el total de costos NPT, para establecer en el reporte (F-270).
    function TotalMantReport($value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT SUM(totalcost_teams_maint) AS TOTAL FROM report_teams_maint AS a INNER JOIN 
        teams_units_rsu AS b ON a.fk_teams_report_maint = b.id_teams_units INNER JOIN 
        father_units_rsu AS c ON b.fk_id_father_teams_units = c.id_units_rsu WHERE c.id_units_rsu = ?");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_m);
        $stmt->fetch();

        return $total_m;
    }

    //Consulta el total de costos NPT, para establecer en el reporte (F-270).
    function TotalNPTReport($value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT SUM(a.costnpt_report_fails) AS TOTAL FROM report_fails AS a INNER JOIN 
        father_units_rsu AS b ON a.fk_units_report_fail = b.id_units_rsu WHERE b.id_units_rsu = ?");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_n);
        $stmt->fetch();

        return $total_n;
    }

    // APARTADO DE REPORTES (Ficha técnica)

    //Primera parte
    function ReportOneteams($value)
    {
        global $mysqli;

        $mt = new mtto();

        $val = $mt->getValueMtto('id_teams_units','teams_units_rsu','token_teams_units',$value);

        $partone = "";

        //TOTAL DE LOS MANTENIMIENTOS REALIZADOS
        $tot = $mysqli->prepare("SELECT SUM(totalcost_teams_maint) AS total FROM report_teams_maint WHERE fk_teams_report_maint = ?");
        $tot->bind_param('i', $val);
        $tot->execute();
        $tot->store_result();
        $tot->bind_result($total_m);
        $tot->fetch();


        $stmt = $mysqli->prepare("SELECT dateregister_teams_units, name_teams_units, type_teams_units, costmaint_teams_units, model_teams_units,
        serie_teams_units, number_teams_units, letter_units_teams, capacity_teams_units, mark_teams_units, plate_teams_units, description_teams_units FROM teams_units_rsu WHERE token_teams_units = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($date, $name, $type, $costmaint, $model, $serie, $number, $letter, $capacity, $mark, $plate, $description);
        $stmt->fetch();

        $partone = "     <table style='border: 1px solid #EAECEE ; margin-top: 20px;'>
        <tr style='font-size: 12px; background-color: #F2DCDB;'>
          <th colspan='3' style='border: 1px solid black; text-align:center;'>PROGRAMA DE MANTENIMIENTO E INSPECCIÓN</th>
          <th style='border: 1px solid black; text-align:center;'>FECHA DE VERIFICACIÓN</th>
          <th style='border: 1px solid black; text-align:center;'>DD/MM/AAAA</th>
        </tr>

        <tr style='font-size: 12px; text-align:center;'>
          <th style='border: 1px solid black; width: 150px; background-color: #F2DCDB;'>FECHA</th>
          <td style='border: 1px solid black; width: 302px;'>".$date."</td>
          <th style='border: 1px solid black; width: 250px; background-color: #F2DCDB;'>NOMBRE ESPECÍFICO</th>
          <td colspan='2' style='border: 1px solid black; width: 302px;'>".$name."</td>
        </tr>
        <tr style='font-size: 12px; text-align:center;'>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>TIPO DE EQUIPO</th>
          <td style='border: 1px solid black; width: 205px;'>".$type."</td>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>COSTOS TOTAL DE MANTENIMIENTOS</th>
          <td colspan='2' style='border: 1px solid black; width: 205px;''>$ ".number_format($total_m)."</td>
        </tr>
        <tr style='font-size: 12px; text-align:center;'>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>MODELO</th>
          <td style='border: 1px solid black; width: 205px;'>".$model."</td>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>SERIE</th>
          <td colspan='2' style='border: 1px solid black; width: 205px;'>".$serie."</td>
        </tr>
        <tr style='font-size: 12px; text-align:center;'>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>REFERENCIA</th>
          <td style='border: 1px solid black; width: 205px;'>".$letter."".$number."</td>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>CAPACIDAD</th>
          <td colspan='2' style='border: 1px solid black; width: 205px;'>$capacity</td>
        </tr>
        <tr style='font-size: 12px; text-align:center;'>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>MARCA</th>
          <td style='border: 1px solid black; width: 205px;'>".$mark."</td>
          <th style='border: 1px solid black; background-color: #F2DCDB;'>PLACA</th>
          <td colspan='2' style='border: 1px solid black; width: 205px;'>".$plate."</td>
        </tr>
        <tr style='font-size: 12px; text-align:center;'>
          <th colspan='5' style='border: 1px solid black; background-color: #F2DCDB;'>CARACTERÍSTICAS</th>
        </tr>
        <tr style='font-size: 12px; text-align:center;'>
          <td colspan='5' style='border: 1px solid black; height: 40px;'>".$description."</td>
        </tr>
      </table>";


      return $partone;


    }

    //Segunda parte
    function ReportTwoTeams($value)
    {
        global $mysqli;

        $mt = new mtto();
        $nums = 0;

        $table = "";



        $val = $mt->getValueMtto('id_teams_units','teams_units_rsu','token_teams_units',$value);

        $stmt = $mysqli->prepare("SELECT maintenance_carried, frequency_inspection_teams FROM inspection_of_mant_teams WHERE fk_teams_units = ?");
        $stmt->bind_param('i', $val);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($maintenance, $frequency);

            $table = "<tr style='border: 1px solid black; text-align:center; background-color: #F2DCDB;'>       
            <th style='border: 1px solid black; width:90px;'>ITEM</th>
            <th style='border: 1px solid black; width:524px;'>MANTENIMIENTO A REALIZAR</th>
            <th style='border: 1px solid black; width:400px;'>FRECUENCIA</th>
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                
                <tr style='border: 1px solid black; text-align:center;'>
                <td style='border: 1px solid black; width:90px;'>".$nums."</td>
                <td style='border: 1px solid black; width:349px;'>".$maintenance."</td>
                <td style='border: 1px solid black; width:240px;'>".$frequency."</td>
                </tr>";
            }

        }
        else
        {
            $table.= "
            <tr style='border: 1px solid black; text-align:center; background-color: #F2DCDB;'>       
            <th style='border: 1px solid black; width:90px;'>ITEM</th>
            <th style='border: 1px solid black; width:524px;'>MANTENIMIENTO A REALIZAR</th>
            <th style='border: 1px solid black; width:400px;'>FRECUENCIA</th>
            </tr>
            <tr style='border: 1px solid black; text-align:center;'>
                <td style='border: 1px solid black; width:90px; height: 20px;'></td>
                <td style='border: 1px solid black; width:349px;'></td>
                <td style='border: 1px solid black; width:240px;'></td>
                </tr>";
        }

        return $table;

    }

    //Tercera parte
    function ReportThreeTeams($value)
    {
        global $mysqli;

        $img = "";

        $stmt = $mysqli->prepare("SELECT teams_image_one, teams_image_two FROM teams_units_rsu WHERE token_teams_units = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($imgone, $imgtwo);
        $stmt->fetch();

        if($imgone == '')
        {
            $img_content_one = "<td style='border: 1px solid black; width:352px; height: 100px;'></td>";
        }
        else
        {
            $img_content_one = "<td style='border: 1px solid black; width:352px; height: 100px;'><img style='width: 300px;' src='".$imgone."' alt='FOTOGRAFIA No. 1'></td>";
        }

        if($imgtwo == '')
        {
            $img_content_two = "<td style='border: 1px solid black; width:352px; height: 100px;'></td>";
        }
        else
        {
            $img_content_two = "<td style='border: 1px solid black; width:352px; height: 100px;'><img style='width: 300px;' src='".$imgtwo."' alt='FOTOGRAFIA No. 1'></td>";
        }

        $img = "<tr style='border: 1px solid black; text-align:center;'>
        ".$img_content_one."".$img_content_two."
        
        
      </tr>";

      return $img;

    }

    //Cuarta parte
    function ReportFourthTeams($value)
    {
        global $mysqli;

        $mt = new mtto();

        $table = "";

        $to = 0;

        $val = $mt->getValueMtto('id_teams_units','teams_units_rsu','token_teams_units',$value);

        $stmt = $mysqli->prepare("SELECT number_maintence_teams_maint, type_teams_maint, description_teams_maint, autor_execution_teams_maint, 
        location_execution_teams_maint, codreportfails_teams_maint, date_teams_maint, alarm_teams_maint, proxdate_teams_maint, 
        confirm_execute_teams_maint, totalcost_teams_maint FROM report_teams_maint WHERE fk_teams_report_maint = ?");
        $stmt->bind_param('i', $val);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($number, $type, $description, $autor, $location, $codreport, $dateteams, $alarm, $proxdate, $confirmexe, $totalmant);

            while($stmt->fetch())
            {
                $to = $to+1;

                if($codreport == '')
                {
                    $cod_report = "N/A";
                }
                else
                {
                    $cod_report = $codreport;
                }

                if($proxdate == '0000-00-00')
                {
                    $prox_date = "NO APLICA";
                }
                else
                {
                    $prox_date = $proxdate;
                }

                $table.= "<tr style='font-size: 9px; text-align:center;'>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 30px; height: 50px;'>".$to."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 30px;'>".$number."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 70px;'>".$type."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 200px;'>".$description."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 80px;'>".$autor."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 100px;'>".$location."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 30px;'>".$cod_report."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 62px;'>".$dateteams."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 100px;'>N/A</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 84px;'>".$prox_date."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 40px;'>".$confirmexe."</td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 90px;'>$".number_format($totalmant)."</td>
                            
                            </tr>";
            }


        }
        else
        {
            $table.= "<tr style='font-size: 9px; text-align:center; '>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 30px; height: 50px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 30px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 70px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 200px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 80px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 100px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 30px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 62px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 100px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 84px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 40px;'></td>
                                <td style='border: 1px solid black; text-align:center; Word-break: break-all; width: 90px;'></td>
                            
                            </tr>";
        }

        return $table;
    }

    //Registrar reporte de mantenimiento
    function InsertMaintReport($token_mant, $number, $type_maint, $reference, $name_teams, $description_maint, $location_maint, $name_machine_maint, $analysis, $num_report_fails, $date_reg_mant, $reference_teams,  $total_analysis, $state_asign)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO report_maint (token_report_maint, number_report_mant, type_activity_report_maint, reference_teams_report_mant, 
        name_teams_report_mant, description_report_mant, location_report_mant, actor_execution_report_mant, analysis_data_report_mant, cod_report_fails_mant, date_report_mant, 
        fk_teams_report_mant, cost_total_mant_analysis, state_asign_report_mant) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sisssssssissii', $token_mant, $number, $type_maint, $reference, $name_teams, $description_maint, $location_maint, $name_machine_maint, $analysis, $num_report_fails, $date_reg_mant, $reference_teams, $total_analysis, $state_asign);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Trae el ultimo número del registro
    function TopReportMaint()
    {
        global $mysqli;

        $top = 0;

        $stmt = $mysqli->prepare("SELECT number_report_mant FROM report_maint ORDER BY id_report_maint DESC LIMIT 1");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($num_mant);
        $stmt->fetch();

        $top = $num_mant + 1;

        return $top;
    }

    //Busca los reportes de mantenimientos
    function SearchReportMant()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT number_report_mant, type_activity_report_maint, location_report_mant, reference_teams_report_mant, name_teams_report_mant, cod_report_fails_mant, description_report_mant, 
        actor_execution_report_mant, analysis_data_report_mant, date_report_mant, token_report_maint FROM report_maint";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT number_report_mant, type_activity_report_maint, location_report_mant, reference_teams_report_mant, name_teams_report_mant, cod_report_fails_mant, description_report_mant, 
        actor_execution_report_mant, analysis_data_report_mant, date_report_mant, token_report_maint FROM report_maint";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "number_report_mant LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();

        while($row = $query->fetch_array())
        {

            if($row[5] == "")
            {
                $st = "<small class='badge badge-danger'>NO APLICA</small>";
            }
            else
            {
                $st = $row[5];
            }

            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $row[2];
            $subdata[] = $row[3];
            $subdata[] = $row[4];
            $subdata[] = $st;
            $subdata[] = $row[6];
            $subdata[] = $row[7];
            $subdata[] = $row[8];
            $subdata[] = $row[9];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='../report/ReportMaint?reportm=".$row[10]."' target='_blank'><i class='fas fa-file'></i></a>
                             
            </div>";

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);

    }

    //Lista de opciones de reporte de mantenimientos
    function OptionMant($id_teams)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_report_maint, number_report_mant, date_report_mant, reference_teams_report_mant, name_teams_report_mant, state_asign_report_mant FROM report_maint 
        WHERE fk_teams_report_mant = ?");
        $stmt->bind_param('i', $id_teams);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_report_maint, $number_maint, $date_report_maint, $reference_report_teams, $name_teams, $state);

        while($stmt->fetch())
        {
            if($state == 0)
            {
                echo "<option value=".$id_report_maint." style='color: red;'>".$number_maint." (".$date_report_maint.") (".$reference_report_teams.") (".$name_teams.")</option>";

            }
            else
            {
                echo "<option value=".$id_report_maint." style='color: green;'>".$number_maint." (".$date_report_maint.") (".$reference_report_teams.") (".$name_teams.")</option>";

            }
        }



    }

    //Insertar el reporte de mantenimiento al equipo
    function InsertReportMaintTeams($token_report, $number_report, $type_report, $description_report, $actor_report, $location_report, $codreportfails_report, $date_report, $date_proxmant, $confirm_execute, $cost_report, $id_teams, $id_user)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO report_teams_maint (token_teams_maint, number_maintence_teams_maint, type_teams_maint, description_teams_maint,
        autor_execution_teams_maint, location_execution_teams_maint, codreportfails_teams_maint, date_teams_maint, proxdate_teams_maint, confirm_execute_teams_maint,
        totalcost_teams_maint, fk_teams_report_maint, fk_user_report_maint) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sissssisssiii', $token_report, $number_report, $type_report, $description_report, $actor_report, $location_report, $codreportfails_report, $date_report, $date_proxmant, $confirm_execute, $cost_report, $id_teams, $id_user);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Consulta los reportes de mantenimientos según el equipo
    function SearchReportTeamsMant($value)
    {
        global $mysqli;

        $num = 0;

        $resquest = $_REQUEST;

        $sql = "SELECT number_maintence_teams_maint, type_teams_maint, description_teams_maint, autor_execution_teams_maint, 
        location_execution_teams_maint, codreportfails_teams_maint, date_teams_maint, alarm_teams_maint, proxdate_teams_maint, 
        confirm_execute_teams_maint, totalcost_teams_maint FROM report_teams_maint WHERE fk_teams_report_maint = '".$value."'";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT number_maintence_teams_maint, type_teams_maint, description_teams_maint, autor_execution_teams_maint, 
        location_execution_teams_maint, codreportfails_teams_maint, date_teams_maint, alarm_teams_maint, proxdate_teams_maint, 
        confirm_execute_teams_maint, totalcost_teams_maint FROM report_teams_maint WHERE fk_teams_report_maint = '".$value."'";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_teams_maint LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;


        $data = array();

        while($row = $query->fetch_array())
        {
            $num = $num+1;

            if($row[8] == '0000-00-00' OR $row[8] == '0000-00-00')
            {
                $st = "<small class='badge badge-danger'>NO APLICA</small>";
            }
            else
            {
                $st = $row[8];
            }

            if($row[5] == '')
            {
                $sts = "<small class='badge badge-danger'>N/A</small>";
            }
            else
            {
                $sts = $row[5];
            }


            $subdata = array();
            $subdata[] = $num;
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $row[2];
            $subdata[] = $row[3];
            $subdata[] = $row[4];
            $subdata[] = $sts;
            $subdata[] = $row[6];
            $subdata[] = $row[7];
            $subdata[] = $st;
            $subdata[] = $row[9];
            $subdata[] = "$". number_format($row[10]);
            $subdata[] = $row[11];

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);


    }

    //Actualiza el estado de registro del reporte de mantenimiento
    function UpdateStateReportMant($value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE report_maint SET state_asign_report_mant = 1 WHERE id_report_maint = ?");
        $stmt->bind_param('i', $value);
        $stmt->execute();
        $stmt->close();
    }


    //Registra las notificaciones
    function InsertNotificationMantMtto($token, $module, $description, $datetime, $view, $statereg, $fkteams, $fktype)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO notifications_mtto(token_notification_mtto, module_notification_mtto, description_notification_mtto, 
        datetime_notification_module, view_notification_mtto, statereg_notification_mtto, fk_maintteams_mtto, fk_type_notification_mtto) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssiiii', $token, $module, $description, $datetime, $view, $statereg, $fkteams, $fktype);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Actualiza el estado de notificación
    function UpdateStateNotification($state, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE report_teams_maint SET state_notification_mtto = ? WHERE id_report_teams_maint = ?");
        $stmt->bind_param('ii',$state, $value);
        $stmt->execute();
        $stmt->close();
    }

    //Registra notificación de stock
    function InsertNotificationStock($token, $module, $description, $datetime, $view, $statereg, $fkstock, $fktype)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO notifications_mtto(token_notification_mtto, module_notification_mtto, description_notification_mtto, 
        datetime_notification_module, view_notification_mtto, statereg_notification_mtto, fk_stockactives_mtto, fk_type_notification_mtto) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssiiii', $token, $module, $description, $datetime, $view, $statereg, $fkstock, $fktype);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Actualiza el estado de notificación en el activo
    function UpdateStateNotificationStock($state, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE spares_parts SET state_notification_mtto_spares_parts = ? WHERE id_spares = ?");
        $stmt->bind_param('ii', $state, $value);
        $stmt->execute();
        $stmt->close();
    }

    //Valida las notificaciones antes de ser registradas
    function ValideNotificationOneMtto($date)
    {

        global $mysqli;

        $mto = new mtto();

        $stmt_con = $mysqli->prepare("SELECT id_report_teams_maint, fk_teams_report_maint, proxdate_teams_maint, state_notification_mtto, letter_units_teams, number_teams_units FROM report_teams_maint INNER JOIN teams_units_rsu ON report_teams_maint.fk_teams_report_maint = teams_units_rsu.id_teams_units WHERE DATEDIFF(proxdate_teams_maint, ?) = 2 AND state_notification_mtto = 0");
        $stmt_con->bind_param('s', $date);
        $stmt_con->execute();
        $stmt_con->store_result();
        $num = $stmt_con->num_rows;

        if($num > 0)
        {
            $stmt_con->bind_result($id_report, $id_teams, $dateprox, $state, $letter, $number);

            while($stmt_con->fetch())
            {

                $state = $state + 1;

                $token = $mto->GenerateTokenMtto();
                $module = "Alerta de mantenimiento.";
                $description = "Proximo mantenimiento: ".$dateprox.", para la siguiente referencia: ".$letter."-".$number;
                $datetime = $mto->DateMtto();
                $view = 0;
                $statereg = 0;
                $fkmainteams = $id_teams;
                $typenot = 2;

                $mto->InsertNotificationMantMtto($token, $module, $description, $datetime, $view, $statereg, $fkmainteams, $typenot);
                $mto->UpdateStateNotification($state, $id_report);


            }
        }
        else
        {
            return null;
        }



    }

    //Valida las notificaciones antes de ser registradas
    function ValideNotificationTwoMtto($date)
    {

        global $mysqli;

        $mto = new mtto();

        $stmt_con = $mysqli->prepare("SELECT id_report_teams_maint, fk_teams_report_maint, proxdate_teams_maint, state_notification_mtto, letter_units_teams, number_teams_units FROM report_teams_maint INNER JOIN teams_units_rsu ON report_teams_maint.fk_teams_report_maint = teams_units_rsu.id_teams_units WHERE proxdate_teams_maint <= ? AND state_notification_mtto = 1");
        $stmt_con->bind_param('s', $date);
        $stmt_con->execute();
        $stmt_con->store_result();
        $num = $stmt_con->num_rows;

        if($num > 0)
        {
            $stmt_con->bind_result($id_report, $id_teams, $dateprox, $state, $letter, $number);

            while($stmt_con->fetch())
            {

                $state = $state + 1;

                $token = $mto->GenerateTokenMtto();
                $module = "Alerta de mantenimiento.";
                $description = "Ejecutar inmediatamente el siguiente mantenimiento: ".$letter."-".$number;
                $datetime = $mto->DateMtto();
                $view = 0;
                $statereg = 0;
                $fkmainteams = $id_teams;
                $typenot = 1;

                $mto->InsertNotificationMantMtto($token, $module, $description, $datetime, $view, $statereg, $fkmainteams, $typenot);
                $mto->UpdateStateNotification($state, $id_report);

            }
        }
        else
        {
            return null;
        }



    }

    //Valida las notificaciones de stock de inventario
    function ValideNotificationStock()
    {
        global $mysqli;

        $mto = new mtto();

        $stmt_con = $mysqli->prepare("SELECT id_spares, stock_spares, alarm_spares_stock, state_notification_mtto_spares_parts, concept_warehouse, num_concept_warehouse FROM spares_parts WHERE alarm_spares_stock = stock_spares AND state_notification_mtto_spares_parts = 0");
        $stmt_con->execute();
        $stmt_con->store_result();
        $num = $stmt_con->num_rows;

        if($num > 0)
        {
            $stmt_con->bind_result($id_spares, $stock, $stock_alarm, $state, $concept, $number);

            while($stmt_con->fetch())
            {
                $state = $state + 1;


                $token = $mto->GenerateTokenMtto();
                $module = "Alerta de Stock de inventario.";
                $description = "Se encuentra en el rango normal, la siguiente referencia: ".$concept.$number;
                $datetime = $mto->DateMtto();
                $view = 0;
                $statereg = 0;
                $fkstock = $id_spares;
                $typenot = 3;

                $mto->InsertNotificationStock($token, $module, $description, $datetime, $view, $statereg, $fkstock, $typenot);
                $mto->UpdateStateNotificationStock($state, $fkstock);

            }
        }
    }

    function ValideNotificationStockNew()
    {
        global $mysqli;

        $mto = new mtto();

        $stmt_con = $mysqli->prepare("SELECT id_spares, stock_spares, alarm_spares_stock, state_notification_mtto_spares_parts, concept_warehouse, num_concept_warehouse FROM spares_parts WHERE stock_spares < alarm_spares_stock AND state_notification_mtto_spares_parts = 1");
        $stmt_con->execute();
        $stmt_con->store_result();
        $num = $stmt_con->num_rows;

        if($num > 0)
        {
            $stmt_con->bind_result($id_spares, $stock, $stock_alarm, $state, $concept, $number);

            while($stmt_con->fetch())
            {
                $state = $state + 1;

                $token = $mto->GenerateTokenMtto();
                $module = "Alerta de Stock de inventario.";
                $description = "Hacer requesición inmediata, de la siguiente referencia: ".$concept.$number;
                $datetime = $mto->DateMtto();
                $view = 0;
                $statereg = 0;
                $fkstock = $id_spares;
                $typenot = 2;

                $mto->InsertNotificationStock($token, $module, $description, $datetime, $view, $statereg, $fkstock, $typenot);
                $mto->UpdateStateNotificationStock($state, $fkstock);


            }
        }
    }

    function ValideNotificationStockF()
    {
        global $mysqli;

        $mto = new mtto();

        $stmt_con = $mysqli->prepare("SELECT id_spares, stock_spares, alarm_spares_stock, state_notification_mtto_spares_parts, concept_warehouse, num_concept_warehouse FROM spares_parts WHERE stock_spares < alarm_spares_stock AND state_notification_mtto_spares_parts = 0");
        $stmt_con->execute();
        $stmt_con->store_result();
        $num = $stmt_con->num_rows;

        if($num > 0)
        {
            $stmt_con->bind_result($id_spares, $stock, $stock_alarm, $state, $concept, $number);

            while($stmt_con->fetch())
            {
                $state = $state + 1;

                $token = $mto->GenerateTokenMtto();
                $module = "Alerta de Stock de inventario.";
                $description = "Hacer requesición inmediata, de la siguiente referencia: ".$concept.$number;
                $datetime = $mto->DateMtto();
                $view = 0;
                $statereg = 0;
                $fkstock = $id_spares;
                $typenot = 2;

                $mto->InsertNotificationStock($token, $module, $description, $datetime, $view, $statereg, $fkstock, $typenot);
                $mto->UpdateStateNotificationStock($state, $fkstock);


            }
        }
    }

    //Trae los ultimos 4 notificaciones
    function ViewNotification()
    {
        global $mysqli;

        $not = "";

        $stmt = $mysqli->prepare("SELECT id_notification_mtto, module_notification_mtto, description_notification_mtto, datetime_notification_module, view_notification_mtto FROM notifications_mtto WHERE view_notification_mtto = 0 ORDER BY id_notification_mtto DESC LIMIT 4");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {

            $stmt->bind_result($id_notification, $module_notification, $description_notification, $datetime, $view);

            $stmts = $mysqli->prepare("SELECT COUNT(*) AS total FROM notifications_mtto WHERE view_notification_mtto = 0");
            $stmts->execute();
            $stmts->store_result();
            $stmts->bind_result($total);
            $stmts->fetch();

            $not = "<li class='nav-item dropdown'>
            <a class='nav-link' data-toggle='dropdown' href=''>
            <i class='nav-icon fas fa-exclamation-triangle'></i>
              <p class='badge badge-danger navbar-badge' style='font-size: 15px; font-weight: bold;'>".$total."</p>
            </a>
            <div class='dropdown-menu dropdown-menu-lg dropdown-menu-right'> ";

            while($stmt->fetch())
            {
                $not.="<div class='dropdown-divider'></div>
                <a href='notifications' class='dropdown-item'>
             
                  <div class='media'>
                    <div class='media-body'>
                      <h3 class='dropdown-item-title'>
                        ".$module_notification."
                        <span class='float-right text-sm text-danger'><i class='fas fa-check-circle'></i></span>
      
                      </h3>
                      <p class='text-sm'>".$description_notification."</p>
                      <p class='text-sm text-muted'><i class='far fa-clock mr-1'></i>".$datetime."</p>
                    </div>
                  </div>

                </a>                
                
                ";
            }

            $not.="<div class='dropdown-divider'></div>
            <a href='notifications' class='dropdown-item dropdown-footer'>Ver todas las notificaciones</a>
          </div>
        </li>";

        }
        else
        {
            $not.="<li class='nav-item dropdown'>
            <a class='nav-link' data-toggle='dropdown' href=''>
            <i class='nav-icon fas fa-exclamation-triangle'></i>
            </a>
            <div class='dropdown-menu dropdown-menu-lg dropdown-menu-right'>          
              
              <div class='dropdown-divider'></div>
              <a href='' class='dropdown-item'>
                
                <div class='media'>
                  <div class='media-body'>
                    
                    <p class='text-sm text-center'>No existen notificaciones</p>
                    
                  </div>
                </div>
          
              </a>
             
              
              <div class='dropdown-divider'></div>
              <a href='notifications' class='dropdown-item dropdown-footer'>Ver todas las notificaciones</a>
            </div>
          </li>";
        }

        return $not;
    }

    //Consulta todas las notificaciones
    function SearchNotifications()
    {
        global $mysqli;

        $st = "";
        $sr = "";

        $resquest = $_REQUEST;

        $sql = "SELECT module_notification_mtto, description_notification_mtto, datetime_notification_module, view_notification_mtto, fk_type_notification_mtto, token_notification_mtto FROM notifications_mtto";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT module_notification_mtto, description_notification_mtto, datetime_notification_module, view_notification_mtto, fk_type_notification_mtto, token_notification_mtto FROM notifications_mtto";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_notification_mtto LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            switch($row[3]){
                case($row[3] = 0):

                    $st = "<i class='fas fa-times-circle text-danger'></i></span>";

                break;

                case($row[3] = 1):

                    $st = "<i class='fas fa-check-circle text-success'></i></span>";

                break;
            }

            switch($row[4]){
                case($row[4] = 1):

                    $sr = "<i class='fas fa-circle text-danger'></i> Muy Alta</span>";

                    break;
                case($row[4] = 2):

                    $sr = "<i class='fas fa-circle text-warning'></i> Alta</span>";

                    break;
                case($row[4] = 3):

                    $sr = "<i class='fas fa-circle text-success'></i> Media</span>";

                    break;
                case($row[4] = 4):

                    $sr = "<i class='fas fa-circle text-primary'></i> Baja</span>";

                break;
        }

            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $row[2];
            $subdata[] = $st;
            $subdata[] = $sr;
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='../functions/Update/UpdateViewNot?notification=".$row[5]."'><i class='fas fa-clipboard-check'></i></a>
                             
            </div>";

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);


    }

    //Actualiza el estado de la notificación
    function UpdateViewNotifications($value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE notifications_mtto SET view_notification_mtto = 1 WHERE token_notification_mtto = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->close();

    }

    //Restaura el conteo de notificaciones
    function ResetStateNot($table, $colum, $value, $cond, $value2)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE $table SET $colum = $value WHERE $cond = ?");
        $stmt->bind_param('i',$value2);
        $stmt->execute();
        $stmt->close();
    }


    //Conteo de alertas según la clasificación (Próximos mantenimientos)
    function CountAlertProxMant($date)
    {
        global $mysqli;

        $widget = "";

        $stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM report_teams_maint INNER JOIN teams_units_rsu ON report_teams_maint.fk_teams_report_maint = teams_units_rsu.id_teams_units WHERE DATEDIFF(proxdate_teams_maint, ?) = 2");
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $stmt->store_result();
        $sto = $stmt->num_rows;

        if($sto > 0)
        {

            $stmt->bind_result($total);
            $stmt->fetch();

            $widget = "<div class='col-lg-4 col-6'>
            
            <div class='small-box bg-light'>
            <div class='inner'>
                <h3>".$total."</h3>

                <p>Próximos mantenimientos</p>
            </div>
            <div class='icon'>                    
                <i class='fas fa-exclamation-circle' style='color: #F4D03F;'></i>
            </div>
            <a href='notifications' class='small-box-footer'>Mas Información <i class='fas fa-arrow-circle-right'></i></a>
            </div>
            </div>";

        }
        else
        {
            $widget = "<div class='col-lg-4 col-6'>
            
            <div class='small-box bg-light'>
            <div class='inner'>
                <h3>0</h3>

                <p>Próximos mantenimientos</p>
            </div>
            <div class='icon'>                    
                <i class='fas fa-exclamation-circle' style='color: #F4D03F;></i>
            </div>
            <a href='notifications' class='small-box-footer'>Mas Información <i class='fas fa-arrow-circle-right'></i></a>
            </div>
            </div>";
        }


        return $widget;
    }

    //Conteo de alertas según la clasificación (Ejecutar inmediatamanente)
    function CountAlertStock()
    {
        global $mysqli;

        $widget = "";

        $stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM spares_parts WHERE stock_spares < alarm_spares_stock");
        $stmt->execute();
        $stmt->store_result();
        $sto = $stmt->num_rows;

        if($sto > 0)
        {
            $stmt->bind_result($total);
            $stmt->fetch();

            $widget = "
          
          <div class='col-lg-4 col-6'>
             
                <div class='small-box bg-light'>
                  <div class='inner'>
                    <h3>".$total."</h3>

                    <p>Requisición inmediata</p>
                  </div>
                  <div class='icon'>
                  <i class='fas fa-plus' style='color: #27AE60;'></i>
                  </div>
                  <a href='notifications' class='small-box-footer'>Más Información <i class='fas fa-arrow-circle-right'></i></a>
                </div>
              </div>";
        }
        else
        {
            $widget = "<div class='col-lg-4 col-6'>
             
            <div class='small-box bg-light'>
              <div class='inner'>
                <h3>0</h3>

                <p>Requisición inmediata</p>
              </div>
              <div class='icon'>
              <i class='fas fa-plus' style='color: #27AE60;'></i>
              </div>
              <a href='notifications' class='small-box-footer'>Más Información <i class='fas fa-arrow-circle-right'></i></a>
            </div>
          </div>";

        }

        return $widget;

    }

    //Conteo de alertas según la clasificación (Ejecutar inmediatamente)
    function CountAlertInMant($date)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM report_teams_maint INNER JOIN teams_units_rsu ON report_teams_maint.fk_teams_report_maint = teams_units_rsu.id_teams_units WHERE proxdate_teams_maint <= ? AND proxdate_teams_maint <> '0000-00-00'");
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $stmt->store_result();
        $sto = $stmt->num_rows;

        if($sto > 0)
        {
            $stmt->bind_result($total);
            $stmt->fetch();

            $widget = "<div class='col-lg-4 col-6'>
            
            <div class='small-box bg-light'>
              <div class='inner'>
                <h3>".$total."</h3>

                <p>Ejecutar inmediatamente</p>
              </div>
              <div class='icon'>
              <i class='fas fa-exclamation-circle' style='color: #E74C3C;'></i>
              </div>
              <a href='notifications' class='small-box-footer'>Más Información <i class='fas fa-arrow-circle-right'></i></a>
            </div>
          </div>";
        }
        else
        {
            $widget = "<div class='col-lg-4 col-6'>
            
            <div class='small-box bg-light'>
              <div class='inner'>
                <h3>0</h3>

                <p>Ejecutar inmediatamente</p>
              </div>
              <div class='icon'>
              <i class='fas fa-exclamation-circle' style='color: #E74C3C;'></i>
              </div>
              <a href='notifications' class='small-box-footer'>Más Información <i class='fas fa-arrow-circle-right'></i></a>
            </div>
          </div>";
        }

        return $widget;
    }

    //ESTADISTICAS

    //Mantenimiento realizados cada mes y consultado por año
    function CountMantsForDateStatic()
    {
        global $mysqli;

        $year = date('Y');
        $total = array();

        for($month = 1; $month <= 12; $month ++)
        {
            $stmt = $mysqli->prepare("SELECT COUNT(*) AS TOTAL FROM report_teams_maint WHERE MONTH(date_teams_maint) = ? AND YEAR(date_teams_maint) = ? AND confirm_execute_teams_maint = 'Si'");
            $stmt->bind_param('ss',$month, $year);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num);
            $stmt->fetch();

            $total[] = $num;
        }

        return json_encode($total);
    }

    //Mantenimiento realizados cada mes y consultado por año
    function CountMantsForDate($year)
    {
        global $mysqli;

        $total = array();

        for($month = 1; $month <= 12; $month ++)
        {
            $stmt = $mysqli->prepare("SELECT COUNT(*) AS TOTAL FROM report_teams_maint WHERE MONTH(date_teams_maint) = ? AND YEAR(date_teams_maint) = ? AND confirm_execute_teams_maint = 'Si'");
            $stmt->bind_param('ss',$month, $year);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num);
            $stmt->fetch();

            $total[] = $num;
        }

        return json_encode($total);
    }

    //Consulta las horas paradas por mes
    function CountHoursTeams($year)
    {
        global $mysqli;
        $total = array();

        for($month = 1; $month <= 12; $month ++)
        {
            $stmt = $mysqli->prepare("SELECT SUM(time_stop_report_fails) AS TOTAL FROM report_fails WHERE MONTH(datereg_report_fails) = ? AND YEAR(datereg_report_fails) = ?");
            $stmt->bind_param('ss',$month, $year);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num);
            $stmt->fetch();

            $total[] = $num;
        }

        return json_encode($total);

    }

    //Consulta las horas paradas por mes
    function CountHoursTeamsStatic()
    {
        global $mysqli;

        $year = date('Y');
        $total = array();

        for($month = 1; $month <= 12; $month ++)
        {
            $stmt = $mysqli->prepare("SELECT SUM(time_stop_report_fails) AS TOTAL FROM report_fails WHERE MONTH(datereg_report_fails) = ? AND YEAR(datereg_report_fails) = ?");
            $stmt->bind_param('ss',$month, $year);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num);
            $stmt->fetch();

            $total[] = $num;
        }

        return json_encode($total);

    }

    //Cantidad de activos en cada RSU
    function CountActivesRSU()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT f.reference_units_rsu AS unidad, COUNT(t.name_teams_units) AS total FROM teams_units_rsu AS t INNER JOIN father_units_rsu AS f ON t.fk_id_father_teams_units = f.id_units_rsu GROUP BY f.reference_units_rsu");
        $stmt->execute();
        $res = $stmt-> get_result();

        $data = array();

        while($row = $res->fetch_array())
        {
            $data[] = $row;

        }

        return json_encode($data);

    }

    //Numero de mantenimiento realizados en cada RSU
    function CountMantRSU()
    {
        global $mysqli;

        $year = date('Y');

        $stmt = $mysqli->prepare("SELECT f.reference_units_rsu AS unidad, COUNT(r.reference_teams_report_mant) AS total FROM report_maint 
        AS r INNER JOIN teams_units_rsu AS t ON r.fk_teams_report_mant = t.id_teams_units INNER JOIN father_units_rsu AS
        f ON t.fk_id_father_teams_units = f.id_units_rsu WHERE YEAR(date_report_mant) = ? GROUP BY f.reference_units_rsu");
        $stmt->bind_param('s', $year);
        $stmt->execute();
        $res = $stmt-> get_result();

        $data = array();

        while($row = $res->fetch_array())
        {
            $data[] = $row;

        }

        return json_encode($data);

    }

    // CONSULTAS DE LOS REPORTES DEL APLICATIVO

    //---------------------OPCIONES DE CONSULTA ----------------------

    //OPCIONES DE ALMACÉN
    function OptionsWarehouse()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_warehouse, description_warehouse FROM warehouse");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_work, $name_work);

        while($stmt->fetch())
        {
            echo "<option value=".$id_work.">".$name_work."</option>";
        }
    }

    //OPCIONES DE UNIDADES
    function OptionsUnity()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_units_rsu, reference_units_rsu FROM father_units_rsu");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_work, $name_work);

        while($stmt->fetch())
        {
            echo "<option value=".$id_work.">".$name_work."</option>";
        }
    }

    //---------------------LISTADO DE ACTIVOS-------------------------

    //LISTADO DE ACTIVOS EN GENERAL
    function RMTTOActivesGeneral($date_ini, $date_end)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT concept_warehouse, num_concept_warehouse, description_element_spares,
        name_active_type, unity_spares, unity_value_spares, maker_spares, model_spares, serie_spares, 
        stock_spares FROM spares_parts INNER JOIN type_active ON spares_parts.type_element_spares = type_active.id_type_active 
        WHERE date_register_spares BETWEEN ? AND ?");
        $stmt->bind_param('ss', $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8, $val9, $val10);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 60px;'>UNIDAD</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>VALOR UNITARIO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>FABRICANTE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>STOCK</th>      
            
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;
                $ref = $val1."-".$val2;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'>".$ref."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 60px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align:center;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val7."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val8."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val9."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align: center;'>".$val10."</td>
                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 60px;'>UNIDAD</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>VALOR UNITARIO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>FABRICANTE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>STOCK</th>      
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 60px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align: center;'></td>
          
          </tr>";
        }

        return $table;



    }

    //LISTADO DE ACTIVOS POR ALMACÉN
    function RMTTOActivesWare($date_ini, $date_end, $id_warehouse)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT concept_warehouse, num_concept_warehouse, description_element_spares,
        name_active_type, unity_spares, unity_value_spares, maker_spares, model_spares, serie_spares, 
        stock_spares FROM spares_parts INNER JOIN type_active ON spares_parts.type_element_spares = type_active.id_type_active 
        WHERE warehouse_reference_spares = ? AND date_register_spares BETWEEN ? AND ?");
        $stmt->bind_param('iss',$id_warehouse, $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8, $val9, $val10);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 60px;'>UNIDAD</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>VALOR UNITARIO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>FABRICANTE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>STOCK</th>      
            
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;
                $ref = $val1."-".$val2;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'>".$ref."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 60px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align:center;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val7."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val8."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val9."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align: center;'>".$val10."</td>
                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 60px;'>UNIDAD</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>VALOR UNITARIO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>FABRICANTE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>STOCK</th>      
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 60px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 70px; text-align: center;'></td>
          
          </tr>";
        }

        return $table;



    }

    //LISTADO DE SALIDA DE ALMACÉN EN GENERAL
    function RMTTOOutputActiveGeneral($date_ini, $date_end)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT number_remission_output, date_output, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_remove 
        FROM output_actives INNER JOIN spares_parts ON output_actives.fk_active_output = spares_parts.id_spares
        WHERE date_output BETWEEN ? AND ?");
        $stmt->bind_param('ss', $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE RETIRO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 140px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 220px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD RETIRADA</th> 
            
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 140px; text-align: center;'>".$val3."".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 220px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'>".$val6."</td>      
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE RETIRO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD RETIRADA</th> 
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 160px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'></td>      
          </tr>";
        }

        return $table;

    }

    //LISTADO DE SALIDA DE ALMACÉN POR ALMACÉN
    function RMTTOOutputActoveWare($date_ini, $date_end, $id_warehouse)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT number_remission_output, date_output, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_remove 
        FROM output_actives INNER JOIN spares_parts ON output_actives.fk_active_output = spares_parts.id_spares
        WHERE fk_warehouse_output = ? AND date_output BETWEEN ? AND ?");
        $stmt->bind_param('iss', $id_warehouse, $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE RETIRO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 140px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 220px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD RETIRADA</th> 
            
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 140px; text-align: center;'>".$val3."".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 220px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'>".$val6."</td>      
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE RETIRO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD RETIRADA</th> 
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 160px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'></td>      
          </tr>";
        }

        return $table;
    }

    //LISTADO DE ENTRADA DE ALMACÉN GENERAL
    function RMTTOInputActiveGeneral($date_ini, $date_end)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT number_remission_input, date_input, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_add 
        FROM input_actives INNER JOIN spares_parts ON input_actives.fk_active_input = spares_parts.id_spares
        WHERE date_input BETWEEN ? AND ?");
        $stmt->bind_param('ss', $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE INGRESO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD INGRESADA</th> 
            
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>".$val3."".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'>".$val6."</td>
                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE INGRESO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD INGRESADA</th> 
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 160px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'></td>      
          </tr>";
        }

        return $table;
    }

    //LISTADO DE ENTRADA DE ALMACÉN POR ALMACÉN
    function RMTTOInputActiveWare($date_ini, $date_end, $warehouse)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT number_remission_input, date_input, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_add 
        FROM input_actives INNER JOIN spares_parts ON input_actives.fk_active_input = spares_parts.id_spares
        WHERE fk_warehouse_input = ? AND date_input BETWEEN ? AND ?");
        $stmt->bind_param('iss',$warehouse, $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE INGRESO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD INGRESADA</th> 
            
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>".$val3."".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'>".$val6."</td>
                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 104px;'>NO. REMISIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 120px;'>FECHA DE INGRESO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 160px;'>REFERENCIA ALMACÉN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL ELEMENTO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>CANTIDAD INGRESADA</th> 
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 104px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 120px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 160px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align:center;'></td>      
          </tr>";
        }

        return $table;
    }

    //LISTADO DE EQUIPOS EN GENERAL
    function RMTTOTeamsUnityGeneral($date_ini, $date_end)
    {
        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT letter_units_teams, number_teams_units, name_teams_units, type_teams_units, model_teams_units, 
        serie_teams_units, capacity_teams_units, mark_teams_units, plate_teams_units, dateregister_teams_units, 
        description_teams_units FROM teams_units_rsu WHERE dateregister_teams_units BETWEEN ? AND ?");
        $stmt->bind_param('ss', $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8, $val9, $val10, $val11);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA DEL EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE ACTIVO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>MARCA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>PLACA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA</th>
          </tr>
          <tr style='background-color: #AEB6BF; text-align:center;'>
            <th colspan='9' style='border-radius: 2px; border: 0.5px solid black; width: 70px;''>CARACTERISTICAS</th>     
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'>".$val1."-".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 105px; text-align:center;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val8."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val9."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val10."</td>
                
                </tr>
                <tr style='text-align:center;'>
                    <td colspan='9' style='border-radius: 2px; border: 0.5px solid black;'>".$val11."</td>
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA DEL EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE ACTIVO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>MARCA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>PLACA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA</th>
          </tr>
          <tr style='background-color: #AEB6BF; text-align:center;'>
            <th colspan='9' style='border-radius: 2px; border: 0.5px solid black; width: 70px;''>CARACTERISTICAS</th>     
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 105px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          
          </tr>
          <tr style='text-align:center;'>
              <td colspan='9' style='border-radius: 2px; border: 0.5px solid black;'>LAS CARACTERISTICAS DE CADA UNI DE LOS EQUIPOS DE LA RSU.</td>
          </tr>";
        }

        return $table;

    }

    //LISTADO DE EQUIPOS POR RSU
    function RMTTOTeamsUnityRsu($date_ini, $date_end, $unity)
    {

        global $mysqli;

        $table = "";

        $nums = 0;

        $stmt = $mysqli->prepare("SELECT letter_units_teams, number_teams_units, name_teams_units, type_teams_units, model_teams_units, 
        serie_teams_units, capacity_teams_units, mark_teams_units, plate_teams_units, dateregister_teams_units, 
        description_teams_units FROM teams_units_rsu WHERE fk_id_father_teams_units = ? AND dateregister_teams_units BETWEEN ? AND ?");
        $stmt->bind_param('iss',$unity, $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8, $val9, $val10, $val11);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA DEL EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE ACTIVO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>MARCA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>PLACA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA</th>
          </tr>
          <tr style='background-color: #AEB6BF; text-align:center;'>
            <th colspan='9' style='border-radius: 2px; border: 0.5px solid black; width: 70px;''>CARACTERISTICAS</th>     
          </tr>";



            while($stmt->fetch())
            {
                $nums = $nums + 1;

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$nums."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'>".$val1."-".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 105px; text-align:center;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val8."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val9."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val10."</td>
                
                </tr>
                <tr style='text-align:center;'>
                    <td colspan='9' style='border-radius: 2px; border: 0.5px solid black;'>".$val11."</td>
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO.</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>REFERENCIA DEL EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE ACTIVO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>MODELO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>SERIE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>MARCA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>PLACA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA</th>
          </tr>
          <tr style='background-color: #AEB6BF; text-align:center;'>
            <th colspan='9' style='border-radius: 2px; border: 0.5px solid black; width: 70px;''>CARACTERISTICAS</th>     
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 105px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          
          </tr>
          <tr style='text-align:center;'>
              <td colspan='9' style='border-radius: 2px; border: 0.5px solid black;'>LAS CARACTERISTICAS DE CADA UNI DE LOS EQUIPOS DE LA RSU.</td>
          </tr>";
        }

        return $table;

    }

    //REPORTE DE MANTENIMIENTOS EN GENERAL
    function RMTTOMantsGeneral($date_ini, $date_end)
    {
        global $mysqli;

        $table = "";


        $stmt = $mysqli->prepare("SELECT number_report_mant, type_activity_report_maint, location_report_mant, 
        reference_teams_report_mant, name_teams_report_mant, description_report_mant, 
        actor_execution_report_mant, date_report_mant FROM report_maint WHERE date_report_mant BETWEEN ? AND ?");
        $stmt->bind_param('ss', $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO. DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>LUGAR</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 90px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>NOMBRE DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>DESCRIPCIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL RESPONSABLE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA DE REGISTRO</th>
          </tr>";



            while($stmt->fetch())
            {

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 90px;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val7."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px; text-align: center;'>".$val8."</td>                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO. DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>LUGAR</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 90px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>NOMBRE DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>DESCRIPCIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL RESPONSABLE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA DE REGISTRO</th>
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 90px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px; text-align: center;'></td>
          
          </tr>";
        }

        return $table;
    }

    //REPORTE DE MANTENIMIENTOS POR UNIDAD
    function RMTTOMantsUnity($date_ini, $date_end, $unity)
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT number_report_mant, type_activity_report_maint, location_report_mant, 
        reference_teams_report_mant, name_teams_report_mant, description_report_mant, 
        actor_execution_report_mant, date_report_mant FROM report_maint INNER JOIN teams_units_rsu 
        ON report_maint.fk_teams_report_mant = teams_units_rsu.id_teams_units INNER JOIN father_units_rsu 
        ON teams_units_rsu.fk_id_father_teams_units = father_units_rsu.id_units_rsu WHERE father_units_rsu.id_units_rsu = ? AND date_report_mant BETWEEN ? AND ?");
        $stmt->bind_param('iss', $unity, $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO. DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>LUGAR</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 90px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>NOMBRE DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>DESCRIPCIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL RESPONSABLE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA DE REGISTRO</th>
          </tr>";



            while($stmt->fetch())
            {

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 90px;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>".$val7."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px; text-align: center;'>".$val8."</td>                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>NO. DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 80px;'>TIPO DE MANTENIMIEN TO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>LUGAR</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 90px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 105px;'>NOMBRE DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>DESCRIPCIÓN</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 200px;'>NOMBRE DEL RESPONSABLE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>FECHA DE REGISTRO</th>
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 80px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 90px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 105px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 200px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px; text-align: center;'></td>
          
          </tr>";
        }

        return $table;
    }

    //REPORTE DE FALLAS O AVERÍAS GENERAL
    function RMTTOFailsGeneral($date_ini, $date_end)
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT num_report_fails, name_teams_report_fails, reference_teams_report_fails, 
        time_stop_report_fails, datereg_report_fails, warning_ambiental_report_fails, warning_person_report_fails,
        description_report_fails FROM report_fails WHERE datereg_report_fails BETWEEN ? AND ?");
        $stmt->bind_param('ss', $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>COD. FALLA O AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 109px;'>EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>PROM. HORAS PARA DAS</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>FECHA DE FALLA / AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>RIESGO TRABA JADO RES</th> 
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>IMPAC TO MEDIO AMBIEN TE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 250px;'>DESCRIPCIÓN</th>
            
          </tr>";



            while($stmt->fetch())
            {

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 109px; text-align: center;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'>".$val7."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 250px;'>".$val8."</td>
                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>COD. FALLA O AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 109px;'>EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>PROM. HORAS PARA DAS</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>FECHA DE FALLA / AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>RIESGO TRABA JADO RES</th> 
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>IMPAC TO MEDIO AMBIEN TE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 250px;'>DESCRIPCIÓN</th>
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 109px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 70px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 250px;'></td>
          
          </tr>";
        }

        return $table;

    }

    //REPORTE DE FALLAS O AVERÍAS POR UNIDAD
    function RMTTOFailsUnity($date_ini, $date_end, $unity)
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT num_report_fails, name_teams_report_fails, reference_teams_report_fails, 
        time_stop_report_fails, datereg_report_fails, warning_ambiental_report_fails, warning_person_report_fails,
        description_report_fails FROM report_fails WHERE fk_units_report_fail = ? AND datereg_report_fails BETWEEN ? AND ?");
        $stmt->bind_param('iss', $unity, $date_ini, $date_end);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8);

            $table = "<tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>COD. FALLA O AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 109px;'>EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>PROM. HORAS PARA DAS</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>FECHA DE FALLA / AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>RIESGO TRABA JADO RES</th> 
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>IMPAC TO MEDIO AMBIEN TE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 250px;'>DESCRIPCIÓN</th>
            
          </tr>";



            while($stmt->fetch())
            {

                $table .="
                <tr>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$val1."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 109px; text-align: center;'>".$val2."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>".$val3."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'>".$val4."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>".$val5."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'>".$val6."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'>".$val7."</td>
                <td style='border-radius: 2px; border: 0.5px solid black; width: 250px;'>".$val8."</td>
                
                </tr>
                
               ";
            }

        }
        else
        {
            $table.= "
            <tr style='background-color: #AEB6BF; text-align:center;'>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>COD. FALLA O AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 109px;'>EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 100px;'>REFERENCIA DE EQUIPO</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>PROM. HORAS PARA DAS</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 70px;'>FECHA DE FALLA / AVERÍA</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>RIESGO TRABA JADO RES</th> 
            <th style='border-radius: 2px; border: 0.5px solid black; width: 40px;'>IMPAC TO MEDIO AMBIEN TE</th>
            <th style='border-radius: 2px; border: 0.5px solid black; width: 250px;'>DESCRIPCIÓN</th>
            
          </tr>
          <tr>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 109px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 100px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align: center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 70px;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 40px; text-align:center;'></td>
          <td style='border-radius: 2px; border: 0.5px solid black; width: 250px;'></td>
          
          </tr>";
        }

        return $table;
    }

    //CRONOGRAMA


    //Registra el cronograma dependiendo el día y hora establecida
    function NewSchedule($description, $year, $token_schedule)
    {
        global $mysqli;

        $mtto = new mtto();
        $dateregister = $mtto->DateMtto();

        $stmt = $mysqli->prepare("INSERT INTO maintenance_schedule(description_schedule_mant, date_register_schedule_mant, year_echedule_mant, token_echedule_mant) VALUES (?,?,?,?)");
        $stmt->bind_param('ssis', $description, $dateregister, $year, $token_schedule);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Devuelve la fecha para registrar.
    function DateSchedule()
    {
        date_default_timezone_set('America/Bogota');

        $date = date("Y-m-d");

        return $date;
    }

    //Consulta de cronogramas
    function SearchSchedule()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT description_schedule_mant, date_register_schedule_mant, 
        year_echedule_mant, token_echedule_mant FROM maintenance_schedule";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT description_schedule_mant, date_register_schedule_mant, 
        year_echedule_mant, token_echedule_mant FROM maintenance_schedule";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_schedule_mant LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $row[2];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='viewschedule?schedule=".$row[3]."' target='_blank'><i class='far fa-calendar-alt'></i></i></a>
                             
            </div>";

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);

    }

    //Busca las opciones de tipos de activos
    function OptionsTypeActivities()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_type_activies_schedule, description_type_activies_schedule FROM type_activies");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_work, $name_work);

        while($stmt->fetch())
        {
            echo "<option value=".$id_work.">".$name_work."</option>";
        }


    }

    //Registra una actividad o plan
    function RegisterActivies($token, $schedule, $category_activies, $description_activies, $resources_activies, $responsable_activies, $where_activies)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO plan_activities_schedule(token_activies_schedule, fk_schedule_id_activies, fk_type_activies_schedule, description_activies_schedule, 
        resources_activies_schedule, responsible_activies_schedule, where_activies_schedule) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('siissss', $token, $schedule, $category_activies, $description_activies, $resources_activies, $responsable_activies, $where_activies);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Registra parte cuantitativa de la actividad o plan
    function RegisterCuantiActivies($token, $schedule, $reg_act, $type_calf, $date)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO ratings_month_schedule(token_ratings_schedule, fk_schedule_id_ratings, fk_activies_id_ratings, fk_state_ratings_schedule, date_register_ratings) VALUES (?,?,?,?,?)");
        $stmt->bind_param('siiis', $token, $schedule, $reg_act, $type_calf, $date);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Realiza el conteo personalizada del cronograma
    function CountValuesSchedule($month, $state_value, $id_schedule)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT COUNT($month) FROM ratings_month_schedule WHERE fk_state_ratings_schedule = ? AND fk_schedule_id_ratings = ?");
        $stmt->bind_param('ii', $state_value, $id_schedule);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($total_count);
        $stmt->fetch();

    }

    //Busca los datos para el cronograma
    function SearchScheduleLast($id_schedule, $year_schedule, $schedule)
    {
        global $mysqli;

        $cron = "";

        $mtto = new mtto();

        $cron = "
            <table class='table table-bordered' style='width: 100%; font-size: 12px;'>
                    <tr style='text-align: center;'>
                        <th colspan='20' style='background-color: #EC7063;'>CRONOGRAMA</th>
                    </tr>
                    <tr style='text-align: center;'>
                        <th rowspan='2' style='background-color: #EC7063;'></th>
                        <th rowspan='2' style='background-color: #F1948A;'>Acciones</th>
                        <th rowspan='2' style='background-color: #F1948A;'>Plan/Actividades a realizar</th>
                        <th rowspan='2' style='background-color: #F1948A;'>Recursos</th>
                        <th rowspan='2' style='background-color: #F1948A;'>Responsable</th>
                        <th rowspan='2' style='background-color: #F1948A;'>P-Prog E-Ejec</th>
                        <th rowspan='2' style='background-color: #F1948A;'>Donde</th>
                        <th colspan='12' style='background-color: #F1948A;'>".$year_schedule."</th>                       
                        <th rowspan='2' style='background-color: #F1948A;'>SEGUIMIENTO/OBSERVACIONES</th>
                    </tr>

                    <tr style='text-align: center;'>                        
                        <th style='background-color: #F1948A'>ENE</th>
                        <th style='background-color: #F1948A'>FEB</th>
                        <th style='background-color: #F1948A'>MAR</th>
                        <th style='background-color: #F1948A'>ABR</th>
                        <th style='background-color: #F1948A'>MAY</th>
                        <th style='background-color: #F1948A'>JUN</th>
                        <th style='background-color: #F1948A'>JUL</th>
                        <th style='background-color: #F1948A'>AGO</th>
                        <th style='background-color: #F1948A'>SEP</th>
                        <th style='background-color: #F1948A'>OCT</th>
                        <th style='background-color: #F1948A' >NOV</th>
                        <th style='background-color: #F1948A'>DIC</th>
                    </tr>
            ";

        //ACTIVIDADES DE PLANEAR

        $stmt = $mysqli->prepare("SELECT id_ratings_schedule, description_activies_schedule, resources_activies_schedule, 
        responsible_activies_schedule, where_activies_schedule, description_type_activies_schedule, fk_activies_id_ratings FROM ratings_month_schedule AS 
        a INNER JOIN plan_activities_schedule AS b ON a.fk_activies_id_ratings = b.id_activies_schedule INNER JOIN type_activies AS 
        c ON b.fk_type_activies_schedule = c.id_type_activies_schedule INNER JOIN maintenance_schedule AS 
        d ON a.fk_schedule_id_ratings = d.id_schedule_mant WHERE b.fk_type_activies_schedule = 1 AND fk_schedule_id_ratings = ? GROUP BY fk_activies_id_ratings");
        $stmt->bind_param('i', $id_schedule);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_activie, $description, $resources, $responsable, $where, $description_type, $id_activies_fk);

            while($stmt->fetch()){

                //Busca los tipos de valores a calificar

                $stmto = $mysqli->prepare("SELECT id_type_ratings, concept_type_ratings FROM type_ratings");
                $stmto->execute();
                $stmto->store_result();
                $stmto->bind_result($id_work, $name_work);

                //Busca los valores de evaluación
                $stmtv = $mysqli->prepare("SELECT id_values_ratings, cant_values_ratings FROM values_ratings");
                $stmtv->execute();
                $stmtv->store_result();
                $stmtv->bind_result($id_work, $name_work);

                $cron.= "
                    <tr style='text-align: center;'>
                            <th rowspan='2' style='background-color: #F7DC6F'>".$description_type."</th>
                            <td rowspan='2' style='background-color: #F2F3F4'><div class='btn-group'>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercal".$id_activies_fk."'><i class='fas fa-clipboard-check'></i></button>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercoment".$id_activies_fk."'><i class='far fa-comment-dots'></i></button>
                            </div></td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$description."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$resources."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$responsable."</td>
                            <th style='background-color: #A9CCE3'>P</th>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$where."</td>

                            
                            <!-- modal de registrar calificación -->

                            <div class='modal fade' id='modal-registercal".$id_activies_fk."'>
                                <div class='modal-dialog'>
                                <form action='../functions/Register/InsertValueSchedule' method='POST'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h4 class='modal-title'>Registrar calificación</h4>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>
                                            <div class='callout callout-info'>
                                                <h5>Pasos para calificar una actividad:</h5>

                                                <p>                                                                             
                                                    1) Seleccionamos el mes, en la casilla Mes.<br>            
                                                    2) Seleccionamos si es ejecutado o programado, en la casilla Prog./Ejec.<br>            
                                                    3) Seleccionamos la calificación, en la casilla Valor evaluativo.<br>            
                                                    4) Presionamos sobre el botón <b>Registrar</b>.<br>                                   
                                                    
                                                </p>
                                            </div> 
                                        
                                        <div class='row'>
                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_activies_fk."' name='fk_activies'>                                                                                                    
                                                    <input type='hidden' class='form-control' value='".$id_schedule."' name='fk_schedule'>                                                                                                    
                                                    <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>                                                   
                                                </div>
                                            </div> 

                                            <div class='col-sm-4'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Mes<b style='color:#B20F0F;'>*</b></label>
                                                    <select class='form-control' name='month_activies' required>
                                                        <option value=''></option>
                                                        <option value='all_ratings'>TODOS</option>
                                                        <option value='january_rantings'>ENE</option>
                                                        <option value='february_rantings'>FEB</option>
                                                        <option value='march_rantings'>MAR</option>
                                                        <option value='april_rantings'>ABR</option>
                                                        <option value='may_rantings'>MAY</option>
                                                        <option value='june_rantings'>JUN</option>
                                                        <option value='july_rantings'>JUÑ</option>
                                                        <option value='august_rantings'>AGO</option>
                                                        <option value='september_rantings'>SEP</option>
                                                        <option value='october_rantings'>OCT</option>
                                                        <option value='november_ratings'>NOV</option>
                                                        <option value='december_ratings'>DIC</option>                                                        
                                                    </select>                                                   
                                                </div>
                                            </div>

                                            <div class='col-sm-4'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Prog./Ejec.<b style='color:#B20F0F;'>*</b></label>
                                                    <select class='form-control' name='category_activies' required>
                                                        <option value=''></option>

                                                        ";
                                                        while($stmto->fetch())
                                                        {
                                                            $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                        };

                                                        $cron.="                                                       
                                                                                                                                                                     
                                                    </select>                                                   
                                                </div>
                                            </div>

                                            <div class='col-sm-4'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Valor evaluativo<b style='color:#B20F0F;'>*</b></label>
                                                    <select class='form-control' name='value_activies' required>
                                                        <option value=''></option>

                                                        ";
                                                        while($stmtv->fetch())
                                                        {
                                                            $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                        };

                                                        $cron.="                                                       
                                                                                                                                                                     
                                                    </select>                                                   
                                                </div>
                                            </div>
                                            
                                        </div>                    
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                        <button type='submit' name='btnregistervalue' class='btn btn-success'>Registrar</button>
                                        </div>
                                     
                                        
                                    </div>
                                </form>
                                
                                </div>
                              
                            </div>

                            <!-- modal de registrar un comentario -->

                            <div class='modal fade' id='modal-registercoment".$id_activies_fk."'>
                                <div class='modal-dialog'>
                                <form action='../functions/Register/InsertCommentSchedule' method='POST'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h4 class='modal-title'>Registrar comentario</h4>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>

                                            <div class='callout callout-info'>
                                                <h5>Pasos para registrar Comentario:</h5>

                                                <p>                                                                             
                                                    1) Escrbimos las sugerencias, en la casilla Comentario.<br>
                                                    2) Presionamos la tecla Enter.<br>                                   
                                                    
                                                </p>
                                            </div>
                                        
                                        <div class='row'>
                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_activies_fk."'  name='id_activie'>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_schedule."'  name='id_schedule'>                                                   
                                                    <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>
                                                </div>
                                            </div> 

                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Comentario<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' name='coment_schedule' required>                                                                                  
                                                </div>
                                            </div> 

                                          
                                            
                                        </div>                    
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                        <button type='submit' class='btn btn-success' name='btnregistercoment'>Registrar</button>
                                        </div>
                                     
                                        
                                    </div>
                                </form>
                                
                                </div>
                                
                            </div>

                     
                ";
                $stmts = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 1");
                $stmts->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmts->execute();
                $stmts->store_result();
                $stmts->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);


                while($stmts->fetch()){

                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};



                    $cron.="
                    
                        <td style='background-color:#F2F3F4'>".$ene."</td>
                        <td style='background-color:#F2F3F4'>".$feb."</td>
                        <td style='background-color:#F2F3F4'>".$mar."</td>
                        <td style='background-color:#F2F3F4'>".$abr."</td>
                        <td style='background-color:#F2F3F4'>".$may."</td>
                        <td style='background-color:#F2F3F4'>".$jun."</td>
                        <td style='background-color:#F2F3F4'>".$jul."</td>
                        <td style='background-color:#F2F3F4'>".$ago."</td>
                        <td style='background-color:#F2F3F4'>".$sep."</td>
                        <td style='background-color:#F2F3F4'>".$oct."</td>
                        <td style='background-color:#F2F3F4'>".$nov."</td>
                        <td style='background-color:#F2F3F4'>".$dic."</td>                        

                    ";
                }

                $st = $mysqli->prepare("SELECT description_observation_schedule FROM observation_activies_schedule WHERE fk_schedule_id_observation = ? AND fk_activies_id_observation = ?");
                $st->bind_param('ii',$id_schedule, $id_activies_fk);
                $st->execute();
                $st->store_result();
                $st->bind_result($description_ob);

                $cron.=" 
                <td rowspan='2' style='background-color: #F2F3F4;'>";

                while($st->fetch())
                {
                    $cron.="<p style='font-size: 12px; text-align: left; margin: auto;'>".$description_ob."</p>";
                }

                $cron.=
                "</td>               
                </tr>
                ";

                $stmta = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 2");
                $stmta->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmta->execute();
                $stmta->store_result();
                $stmta->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmta->fetch()){

                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                    <tr style='text-align: center;'>                      
                      
                        <th style='background-color: #82E0AA'>E</th>

                        <td style='background-color: #F2F3F4'>".$ene."</td>
                        <td style='background-color: #F2F3F4'>".$feb."</tds>
                        <td style='background-color: #F2F3F4'>".$mar."</td>
                        <td style='background-color: #F2F3F4'>".$abr."</td>
                        <td style='background-color: #F2F3F4'>".$may."</td>
                        <td style='background-color: #F2F3F4'>".$jun."</td>
                        <td style='background-color: #F2F3F4'>".$jul."</td>
                        <td style='background-color: #F2F3F4'>".$ago."</td>
                        <td style='background-color: #F2F3F4'>".$sep."</td>
                        <td style='background-color: #F2F3F4'>".$oct."</td>
                        <td style='background-color: #F2F3F4'>".$nov."</td>
                        <td style='background-color: #F2F3F4'>".$dic."</td>
                      
                    </tr>
                    
                    ";
                }


            };

            // $cron.= "</table>";

        }
        else
        {
            $cron.= "";
        }

        //ACTIVIDADES DE HACER

        $stmt = $mysqli->prepare("SELECT id_ratings_schedule, description_activies_schedule, resources_activies_schedule, 
        responsible_activies_schedule, where_activies_schedule, description_type_activies_schedule, fk_activies_id_ratings FROM ratings_month_schedule AS 
        a INNER JOIN plan_activities_schedule AS b ON a.fk_activies_id_ratings = b.id_activies_schedule INNER JOIN type_activies AS 
        c ON b.fk_type_activies_schedule = c.id_type_activies_schedule INNER JOIN maintenance_schedule AS 
        d ON a.fk_schedule_id_ratings = d.id_schedule_mant WHERE b.fk_type_activies_schedule = 2 AND fk_schedule_id_ratings = ? GROUP BY fk_activies_id_ratings");
        $stmt->bind_param('i', $id_schedule);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_activie, $description, $resources, $responsable, $where, $description_type, $id_activies_fk);

            while($stmt->fetch()){

                //Busca los tipos de valores a calificar

                $stmto = $mysqli->prepare("SELECT id_type_ratings, concept_type_ratings FROM type_ratings");
                $stmto->execute();
                $stmto->store_result();
                $stmto->bind_result($id_work, $name_work);

                //Busca los valores de evaluación
                $stmtv = $mysqli->prepare("SELECT id_values_ratings, cant_values_ratings FROM values_ratings");
                $stmtv->execute();
                $stmtv->store_result();
                $stmtv->bind_result($id_work, $name_work);

                $cron.= "
                    <tr style='text-align: center;'>
                            <th rowspan='2' style='background-color: #D98880'>".$description_type."</th>
                            <td rowspan='2' style='background-color: #F2F3F4'><div class='btn-group'>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercal".$id_activies_fk."'><i class='fas fa-clipboard-check'></i></button>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercoment".$id_activies_fk."'><i class='far fa-comment-dots'></i></button>
                            </div></td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$description."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$resources."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$responsable."</td>
                            <th style='background-color: #A9CCE3'>P</th>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$where."</td>

                            <div class='modal fade' id='modal-registercal".$id_activies_fk."'>
                            <div class='modal-dialog'>
                            <form action='../functions/Register/InsertValueSchedule' method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                    <h4 class='modal-title'>Registrar calificación</h4>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    </div>
                                    <div class='modal-body'>

                                    <div class='callout callout-info'>
                                        <h5>Pasos para calificar una actividad:</h5>

                                        <p>                                                                             
                                            1) Seleccionamos el mes, en la casilla Mes.<br>            
                                            2) Seleccionamos si es ejecutado o programado, en la casilla Prog./Ejec.<br>            
                                            3) Seleccionamos la calificación, en la casilla Valor evaluativo.<br>            
                                            4) Presionamos sobre el botón <b>Registrar</b>.<br>                                   
                                            
                                        </p>
                                    </div> 
                                    
                                    <div class='row'>
                                        <div class='col-sm-12'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                <input type='hidden' class='form-control' value='".$id_activies_fk."' name='fk_activies'>                                                                                                    
                                                <input type='hidden' class='form-control' value='".$id_schedule."' name='fk_schedule'>                                                                                                    
                                                <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>                                                   
                                            </div>
                                        </div> 

                                        <div class='col-sm-4'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Mes<b style='color:#B20F0F;'>*</b></label>
                                                <select class='form-control' name='month_activies' required>
                                                    <option value=''></option>
                                                    <option value='all_ratings'>TODOS</option>
                                                    <option value='january_rantings'>ENE</option>
                                                    <option value='february_rantings'>FEB</option>
                                                    <option value='march_rantings'>MAR</option>
                                                    <option value='april_rantings'>ABR</option>
                                                    <option value='may_rantings'>MAY</option>
                                                    <option value='june_rantings'>JUN</option>
                                                    <option value='july_rantings'>JUÑ</option>
                                                    <option value='august_rantings'>AGO</option>
                                                    <option value='september_rantings'>SEP</option>
                                                    <option value='october_rantings'>OCT</option>
                                                    <option value='november_ratings'>NOV</option>
                                                    <option value='december_ratings'>DIC</option>                                                        
                                                </select>                                                   
                                            </div>
                                        </div>

                                        <div class='col-sm-4'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Prog./Ejec.<b style='color:#B20F0F;'>*</b></label>
                                                <select class='form-control' name='category_activies' required>
                                                    <option value=''></option>

                                                    ";
                                                    while($stmto->fetch())
                                                    {
                                                        $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                    };

                                                    $cron.="                                                       
                                                                                                                                                                 
                                                </select>                                                   
                                            </div>
                                        </div>

                                        <div class='col-sm-4'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Valor evaluativo<b style='color:#B20F0F;'>*</b></label>
                                                <select class='form-control' name='value_activies' required>
                                                    <option value=''></option>

                                                    ";
                                                    while($stmtv->fetch())
                                                    {
                                                        $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                    };

                                                    $cron.="                                                       
                                                                                                                                                                 
                                                </select>                                                   
                                            </div>
                                        </div>
                                        
                                    </div>                    
                                    </div>
                                    <div class='modal-footer justify-content-between'>
                                    <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                    <button type='submit' name='btnregistervalue' class='btn btn-success'>Registrar</button>
                                    </div>
                                 
                                    
                                </div>
                            </form>
                            
                            </div>
                          
                        </div>

                        <!-- modal de registrar un comentario -->

                        <div class='modal fade' id='modal-registercoment".$id_activies_fk."'>
                        <div class='modal-dialog'>
                        <form action='../functions/Register/InsertCommentSchedule' method='POST'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                <h4 class='modal-title'>Registrar comentario</h4>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                                </div>
                                <div class='modal-body'>

                                <div class='callout callout-info'>
                                    <h5>Pasos para registrar Comentario:</h5>

                                    <p>                                                                             
                                        1) Escrbimos las sugerencias, en la casilla Comentario.<br>
                                        2) Presionamos la tecla Enter.<br>                                   
                                        
                                    </p>
                                </div>
                                
                                <div class='row'>
                                    <div class='col-sm-12'>                
                                        <div class='form-group'>
                                            <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                            <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                            <input type='hidden' class='form-control' value='".$id_activies_fk."'  name='id_activie'>                                                   
                                            <input type='hidden' class='form-control' value='".$id_schedule."'  name='id_schedule'>                                                   
                                            <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>
                                        </div>
                                    </div> 

                                    <div class='col-sm-12'>                
                                        <div class='form-group'>
                                            <label for='inputSuccess'>Comentario<b style='color:#B20F0F;'>*</b></label>
                                            <input type='text' class='form-control' name='coment_schedule' required>                                                                                  
                                        </div>
                                    </div> 

                                  
                                    
                                </div>                    
                                </div>
                                <div class='modal-footer justify-content-between'>
                                <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                <button type='submit' class='btn btn-success' name='btnregistercoment'>Registrar</button>
                                </div>
                             
                                
                            </div>
                        </form>
                        
                        </div>
                        
                    </div>
                ";
                $stmts = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 1");
                $stmts->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmts->execute();
                $stmts->store_result();
                $stmts->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmts->fetch()){
                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                        <td style='background-color:#F2F3F4'>".$ene."</td>
                        <td style='background-color:#F2F3F4'>".$feb."</td>
                        <td style='background-color:#F2F3F4'>".$mar."</td>
                        <td style='background-color:#F2F3F4'>".$abr."</td>
                        <td style='background-color:#F2F3F4'>".$may."</td>
                        <td style='background-color:#F2F3F4'>".$jun."</td>
                        <td style='background-color:#F2F3F4'>".$jul."</td>
                        <td style='background-color:#F2F3F4'>".$ago."</td>
                        <td style='background-color:#F2F3F4'>".$sep."</td>
                        <td style='background-color:#F2F3F4'>".$oct."</td>
                        <td style='background-color:#F2F3F4'>".$nov."</td>
                        <td style='background-color:#F2F3F4'>".$dic."</td>

                    ";
                }

                $st = $mysqli->prepare("SELECT description_observation_schedule FROM observation_activies_schedule WHERE fk_schedule_id_observation = ? AND fk_activies_id_observation = ?");
                $st->bind_param('ii',$id_schedule, $id_activies_fk);
                $st->execute();
                $st->store_result();
                $st->bind_result($description_ob);

                $cron.=" 
                    <td rowspan='2' style='background-color: #F2F3F4'>";

                while($st->fetch())
                {
                    $cron.="<p>".$description_ob."</p>";
                }

                $cron.=
                "</td>               
                </tr>
                ";

                $stmta = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 2");
                $stmta->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmta->execute();
                $stmta->store_result();
                $stmta->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmta->fetch()){
                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                    <tr style='text-align: center;'>                      
                      
                        <th style='background-color: #82E0AA'>E</th>

                        <td style='background-color: #F2F3F4'>".$ene."</td>
                        <td style='background-color: #F2F3F4'>".$feb."</tds>
                        <td style='background-color: #F2F3F4'>".$mar."</td>
                        <td style='background-color: #F2F3F4'>".$abr."</td>
                        <td style='background-color: #F2F3F4'>".$may."</td>
                        <td style='background-color: #F2F3F4'>".$jun."</td>
                        <td style='background-color: #F2F3F4'>".$jul."</td>
                        <td style='background-color: #F2F3F4'>".$ago."</td>
                        <td style='background-color: #F2F3F4'>".$sep."</td>
                        <td style='background-color: #F2F3F4'>".$oct."</td>
                        <td style='background-color: #F2F3F4'>".$nov."</td>
                        <td style='background-color: #F2F3F4'>".$dic."</td>
              
                    </tr>
                    
                    ";
                }


            };

            // $cron.= "</table>";

        }
        else
        {
            $cron.= "";
        }

        //ACTIVIDADES DE VERIFICAR

        $stmt = $mysqli->prepare("SELECT id_ratings_schedule, description_activies_schedule, resources_activies_schedule, 
        responsible_activies_schedule, where_activies_schedule, description_type_activies_schedule, fk_activies_id_ratings FROM ratings_month_schedule AS 
        a INNER JOIN plan_activities_schedule AS b ON a.fk_activies_id_ratings = b.id_activies_schedule INNER JOIN type_activies AS 
        c ON b.fk_type_activies_schedule = c.id_type_activies_schedule INNER JOIN maintenance_schedule AS 
        d ON a.fk_schedule_id_ratings = d.id_schedule_mant WHERE b.fk_type_activies_schedule = 3 AND fk_schedule_id_ratings = ? GROUP BY fk_activies_id_ratings");
        $stmt->bind_param('i', $id_schedule);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_activie, $description, $resources, $responsable, $where, $description_type, $id_activies_fk);



            while($stmt->fetch()){

                //Busca los tipos de valores a calificar

                $stmto = $mysqli->prepare("SELECT id_type_ratings, concept_type_ratings FROM type_ratings");
                $stmto->execute();
                $stmto->store_result();
                $stmto->bind_result($id_work, $name_work);

                //Busca los valores de evaluación
                $stmtv = $mysqli->prepare("SELECT id_values_ratings, cant_values_ratings FROM values_ratings");
                $stmtv->execute();
                $stmtv->store_result();
                $stmtv->bind_result($id_work, $name_work);

                $cron.= "
                    <tr style='text-align: center;'>
                            <th rowspan='2' style='background-color: #5DADE2'>".$description_type."</th>
                            <td rowspan='2' style='background-color: #F2F3F4'><div class='btn-group'>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercal".$id_activies_fk."'><i class='fas fa-clipboard-check'></i></button>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercoment".$id_activies_fk."'><i class='far fa-comment-dots'></i></button>  
                            </div></td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$description."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$resources."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$responsable."</td>
                            <th style='background-color: #A9CCE3'>P</th>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$where."</td>
                            <div class='modal fade' id='modal-registercal".$id_activies_fk."'>
                            <div class='modal-dialog'>
                            <form action='../functions/Register/InsertValueSchedule' method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                    <h4 class='modal-title'>Registrar calificación</h4>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    </div>
                                    <div class='modal-body'>

                                    <div class='callout callout-info'>
                                        <h5>Pasos para calificar una actividad:</h5>

                                        <p>                                                                             
                                            1) Seleccionamos el mes, en la casilla Mes.<br>            
                                            2) Seleccionamos si es ejecutado o programado, en la casilla Prog./Ejec.<br>            
                                            3) Seleccionamos la calificación, en la casilla Valor evaluativo.<br>            
                                            4) Presionamos sobre el botón <b>Registrar</b>.<br>                                   
                                            
                                        </p>
                                    </div> 
                                        
                                    <div class='row'>
                                        <div class='col-sm-12'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                <input type='hidden' class='form-control' value='".$id_activies_fk."' name='fk_activies'>                                                                                                    
                                                <input type='hidden' class='form-control' value='".$id_schedule."' name='fk_schedule'>                                                                                                    
                                                <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>                                                   
                                            </div>
                                        </div> 

                                        <div class='col-sm-4'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Mes<b style='color:#B20F0F;'>*</b></label>
                                                <select class='form-control' name='month_activies' required>
                                                    <option value=''></option>
                                                    <option value='all_ratings'>TODOS</option>
                                                    <option value='january_rantings'>ENE</option>
                                                    <option value='february_rantings'>FEB</option>
                                                    <option value='march_rantings'>MAR</option>
                                                    <option value='april_rantings'>ABR</option>
                                                    <option value='may_rantings'>MAY</option>
                                                    <option value='june_rantings'>JUN</option>
                                                    <option value='july_rantings'>JUÑ</option>
                                                    <option value='august_rantings'>AGO</option>
                                                    <option value='september_rantings'>SEP</option>
                                                    <option value='october_rantings'>OCT</option>
                                                    <option value='november_ratings'>NOV</option>
                                                    <option value='december_ratings'>DIC</option>                                                        
                                                </select>                                                   
                                            </div>
                                        </div>

                                        <div class='col-sm-4'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Prog./Ejec.<b style='color:#B20F0F;'>*</b></label>
                                                <select class='form-control' name='category_activies' required>
                                                    <option value=''></option>

                                                    ";
                                                    while($stmto->fetch())
                                                    {
                                                        $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                    };

                                                    $cron.="                                                       
                                                                                                                                                                 
                                                </select>                                                   
                                            </div>
                                        </div>

                                        <div class='col-sm-4'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Valor evaluativo<b style='color:#B20F0F;'>*</b></label>
                                                <select class='form-control' name='value_activies' required>
                                                    <option value=''></option>

                                                    ";
                                                    while($stmtv->fetch())
                                                    {
                                                        $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                    };

                                                    $cron.="                                                       
                                                                                                                                                                 
                                                </select>                                                   
                                            </div>
                                        </div>
                                        
                                    </div>                    
                                    </div>
                                    <div class='modal-footer justify-content-between'>
                                    <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                    <button type='submit' name='btnregistervalue' class='btn btn-success'>Registrar</button>
                                    </div>
                                 
                                    
                                </div>
                            </form>
                            
                            </div>
                          
                        </div>

                        <!-- modal de registrar un comentario -->

                            <div class='modal fade' id='modal-registercoment".$id_activies_fk."'>
                            <div class='modal-dialog'>
                            <form action='../functions/Register/InsertCommentSchedule' method='POST'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                    <h4 class='modal-title'>Registrar comentario</h4>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    </div>
                                    <div class='modal-body'>

                                    <div class='callout callout-info'>
                                        <h5>Pasos para registrar Comentario:</h5>

                                        <p>                                                                             
                                            1) Escrbimos las sugerencias, en la casilla Comentario.<br>
                                            2) Presionamos la tecla Enter.<br>                                   
                                            
                                        </p>
                                    </div>
                                    
                                    <div class='row'>
                                        <div class='col-sm-12'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                <input type='hidden' class='form-control' value='".$id_activies_fk."'  name='id_activie'>                                                   
                                                <input type='hidden' class='form-control' value='".$id_schedule."'  name='id_schedule'>                                                   
                                                <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>
                                            </div>
                                        </div> 

                                        <div class='col-sm-12'>                
                                            <div class='form-group'>
                                                <label for='inputSuccess'>Comentario<b style='color:#B20F0F;'>*</b></label>
                                                <input type='text' class='form-control' name='coment_schedule' required>                                                                                  
                                            </div>
                                        </div> 

                                      
                                        
                                    </div>                    
                                    </div>
                                    <div class='modal-footer justify-content-between'>
                                    <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                    <button type='submit' class='btn btn-success' name='btnregistercoment'>Registrar</button>
                                    </div>
                                 
                                    
                                </div>
                            </form>
                            
                            </div>
                            
                        </div>
                ";
                $stmts = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 1");
                $stmts->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmts->execute();
                $stmts->store_result();
                $stmts->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmts->fetch()){
                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                    <td style='background-color:#F2F3F4'>".$ene."</td>
                    <td style='background-color:#F2F3F4'>".$feb."</td>
                    <td style='background-color:#F2F3F4'>".$mar."</td>
                    <td style='background-color:#F2F3F4'>".$abr."</td>
                    <td style='background-color:#F2F3F4'>".$may."</td>
                    <td style='background-color:#F2F3F4'>".$jun."</td>
                    <td style='background-color:#F2F3F4'>".$jul."</td>
                    <td style='background-color:#F2F3F4'>".$ago."</td>
                    <td style='background-color:#F2F3F4'>".$sep."</td>
                    <td style='background-color:#F2F3F4'>".$oct."</td>
                    <td style='background-color:#F2F3F4'>".$nov."</td>
                    <td style='background-color:#F2F3F4'>".$dic."</td>

                    ";
                }

                $st = $mysqli->prepare("SELECT description_observation_schedule FROM observation_activies_schedule WHERE fk_schedule_id_observation = ? AND fk_activies_id_observation = ?");
                $st->bind_param('ii',$id_schedule, $id_activies_fk);
                $st->execute();
                $st->store_result();
                $st->bind_result($description_ob);

                $cron.=" 
                    <td rowspan='2' style='background-color: #F2F3F4'>";

                while($st->fetch())
                {
                    $cron.="<p>".$description_ob."</p>";
                }

                $cron.=
                    "</td>               
                    </tr>
                ";

                $stmta = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 2");
                $stmta->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmta->execute();
                $stmta->store_result();
                $stmta->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmta->fetch()){

                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                    <tr style='text-align: center;'>                      
                      
                    <th style='background-color: #82E0AA'>E</th>

                    <td style='background-color: #F2F3F4'>".$ene."</td>
                    <td style='background-color: #F2F3F4'>".$feb."</tds>
                    <td style='background-color: #F2F3F4'>".$mar."</td>
                    <td style='background-color: #F2F3F4'>".$abr."</td>
                    <td style='background-color: #F2F3F4'>".$may."</td>
                    <td style='background-color: #F2F3F4'>".$jun."</td>
                    <td style='background-color: #F2F3F4'>".$jul."</td>
                    <td style='background-color: #F2F3F4'>".$ago."</td>
                    <td style='background-color: #F2F3F4'>".$sep."</td>
                    <td style='background-color: #F2F3F4'>".$oct."</td>
                    <td style='background-color: #F2F3F4'>".$nov."</td>
                    <td style='background-color: #F2F3F4'>".$dic."</td>
              
                    </tr>
                    
                    ";
                }


            };

            // $cron.= "</table>";

        }
        else
        {
            $cron.= "";
        }

        //ACTIVIDADES DE ACTUAR

        $stmt = $mysqli->prepare("SELECT id_ratings_schedule, description_activies_schedule, resources_activies_schedule, 
        responsible_activies_schedule, where_activies_schedule, description_type_activies_schedule, fk_activies_id_ratings FROM ratings_month_schedule AS 
        a INNER JOIN plan_activities_schedule AS b ON a.fk_activies_id_ratings = b.id_activies_schedule INNER JOIN type_activies AS 
        c ON b.fk_type_activies_schedule = c.id_type_activies_schedule INNER JOIN maintenance_schedule AS 
        d ON a.fk_schedule_id_ratings = d.id_schedule_mant WHERE b.fk_type_activies_schedule = 4 AND fk_schedule_id_ratings = ? GROUP BY fk_activies_id_ratings");
        $stmt->bind_param('i', $id_schedule);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_activie, $description, $resources, $responsable, $where, $description_type, $id_activies_fk);



            while($stmt->fetch()){

                //Busca los tipos de valores a calificar

                $stmto = $mysqli->prepare("SELECT id_type_ratings, concept_type_ratings FROM type_ratings");
                $stmto->execute();
                $stmto->store_result();
                $stmto->bind_result($id_work, $name_work);

                //Busca los valores de evaluación
                $stmtv = $mysqli->prepare("SELECT id_values_ratings, cant_values_ratings FROM values_ratings");
                $stmtv->execute();
                $stmtv->store_result();
                $stmtv->bind_result($id_work, $name_work);


                $cron.= "
                    <tr style='text-align: center;'>
                            <th rowspan='2' style='background-color: #C39BD3'>".$description_type."</th>
                            <td rowspan='2' style='background-color: #F2F3F4'><div class='btn-group'>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercal".$id_activies_fk."'><i class='fas fa-clipboard-check'></i></button>
                            <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercoment".$id_activies_fk."'><i class='far fa-comment-dots'></i></button>  
                            </div></td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$description."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$resources."</td>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$responsable."</td>
                            <th style='background-color: #A9CCE3'>P</th>
                            <td rowspan='2' style='background-color: #F2F3F4'>".$where."</td>

                            <div class='modal fade' id='modal-registercal".$id_activies_fk."'>
                                <div class='modal-dialog'>
                                <form action='../functions/Register/InsertValueSchedule' method='POST'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h4 class='modal-title'>Registrar calificación</h4>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>

                                        <div class='callout callout-info'>
                                            <h5>Pasos para calificar una actividad:</h5>

                                            <p>                                                                             
                                                1) Seleccionamos el mes, en la casilla Mes.<br>            
                                                2) Seleccionamos si es ejecutado o programado, en la casilla Prog./Ejec.<br>            
                                                3) Seleccionamos la calificación, en la casilla Valor evaluativo.<br>            
                                                4) Presionamos sobre el botón <b>Registrar</b>.<br>                                   
                                                
                                            </p>
                                        </div> 
                                        
                                        <div class='row'>
                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_activies_fk."' name='fk_activies'>                                                                                                    
                                                    <input type='hidden' class='form-control' value='".$id_schedule."' name='fk_schedule'>                                                                                                    
                                                    <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>                                                   
                                                </div>
                                            </div> 

                                            <div class='col-sm-4'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Mes<b style='color:#B20F0F;'>*</b></label>
                                                    <select class='form-control' name='month_activies' required>
                                                        <option value=''></option>
                                                        <option value='all_ratings'>TODOS</option>
                                                        <option value='january_rantings'>ENE</option>
                                                        <option value='february_rantings'>FEB</option>
                                                        <option value='march_rantings'>MAR</option>
                                                        <option value='april_rantings'>ABR</option>
                                                        <option value='may_rantings'>MAY</option>
                                                        <option value='june_rantings'>JUN</option>
                                                        <option value='july_rantings'>JUÑ</option>
                                                        <option value='august_rantings'>AGO</option>
                                                        <option value='september_rantings'>SEP</option>
                                                        <option value='october_rantings'>OCT</option>
                                                        <option value='november_ratings'>NOV</option>
                                                        <option value='december_ratings'>DIC</option>                                                        
                                                    </select>                                                   
                                                </div>
                                            </div>

                                            <div class='col-sm-4'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Prog./Ejec.<b style='color:#B20F0F;'>*</b></label>
                                                    <select class='form-control' name='category_activies' required>
                                                        <option value=''></option>

                                                        ";
                                                        while($stmto->fetch())
                                                        {
                                                            $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                        };

                                                        $cron.="                                                       
                                                                                                                                                                     
                                                    </select>                                                   
                                                </div>
                                            </div>

                                            <div class='col-sm-4'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Valor evaluativo<b style='color:#B20F0F;'>*</b></label>
                                                    <select class='form-control' name='value_activies' required>
                                                        <option value=''></option>

                                                        ";
                                                        while($stmtv->fetch())
                                                        {
                                                            $cron.="<option value=".$id_work.">".$name_work."</option>";
                                                        };

                                                        $cron.="                                                       
                                                                                                                                                                     
                                                    </select>                                                   
                                                </div>
                                            </div>
                                            
                                        </div>                    
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                        <button type='submit' name='btnregistervalue' class='btn btn-success'>Registrar</button>
                                        </div>
                                     
                                        
                                    </div>
                                </form>
                                
                                </div>
                              
                            </div>

                            <!-- modal de registrar un comentario -->

                            <div class='modal fade' id='modal-registercoment".$id_activies_fk."'>
                                <div class='modal-dialog'>
                                <form action='../functions/Register/InsertCommentSchedule' method='POST'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h4 class='modal-title'>Registrar comentario</h4>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>

                                            <div class='callout callout-info'>
                                                <h5>Pasos para registrar Comentario:</h5>

                                                <p>                                                                             
                                                    1) Escrbimos las sugerencias, en la casilla Comentario.<br>
                                                    2) Presionamos la tecla Enter.<br>                                   
                                                    
                                                </p>
                                            </div>

                                        
                                        <div class='row'>
                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_activies_fk."'  name='id_activie'>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_schedule."'  name='id_schedule'>                                                   
                                                    <input type='hidden' class='form-control' value='".$schedule."'  name='tk_schedule'>
                                                </div>
                                            </div> 

                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Comentario<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' name='coment_schedule' required>                                                                                  
                                                </div>
                                            </div> 

                                          
                                            
                                        </div>                    
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                        <button type='submit' class='btn btn-success' name='btnregistercoment'>Registrar</button>
                                        </div>
                                     
                                        
                                    </div>
                                </form>
                                
                                </div>
                                
                            </div>
                ";
                $stmts = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 1");
                $stmts->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmts->execute();
                $stmts->store_result();
                $stmts->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmts->fetch()){

                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-info' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                    <td style='background-color:#F2F3F4'>".$ene."</td>
                    <td style='background-color:#F2F3F4'>".$feb."</td>
                    <td style='background-color:#F2F3F4'>".$mar."</td>
                    <td style='background-color:#F2F3F4'>".$abr."</td>
                    <td style='background-color:#F2F3F4'>".$may."</td>
                    <td style='background-color:#F2F3F4'>".$jun."</td>
                    <td style='background-color:#F2F3F4'>".$jul."</td>
                    <td style='background-color:#F2F3F4'>".$ago."</td>
                    <td style='background-color:#F2F3F4'>".$sep."</td>
                    <td style='background-color:#F2F3F4'>".$oct."</td>
                    <td style='background-color:#F2F3F4'>".$nov."</td>
                    <td style='background-color:#F2F3F4'>".$dic."</td>

                    ";
                }

                $st = $mysqli->prepare("SELECT description_observation_schedule FROM observation_activies_schedule WHERE fk_schedule_id_observation = ? AND fk_activies_id_observation = ?");
                $st->bind_param('ii',$id_schedule, $id_activies_fk);
                $st->execute();
                $st->store_result();
                $st->bind_result($description_ob);

                $cron.=" 
                    <td rowspan='2' style='background-color: #F2F3F4'>";

                while($st->fetch())
                {
                    $cron.="<p>".$description_ob."</p>";
                }

                $cron.=
                "</td>               
                </tr>
                ";

                $stmta = $mysqli->prepare("SELECT id_ratings_schedule, january_rantings, february_rantings, march_rantings, april_rantings, may_rantings, june_rantings, july_rantings,
                august_rantings, september_rantings, october_rantings, november_ratings, december_ratings FROM ratings_month_schedule WHERE fk_schedule_id_ratings = ? AND fk_activies_id_ratings = ? AND fk_state_ratings_schedule = 2");
                $stmta->bind_param('ii', $id_schedule, $id_activies_fk);
                $stmta->execute();
                $stmta->store_result();
                $stmta->bind_result($id_values, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic);

                while($stmta->fetch()){

                    if($ene == 1){$ene = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=january_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($feb == 1){$feb = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=february_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($mar == 1){$mar = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=march_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($abr == 1){$abr = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=april_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($may == 1){$may = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=may_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jun == 1){$jun = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=june_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($jul == 1){$jul = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=july_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($ago == 1){$ago = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=august_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($sep == 1){$sep = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=september_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($oct == 1){$oct = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=october_rantings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($nov == 1){$nov = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=november_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};
                    if($dic == 1){$dic = "<a href='../functions/Delete/DeleteValueSchedule?idvalue=".$id_values."&month=december_ratings&tkschedule=".$schedule."' class='btn btn-success' style='width: 30px; height: 30px;'></a>";};

                    $cron.="
                    <tr style='text-align: center;'>                      
                      
                        <th style='background-color: #82E0AA'>E</th>

                        <td style='background-color: #F2F3F4'>".$ene."</td>
                        <td style='background-color: #F2F3F4'>".$feb."</tds>
                        <td style='background-color: #F2F3F4'>".$mar."</td>
                        <td style='background-color: #F2F3F4'>".$abr."</td>
                        <td style='background-color: #F2F3F4'>".$may."</td>
                        <td style='background-color: #F2F3F4'>".$jun."</td>
                        <td style='background-color: #F2F3F4'>".$jul."</td>
                        <td style='background-color: #F2F3F4'>".$ago."</td>
                        <td style='background-color: #F2F3F4'>".$sep."</td>
                        <td style='background-color: #F2F3F4'>".$oct."</td>
                        <td style='background-color: #F2F3F4'>".$nov."</td>
                        <td style='background-color: #F2F3F4'>".$dic."</td>
              
                    </tr>
                    
                    ";
                }


            };

            $st = $mysqli->prepare("SELECT 
            COUNT(january_rantings) AS enero,
            COUNT(february_rantings) AS febrero,
            COUNT(march_rantings) AS marzo,
            COUNT(april_rantings) AS abril,
            COUNT(may_rantings) AS mayo,
            COUNT(june_rantings) AS junio,
            COUNT(july_rantings) AS julio,
            COUNT(august_rantings) AS agosto,
            COUNT(september_rantings) AS septiembre,
            COUNT(october_rantings) AS octubre,
            COUNT(november_ratings) AS noviembre,
            COUNT(december_ratings) AS diciembre FROM ratings_month_schedule WHERE fk_state_ratings_schedule = 1 AND fk_schedule_id_ratings = ?");
            $st->bind_param('i', $id_schedule);
            $st->execute();
            $st->store_result();

                $st->bind_result($ene1, $feb1, $mar1, $abr1, $may1, $jun1, $jul1, $ago1, $sep1, $oct1, $nov1, $dic1);
                $st->fetch();

                $sts = $mysqli->prepare("SELECT 
                COUNT(january_rantings) AS enero,
                COUNT(february_rantings) AS febrero,
                COUNT(march_rantings) AS marzo,
                COUNT(april_rantings) AS abril,
                COUNT(may_rantings) AS mayo,
                COUNT(june_rantings) AS junio,
                COUNT(july_rantings) AS julio,
                COUNT(august_rantings) AS agosto,
                COUNT(september_rantings) AS septiembre,
                COUNT(october_rantings) AS octubre,
                COUNT(november_ratings) AS noviembre,
                COUNT(december_ratings) AS diciembre FROM ratings_month_schedule WHERE fk_state_ratings_schedule = 2 AND fk_schedule_id_ratings = ?");
                $sts->bind_param('i', $id_schedule);
                $sts->execute();
                $sts->store_result();
                $sts->bind_result($ene2, $feb2, $mar2, $abr2, $may2, $jun2, $jul2, $ago2, $sep2, $oct2, $nov2, $dic2);
                $sts->fetch();


                        @$ene = $ene2 / $ene1 * 100;
                        @$feb = $feb2 / $feb1 * 100;
                        @$mar = $mar2 / $mar1 * 100;
                        @$abr = $abr2 / $abr1 * 100;
                        @$may = $may2 / $may1 * 100;
                        @$jun = $may2 / $may1 * 100;
                        @$jul = $jul2 / $jul1 * 100;
                        @$ago = $ago2 / $ago1 * 100;
                        @$sep = $sep2 / $sep1 * 100;
                        @$oct = $oct2 / $oct1 * 100;
                        @$nov = $nov2 / $nov1 * 100;
                        @$dic = $dic2 / $dic1 * 100;





                        $cron.="<tr>
                        <th colspan='7' style='text-align: center; background-color: #F1948A;'>% CUMPLIMIENTO (No. actividades ejecutadas/No. actividades programadas x 100)</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($ene))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($feb))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($mar))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($abr))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($may))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($jun))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($jul))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($ago))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($sep))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($oct))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($nov))."%</th>
                        <th style='text-align: center; font-size: 20px; '>".round(number_format($dic))."%</th>          
                        </tr>";


            $cron.= "</table>";


        }
        else
        {
            $cron.= "";
        }

        return $cron;
    }

    //Inserta la evaluación de manera general
    function InsertValueScheduleGen($id_activies, $category_activies, $id_schedule)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE ratings_month_schedule SET january_rantings = 1, february_rantings = 1, march_rantings = 1,
        april_rantings = 1, may_rantings = 1, june_rantings = 1, july_rantings = 1, august_rantings = 1, september_rantings = 1, 
        october_rantings = 1, november_ratings = 1, december_ratings = 1 WHERE fk_activies_id_ratings = ? AND fk_state_ratings_schedule = ? AND fk_schedule_id_ratings = ?");
        $stmt->bind_param('iii', $id_activies, $category_activies, $id_schedule);
        $stmt->execute();
        $stmt->close();
    }

    //Inserta la evaluación de manera individual
    function InsertValueScheduleInd($month_activies, $value_activies, $id_activies, $category_activies, $id_schedule)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE ratings_month_schedule SET $month_activies = ? WHERE fk_activies_id_ratings = ? AND fk_state_ratings_schedule = ? AND fk_schedule_id_ratings = ?");
        $stmt->bind_param('iiii', $value_activies, $id_activies, $category_activies, $id_schedule);
        $stmt->execute();
        $stmt->close();
    }

    //Elimina el valor según la evaluación seleccionada
    function UpdateValueInd($month, $id_value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE ratings_month_schedule SET $month = NULL WHERE id_ratings_schedule = ?");
        $stmt->bind_param('i', $id_value);
        $stmt->execute();
        $stmt->close();
    }

    //Registra un nuevo cronograma
    function RegisterScheduleNew($year, $date, $token)
    {
        global $mysqli;

        $description = "Cronograma - ".$year;

        $stmt = $mysqli->prepare("INSERT INTO maintenance_schedule(description_schedule_mant, date_register_schedule_mant, year_echedule_mant, token_echedule_mant) VALUES (?,?,?,?)");
        $stmt->bind_param('ssis',$description, $date, $year, $token);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Realiza la inserción de actvidades con el nuevo cronograma
    function InsertActiviesNew($id_schedule, $id_activie, $state_rating, $date)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO ratings_month_schedule(fk_schedule_id_ratings, fk_activies_id_ratings, fk_state_ratings_schedule, date_register_ratings) VALUES (?,?,?,?)");
        $stmt->bind_param('iiis', $id_schedule, $id_activie, $state_rating, $date);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Inserta el plan de actividades según el cronograma
    function ConsultPlanActivies($id_schedule, $date)
    {
        global $mysqli;

        $mtto = new mtto();

        $stmt = $mysqli->prepare("SELECT id_activies_schedule FROM plan_activities_schedule");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_activies);

            while($stmt->fetch())
            {
                $state_uno = 1;
                $state_dos = 2;

                $mtto->InsertActiviesNew($id_schedule, $id_activies, $state_uno, $date);
                $mtto->InsertActiviesNew($id_schedule, $id_activies, $state_dos, $date);
            }

            $stmt->close();
        }
        else
        {
            $stmt->close();
        }


    }

    //Inserta comentario por cada actividad
    function InsertComentSchedule($coment, $id_schedule, $id_activie)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO observation_activies_schedule(fk_schedule_id_observation, fk_activies_id_observation, description_observation_schedule) VALUES (?,?,?)");
        $stmt->bind_param('iis',$id_schedule, $id_activie, $coment);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }


    //INDICADORES

    //SE CONTINUA EL DESARROLLO DESPUES DE ACLARACIONES DE INDICADORES ESTABLECIDOS PARA EL AÑO 2022.

    //Busca el historial de los indicadores
    function SearchHistoryIndicators()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT 	token_history_indicators, date_reg_indicators_history, description_history_indicators, 
        year_history_indication FROM history_indicators";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT 	token_history_indicators, date_reg_indicators_history, description_history_indicators, 
        year_history_indication FROM history_indicators";

        if(!empty($resquest['search']['value']))
        {
            $sql.= "description_history_indicators LIKE '".$resquest['search']['value']."%'";
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            $subdata = array();
            $subdata[] = $row[2];
            $subdata[] = $row[1];
            $subdata[] = $row[3];
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='viewindicators?indicators=".$row[0]."' target='_blank'><i class='far fa-calendar-alt'></i></i></a>
                             
            </div>";

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);


    }

    //Consulta los indicadores en meta programda
    function SearchMeta($id_history_indicator, $indicator)
    {
        global $mysqli;

        $table = "";

        $mtto = new mtto();

        $stmt = $mysqli->prepare("SELECT b.id_meta_indicator_program, a.description_indicator_program, b.activies_evidences_indicator, b.value_meta_indicator, c.description_frequency 
        FROM indicator_program AS a INNER JOIN meta_indicator_program AS b 
        ON a.id_indicator_program = b.fk_indicator_program INNER JOIN frequency_measuring_indicator AS c 
        ON b.fk_frequency_measuring = c.id_frequency_measuring 
        WHERE b.fk_history_indicators_meta = ?");
        $stmt->bind_param('i', $id_history_indicator);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $table = "
                <table class='table table-bordered' style='font-size: 12px;'>
                    <tr style='background-color: #FFB8B8'>
                        <th>IMPACTO DE RIESGO QUE CONTROLA</th>
                        <th rowspan='2'>ACCIONES</th>
                        <th rowspan='2'>INDICADOR</th>
                        <th rowspan='2'>META PROGRAMADA</th>
                        <th rowspan='2'>FRECUENCIA PARA MEDIR EL INDICADOR</th>
                        <th rowspan='2'>ACTIVIDADES O EVIDENCIAS</th>
                        <th>RESPONSABILIDADES</th>
                        
                    </tr>
                    
                    <tr>
                        <td rowspan='8' style='text-align: justify;'>Todos los riesgos y todos los aspectos</td>
                        
                        
                        <td rowspan='8' style='text-align: justify;'>Área SSTA: Velar y garantizar que se realice mantenimiento correctivo / preventivo según necesidad al 100% de los vehículos, maquinaria, herramientas, equipos, accesorios e instalaciones utilizadas en las actividades de la empresa.
                            Solicitar diligenciamiento de formatos de  mantenimiento.
                            Coordinador de mantenimiento: Deben garantizar la realización del buen mantenimiento al 100% de los vehículos, maquinaria, herramientas, equipos y accesorios utilizados por la empresa.
                        </td>
                    </tr>
            ";

            $stmt->bind_result($id_activie, $description, $evidences, $value, $frequency);

            while($stmt->fetch())
            {
                $stmto = $mysqli->prepare("SELECT id_frequency_measuring, description_frequency FROM frequency_measuring_indicator");
                $stmto->execute();
                $stmto->store_result();
                $stmto->bind_result($id_work, $name_work);



                $table.= "
                    <tr>
                        <td style='text-align: center;'><div>
                        <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercal".$id_activie."'><i class='fas fa-clipboard-check'></i></button>
                        </div></td>
                        <td style='text-align: justify;'>".$description."</td>
                        <td>
                            <div class='input-group col-12'>
                                <div class='input-group-prepend'>
                                <span style='background-color: #F8F9F9;'  class='input-group-text'><i class='fas fa-percentage'></i></span>
                                </div>
                                <input type='text' class='form-control form-control-sm' value='".$value."' disabled>
                            </div>
                        </td>
   
                        <td style='text-align: center;'>".$frequency."</td>
                        <td style='text-align: center;'>".$evidences."</td>
                    </tr>

                    <div class='modal fade' id='modal-registercal".$id_activie."'>
                                <div class='modal-dialog'>
                                <form action='../functions/Update/UpdatePorcent' method='POST'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                        <h4 class='modal-title'>Registrar porcentaje</h4>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div>
                                        <div class='modal-body'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-12'>                
                                                <div class='form-group'>
                                                    <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                                    <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                                    <input type='hidden' class='form-control' value='".$id_activie."' name='id_activies'>                                                                                                
                                                    <input type='hidden' class='form-control' value='".$indicator."' name='indicator_tk'>                                                                                                
                                                  
                                                </div>
                                            </div>
                                            
                                            <div class='input-group col-4'>
                                                <div class='input-group-prepend'>
                                                <span style='background-color: #F8F9F9;'  class='input-group-text'><i class='fas fa-percentage'></i></span>
                                                </div>
                                                <input type='number' class='form-control' name='value_porcent' required>
                                            </div>

                                            <div class='input-group col-8'>
                                                <div class='input-group-prepend'>
                                                <span style='background-color: #F8F9F9;'  class='input-group-text'>Frecuencia</span>
                                                </div>
                                                <select class='form-control' name='frequency' required>";
                                                while($stmto->fetch())
                                                {
                                                    $table.="<option value=".$id_work.">".$name_work."</option>";
                                                };

                                                $table.="
                                                    
                                                </select>
                                            </div>

                                            
                                            
                                        </div>                    
                                        </div>
                                        <div class='modal-footer justify-content-between'>
                                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                                        <button type='submit' name='btnregistervalue' class='btn btn-success'>Registrar</button>
                                        </div>
                                     
                                        
                                    </div>
                                </form>
                                
                                </div>
                              
                            </div>
                ";
            }

            $table.= "</table>";

        }
        else
        {
            $table.= "";
        }

        return $table;


    }

    //Registra un nuevo indicador anual
    function RegisterIndicatorAnual($tk_ind_anual, $datereg_ind, $des_ind, $year)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO history_indicators(token_history_indicators, date_reg_indicators_history, description_history_indicators, year_history_indication) VALUES (?,?,?,?)");
        $stmt->bind_param('sssi',$tk_ind_anual, $datereg_ind, $des_ind, $year);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Inserta la frecuencia de los indicadores
    function InsertIndicatorFrequency($id_reg, $value_meta, $fk_frequency, $activies_ind, $fk_indicator)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO meta_indicator_program(fk_history_indicators_meta, value_meta_indicator, fk_frequency_measuring, 	activies_evidences_indicator, fk_indicator_program) VALUES (?,?,?,?,?)");
        $stmt->bind_param('iiisi', $id_reg, $value_meta, $fk_frequency, $activies_ind, $fk_indicator);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Inserta la matriz de evaluación de los indicadores

    function InsertIndicatorMonth($fk_indicator, $id_reg)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO month_indicators_quantity (fk_indicators_program, fk_history_indicators_quantity) VALUES (?,?)");
        $stmt->bind_param('ii', $fk_indicator, $id_reg);
        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Registra los indicadores con las frecuencias establecidas
    function RegisterIndicatorsFrequency($id_reg)
    {
        global $mysqli;

        $mtto = new mtto();

        $stmt = $mysqli->prepare("SELECT value_meta_indicator, fk_frequency_measuring, activies_evidences_indicator, fk_indicator_program FROM meta_indicator_program LIMIT 7");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($value_meta, $fk_frequency, $activies_ind, $fk_indicator);

            while($stmt->fetch())
            {
                $mtto->InsertIndicatorFrequency($id_reg, $value_meta, $fk_frequency, $activies_ind, $fk_indicator);
                $mtto->InsertIndicatorMonth($fk_indicator, $id_reg);
            }
            $stmt->close();
        }
        else
        {
            $stmt->close();
        }
    }

    //Actualiza los valores cuantitativos del indicador
    function UpdateQuantityIndicator($v_ene, $v_feb, $v_mar, $v_abr, $v_may, $v_jun, $v_jul, $v_ago, $v_sep, $v_oct, $v_nov, $v_dic, $id_month_indicator)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE month_indicators_quantity SET january_indicator = ?, february_indicator = ?, march_indicator = ?, april_indicator = ?,
        may_indicator = ?, june_indicator = ?, july_indicator = ?, august_indicator = ?, september_indicator = ?, october_indicator = ?, 
        november_indicator = ?, december_indicator = ? WHERE id_indicators_program = ?");
        $stmt->bind_param('iiiiiiiiiiiii', $v_ene, $v_feb, $v_mar, $v_abr, $v_may, $v_jun, $v_jul, $v_ago, $v_sep, $v_oct, $v_nov, $v_dic, $id_month_indicator);
        $stmt->execute();
        $stmt->close();
    }

    //Consulta seguimiento de indicador
    function SearchIndicatorValues($id_history_indicator, $indicator)
    {
        global $mysqli;

        $cons = new mtto();

        $year = $cons->getValueMtto('year_history_indication', 'history_indicators', 'id_history_indicators', $id_history_indicator);

        $table = "";

        $table = "<table class='table table-bordered' style='font-size: 12px;'>

            <tr>
                <th colspan='15' style='text-align: center; background-color: #FFB8B8;'>SEGUMIENTO INDICADOR</th>
            </tr>
            <tr style='text-align: center;'>
                <th rowspan='2' style='background-color: #FFD0D0;'>INDICADOR</th>
                <th colspan='12' style='background-color: #FFD0D0;'>".$year."</th>
                <th rowspan='2' style='background-color: #FFD0D0;'>SEGUIMIENTO / OBSERVACIONES</th>
                <th rowspan='2' style='background-color: #FFD0D0;'>ACCIÓN</th>
                
            </tr>
            <tr style='text-align: center; background-color: #FFD0D0;'>
                <th>ENE</th>
                <th>FEB</th>
                <th>MAR</th>
                <th>ABR</th>
                <th>MAY</th>
                <th>JUN</th>
                <th>JUL</th>
                <th>AGO</th>
                <th>SEP</th>
                <th>OCT</th>
                <th>NOV</th>
                <th>DIC</th>
            </tr>";

        $stmt = $mysqli->prepare("SELECT a.id_indicator_program, a.description_indicator_program, b.comment_indicators_quantity, d.id_frequency_measuring, d.description_frequency, b.id_indicators_program,
        b.january_indicator,
        b.february_indicator, b.march_indicator, b.april_indicator, b.may_indicator, b.june_indicator,
        b.july_indicator, b.august_indicator, b.september_indicator, b.october_indicator, b.november_indicator,
        b.december_indicator
         FROM indicator_program AS a INNER JOIN month_indicators_quantity AS b ON
         a.id_indicator_program = b.fk_indicators_program INNER JOIN meta_indicator_program AS c ON 
         a.id_indicator_program = c.fk_indicator_program INNER JOIN frequency_measuring_indicator AS d ON 
         c.fk_frequency_measuring = d.id_frequency_measuring WHERE c.fk_history_indicators_meta = ? AND b.fk_history_indicators_quantity = ?");
        $stmt->bind_param('ii', $id_history_indicator, $id_history_indicator);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {

            $stmt->bind_result($id_indicator, $description, $comment, $frequency, $description_frequency, $id_mont_indicator,
                                $ene_v, $feb_v, $mar_v, $abr_v, $may_v, $jun_v, $jul_v, $ago_v, $sep_v, $oct_v, $nov_v, $dic_v);

            while($stmt->fetch()){

                switch($id_indicator){
                    //Listado de indicadores

                    //INDICADOR DE CUMPLIMIENTO
                    case 1:
                        switch($frequency){
                            case 1:


                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);


                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                 //CONSULTA DATOS HASTA NOVIEMBRE

                                 $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                 $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                 $prom_anual = ($value_ene + $value_feb + $value_mar +
                                                $value_abr + $value_may + $value_jun +
                                                $value_jul + $value_ago + $value_sep +
                                                $value_oct + $value_nov + $value_dec) / 12;

                                 $valueene = '';
                                 $valuefeb = '';
                                 $valuemar = '';
                                 $valueabr = '';
                                 $valuemay = '';
                                 $valuejun = '';
                                 $valuejul = '';
                                 $valueago = '';
                                 $valuesep = '';
                                 $valueoct = '';
                                 $valuenov = '';
                                 $valuedic = round($prom_anual);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);

                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);


                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                    case 2:
                        switch($frequency){
                            case 1:
                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                //CONSULTA DATOS HASTA NOVIEMBRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                $prom_anual = ($value_ene + $value_feb + $value_mar +
                                               $value_abr + $value_may + $value_jun +
                                               $value_jul + $value_ago + $value_sep +
                                               $value_oct + $value_nov + $value_dec) / 12;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = '';
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_anual);


                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);


                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                    case 3:
                        switch($frequency){
                            case 1:

                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                //CONSULTA DATOS HASTA NOVIEMBRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                $prom_anual = ($value_ene + $value_feb + $value_mar +
                                               $value_abr + $value_may + $value_jun +
                                               $value_jul + $value_ago + $value_sep +
                                               $value_oct + $value_nov + $value_dec) / 12;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = '';
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_anual);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);

                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                    case 4:
                        switch($frequency){
                            case 1:

                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                //CONSULTA DATOS HASTA NOVIEMBRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                $prom_anual = ($value_ene + $value_feb + $value_mar +
                                               $value_abr + $value_may + $value_jun +
                                               $value_jul + $value_ago + $value_sep +
                                               $value_oct + $value_nov + $value_dec) / 12;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = '';
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_anual);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);

                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                    case 5:
                        switch($frequency){
                            case 1:

                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                //CONSULTA DATOS HASTA NOVIEMBRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                $prom_anual = ($value_ene + $value_feb + $value_mar +
                                               $value_abr + $value_may + $value_jun +
                                               $value_jul + $value_ago + $value_sep +
                                               $value_oct + $value_nov + $value_dec) / 12;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = '';
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_anual);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);

                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                    case 6:
                        switch($frequency){
                            case 1:

                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                //CONSULTA DATOS HASTA NOVIEMBRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                $prom_anual = ($value_ene + $value_feb + $value_mar +
                                               $value_abr + $value_may + $value_jun +
                                               $value_jul + $value_ago + $value_sep +
                                               $value_oct + $value_nov + $value_dec) / 12;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = '';
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_anual);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);

                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                    case 7:
                        switch($frequency){
                            case 1:

                                //CONSULTA DATOS DEL PRIMER SEMESTRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                //CONSULTA LOS DATOS DEL SEGUNDO SEMESTRE

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_sem1 = ($value_ene + $value_feb + $value_mar + $value_abr + $value_may + $value_jun) / 6;
                                $prom_sem2 = ($value_jul + $value_ago + $value_sep + $value_oct + $value_nov + $value_dec) / 6;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($prom_sem1);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_sem2);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 2:

                                //CONSULTA DATOS HASTA NOVIEMBRE

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dec = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);


                                $prom_anual = ($value_ene + $value_feb + $value_mar +
                                               $value_abr + $value_may + $value_jun +
                                               $value_jul + $value_ago + $value_sep +
                                               $value_oct + $value_nov + $value_dec) / 12;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = '';
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = '';
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = '';
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($prom_anual);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#FCF3CF';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#FCF3CF';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#FCF3CF';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 3:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $prom_bi_1 = ($value_ene + $value_feb) / 2;
                                $prom_bi_2 = ($value_mar + $value_abr) / 2;
                                $prom_bi_3 = ($value_may + $value_jun) / 2;
                                $prom_bi_4 = ($value_jul + $value_ago) / 2;
                                $prom_bi_5 = ($value_sep + $value_oct) / 2;
                                $prom_bi_6 = ($value_nov + $value_dic) / 2;

                                $valueene = '';
                                $valuefeb = round($prom_bi_1);
                                $valuemar = '';
                                $valueabr = round($prom_bi_2);
                                $valuemay = '';
                                $valuejun = round($prom_bi_3);
                                $valuejul = '';
                                $valueago = round($prom_bi_4);
                                $valuesep = '';
                                $valueoct = round($prom_bi_5);
                                $valuenov = '';
                                $valuedic = round($prom_bi_6);

                                $ene = '#FCF3CF';
                                $feb = '#AED6F1';
                                $mar = '#FCF3CF';
                                $abr = '#AED6F1';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#AED6F1';
                                $sep = '#FCF3CF';
                                $oct = '#AED6F1';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 4:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $promtri_1 = ($value_ene + $value_feb + $value_mar) / 3;
                                $promtri_2 = ($value_abr + $value_may + $value_jun) / 3;
                                $promtri_3 = ($value_jul + $value_ago + $value_sep) / 3;
                                $promtri_4 = ($value_oct + $value_nov + $value_dic) / 3;

                                $valueene = '';
                                $valuefeb = '';
                                $valuemar = round($promtri_1);
                                $valueabr = '';
                                $valuemay = '';
                                $valuejun = round($promtri_2);
                                $valuejul = '';
                                $valueago = '';
                                $valuesep = round($promtri_3);
                                $valueoct = '';
                                $valuenov = '';
                                $valuedic = round($promtri_4);

                                $ene = '#FCF3CF';
                                $feb = '#FCF3CF';
                                $mar = '#AED6F1';
                                $abr = '#FCF3CF';
                                $may = '#FCF3CF';
                                $jun = '#AED6F1';
                                $jul = '#FCF3CF';
                                $ago = '#FCF3CF';
                                $sep = '#AED6F1';
                                $oct = '#FCF3CF';
                                $nov = '#FCF3CF';
                                $dic = '#AED6F1';
                            break;

                            case 5:

                                $value_ene = $cons->getValueMtto('january_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_feb = $cons->getValueMtto('february_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_mar = $cons->getValueMtto('march_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_abr = $cons->getValueMtto('april_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_may = $cons->getValueMtto('may_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jun = $cons->getValueMtto('june_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_jul = $cons->getValueMtto('july_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_ago = $cons->getValueMtto('august_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_sep = $cons->getValueMtto('september_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_oct = $cons->getValueMtto('october_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_nov = $cons->getValueMtto('november_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);
                                $value_dic = $cons->getValueMtto('december_indicator', 'month_indicators_quantity', 'id_indicators_program', $id_mont_indicator);

                                $valueene = $value_ene;
                                $valuefeb = $value_feb;
                                $valuemar = $value_mar;
                                $valueabr = $value_abr;
                                $valuemay = $value_may;
                                $valuejun = $value_jun;
                                $valuejul = $value_jul;
                                $valueago = $value_ago;
                                $valuesep = $value_sep;
                                $valueoct = $value_oct;
                                $valuenov = $value_nov;
                                $valuedic = $value_dic;

                                $ene = '#AED6F1';
                                $feb = '#AED6F1';
                                $mar = '#AED6F1';
                                $abr = '#AED6F1';
                                $may = '#AED6F1';
                                $jun = '#AED6F1';
                                $jul = '#AED6F1';
                                $ago = '#AED6F1';
                                $sep = '#AED6F1';
                                $oct = '#AED6F1';
                                $nov = '#AED6F1';
                                $dic = '#AED6F1';
                            break;

                        }
                    break;

                };

                $table.="
                <tr>
                <td>".$description."</td>
                <td><input style='background-color:".$ene."' type='text' value='".$valueene."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$feb."' type='text' value='".$valuefeb."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$mar."' type='text' value='".$valuemar."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$abr."' type='text' value='".$valueabr."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$may."' type='text' value='".$valuemay."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$jun."' type='text' value='".$valuejun."' class='form-control form-control-sm' disabled></td>                               
                <td><input style='background-color:".$jul."' type='text' value='".$valuejul."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$ago."' type='text' value='".$valueago."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$sep."' type='text' value='".$valuesep."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$oct."' type='text' value='".$valueoct."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$nov."' type='text' value='".$valuenov."' class='form-control form-control-sm' disabled></td>
                <td><input style='background-color:".$dic."' type='text' value='".$valuedic."' class='form-control form-control-sm' disabled></td>
                <td>".$comment."</td>
                <td><button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registercomment".$id_indicator."'><i class='fas fa-comment-alt'></i></button>
                    <button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#modal-registerporcent".$id_indicator."'><i class='fas fa-percent'></i></button>
                </td>

                </tr>

                <div class='modal fade' id='modal-registercomment".$id_indicator."'>
                    <div class='modal-dialog'>
                    <form action='../functions/Update/AddObservationIndicators' method='POST'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>Registrar observaciones</h4>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>
                            <div class='modal-body'>
                            
                            <div class='row'>
                                <div class='col-sm-12'>                
                                    <div class='form-group'>
                                        <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                        <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                        <input type='hidden' class='form-control' value='".$id_mont_indicator."' name='id_indicator_month'>                                                                                                
                                        <input type='hidden' class='form-control' value='".$indicator."' name='indicator_tk'>                                                                                               
                                        
                                    </div>
                                </div>
                                
                                <div class='col-sm-12'>
                                
                                <div class='form-group'>
                                    <label>Textarea</label>
                                    <textarea class='form-control' rows='3' name='observation' placeholder='Enter ...' >".$comment."</textarea>
                                </div>
                                </div>

                                
                            </div>                    
                            </div>
                            <div class='modal-footer justify-content-between'>
                            <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                            <button type='submit' name='btnaddobservation' class='btn btn-success'>Registrar</button>
                            </div>
                            
                            
                        </div>
                    </form>
                    
                    </div>
                    
                </div>

                <div class='modal fade' id='modal-registerporcent".$id_indicator."'>
                    <div class='modal-dialog modal-xl'>
                    <form action='../functions/Update/AddQuantityIndicators' method='POST'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h4 class='modal-title'>Registrar porcentaje</h4>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>
                            <div class='modal-body'>                                                       
                            <div class='row'>
                                <div class='col-sm-12'>                
                                    <div class='form-group'>
                                        <label for='inputSuccess'>Descripción de la actividad<b style='color:#B20F0F;'>*</b></label>
                                        <input type='text' class='form-control' value='".$description."' required disabled>                                                   
                                        <input type='hidden' class='form-control' value='".$id_mont_indicator."' name='id_indicator_month'>                                                                                                
                                        <input type='hidden' class='form-control' value='".$indicator."' name='indicator_tk'>                                                                                               
                                        
                                    </div>
                                </div>
                                <div class='col-sm-3'>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>ENERO</span>
                                        </div>                                    
                                        <input type='number' class='form-control' name='v_ene' value='".$ene_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>MAYO</span>
                                        </div>                                    
                                        <input type='number' class='form-control' name='v_may' value='".$may_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>SEPTIEMBRE</span>
                                        </div>                                    
                                        <input type='number' class='form-control' name='v_sep' value='".$sep_v."'>
                                    </div>
                                
                                
                                </div>

                                <div class='col-sm-3'>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>FEBRERO</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_feb' value='".$feb_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>JUNIO</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_jun' value='".$jun_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>OCTUBRE</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_oct' value='".$oct_v."'>
                                    </div>
                                
                                
                                </div>

                                <div class='col-sm-3'>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>MARZO</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_mar' value='".$mar_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>JULIO</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_jul' value='".$jul_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>NOVIEMBRE</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_nov' value='".$nov_v."'>
                                    </div>
                                
                                
                                </div>

                                <div class='col-sm-3'>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>ABRIL</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_abr' value='".$abr_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>AGOSTO</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_ago' value='".$ago_v."'>
                                    </div>

                                    <div class='input-group mb-1'>
                                        <div class='input-group-prepend'>
                                        <span class='input-group-text'>DICIEMBRE</span>
                                        </div>                                    
                                        <input type='text' class='form-control' name='v_dic' value='".$dic_v."'>
                                    </div>
                                
                                
                                </div>

                            </div>                    
                            </div>
                            <div class='modal-footer justify-content-between'>
                            <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                            <button type='submit' name='btnaddquantity' class='btn btn-success'>Registrar</button>
                            </div>
                            
                            
                        </div>
                    </form>
                    
                    </div>
                    
                </div>
                
                ";

            };

        }

        else
        {
            $table.= "";
        }

        $table.="</table>";

        return $table;

    }

    //Actualiza el porcentaje de los indicadores base y cronograma
    function UpdatePorcentIndicators($value_porcent, $frequency, $id_activies)
    {
        global $mysqli;

        $stmt1 = $mysqli->prepare("UPDATE meta_indicator_program SET value_meta_indicator = ?, fk_frequency_measuring = ? WHERE id_meta_indicator_program = ?");
        $stmt1->bind_param('iii', $value_porcent, $frequency, $id_activies);
        $stmt1->execute();
        $stmt1->close();

    }

    //Adicionar observaciones en los indicadores
    function AddObservationInd($id_mont_indicator, $observation)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE month_indicators_quantity SET comment_indicators_quantity = ? WHERE id_indicators_program = ?");
        $stmt->bind_param('si', $observation, $id_mont_indicator);
        $stmt->execute();
        $stmt->close();

    }

    //CONSUMIBLES

    //Registrar reporte de consumibles
    function RegisterReportConsumables($token_consum, $date_register, $initial_delivery, $reposition_delivery, $description_consum, $site, $team, $contract, $value_total, $user_id)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO consumables_report (token_consumables, dateregister_consumables, 
        delivery_initial_consumables, delivery_reposition_consumables, description_consumables, site_consumables, 
        rsu_consumables, contract_consumables, valuetotal_consumables, fk_id_user_consumables) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssiissisii', $token_consum, $date_register, $initial_delivery, $reposition_delivery, $description_consum, $site, $team, $contract, $value_total, $user_id);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Actualiza el estado de reporte de los productos de los consumibles
    function UpdateStateReportConsu($reg_report)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE products_consumables SET fk_id_report_consumables = ?, state_products_report = 1 WHERE state_products_report = 0");
        $stmt->bind_param('i', $reg_report);
        $stmt->execute();
        $stmt->close();
    }

    //Registrar articulos de los consumibles
    function RegisterArticlesConsumables($token, $description, $quantity, $price, $observation)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO products_consumables (token_products_consumables, description_products_consumables, 
        quantity_products_consumables, price_products_consumables, observation_products_consumables) VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssiis', $token, $description, $quantity, $price, $observation);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }

    }

    //Eliminar productos de los consumibles
    function DeleteProductCons($token_product)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("DELETE FROM products_consumables WHERE token_products_consumables = ?");
        $stmt->bind_param('s', $token_product);
        if($stmt->execute())
        {
            return true;
            }else{
            return false;
        }
    }

    //Consulta la información de los productos del consumible
    function SearchProductsConsumables($id_user)
    {
        global $mysqli;

        $form = "";

        $cont = 0;
        $suma = 0;

        $opt = new mtto();




        $form.= "<table class='table table-bordered'>
        <thead style='text-align: center;'>
        <tr><th colspan='6' style='background-color: #F7F9F9; text-algn: center;'>ENTREGA DE HERRAMIENTAS Y/O MATERIALES A UNIDADES RSU</th></tr>
          <tr style='background-color: #F7F9F9;'>                                  
            <th>Item</th>
            <th>Descripción del material y/o herramientas</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Observaciones</th>
            <th>Acción</th>
          </tr>
        </thead>";

        $stmt = $mysqli->prepare("SELECT id_products_consumables, token_products_consumables, description_products_consumables, quantity_products_consumables, 
        price_products_consumables, observation_products_consumables FROM products_consumables WHERE state_products_report = 0");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {

            $stmt->bind_result($id_product, $token_product, $description, $quantity, $price, $observation);

            while($stmt->fetch())
            {
                $cont = $cont + 1;

                $suma += $price;

                $form.= "
                <tr>
                    <th style='text-align: center;'>".$cont."</th>
                    <td>".$description."</td>
                    <td style='text-align: center;'>".$quantity."</td>
                    <td><input style='background-color: #D4E6F1; font-weight: bold;' type='text' class='form-control' value='$".number_format($price)."' disabled></td>
                    <td>".$observation."</td>
                    <td style='text-align: center;'><button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#modal-deleteuno".$id_product."'><i class='fas fa-ban'></i></button></td>
                </tr>

                <div class='modal fade' id='modal-deleteuno".$id_product."'>
						<div class='modal-dialog'>
                        <form action='../functions/Delete/DeleteProductConsumables' method='POST'>
						<div class='modal-content'>
							<div class='modal-header'>
							<h4 class='modal-title'>Confirmación de eliminación</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>
                            <input type='hidden' value='".$token_product."' name='tk_product'>
							<div class='modal-body'>
							<p>¿Desea confirma la eliminación del concepto? <br><br>						
							<b>Descripción: </b>".$description."<br>
							<b>Cantidad: </b>".$quantity."<br>
							<b>Valor: </b>$".number_format($price)."</b></p>
							</div>
							<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
							<button type='submit' class='btn btn-success'>Aceptar</button>							
							</div>
						</div>
                        </form>
						</div>
					</div>
                ";

            }
            $stmt->close();

            $opt = $mysqli->prepare("SELECT id_units_rsu, reference_units_rsu FROM father_units_rsu");
            $opt->execute();
            $rs = $opt->get_result();

            $form.= "
            <form action='../functions/Register/InsertProductsConsumables' method='POST'>

            <tr style='background-color: #F7F9F9;'>                                  
                <td></td>
                <td><input style='background-color: #FCF3CF' type='text' class='form-control' name='description_consumables' required></td>
                <td><input style='background-color: #FCF3CF' type='number' class='form-control' name='quantity_consumables' required></td>
                <td><input style='background-color: #FCF3CF' type='number' class='form-control' name='price_consumables' required></td>
                <td><input style='background-color: #FCF3CF' type='text' class='form-control' name='observation_consumables' required></td>
                <td style='background-color: #F7F9F9; text-align: center;'><button type='submit' class='btn btn-success btn-sm' name='btnproductscons'><i class='fas fa-plus'></i></button></td>
            </tr>

            </form>

            </table>
            
            <div class='row no-print'>
            <div class='col-12'>
            
            <button type='button' class='btn btn-success float-right' data-toggle='modal' data-target='#modal-confirm'><i class='fas fa-edit'></i>
            Registrar Entrega
            </button>
            
            </div>
            </div>

                <div class='modal fade' id='modal-confirm'>
                <form action='../functions/Register/InsertConsumablesReport' method='POST'>
                    <div class='modal-dialog modal-lg'>                            
                    <div class='modal-content'>
                        <div class='modal-header'>
                        <h4 class='modal-title'>Confirmación de registro</h4>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                        <div class='row'>
                        <div class='col-6'>
                            <input type='hidden' name='user_id' value='".$id_user."'>
                            <b>Entrega inicial:</b>  <input type='checkbox' name='initial_delivery' value='1' class='form-control'>
                            </div>
                            <div class='col-6'>
                            <b>Entrega por reposicón:</b>  <input type='checkbox' name='reposition_delivery' value='1' class='form-control'>
                            </div>
                            <div class='col-12'>					
                            <b>Descripción:</b>  <input type='text' name='description' class='form-control' required>
                            </div>                                      
                            <div class='col-6'>					
                            <b>Lugar:</b>  <input type='text' name='site' class='form-control' required>
                            </div>
                            <div class='col-6'>					
                            <b>RSU:</b>
                                <select name='rsu' id='cbx_rsu' class='form-control' required>
                                    <option>Seleccione Unidad RSU</option>
                                    ";
                                    foreach($rs as $op_rsu):
                                        $form.="<option value='".$op_rsu['id_units_rsu']."'>".$op_rsu['reference_units_rsu']."</option>";

                                    endforeach;

                                    $form.="
                                    
                                </select>
                            </div>
                            <div class='col-12'>					
                            <b>Equipo / Maquinaria:</b>
                                <select name='team' id='cbx_team' class='form-control' required>                                  
                                    
                                </select>
                            </div>
                            <div class='col-6'>					
                            <b>Contrato:</b>  <input type='text' name='contract' class='form-control' required>
                            </div>
                            <div class='col-6'>	
                            <input type='hidden' name='value_total' value='".$suma."'>				
                            <b>Valor total:</b>  <input style='font-weight: bold;' type='text'  value='$".number_format($suma)."' class='form-control' disabled>
                            </div>
                        </div>                            
                
                        </div>
                <div class='modal-footer justify-content-between'>
                    <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
                    <button type='submit' class='btn btn-success' name='btnregister'>Aceptar</button>							
                </div>
                </div>
                
                </div>
                </form>
            </div>
            
            </div>";

        }
        else
        {
            $form.="
            
                <tr>
                    <th colspan='6' style='text-align: center;'>NO EXISTEN REGISTROS</th>
                </tr>
                <form action='../functions/Register/InsertProductsConsumables' method='POST'>

                <tr style='background-color: #F7F9F9;'>                                  
                    <td></td>
                    <td><input style='background-color: #FCF3CF' type='text' class='form-control' name='description_consumables' required></td>
                    <td><input style='background-color: #FCF3CF' type='number' class='form-control' name='quantity_consumables' required></td>
                    <td><input style='background-color: #FCF3CF' type='number' class='form-control' name='price_consumables' required></td>
                    <td><input style='background-color: #FCF3CF' type='text' class='form-control' name='observation_consumables' required></td>
                    <td style='background-color: #F7F9F9; text-align: center;'><button type='submit' class='btn btn-success btn-sm' name='btnproductscons'><i class='fas fa-plus'></i></button></td>
                </tr>

                </form>
                
                </table>";

        }

        return $form;
    }

    //Consulta los reportes de consumibles
    function SearchReportConsum()
    {
        global $mysqli;

        $resquest = $_REQUEST;

        $sql = "SELECT description_consumables, dateregister_consumables, delivery_initial_consumables, delivery_reposition_consumables, site_consumables, 
        letter_units_teams, contract_consumables, valuetotal_consumables, token_consumables FROM consumables_report AS q INNER JOIN teams_units_rsu AS b ON q.rsu_consumables = b.id_teams_units";
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        $sql = "SELECT description_consumables, dateregister_consumables, delivery_initial_consumables, delivery_reposition_consumables, site_consumables, 
        letter_units_teams, contract_consumables, valuetotal_consumables, token_consumables FROM consumables_report AS q INNER JOIN teams_units_rsu AS b ON q.rsu_consumables = b.id_teams_units";

        if(!empty($resquest['search']['value']))
        {
            $sql.="description_consumables LIKE '".$resquest['search']['value']."%'";
        }

        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;

        $data = array();

        while($row = $query->fetch_array())
        {
            if($row[2] == 1)
            {
                $state = "ENTREGA INICIAL";
            }
            else
            {
                if($row[3] == 1)
                {
                    $state = "ENTREGA POR REPOSICIÓN";
                }
            }
            $subdata = array();
            $subdata[] = $row[0];
            $subdata[] = $row[1];
            $subdata[] = $state;
            $subdata[] = $row[4];
            $subdata[] = $row[5];
            $subdata[] = $row[6];
            $subdata[] = "$".number_format($row[7]);
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Ver información' href='../report/ReportConsumables?report=".$row[8]."' target='_blank'><i class='fas fa-file'></i></a>
            <a class='btn btn-default btn-sm' title='Ver información' href='../report/ReportConsumablesCost?report=".$row[8]."' target='_blank'><i class='fas fa-hand-holding-usd'></i></a>                             
            </div>";

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      => intval($totalData),
            "recordsFiltered"   => intval($totalFilter),
            "data"              => $data
        );

        return json_encode($json_data);
    }

    //Consulta si el reporte de consumibles es entrega inicial o entrega por reposición
    function ConditionTypeConsumables($id_report_consumables)
    {
        global $mysqli;

        $cond = "";

        $stmt = $mysqli->prepare("SELECT delivery_initial_consumables, delivery_reposition_consumables FROM consumables_report WHERE id_consumables = ?");
        $stmt->bind_param('i', $id_report_consumables);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($con_1, $con_2);
            $stmt->fetch();

            if($con_1 == 1)
            {
                $cond.="
                <table style='border: 1px solid white;'>
                    <tr style='border: 1px solid black; font-size: 12px'>
                        <th style='width: 300px; text-align: right;'>ENTREGA INICIAL</th>
                        <th style='border: 1px solid black; width: 30px; height: 30px; font-size: 20px; text-align: center;'>X</th>
                        <th style='width: 300px; text-align: right;'>ENTREGA POR REPOSICIÓN</th>
                        <th style='border: 1px solid black; width: 30px; height: 30px; font-size: 20px; text-align: center;'></th>
                    </tr>
                </table>";
            }
            else
            {
                $cond.="
                <table style='border: 1px solid white;'>
                    <tr style='border: 1px solid black; font-size: 12px'>
                        <th style='width: 300px; text-align: right;'>ENTREGA INICIAL</th>
                        <th style='border: 1px solid black; width: 30px; height: 30px; font-size: 20px; text-align: center;'></th>
                        <th style='width: 300px; text-align: right;'>ENTREGA POR REPOSICIÓN</th>
                        <th style='border: 1px solid black; width: 30px; height: 30px; font-size: 20px; text-align: center;'>X</th>
                    </tr>
                </table>";
            }
        }
        else
        {
            $cond."";

        }

        return $cond;
    }

    //Consulta los productos del reporte de consumibles
    function ProductsConsumables($id_report_consumables)
    {
        global $mysqli;

        $table = "";
        $b = 0;

        $stmt = $mysqli->prepare("SELECT description_products_consumables, quantity_products_consumables, observation_products_consumables FROM products_consumables WHERE fk_id_report_consumables = ?");
        $stmt->bind_param('i', $id_report_consumables);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($description, $quantity, $observation);

            while($stmt->fetch())
            {
                $b = $b+1;

                $table.="
                <tr>
                    <td style='border: 1px solid black; width: 45px; height: 10px; text-align: center;'>".$b."</td>
                    <td style='border: 1px solid black; width: 385px; height: 10px;'>".$description."</td>
                    <td style='border: 1px solid black; width: 80px; height: 10px; text-align: center;'>".$quantity."</td>
                    <td style='border: 1px solid black; width: 200px; height: 10px;'>".$observation."</td>
                </tr>";
            }

            $stmt->close();
        }
        else
        {
            $table.="
                <tr>
                    <td style='border: 1px solid black; width: 45px; height: 10px;'></td>
                    <td style='border: 1px solid black; width: 385px; height: 10px;'></td>
                    <td style='border: 1px solid black; width: 80px; height: 10px;'></td>
                    <td style='border: 1px solid black; width: 200px; height: 10px;'></td>
                </tr>";
        }

        return $table;
    }

    //Consulta los productos del reporte de consumibles con costos
    function ProductsConsumablesCost($id_report_consumables)
    {
        global $mysqli;

        $table = "";
        $b = 0;

        $stmt = $mysqli->prepare("SELECT description_products_consumables, quantity_products_consumables, observation_products_consumables, price_products_consumables FROM products_consumables WHERE fk_id_report_consumables = ?");
        $stmt->bind_param('i', $id_report_consumables);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($description, $quantity, $observation, $value_total);

            while($stmt->fetch())
            {
                $b = $b+1;

                $table.="
                <tr>
                    <td style='border-radius: 2px; border: 1px solid black; width: 40px; height: 10px; text-align: center;'>".$b."</td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 300px; height: 10px;'>".$description."</td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 60px; height: 10px; text-align: center;'>".$quantity."</td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 110px; height: 10px; text-align: center;'>$".number_format($value_total)."</td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 160px; height: 10px;'>".$observation."</td>
                </tr>";
            }

            $stmt->close();
        }
        else
        {
            $table.="
                <tr>
                    <td style='border-radius: 2px; border: 1px solid black; width: 40px; height: 10px;'></td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 300px; height: 10px;'></td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 60px; height: 10px;'></td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 110px; height: 10px;'></td>
                    <td style='border-radius: 2px; border: 1px solid black; width: 160px; height: 10px;'></td>
                </tr>";
        }

        return $table;
    }

    //Consulta si el reporte de consumibles es entrega inicial o entrega por reposición (Reporte con costo)
    function ConditionTypeConsumablesCost($id_report_consumables)
    {
        global $mysqli;

        $cond = "";

        $stmt = $mysqli->prepare("SELECT delivery_initial_consumables, delivery_reposition_consumables FROM consumables_report WHERE id_consumables = ?");
        $stmt->bind_param('i', $id_report_consumables);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($con_1, $con_2);
            $stmt->fetch();

            if($con_1 == 1)
            {
                $cond.="
                <table style='border-radius: 2px; border: 1px solid black; margin-top: 10px; font-size: 12px; text-align: center;'>
                    <tr>
                        <th style='border-radius: 2px; border: 1px solid black; width: 100px; height: 20px; background-color: #273746; color: white;'>ENTREGA INICIAL</th>
                        <td style='border: 1px solid black; width: 20px; height: 20px; border-radius: 2px; font-weight: bold; font-size: 20px;'>X</td>
                    </tr>
                    <tr>
                        <th style='border-radius: 2px; border: 1px solid black; width: 100px; height: 20px; background-color: #273746; color: white;'>ENTREGA POR REPOSICIÓN</th>
                        <td style='border: 1px solid black; width: 20px; height: 20px; border-radius: 2px; font-weight: bold; font-size: 20px;'></td>
                    </tr>        

                </table>";
            }
            else
            {
                $cond.="
                <table style='border-radius: 2px; border: 1px solid black; margin-top: 10px; font-size: 12px; text-align: center;'>
                    <tr>
                        <th style='border-radius: 2px; border: 1px solid black; width: 100px; height: 20px; background-color: #273746; color: white;'>ENTREGA INICIAL</th>
                        <td style='border: 1px solid black; width: 20px; height: 20px; border-radius: 2px; font-weight: bold; font-size: 20px;'></td>
                    </tr>
                    <tr>
                        <th style='border-radius: 2px; border: 1px solid black; width: 100px; height: 20px; background-color: #273746; color: white;'>ENTREGA POR REPOSICIÓN</th>
                        <td style='border: 1px solid black; width: 20px; height: 20px; border-radius: 2px; font-weight: bold; font-size: 20px;'>X</td>
                    </tr>        

                </table>";
            }
        }
        else
        {
            $cond."";

        }

        return $cond;
    }

    //REPORTES DE LOS FORMATOS

    //Consulta sobre el formato F-262 -> Gestión de inventarios del almacén
    function getDatosF262($id_warehouse)
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT concept_warehouse, num_concept_warehouse, description_element_spares, name_active_type, unity_spares,
        alarm_spares_stock, unity_value_spares, maker_spares, model_spares, serie_spares, stock_spares FROM spares_parts INNER JOIN type_active 
        ON spares_parts.type_element_spares = type_active.id_type_active 
        WHERE warehouse_reference_spares = ?");
        $stmt->bind_param('i', $id_warehouse);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($concept, $num_concept, $description, $name, $unity, $alarm, $unity_value, $maker, $model, $serie, $stock);

            while($stmt->fetch())
            {
                $table.= "<tr style='font-size: 10px; text-align: center;'>
                <td style='border: 1px solid black; width:80px;'>".$concept."".$num_concept."</td>
                <td style='border: 1px solid black; width:160px;'>".$description."</td>
                <td style='border: 1px solid black; width:90px;'>".$name."</td>
                <td style='border: 1px solid black; width:60px;'>".$unity."</td>
                <td style='border: 1px solid black; width:88px;'>".$alarm."</td>
                <td style='border: 1px solid black; width:100px;'>".$unity_value."</td>
                <td style='border: 1px solid black; width:100px;'>".$maker."</td>
                <td style='border: 1px solid black; width:100px;'>".$model."</td>
                <td style='border: 1px solid black; width:100px;'>".$serie."</td>
                <td style='border: 1px solid black; width:40px;'></td>
                <td style='border: 1px solid black; width:50px;'>".$stock."</td>
                </tr>";
            }

        }
        else
        {
            $table.= "<tr style='font-size: 10px; text-align: center;'>
                <td style='border: 1px solid black; width:150px;'></td>
                <td style='border: 1px solid black; width:150px;'></td>
                <td style='border: 1px solid black; width:100px;'></td>
                <td style='border: 1px solid black; width:100px;'></td>
                <td style='border: 1px solid black; width:90px;'></td>
                <td style='border: 1px solid black; width:100px;'></td>
                <td style='border: 1px solid black; width:100px;'></td>
                <td style='border: 1px solid black; width:100px;'></td>
                <td style='border: 1px solid black; width:40px;'></td>
                <td style='border: 1px solid black; width:50px;'></td>
                </tr>";
        }

        return $table;

    }

    //Consulta sobre el formato F-263 -> Entrada de activos al almacén
    function getDatosF263($id_warehouse)
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT number_remission_input, date_input, concept_warehouse, num_concept_warehouse, description_element_spares, quantity_add 
        FROM input_actives INNER JOIN spares_parts ON input_actives.fk_active_input = spares_parts.id_spares WHERE fk_warehouse_input = ?");
        $stmt->bind_param('i', $id_warehouse);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {

            $stmt->bind_result($number, $date_input, $concept, $num_concept, $description_element, $quantity);

            while($stmt->fetch())
            {
                $table.= "<tr style='font-size: 12px; text-align: center;'>
                <td style='border: 1px solid black; width:90px;'>".$number."</td>
                <td style='border: 1px solid black; width:130px;'>".$date_input."</td>
                <td style='border: 1px solid black; width:200px;'>".$concept."-".$num_concept."</td>
                <td style='border: 1px solid black; width:200px;'>".$description_element."</td>
                <td style='border: 1px solid black; width:90px;'>".$quantity."</td>
                </tr>";
            }

        }
        else
        {
            $table.= "<tr style='font-size: 12px; text-align: center;'>
                <td style='border: 1px solid black; width:90px;'></td>
                <td style='border: 1px solid black; width:130px;'></td>
                <td style='border: 1px solid black; width:200px;'></td>
                <td style='border: 1px solid black; width:200px;'></td>
                <td style='border: 1px solid black; width:90px;'></td>
                </tr>";
        }

        return $table;

    }

    //Consulta sobre el formato F-264 -> Salida de activos al almacén
    function getDatosF264($id_warehouse)
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT number_remission_output, date_output, concept_warehouse, num_concept_warehouse, 
        description_element_spares, quantity_remove 
        FROM output_actives INNER JOIN spares_parts ON output_actives.fk_active_output = spares_parts.id_spares WHERE fk_warehouse_output = ?");
        $stmt->bind_param('i', $id_warehouse);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {

            $stmt->bind_result($number, $date_input, $concept, $num_concept, $description_element, $quantity);

            while($stmt->fetch())
            {
                $table.= "<tr style='font-size: 12px; text-align: center;'>
                <td style='border: 1px solid black; width:90px;'>".$number."</td>
                <td style='border: 1px solid black; width:130px;'>".$date_input."</td>
                <td style='border: 1px solid black; width:200px;'>".$concept."-".$num_concept."</td>
                <td style='border: 1px solid black; width:200px;'>".$description_element."</td>
                <td style='border: 1px solid black; width:90px;'>".$quantity."</td>
                </tr>";
            }

        }
        else
        {
            $table.= "<tr style='font-size: 12px; text-align: center;'>
                <td style='border: 1px solid black; width:90px;'></td>
                <td style='border: 1px solid black; width:130px;'></td>
                <td style='border: 1px solid black; width:200px;'></td>
                <td style='border: 1px solid black; width:200px;'></td>
                <td style='border: 1px solid black; width:90px;'></td>
                </tr>";
        }

        return $table;

    }

    //Consulta sobre el formato F-265 -> Base de datos de fallas o averías de herramientas - mantenimiento correctivo
    function getDatosF265()
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT num_report_fails, reference_teams_report_fails, no_analysis_report_fails, name_teams_report_fails, name_report_fails, description_report_fails,
        datereg_report_fails, time_stop_report_fails, warning_person_report_fails, 
        warning_ambiental_report_fails FROM report_fails");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($num_report_fails, $reference, $no_analysis, $name_teams, $name_report, $description_fails, $datereg_fails, $time_stop, $warning_person, $warning_ambiental);

            while($stmt->fetch())
            {
                $table.="<tr style='font-size: 10px;'>
                <td style='border: 1px solif black; width: 80px; text-align: center;'>".$num_report_fails."</td>
                <td style='border: 1px solif black; width: 160px; text-align: center;'>".$name_teams."</td>
                <td style='border: 1px solif black; width: 90px; text-align: center;'>".$reference."</td>
                <td style='border: 1px solif black; width: 80px; text-align: center;'>".$name_report."</td>
                <td style='border: 1px solif black; width: 88px; text-align: justify;'>".$description_fails."</td>
                <td style='border: 1px solif black; width: 100px; text-align: center;'>".$time_stop."</td>
                <td style='border: 1px solif black; width: 100px; text-align: center;'>".$warning_person."</td>
                <td style='border: 1px solif black; width: 100px; text-align: center;'>".$warning_ambiental."</td>
                <td style='border: 1px solif black; width: 100px; text-align: center;'>".$datereg_fails."</td>
                <td style='border: 1px solif black; width: 80px; text-align: center;'>".$no_analysis."</td>
                </tr>";

            }

        }
        else
        {
            $table.= "<td style='border: 1px solif black; width: 80px;'></td>
            <td style='border: 1px solif black; width: 160px;'></td>
            <td style='border: 1px solif black; width: 90px;'></td>
            <td style='border: 1px solif black; width: 80px;'></td>
            <td style='border: 1px solif black; width: 88px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 80px;'></td>
            </tr>";

        }

        return $table;

    }

    //Consulta sobre el formato F-266 -> Base de datos - Hoja de mantenimiento correctivo/preventivo
    function getDatosF266()
    {
        global $mysqli;

        $table = "";

        $stmt = $mysqli->prepare("SELECT number_report_mant, type_activity_report_maint, location_report_mant, reference_teams_report_mant,
         name_teams_report_mant, cod_report_fails_mant, description_report_mant, actor_execution_report_mant, analysis_data_report_mant, 
         date_report_mant FROM report_maint");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($number_report, $type_activity, $location_report, $reference_report_teams, $name_teams, $cod_report_fails, $description_report,
            $actor_execution_report, $analysis_data, $date_report);

            while($stmt->fetch()){

                if($cod_report_fails == "")
                {
                    $cod = 'NO APLICA';
                }
                else
                {
                    $cod = $cod_report_fails;
                }


            $table.= "
            <tr style='font-size: 10px; text-align: center;'>
            <td style='border: 1px solif black; width: 80px;'>".$number_report."</td>
            <td style='border: 1px solif black; width: 140px;'>".$type_activity."</td>
            <td style='border: 1px solif black; width: 90px;'>".$location_report."</td>
            <td style='border: 1px solif black; width: 80px;'>".$reference_report_teams."</td>
            <td style='border: 1px solif black; width: 88px;'>".$name_teams."</td>
            <td style='border: 1px solif black; width: 100px;'>".$cod."</td>
            <td style='border: 1px solif black; width: 100px;'>".$description_report."</td>
            <td style='border: 1px solif black; width: 100px;'>".$actor_execution_report."</td>
            <td style='border: 1px solif black; width: 100px;'>".$analysis_data."</td>
            <td style='border: 1px solif black; width: 80px;'>".$date_report."</td>
            </tr>";

            }

        }
        else
        {
            $table.= "
            <tr>
            <td style='border: 1px solif black; width: 80px;'></td>
            <td style='border: 1px solif black; width: 160px;'></td>
            <td style='border: 1px solif black; width: 90px;'></td>
            <td style='border: 1px solif black; width: 80px;'></td>
            <td style='border: 1px solif black; width: 88px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 100px;'></td>
            <td style='border: 1px solif black; width: 80px;'></td>
            </tr>";

        }

        return $table;
    }

    function getDatosF270()
    {
        global $mysqli;

        $mtto = new mtto();

        $table = "";

        $stmt = $mysqli->prepare("SELECT id_units_rsu, date_register_units, reference_units_rsu, 
        state_units_rsu, costmaint_units_rsu, costnpt_units_rsu, location_contract_units_rsu,
        client_contract_units_rsu, contract_units_rsu FROM father_units_rsu INNER JOIN 
        contract_units_rsu ON father_units_rsu.id_units_rsu = contract_units_rsu.fk_id_father_units_rsu");
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($id_units, $date_register, $reference_units, $state_units, $cost_maints, $costnpt, $location, $client_contract, $contract_units);

            while($stmt->fetch())
            {
                $cost_mant = $mtto->TotalMantReport($id_units);
                $cost_npt = $mtto->TotalNPTReport($id_units);

                $table.="<tr style='font-size: 10px; text-align: center;'>
                    <td style='border: 1px solid black; width: 100px;'>".$reference_units."</td>
                    <td style='border: 1px solid black; width: 100px;'>".$state_units."</td>
                    <td style='border: 1px solid black; width: 100px;'>".$location."</td>
                    <td style='border: 1px solid black; width: 100px;'>".$contract_units."</td>
                    <td style='border: 1px solid black; width: 100px;'>".$client_contract."</td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'>$".number_format($cost_mant)."</td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'>$".number_format($cost_npt)."</td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                </tr>";

            }

        }
        else
        {
            $table.="<tr>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                    <td style='border: 1px solid black; width: 100px;'></td>
                </tr>";
        }

        return $table;
    }

    function insertRequisition($data) {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO requisitions (user_id, equipment, requested_items, place, request_date, status, status_text) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssss', $data['user_id'], $data['equipment'], $data['requested_items'], $data['place'], $data['request_date'], $data['status'], $data['status_text']);

        if ($stmt->execute()) {
            return true;
        } else {
            return 0;
        }
    }

    function actualizarRequisition($id, $status) {
        global $mysqli;

        $statusText = '';
        if ($status == 2) {
            $statusText = 'Atendiendo';
        } else {
            $statusText = 'Entregado';
        }

        $stmt = $mysqli->prepare("UPDATE requisitions SET status = ?, status_text = ? WHERE id = ?");
        $stmt->bind_param('ssi', $status, $statusText, $id);
        $stmt->execute();
        $stmt->close();
    }

    function obtenerRequisiciones() {
        global $mysqli;
        $result = $mysqli->query('select user_id, admin_id, equipment, requested_items, place, request_date, delivery_date, status, status_text from requisitions where user_id = ' . $_SESSION['id_user']);
        $resultArr = [];
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $resultArr[] = $row;
            }
        }
        return $resultArr;
    }

    function obtenerRequisicionesAdmin($status) {
        global $mysqli;
        $result = $mysqli->query("select id, user_id, admin_id, equipment, requested_items, place, request_date, delivery_date, status, status_text, users.first_name, users.second_name from requisitions inner join users on users.id_user = requisitions.user_id where status in($status)");
        $resultArr = [];
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $resultArr[] = $row;
            }
        }
        return $resultArr;
    }
}


