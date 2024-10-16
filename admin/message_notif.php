<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{
date_default_timezone_set('Asia/Manila');// change according timezone
$currentTime = date( 'd-m-Y h:i:s A', time () );

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maintenance | Notification</title>

 <!-- Google Font: Source Sans Pro -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../admin/js/sweetalert2@10.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../admin/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../admin/dist/css/adminlte.min.css">
  <link href="../admin/css2/font-face.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="../admin/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="../admin/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="../admin/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="../admin/css2/theme.css" rel="stylesheet" media="all">
    <?php include 'includes/head.php'; ?>
    <?php include 'includes/header.php'; ?>
    <?php include 'notif.php'; ?>
</head>
<body class="animsition">
    <div class="wrapper">
     <nav class="main-header">
    <?php include 'include/mobile.php'; ?>
     </nav>
    <aside class="main-sidebar">
       <?php include 'include/sidebar.php'; ?>    
    </aside>
    <br>
    <br>
    <br>

          <div class="content-wrapper">
                <br>
         <section class="container-fluid">
          <div class="row justify-content-center">
                             
                             <div class="col-lg-6">
                                <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                                    <div class="au-card-title" style="background-image:url('<?php echo $data['Image'] ?>');">
                                        <div class="bg-overlay bg-overlay--blue"></div>
                                        <h3>
                                        <?php
                                       $id=intval($_GET['id']);
                                       $query=mysqli_query($bd, "select * from notifications where id='$id'");
                                       while($row=mysqli_fetch_array($query))
                                       {
                                       ?> 
                                        <button class="btn-sm" style="margin-right: 1px;" onclick="location.href='home.php'"><i class="fa fa-arrow-circle-left"></i></button>
                                            <i class="zmdi zmdi-account-calendar"></i>Notifications</h3>
                                            <!--  <button class="au-btn-plus">
                                             <a href="home.php?main_id=<?php echo htmlentities($row['main_id']); ?>" style="color:white">Back</a>
                                             </button> -->
                                    </div>
                                     
                                    <div class="au-task js-list-load">
                                        <div class="au-task-list js-scrollbar3">
                                        <div class="au-task__item au-task__item--primary">
                                         <div class="au-task__item-inner">

                                        <div class="text">
                                            <strong><p>Information :</p></strong>
                                            <h5 class="name"><?php echo htmlentities($row['message']); ?></h5>
                                            <!-- <p><strong><?php echo htmlentities($row['awardee_name']); ?></strong></p> -->
                                            <br>
                                             <strong><p><?php echo htmlentities($row['awardee_name']); ?> <br>
                                                <!-- <?php echo htmlentities($row['agentsname']); ?><br> -->
                                                <!-- <?php echo htmlentities($row['main_name']); ?> -->
                                            </p></strong>
                        
                                            <br>
                                            <!-- <strong><p>Address :</p></strong> -->
                                            <!-- <p><?php echo htmlentities($row['address']); ?></p> -->
                                            <span class="date"><?php echo date('F j, Y g:ia', strtotime($row['timestamp'])); ?></span>
                                        </div>
                                        </div>
                                         <?php
                                         }
                                         ?>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                </div> 
                                </div>       
    </section>
    </div>

  
 </div>
  <footer class="main-footer">
    <?php include 'include/footer.php'; ?>
  </footer>

<!-- ./wrapper -->

 <style type="text/css">

      .unread {
     background-color: antiquewhite; /* Example background color for unread notifications */
    /* Add any other styles you want for unread notifications */
     }

   </style>


<script src="../admin/plugins2/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../admin/plugins2/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../admin/plugins2/datatables/jquery.dataTables.min.js"></script>
<script src="../admin/plugins2/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../admin/plugins2/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../admin/plugins2/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../admin/plugins2/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../admin/plugins2/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../admin/plugins2/jszip/jszip.min.js"></script>
<script src="../admin/plugins2/pdfmake/pdfmake.min.js"></script>
<script src="../admin/plugins2/pdfmake/vfs_fonts.js"></script>
<script src="../admin/plugins2/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../admin/plugins2/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../admin/plugins2/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../admin/dist/js/adminlte.min.js"></script>

 <!-- Jquery JS-->
  
    <!-- Bootstrap JS-->
    <script src="../admin/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="../admin/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="../admin/vendor/slick/slick.min.js">
    </script>
    <script src="../admin/vendor/wow/wow.min.js"></script>
    <script src="../admin/vendor/animsition/animsition.min.js"></script>
    <script src="../admin/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="../admin/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="../admin/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="../admin/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="../admin/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../admin/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="../admin/vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="../admin/js/main.js"></script>

    <!-- AdminLTE for demo purposes -->
<!-- Page specific script -->

</body>

</html>
