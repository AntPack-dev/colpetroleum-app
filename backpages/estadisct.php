<?php

//PÁGINA DE VISUALIZACIÓN DE ESTADISTICAS ANUALES

$mtto = new mtto();

$year = date('Y');

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Estadísticas anuales</h5> 
                      
          </div>              
            <div class="card-body"> 

              <div class="callout callout-info">
                <h5>Pasos para ver las estadisticas anuales:</h5>

                <p>                                                                             
                    1) Escribimos el año a consultar, en la casilla Año a consultar.<br>            
                    2) Presionamos la tecla Enter.<br>           
                 
                      
                </p>
              </div> 

            
            <div class="row">
                  
                <div class="input-group mb-2 col-sm-3">
                      <div class="input-group-prepend">
                          <span style="background-color: #F8F9F9;"  class="input-group-text">Año a consultar</span>
                      </div>
                      <input id="year" value="" type="number" class="form-control" name="" min="4" max="5" required>
                </div>
            </div>


            <div class="row">

              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-header">
                    <h3 class="card-title">No. mantenimientos realizados en general</h3>
                   

                                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="ChartMantsEst" ></canvas>
                    </div>
                  </div>
         
                </div>               
                
              </div>

              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-header">
                    <h3 class="card-title">No. de horas paradas de equipos por fallas</h3>

                                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="ChartHoursEst" ></canvas>
                    </div>
                    
                  </div>

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
              <h4 class="modal-title">Indicadores</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="../report/ReportsMtto" method="POST" target="_blank">
              <div class="modal-body">

         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
   
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


      
