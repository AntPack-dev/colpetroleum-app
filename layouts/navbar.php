<?php

//$nav = "navbar-dark  ";

$mtto = new mtto();

?>

<nav class=" main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-danger" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
      <?php

      $date = new UserFunctions();
      echo $date->DateSession();

      ?>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">


    <?php echo $mtto->ViewNotification();?>
           
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" href="../pages/profile" title="Configuración de mi cuenta">
        <i class="nav-icon  fas fa-cog"></i>     
        </a>    
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="../pages/" title="Página Principal">
        <i class="nav-icon  fas fa-home"></i>     
        </a>    
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="../functions/CloseSession" title="Cerrar Sesión">
        <i class="nav-icon text-danger fas fa-power-off"></i>   
        </a>    
      </li>
      
    </ul>
  </nav>