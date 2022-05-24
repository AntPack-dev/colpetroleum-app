<?php

//$nav = "navbar-dark  ";

$mtto = new mtto();

?>

<nav class=" navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
     
      
      <?php

      $date = new UserFunctions();
      echo $date->DateSession();

      ?>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

    </ul>
  </nav>