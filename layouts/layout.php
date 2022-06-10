<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/OperationsUser.php');
include('../functions/FunctionsMtto.php');

if (!isset($_SESSION['id_user'])) {
    header("Location: ../");
}

if (!isset($_SESSION['token'])) {
    header("Location: ../");
}
$id_user = $_SESSION['id_user'];
$tokenuser = $_SESSION['token'];

$mtto = new mtto();

date_default_timezone_set('America/Bogota');

$dates = date("Y-m-d");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../styles/plugins/fontawesome-free/css/all.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../styles/plugins/overlayScrollbars/css/OverlayScrollbars.css">
    <!-- Theme style -->


    <link rel="stylesheet" href="../styles/dist/css/adminlte.css">
    <link rel="stylesheet" href="../styles/plugins/datatables/Datatables.css">
    <link rel="stylesheet" href="../styles/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../styles/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../styles/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../styles/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../styles/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../styles/plugins/summernote/summernote.min.css">

    <link rel="shortcut icon" href="../styles/dist/img/icono.png">
</head>
<body class="hold-transition  sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">


    <!-- Preloader -->

    <!-- Navbar -->
    <?php include('../layouts/navbar.php'); ?>
    <!-- menu -->
    <?php include('../layouts/maintenance/menu.php'); ?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header"></div>
        <!-- Main content -->
        <?php include($childview); ?>

        <?php echo $mtto->ValideNotificationOneMtto($dates); ?>
        <?php echo $mtto->ValideNotificationTwoMtto($dates); ?>
        <?php echo $mtto->ValideNotificationStock(); ?>
        <?php echo $mtto->ValideNotificationStockNew(); ?>
        <?php echo $mtto->ValideNotificationStockF(); ?>
        <!-- /.content -->
    </div>


    <!-- Main Footer -->
    <?php include('../layouts/footer.php'); ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<?php include('../layouts/scripts.php'); ?>

</body>

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>

</html>
