<?php
error_reporting(0);
include 'header.php';
echo '<div class="container">';
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" WHERE (ad LIKE '%".$_POST['sorgu']."%' OR soyad LIKE '%".$_POST['sorgu']."%' OR telefon LIKE '%".$_POST['sorgu']."%' OR email LIKE '%".$_POST['sorgu']."%' OR sirket LIKE '%".$_POST['sorgu']."%' OR tarix LIKE '%".$_POST['sorgu']."%')";
}
else
{$axtar="";}


if(isset($_POST['d']))
{
	$ad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['ad']))));

	$soyad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['soyad']))));

	$telefon=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['telefon']))));

	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));

	$sirket=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sirket']))));



	if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($sirket))
	{
		$yoxla=mysqli_query($con,"SELECT * FROM clients WHERE telefon='".$telefon."' AND user_id='".$_SESSION['user_id']."'");

		if(mysqli_num_rows($yoxla)==0)
		{
			include'upload.php';

			if(!isset($error))
			{
				$daxilet=mysqli_query($con,"INSERT INTO clients(ad,soyad,telefon,email,sirket,foto,tarix,user_id)
					VALUES('".$ad."','".$soyad."','".$telefon."','".$email."','".$sirket."','".$unvan."','".$tarix."','".$_SESSION['user_id']."')");

			if($daxilet==true)
				{echo '<div class="alert alert-success" role="alert">Melumat ugurla bazaya yerlesdirildi!</div>';}
			else
				{echo '<div class="alert alert-danger" role="alert">Melumat bazaya daxil edilmedi!</div>';}
			}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Bu melumat bazada movcuddur!</div>';}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa melumatlarinizi duzgun daxil edin!</div>';}
}

if(!isset($_POST['edit']))
{	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
	Ad:<br>
	<input type="text" class="form-control" name="ad">
	Soyad:<br>
	<input type="text" class="form-control" name="soyad">
	Foto:<br>
	<input type="file" class="form-control" name="foto">
	Telefon:<br>
	<input type="text" class="form-control" name="telefon">
	Email:<br>
	<input type="text" class="form-control" name="email">
	Sirket:<br>
	<input type="text" class="form-control" name="sirket">
	<button type="submit" class="btn btn-primary btn-sm"name="d">OK</button>
</form> 
</div>';
}

if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM clients WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

	echo'
	<div class="alert alert-primary role="alert">
<form method="post" enctype="multipart/form-data">
	Ad:<br>
	<input type="text" class="form-control" name="ad" value="'.$info['ad'].'">
	Soyad:<br>
	<input type="text" class="form-control" name="soyad" value="'.$info['soyad'].'">
	Foto:<br>
	<img style="width:75px; height:60px;" src="'.$info['foto'].'">	
	<input type="file" class="form-control" name="foto">
	Telefon:<br>
	<input type="text" class="form-control" name="telefon" value="'.$info['telefon'].'">
	Email:<br>
	<input type="text" class="form-control" name="email" value="'.$info['email'].'">
	Sirket:<br>
	<input type="text" class="form-control" name="sirket" value="'.$info['sirket'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="cfoto" value="'.$info['foto'].'">
	<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
</form>
</div>';
}

