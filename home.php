<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Mcc Faculty Scheduling</title>
  <link rel="icon" href="back.png" type="image/png">

  <!-- Include Bootstrap for styling -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

  <?php include('./header.php'); ?>

  <?php 
  if(isset($_SESSION['login_id']))
  header("location:index.php");
  ?>

</head>
<style>
  body{
    width: 100%;
    height: calc(100%);
    position:fixed;
  }
  #main{
    width: calc(100%);
    height: calc(100%);
    display:flex;
    align-items:center;
    justify-content:center;
  }
  #login{
  }
  
  .navbar-brand {
    color: white;
    font-size: 0.9rem;
    font-weight: bold;
    height: 30%;
  }
</style>

<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
  <img src="back.png" alt="Logo" style="width: 40px; height: 30px; margin-right: 8px;"> Faculty Scheduling System
</a>


   
  </nav>

  <main id="main" class="bg-dark">
    <div id="login" class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form id="login-form">
            <h4><b>Welcome To Faculty Scheduling System</b></h4>
            <div class="form-group">
              <label for="id_no" class="control-label">Please enter your Faculty ID No.</label>
              <input type="text" id="id_no" name="id_no" class="form-control">
            </div>
            <center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
          </form>
          <br>
        </div>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Include Bootstrap JS and jQuery for functionality -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    $('#login-form').submit(function(e){
      e.preventDefault();
      $('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
      if($(this).find('.alert-danger').length > 0 )
        $(this).find('.alert-danger').remove();
      $.ajax({
        url:'admin/ajax.php?action=login_faculty',
        method:'POST',
        data:$(this).serialize(),
        error:err=>{
          console.log(err);
          $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
        },
        success:function(resp){
          if(resp == 1){
            location.href ='index.php';
          }else{
            $('#login-form').prepend('<div class="alert alert-danger">ID Number is incorrect.</div>');
            $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
          }
        }
      })
    });
  </script>

</body>
</html>
