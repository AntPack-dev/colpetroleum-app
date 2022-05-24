<?php

//PANEL PRINCIPAL DEL APLICATIVO

$mtto = new mtto();


$dates = date("Y-m-d");
$year = date('Y');

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">DashBoard</h5> 
            <div class="card-tools">


                <a href="indicators" class="btn btn-dark"><i class="fas fa-chart-bar"></i> Ver Indicadores</a>
                <a href="schedule" class="btn btn-dark"><i class="far fa-calendar-alt"></i> Ver Cronograma</a>
                <a href="estadisct" class="btn btn-dark"><i class="fas fa-chart-pie"></i> Ver Estadisticas anuales</a>
          
                  <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modal-default">
                    <i class="fas fa-paste"></i> Generar Reportes
                  </button>
                  
                  
            </div>           
          </div>              
            <div class="card-body"> 

            <div class="row">
 
              <?php echo $mtto->CountAlertStock(); ?>
              <?php echo $mtto->CountAlertProxMant($dates); ?>
              <?php echo $mtto->CountAlertInMant($dates); ?>

            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-header">
                    <h3 class="card-title">No. de activos en cada unidad (RSU)</h3>

                                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="ChartUnits" ></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
              </div>

              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-header">
                    <h3 class="card-title">No. mantenimientos realizados en cada RSU - <?php echo $year; ?></h3>

                                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="ChartMantsRsu" ></canvas>
                    </div>
                    
                  </div>
                  <!-- /.card-body -->
                </div>
              </div>

              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-header">
                    <h3 class="card-title">No. mantenimientos realizados en general - <?php echo $year; ?></h3>

                                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="ChartMants" ></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>               
                
              </div>

              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-header">
                    <h3 class="card-title">No. de horas paradas de equipos por fallas - <?php echo $year; ?></h3>

                                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="ChartHours" ></canvas>
                    </div>
                    
                  </div>
                  <!-- /.card-body -->
                </div>

              </div>

              

            </div>
            
      

            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>

<div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Generar reportes</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="../report/ReportsMtto" method="POST" target="_blank">
              <div class="modal-body">

                  <div class="callout callout-info">
                    <h5>Pasos para generar reporte de la plataforma:</h5>

                    <p>                                                                             
                        1) Seleccionamos el tipo de reporte a realizar, en la casilla Tipo de reporte.<br>            
                        2) Seleccionamos el alcance (General o Específico), en la casilla Alcance.<br>
                        3) Seleccionamos la unidad, en la casilla Unidad. (Esto aplica para los reportes relacionados con unidades y equipos, y mantenimientos realizados).<br>
                        4) Seleccionamos el almacén de ubicación, en la casilla Almacén (Esto aplica para los reportes relacionados con activos de inventario, entrada y salida de almacén).<br>
                        5) ingresamos el rango de fechas, en las casilla de Fecha desde y Fecha hasta.<br>
                        6) Presionamos sobre el botón <b>Generar</b>.
                          
                    </p>
                  </div> 
                

                <div class="row">
                  <div class="form-group col-6">
                  <label for="inputSuccess">Tipo de reporte:<b style="color:#B20F0F;">*</b></label>
                      <select id="id_type_report" size="8" class="form-control" name="type_report" required>
                        <option value="635">REPORTE LISTADO DE ACTIVOS</option>
                        <option value="734">REPORTE LISTADO DE SALIDA ALMACÉN</option>
                        <option value="528">REPORTE LISTADO DE ENTRADA ALMACÉN</option>
                        <option value="479">REPORTE LISTADO DE UNIDADES/EQUIPOS</option>
                        <option value="845">REPORTE LISTADO MANTENIMIENTOS REALIZADOS</option>
                        <option value="564">REPORTE LISTADO FALLAS/AVERIAS</option>
                        <option value="890">REPORTE ESTADISTICO</option>
                      </select>
                  </div>

                  <div class="form-group col-6">
                    <label for="inputSuccess">Alcance:<b style="color:#B20F0F;">*</b></label>
                      
                      <select id="id_alcance_report" class="form-control" name="alcance_report" required>
                        <option value="">SELECCIONE ALCANCE</option>
                        <option value="General">GENERAL</option>
                        <option value="Especifico">ESPECIFICO</option>
                      </select>

                      <label for="inputSuccess">Unidad:<b style="color:#B20F0F;">*</b></label>
                      
                      <select id="id_unity_report" class="form-control" name="unity_rsu" disabled>
                        <option value="">SELECCIONE UNIDAD</option>
                        <?php $war = $mtto->OptionsUnity(); 
                              echo $war;
                        ?>                       
                      </select>

                      <label for="inputSuccess">Almacén:<b style="color:#B20F0F;">*</b></label>
                      
                      <select id="id_warehouse_report" class="form-control" name="warehouse" disabled>
                        <option value="">SELECCIONE ALMACEN</option>
                        <?php $war = $mtto->OptionsWarehouse(); 
                              echo $war;
                        ?>
                     
                      </select>
                  </div>

                  <div class="input-group mb-2 col-sm-6">
                      <div class="input-group-prepend">
                          <span style="background-color: #F8F9F9;"  class="input-group-text">Fecha desde</span>
                      </div>
                      <input value="" type="date" class="form-control" name="date_ini" required>
                  </div>

                  <div class="input-group mb-2 col-sm-6">
                      <div class="input-group-prepend">
                          <span style="background-color: #F8F9F9;"  class="input-group-text">Fecha hasta</span>
                      </div>
                      <input value="" type="date" class="form-control" name="date_end" required>
                  </div>
                    
                </div>
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" name="btngeneratereport" class="btn btn-success">Generar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


