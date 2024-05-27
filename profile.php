<?php
include"header.php";
?>

<div class="container">

<?php
//error_reporting(0);


if (isset($_POST['uptade']))
{
	if($_POST['cparol']==$_SESSION['parol'])
	{

		$ad = mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['ad']))));
		$soyad = mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['soyad']))));
		$telefon = mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['telefon']))));
		$email = mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['email']))));
		$yparol = mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['yparol']))));


		if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($yparol))
		{
			$yoxla = mysqli_query ($con,"SELECT * FROM users 
											WHERE 
											telefon='".$telefon."'AND id!='".$_SESSION['user_id']."' OR 
											email='".$email."' AND id!='".$_SESSION['user_id']."'");
			if(mysqli_num_rows($yoxla)==0)
			
			{
				if(!isset($error))
				{
					if($_FILES['foto']['size']<1024)
					{$unvan = $_SESSION['foto'];}
					else
					{include"upload.php";}

					$yenile=mysqli_query($con,"UPDATE users SET

						ad='".$ad."',
						soyad='".$soyad."',
						telefon='".$telefon."',
						email='".$email."',
						foto='".$unvan."',
						parol='".$yparol."'
						WHERE id='".$_SESSION['user_id']."'");

					if($yenile==true)
					{
						echo'<div class="alert alert-success" role="alert">Müştəri uğurla yeniləndi</div>';
						$_SESSION['ad']=$ad;
						$_SESSION['soyad']=$soyad;
						$_SESSION['telefon']=$telefon;
						$_SESSION['email']=$email;
						$_SESSION['foto']=$unvan;
						$_SESSION['parol']=$yparol;
						echo'<meta http-equiv="refresh" content="3; URL=profile.php">';
					}
					else
					{echo'<div class="alert alert-danger" role="alert">Müştərini yeniləmək mümkün olmadı</div>';}
				}	
			}
			else
		  	{echo'<div class="alert alert-danger" role="alert">Müştəri bazada mövcuddur</div>';}
		}
		elseif(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email))
		{
			$yoxla = mysqli_query ($con,"SELECT * FROM users 
											WHERE 
											telefon='".$telefon."'AND id!='".$_SESSION['user_id']."' OR 
											email='".$email."' AND id!='".$_SESSION['user_id']."'");
			if(mysqli_num_rows($yoxla)==0)
			{
				if(!isset($error))
				{
					if($_FILES['foto']['size']<1024)
					{$unvan = $_SESSION['foto'];}
					else
					{include"upload.php";}

					$yenile=mysqli_query($con,"UPDATE users SET

						ad='".$ad."',
						soyad='".$soyad."',
						telefon='".$telefon."',
						email='".$email."',
						foto='".$unvan."'
						WHERE id='".$_SESSION['user_id']."'");

					if($yenile==true)
					{	echo'<div class="alert alert-success" role="alert">Profil uğurla yeniləndi. Bir neçə saniyədən sonra təkrar profil səhifəsinə yönləndiriləcəksiniz</div>';
						$_SESSION['ad']=$ad;
						$_SESSION['soyad']=$soyad;
						$_SESSION['telefon']=$telefon;
						$_SESSION['email']=$email;
						$_SESSION['foto']=$unvan;
						echo'<meta http-equiv="refresh" content="3; URL=profile.php">';
					}
					else
					{echo'<div class="alert alert-danger" role="alert">Müştərini yeniləmək mümkün olmadı</div>';}
					
				}	
			}
			else
			{echo'<div class="alert alert-danger" role="alert">Müştəri bazada mövcuddur</div>';}

		  	
		}
		else
		{echo'<div class="alert alert-danger" role="alert">Məlumatlar tam deyil</div>';}

	}
	else
	{echo'<div class="alert alert-danger" role="alert">Cari şifrə doğru deyil</div>';}
	
}



	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post"enctype="multipart/form-data">';
		
$tut=mysqli_query($con,"SELECT * FROM users WHERE id='".$_SESSION['user_id']."'");

$inf=mysqli_fetch_array($tut);
		echo'
		<img style="width:75px; heigth:75px;" src="'.$_SESSION['foto'].'"><br>
		Photo:<br>
		<input type="file" name="foto" class="form-control"><br>	
		Ad:<br>
		<input type="text" name="ad" class="form-control"  value="'.$_SESSION['ad'].'">
		Soyad:<br>
		<input type="text" name="soyad" class="form-control" value="'.$_SESSION['soyad'].'">
		Əlaqə nömrəsi:<br>
		<input type="text" name="telefon" class="form-control" value="'.$_SESSION['telefon'].'">
		Elektron poçt ünvanı:<br>
		<input type="text" name="email" class="form-control" value="'.$_SESSION['email'].'">
		Yeni şifrə:<br>
		<b>*Əgər redaktə etmirsinizsə bosh buraxin</b>
		<input type="password" name="yparol" class="form-control">
		Cari şifrə:<br>
		<b>*Dəyişiklikləri yadda sxalamaq üçün cari şifrəni daxil edin</b>
		<input type="password" name="cparol" class="form-control"><br>
		<button type="submit" name="uptade" class="btn btn-success btn-sm">Təsdiq et</button>
	</form>
	</div>';
	

	?>
</div>