if(isset($_POST['update']))
{
	$ad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['ad']))));

	$soyad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['soyad']))));

	$telefon=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['telefon']))));

	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));

	$sirket=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sirket']))));
	
	if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($sirket))
	{
		if($_FILES['foto']['size']<1024)
		{$unvan=$_POST['cfoto'];}
			
			else
			 {include 'upload.php';}

		if(!isset($error))
		{
			$yenile=mysqli_query($con,"UPDATE clients SET
							ad='".$ad."',
							soyad='".$soyad."',
							telefon='".$telefon."',
							email='".$email."',
							sirket='".$sirket."',
							foto='".$unvan."'

							WHERE id='".$_POST['id']."'");
			if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Melumatiniz ugurla yenilendi!</div>';}
			else
			{echo '<div class="alert alert-danger" role="alert">Melumatiniz yenilenmedi!</div>';}
		}
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa melumatlarinizi tam daxil edin!</div>';}
}

if(isset($_POST['sil']))
{
	$yoxla=mysqli_query($con,"SELECT * FROM orders WHERE client_id='".$_POST['id']."'");

	if(mysqli_num_rows($yoxla)==0)
	{echo '
	<div class="alert alert-warning role="alert">
	Bu melumati silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
	}
	else {echo '<div class="alert alert-danger" role="alert">Bu musterinin sifarisi movcuddur!</div>';}
}

if(isset($_POST['d1']))
	{$sil=mysqli_query($con,"DELETE FROM clients WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Melumat ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Melumati silmek mumkun olmadi!</div>';}
	}
	else{;}



if($_GET['f1']=='ASC')
{
	$f1= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY ad ASC ';
}
elseif($_GET['f1']=='DESC')
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY ad DESC ';
}
else
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}



if($_GET['f2']=='ASC')
{
	$f2= '<a href="?f2=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY soyad ASC ';
}
elseif($_GET['f2']=='DESC')
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY soyad DESC ';
}
else
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}



if($_GET['f3']=='ASC')
{
	$f3= '<a href="?f3=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
	$order=' ORDER BY telefon ASC ';
}
elseif($_GET['f3']=='DESC')
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY telefon DESC ';
}
else
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f4']=='ASC')
{
	$f4= '<a href="?f4=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
	$order=' ORDER BY email ASC ';
}
elseif($_GET['f4']=='DESC')
{
	$f4= '<a href="?f4=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY email DESC ';
}
else
{
	$f4= '<a href="?f4=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f5']=='ASC')
{
	$f5= '<a href="?f5=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
	$order=' ORDER BY sirket ASC ';
}
elseif($_GET['f5']=='DESC')
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY sirket DESC ';
}
else
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f6']=='ASC')
{
	$f6= '<a href="?f6=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
	$order=' ORDER BY tarix ASC ';
}
elseif($_GET['f6']=='DESC')
{
	$f6= '<a href="?f6=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY tarix DESC ';
}
else
{
	$f6= '<a href="?f6=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if(!isset($_GET['f1']) && !isset($_GET['f2']) && !isset($_GET['f3']) && !isset($_GET['f4']) && !isset($_GET['f5']) && !isset($_GET['f6']))
{$order=' ORDER BY id DESC ';}

$sec=mysqli_query($con,"SELECT * FROM clients WHERE user_id='".$_SESSION['user_id']."' ".$axtar.$order);

$say=mysqli_num_rows($sec);
echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> musteri var</div>';


$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Foto</th>
<th>Ad '.$f1.'</th>
<th>Soyad '.$f2.'</th>
<th>Telefon '.$f3.'</th>
<th>Email '.$f4.'</th>
<th>Sirket '.$f5.'</th>
<th>Tarix '.$f6.'</th>
<th></th>
</thead>

<tbody>';


while($info=mysqli_fetch_array($sec))
{
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo'<td><img style="width:75px; height:60px;" src="'.$info['foto'].'"></td>';
	echo '<td>'.$info['ad'].'</td>';
	echo '<td>'.$info['soyad'].'</td>';
	echo '<td>'.$info['telefon'].'</td>';
	echo '<td>'.$info['email'].'</td>';
	echo '<td>'.$info['sirket'].'</td>';
	echo '<td>'.$info['tarix'].'</td>';

	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="ad" value="'.$info['ad'].'">
	<input type="hidden" name="soyad" value="'.$info['soyad'].'">
	<input type="hidden" name="telefon" value="'.$info['telefon'].'">
	<input type="hidden" name="email" value="'.$info['email'].'">
	<input type="hidden" name="sirket" value="'.$info['sirket'].'">
	<button type="submit" name="edit" class="btn btn-outline-success btn-sm"><i class="bi bi-pencil-square"></i></button>
	<button type="submit" name="sil" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
</form>
</td>';
echo'</tr>';
}


echo '</tbody>
	</table>';

?>
</div>