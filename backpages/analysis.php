<?php

//PÁGINA DE REGISTRAR ANÁLISIS DE COSTOS

$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 2);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };

$tokenw = $mysqli->real_escape_string($_GET['warehouse']);

$mtto = new mtto();

$id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $tokenw);



?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Análisis de costos</h5>            
          </div> 

            <div class="card-body">

            <?php

            echo $tablework = $mtto->SearchAnalysisCosts($id_warehouse, $tokenw);         

            ?>          
       
          </div>

        </div> 

       
      </div>          
    </div>
  </div>


</section>


<!-- Modales de los formularios -->

<div class="modal fade" id="modal-defaultone">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar Concepto Personal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../functions/Register/Insertperson?warehouse=<?php echo $tokenw; ?>" method="POST">
        <div class="modal-body">

        <div class="callout callout-info">
            <h5>Pasos a registrar un personal:</h5>

            <p>
                  1) Selecciona el concepto de personal.<br>
                  2) Insertar el número de horas.<br>
                                          
                  
            </p>
        </div>         
                          

          <div class="row">

            <div class="col-sm-9">                
                <div class="form-group">
                <label for="inputSuccess">Mano de obra<b style="color:#B20F0F;">*</b></label>
                <select name="personanalysis" class="form-control">
                  <option value="">Seleccione mano de obra:</option>
                  <?php

                    $work = $mtto->OptionWorforce();

                    echo $work;

                  ?>

                </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                <label>Cantidad de horas<b style="color:#B20F0F;">*</b></label>
                <input type="number" class="form-control" name="canthoursperson" required>
                </div>  
            </div>           
              
          </div>
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="input" class="btn btn-success">Guardar</button>
        </div>

      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>



<div class="modal fade" id="modal-defaulttwo">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar Costo de Repuesto de Maquinaria, Equipos o Herramientas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../functions/Register/Insertspare?warehouse=<?php echo $tokenw; ?>" method="POST">
        <div class="modal-body">

        <div class="callout callout-info">
            <h5>Pasos a registrar activo:</h5>

            <p>
                  1) Selecciona el activo de la lista.<br>
                  2) Inserte la cantidad deseada.<br>
                  <b>NOTA:</b> La cantidad a insertar, debe ser igual o inferior a la cantidad registrada en stock. 
                                          
                  
            </p>
        </div>    
        
        <div class="row">

          <div class="col-sm-12">                
              <div class="form-group">
              <label for="inputSuccess">Activo de inventario:<b style="color:#B20F0F;">*</b></label>
                <select name="active" size="5" class="form-control" required>
                  <?php

                    $actives = $mtto->OptionActiveA($id_warehouse);

                    echo $actives;

                  ?>

                </select>
              </div>
          </div>

          <div class="col-sm-3">
              <div class="form-group">
              <label>Cantidad:<b style="color:#B20F0F;">*</b></label>
              <input type="number" class="form-control" name="cant" required>
              </div>  
          </div>           
              
        </div>
                          

          
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="input" class="btn btn-success">Guardar</button>
        </div>

      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modal-defaultthree">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar Costo No previstos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../functions/Register/Insertcostpre?warehouse=<?php echo $tokenw; ?>" method="POST">
        <div class="modal-body">

        <div class="callout callout-info">
            <h5>Pasos a registrar Costo No previstos:</h5>

            <p>
                  1) Diligenciar el formulario completamente.<br>
                  2) El precio unitario <b>NO</b> debe ir separada ni por puntos ni comas.<br>
                                          
                  
            </p>
        </div>         
                          

          <div class="row">

            <div class="col-sm-6">                
                <div class="form-group">
                <label for="inputSuccess">Descripción: <b style="color:#B20F0F;">*</b></label>
                <input type="text" class="form-control"  name="description" required>
                </div>
            </div>

            <div class="col-sm-6">                
                <div class="form-group">
                <label for="inputSuccess">Unidad:<b style="color:#B20F0F;">*</b></label>
                <input type="text" class="form-control"  name="unitymed" required>
                </div>
            </div>

            <div class="col-sm-6">                
                <div class="form-group">
                <label for="inputSuccess">Cantidad:<b style="color:#B20F0F;">*</b></label>
                <input type="number" class="form-control"  name="quantity" required>
                </div>
            </div>

            <div class="col-sm-6">                
                <div class="form-group">
                <label for="inputSuccess">Precio Unitario:<b style="color:#B20F0F;">*</b></label>
                <input type="text" class="form-control"  name="unityprice" required>
                </div>
            </div>          

            
              
          </div>
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="input" class="btn btn-success">Guardar</button>
        </div>

      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>