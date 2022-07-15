<?php
// $dark = "sidebar-dark-primary";

$session = new UserFunctions();

?>
<aside class="main-sidebar elevation-4 sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="../pages/" class="brand-link">
      <img src="../styles/dist/img/icono.png" alt="AdminLTE Logo" class="brand-image img-circle ">
      <span class="brand-text" style="font-weight: bold;">CPS-</span><span class="brand-text" style=" font-weight: bold;">MTTO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">MI CUENTA</li>

          <?php
            
            echo $session->NamesUser($tokenuser);
          ?>
         
          <li class="nav-header">LISTADO DE OPCIONES</li>

          <?php echo $session->AdminUsers($tokenuser); ?>
          <?php echo $session->AdminAnalysis($tokenuser); ?>
          <?php echo $session->AdminConcepts($tokenuser); ?>
          <?php echo $session->AdminTeams($tokenuser); ?>
          <?php echo $session->AdminWarehouse($tokenuser); ?>
          <?php echo $session->AdminProcedures($tokenuser); ?>
          <?php echo $session->AdminRequisitions($tokenuser); ?>
            <li class="nav-item">
                <a href="/pages/masterCalendar.php" class="nav-link">
                    <i class="text-danger nav-icon fas fa-calendar"></i>
                    Calendario General
                </a>
            </li>
            <li class="nav-item">
                <a href="/pages/requisition.php" class="nav-link">
                    <i class='text-danger nav-icon fas fa-file-alt'></i>
                    Requisición
                </a>
            </li>
            <!--<li class="nav-item">
                <a href="/pages/adminrequisition.php" class="nav-link">
                    <i class="nav-icon far fa-image"></i>
                    <p>
                        Administrar requisiciones
                    </p>
                </a>
            </li>-->
          
          <?php

        //   $session = new UserFunctions();
        //   echo $session->SessionUser($tokenuser);
          ?>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

