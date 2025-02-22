<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$authenticated = isset($_SESSION["email"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Syzygy App - Home</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Custom CSS (if any) -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="images/logo.png" width="30" height="30" alt=""> Syzygy & Snazzy Co.
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active text-dark"  href="Index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="shop_now.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="cart.php">View Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="About_us.php">About Us</a>
        </li> 
       
       
       
      </ul>
      <?php
      if ($authenticated){

      ?>
      <ul class="navbar-nav ">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Admin
          </a>
          <ul class="dropdown-menu">
  
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        
        </li>


        <?php
     } else{
      ?>

      </ul>
      <ul class="navbar-nav ">

      <li class="nav-item">
        <a href="register.php" class="btn btn-outline-primary me-2">Register</a>
      </li>
      <li class="nav-item">
        <a href="login.php" class="btn btn-primary ">Login</a>
      </li>
      <?php
     }
      ?>
      </ul>
    </div>
  </div>
</nav>