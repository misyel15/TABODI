<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Semester</title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:sem.php");

?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		background:#59b6ec61;
		display: flex;
		align-items: center;
		background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>);
	    background-repeat: no-repeat;
	    background-size: cover;
	}
	#login-right .card{
		margin: auto;
		z-index: 1
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    background: white;
    padding: .5em 0.7em;
    border-radius: 50% 50%;
    color: #000000b3;
    z-index: 10;
}
div#login-right::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100%);
    height: calc(100%);
    background: #000000e0;
}

</style>

<body>


  <main id="main" class=" bg-dark">
  		<div id="login-left">
  		</div>

  		<div id="login-right">
  			<div class="card col-md-8">
  				<div class="card-body">
  						
  					
  				</div>
  			</div>
  		</div>
   

  
</html>