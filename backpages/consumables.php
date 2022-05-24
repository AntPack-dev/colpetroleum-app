<?php

$mtto = new mtto();

?>


<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Gestión de consumibles</h5>                    
          </div>    
          
          <div class="card-body">
              <div class="row">
                  <div class="col-md-12">                      
                
                  <?php echo $mtto->SearchProductsConsumables($id_user); ?>   

                          <div class='modal fade' id='modal-confirm'>
                            <form action='' method='POST'>
                              <div class='modal-dialog modal-lg'>                            
                                <div class='modal-content'>
                                  <div class='modal-header'>
                                    <h4 class='modal-title'>Confirmación de registro</h4>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                          <span span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                  <div class='modal-body'>
                                    <div class="row">
                                    <div class='col-6'>
                                        <b>Entrega inicial:</b>  <input type='checkbox' name='description' class='form-control'><br>
                                      </div>
                                      <div class='col-6'>
                                        <b>Entrega por reposicón:</b>  <input type='checkbox' name='description' class='form-control'><br>
                                      </div>
                                      <div class='col-12'>					
                                        <b>Descripción:</b>  <input type='text' name='description' class='form-control'><br>
                                      </div>                                      
                                      <div class='col-6'>					
                                        <b>Lugar:</b>  <input type='text' name='description' class='form-control'><br>
                                      </div>
                                      <div class='col-6'>					
                                        <b>RSU:</b>  <input type='text' name='description' class='form-control'><br>
                                      </div>
                                      <div class='col-6'>					
                                        <b>Contrato:</b>  <input type='text' name='description' class='form-control'><br>
                                      </div>
                                      <div class='col-6'>					
                                        <b>Valor total:</b>  <input type='text' name='description' class='form-control'><br>
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
                        
                      </div>
                  
                  <div class="col-md-12">
                      <div class="card">
                          <div class="card-header">
                              <h3 class="card-title">Tabla de registros de entregas</h3>
                          </div>
                          <div class="card-body">
                            <table id="id_table_consumables" class="display" style="width: 100%;">
                  
                              <thead>                 
                                
                                <tr style="text-align: center;">
                                  <th>Descripción</th>
                                  <th>Fecha de Registro</th>                                                          
                                  <th>Condición</th>
                                  <th>Lugar</th>
                                  <th>Unidad RSU</th>
                                  <th>Contrato</th>
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
                      
        </div>           
      </div>          
    </div>
  </div>

</section>
