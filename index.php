<?php
session_start();
if(isset($_SESSION['email']) && isset($_SESSION['parol']))
{echo '<meta http-equiv="refresh" content="0; url=brands.php">'; exit;}

?>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="#">Anbar</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		
		<ul class="navbar-nav mr-auto">
		 
			<li class="nav-item active">
				<a class="nav-link" href="#">Haqqımızda</a>
			</li>

			<li class="nav-item active">
				<a class="nav-link" href="#">Əlaqə</a>
			</li>

		 
		</ul>


		
	</div>
</nav>


<br><br><br><br>


<?php

error_reporting(0);
$con=mysqli_connect('localhost','atilla13','12345','anbar');

if(isset($_POST['daxilol']))
{
	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));
	$parol=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['parol']))));

	$yoxla=mysqli_query($con," SELECT * FROM users WHERE email='".$email."' AND parol='".$parol."' ");
	if(mysqli_num_rows($yoxla)>0)
	{
		$info=mysqli_fetch_array($yoxla);

		$_SESSION['user_id']=$info['id'];
		$_SESSION['ad']=$info['ad'];
		$_SESSION['soyad']=$info['soyad'];
		$_SESSION['foto']=$info['foto'];
		$_SESSION['telefon']=$info['telefon'];
		$_SESSION['email']=$info['email'];
		$_SESSION['parol']=$info['parol'];
		echo '<meta http-equiv="refresh" content="0; url=brands.php">'; exit;
	}
}

if(isset($_POST['d']))
{
	$ad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['ad']))));
	$soyad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['soyad']))));
	$telefon=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['telefon']))));
	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));
	$parol=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['parol']))));

	if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($parol))
		{
			$yoxla=mysqli_query($con," SELECT * FROM users WHERE telefon='".$telefon."' OR email='".$email."' ");

			if(mysqli_num_rows($yoxla)==0)
			{
				include 'upload.php';

				if(!isset($error))
				{
					$daxilet=mysqli_query($con," INSERT INTO users (ad,soyad,foto,telefon,email,parol)
					 VALUES ('".$ad."','".$soyad."','".$unvan."','".$telefon."','".$email."','".$parol."')");

					if($daxilet==true)
						{echo '<div class="alert alert-success" role="alert">Ugurla qeydiyyatdan kecdiniz!</div>';}
					else
						{echo '<div class="alert alert-danger" role="alert">Siz qeydiyyatdan kece bilmediniz!</div>';}
				}
			}
			else
				{echo '<div class="alert alert-warning" role="alert">Bu istifadeci qeydiyyatdan kecirilib!</div>';}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa melumatlari duzgun doldurun!</div>';}
}

			

if(!isset($_POST['edit']))
{
	echo'
	<div class="container" style="width: 900px;text-align:center;">

	<div class="alert alert-warning" role="alert">Anbar proqramında işləmək üçün lütfən ya qeydiyyatdan keçin, ya da sistemə daxil olun</div>
			<div class="alert alert-info" style="display: flex;" role="alert">
				<form method="post" style="margin-left:60px" enctype="multipart/form-data">
					<h3>Qeydiyyatdan Keç</h3>	
					Ad:<br>
					<input type="text" name="ad" class="form-control" autocomplete="off" value="'.$_POST['ad'].'">
					Soyad:<br>
					<input type="text" name="soyad" class="form-control" autocomplete="off" value="'.$_POST['soyad'].'">
					Foto:<br>
					<input type="file" name="foto" class="form-control" value="'.$_POST['foto'].'">
					Telefon:<br>
					<input type="text" name="telefon" class="form-control" autocomplete="off" value="'.$_POST['telefon'].'">
					Email:<br>
					<input type="email" name="email" class="form-control" autocomplete="off" value="'.$_POST['email'].'">
					Parol:<br>
					<input type="password" name="parol" class="form-control" autocomplete="off" value="'.$_POST['parol'].'"><br>
					<button type="submit" name="d"  class="btn btn-primary btn-sm ">Qeydiyyatdan keç</button>
				</form>

				<div class="alert alert-info" role="alert">
				<form method="post" style="margin-left:150px;">
					<h3>Daxil Ol</h3>
					Email:<br>
					<input class="form-control mr-sm-2" type="email" placeholder="email" aria-label="Search" name="email"><br>
					Password:
					<input class="form-control mr-sm-2" type="password" placeholder="parol" aria-label="Search" name="parol"><br>
					<button class="btn btn-success my-2 my-sm-0" type="submit" name="daxilol">Daxil ol</button>
				</form></div>
			</div>';
}


?>