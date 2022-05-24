<?php

//PÁGINA TABLA DE ANÁLISIS DE COSTOS

$mtto = new mtto();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 2);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };



?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Historial de Analisis de costos</h5>            
          </div>          
                     
              <div class="card-body">

                

                    <div class="row">

                        <div class="col-sm-6">

                            <select id="ware" name="ware" class="form-control">
                                <option value = "">Seleccionar Almacén</option>
                                <?php
                                $ware = $mtto->OptionWarehouses();
                                ?>
                            </select>                        
                        </div>                    

                    </div>                 
                   
                
                <br>
              
                <table id="id_table_analisys" class="display" style="width: 100%;">
                
                  <thead>                 
                    
                    <tr style="text-align: center;">
                      <th>No.</th>
                      <th>Fecha</th>
                      <th>Descripción</th>                                                          
                      <th>Valor total</th>
                      <th>Acciones</th>                     
                    </tr>
                  </thead>
                  <tbody style="text-align: center;">
                 
                  
                  </tbody>
                
                </table>
                
               

              </div>             
        </div>           
      </div>          
    </div>
  </div>


</section>