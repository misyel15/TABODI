
<style>
  .logo {
    margin: auto;
    font-size: 0px;
    background: white;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
  }

  .custom-navbar {
    background-color: white; /* Dark red color */
   
  }

  .navbar-brand img {
    width: 50px;
    height: 40px;
  }

  .navbar-brand {
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
  }

  .dropdown-menu {
    background: white; /* Same as navbar background */
    border: none;
  }

  .dropdown-item {
    color: black;
  }

  .dropdown-item:hover {
    background-color:  #eae6f5; /* Darker red for hover effect */
  }

  .navbar-nav {
    margin-left: auto; /* Align items to the right */
    font-size: 0.8rem;
    font-weight: bold;
  }

  .nav-item {
    margin-left: 15px; /* Space between items */
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light fixed-top custom-navbar" style="padding: 0; min-height: 2.0rem;">
  <div class="container-fluid mt-2 mb-2">
    <a class="navbar-brand" href="#">
      <img src="mcclogo.jpg" alt="Logo"> MFSS
    </a>
    <ul class="navbar-nav ml-auto"> <!-- Aligns the nav items to the right -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-black" href="#" id="account_settings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-user text-secondary" aria-hidden="true"></i> <?php echo $_SESSION['login_name'] ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="account_settings">
          <a class="dropdown-item" href="admin/ajax.php?action=logout2"><i class="fa fa-power-off"></i> Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own");
  });
</script>
