<?php

//PÁGINA DE GESTIÓN DE PERMISOS DE LOS USUARIOS

include_once('../db/ConnectDB.php');

$ser = new RegisterUsers();
$mod = new Admin();

$access_page = $mod->VerificPermitions($id_user, 1);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };

$user = $mysqli->real_escape_string($_GET['user']);

$firstname = $ser->getValue('first_name','users','token', $user);
$secondname = $ser->getValue('second_name','users','token', $user);
$emailuser = $ser->getValue('email_user','users','token', $user);
$userid = $ser->getValue('id_user','users','token', $user);


?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Gestión de Usuarios</h5>            
            </div> 

            <div class="row">
            
            </div>   

            <div class="card-body"> 

                <div class="row">

                    <div class="card card-danger card-outline col-md-6">

                        <div class="card-body box-profile">                        

                            <h3 class="profile-username text-center"><?php echo $firstname .' '. $secondname; ?></h3>

                            <p class="text-muted text-center"><?php echo $role; ?></p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Correo Eletrónico</b> <a class="float-right"><?php echo $emailuser; ?></a>
                                </li>
                               
                                
                            </ul>

                            <form action="../functions/AssignPermits.php?token=<?php echo $user; ?>" method="POST"/>
                                <div class="form-group">
                                    <input type="hidden" name="userid" value="<?php echo $userid;?>">
                                
                                    
                                    <div class="row">

                                         
                                        
                                        <div class="col-sm-7">

                                        
                                            <div class="card card-danger card-outline">
                                                <div class="card-body box-profile">

                                                        <select name="module" size=10" class="form-control">
                                                            <?php echo $mod->SearchModules(); ?>

                                                        </select>

                                                </div>

                                            </div>                                           


                                        </div> 
                                        
                                        <div class="col-sm-5">

                                        
                                            <div class="card card-danger card-outline">
                                                <div class="card-body box-profile">                                                 

                                                        <?php                                       
                                                       
                                                        echo $mod->ModulesOptions();
                                                        ?>

                                                </div>

                                            </div>                                           


                                        </div> 
                                        
                       

                                    </div>

                                    
                              
                                    
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">  
                                    <label style="color: white;">.</label>                
                                    <button type="submit" class="btn btn-danger form-control"> Registrar</button>
                                    </div>
                                </div>                         
                            </form>  

                        </div>   

                                       

                    </div>

                    <div class="card card-danger card-outline col-md-6">

                        <div class="card-body box-profile">                        

                            <h3 class="profile-username text-center">Permisos Asignados</h3>  

                            <table id="table_id_permision" class="display" style="width: 100%;">
                  <thead>
                    
                    <tr style="text-align: center;">
                      <th>Modulo</th>
                      <th>Permiso</th>
                      <th>Acción</th>
                                         
                    </tr>
                  </thead>
                  <tbody>
                  
                  <?php 
                           	
                   	$query = "SELECT id_asign_permits, name_permits, description_module_mtto FROM asign_permits INNER JOIN modules_mtto ON asign_permits.id_module_permit = modules_mtto.id_module_mtto INNER JOIN users ON asign_permits.user_id_asign = users.id_user INNER JOIN permits ON asign_permits.permit_user_id = permits.id_permits WHERE user_id_asign = '$userid'";
                   	$result = $mysqli->query($query);
                   	
                   	while($mos = $result->fetch_array())
                   	{                           	
                   ?> 
                  <tr style="text-align: center;">

                      <td><?php echo $mos['description_module_mtto']; ?></td>
                      <td><?php echo $mos['name_permits']; ?></td>
                                          
                      <td>                         
                            <a class="btn btn-danger btn-xs" title="Eliminar Permiso" href="../functions/DeletePermitUser?user=<?php echo $user; ?>&permit=<?php echo $mos['id_asign_permits']; ?>">Quitar permiso</a>                 
                      </td>  

                  </tr> 
                        
	              <?php
	              }
	              ?>
                 
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


</section>




