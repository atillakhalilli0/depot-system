<?php
session_start();

if(!isset($_SESSION['email']) or !isset($_SESSION['parol']))
{echo '<meta http-equiv="refresh" content="0; url=index.php">'; exit;}

$con=mysqli_connect('localhost','atilla6','12345','anbar');

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Anbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="brands.php">Brands</a>
        </li>
           <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="clients.php">Clients</a>
        </li>
           <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="xerc.php">Xerc</a>
        </li>
           <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="products.php">Products</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="orders.php">Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="sobe.php">Sobe</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="vezife.php">Vezife</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="staff.php">Staff</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="profile.php"><?=$_SESSION['ad'].' '.$_SESSION['soyad']?></a>
        </li>
      </ul>
      <form class="d-flex" method="post">
        <input class="form-control me-2" type="search" name="sorgu" placeholder="Axtar" aria-label="Search">
        <button class="btn btn-outline-success" type="submit" name="axtar">Axtar</button>&nbsp;&nbsp;
        <button class="btn btn-outline-success" type="submit" name="all">Hamisi</button>&nbsp;&nbsp;
        <a href="exit.php" class="btn btn-outline-danger">Cixis</a>
      </form>
        
    </div>
  </div>
</nav>

