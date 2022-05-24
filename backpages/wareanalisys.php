<?php

//PÁGINA DE LISTADO DE ALMACENES PARA REGISTRAR UN ANÁLISIS DE COSTOS
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
            <h5 class="card-title">Almacenes registrados</h5>            
          </div>              
              <div class="card-body">
                <table id="id_table_warehouse_analisys" class="display" style="width: 100%;">
                
                  <thead>                 
                    
                    <tr style="text-align: center;">
                      <th>Descripción</th>
                      <th>Fecha de Registro</th>                                                          
                      <th>Estado</th>
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