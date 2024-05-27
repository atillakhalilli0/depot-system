<?php
include 'header.php';
error_reporting(0);
echo'<div class="container">';
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" WHERE (ad LIKE '%".$_POST['sorgu']."%' OR soyad LIKE '%".$_POST['sorgu']."%' OR telefon LIKE '%".$_POST['sorgu']."%' OR email LIKE '%".$_POST['sorgu']."%' OR maas LIKE '%".$_POST['sorgu']."%' OR sobe_id LIKE '%".$_POST['sorgu']."%' OR vezife_id LIKE '%".$_POST['sorgu']."%' OR tarix LIKE '%".$_POST['sorgu']."%')";
}
else
{$axtar="";}

if(isset($_POST['d']))
{	
	$ad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['ad']))));
	$soyad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['soyad']))));
	$telefon=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['telefon']))));
	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));
	$maas=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['maas']))));
	$sobe_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sobe_id']))));
	$vezife_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['vezife_id']))));


	if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($maas))
	{	
		$yoxla=mysqli_query($con,"SELECT * FROM staff WHERE ad='".$ad."' AND user_id='".$_SESSION['user_id']."' ");

		if(mysqli_num_rows($yoxla)==0)
		{
			include'upload.php';

			if(!isset($error))
			{
				$daxilet=mysqli_query($con,"INSERT INTO staff (ad,soyad,foto,telefon,email,maas,sobe_id,vezife_id,tarix,qtarix,user_id) 
				VALUES ('".$ad."','".$soyad."','".$unvan."','".$telefon."','".$email."','".$maas."','".$sobe_id."','".$vezife_id."','".$_POST['tarix']."','".$tarix."','".$_SESSION['user_id']."')");

			if($daxilet==true)
				{echo '<div class="alert alert-success" role="alert">Qeydiyyatiniz ugurla alindi !</div>';}
			else
				{echo '<div class="alert alert-danger" role="alert">Qeydiyyatiniz daxil edilmedi!</div>';}
			}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Bu sexs artiq iscimizdir!</div>';}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa butun anketi doldurun!</div>';}
}



if(!isset($_POST['edit']))
{
	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
	Iscinin adi:<br>
	<input type="text" name="ad" class="form-control">
	Iscinin soyadi:<br>
	<input type="text" name="soyad" class="form-control">
	Foto:<br>
	<input type="file" name="foto" class="form-control">
	Telefon:<br>
	<input type="text" name="telefon" class="form-control">
	Email:<br>
	<input type="email" name="email" class="form-control">
	Maas:<br>
	<input type="text" name="maas" class="form-control">

	Vezife:<br>
	<select name="vezife_id" class="form-control">
		<option value="">Vezife secin</option>';

	$sec = mysqli_query($con, "SELECT 
                            sobe.sobe,
                            vezife.id,
                            vezife.vezife
                            FROM sobe, vezife 
                            WHERE sobe.id = vezife.sobe_id AND
                            vezife.user_id = '" . $_SESSION['user_id'] . "' 
                            ORDER BY vezife.vezife ASC");


	while($info=mysqli_fetch_array($sec))
	{
		echo'<option value="'.$info['id'].'">'.$info['sobe'].' - '.$info['vezife'].'</option>';
	}
	echo'
	</select>

	Ise baslama tarixi:<br>
	<input type="date" name="tarix" class="form-control">
	<button type="submit" class="btn btn-primary btn-sm" name="d">OK</button>
	</form></div>';
}

if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM staff WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
	Iscinin adi:<br>
	<input type="text" name="ad" class="form-control" value="'.$info['ad'].'">
	Iscinin soyadi:<br>
	<input type="text" name="soyad" class="form-control" value="'.$info['soyad'].'">
	Foto:<br>
	<img style="width:75px; height:60px;" src="'.$info['foto'].'">	
	<input type="file" name="foto" class="form-control">
	Telefon:<br>
	<input type="text" name="telefon" class="form-control" value="'.$info['telefon'].'">
	Email:<br>
	<input type="email" name="email" class="form-control" value="'.$info['email'].'">
	Maas:<br>
	<input type="text" name="maas" class="form-control" value="'.$info['maas'].'">
	Sobe:<br>
	<select name="sobe_id" class="form-control">';

	$ssec=mysqli_query($con,"SELECT * FROM sobe WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY sobe ASC");
	while($sinfo=mysqli_fetch_array($ssec))
	{
		if($info['sobe_id']==$sinfo['id'])
			{echo '<option selected value="'.$sinfo['id'].'">'.$sinfo['sobe'].'</option>';}
		else{echo '<option value="'.$sinfo['id'].'">'.$sinfo['sobe'].'</option>';}
	}

	echo'
	</select>
	Vezife:<br>
		<select name="vezife_id" class="form-control">';
 
	$vsec=mysqli_query($con,"SELECT * FROM vezife WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY vezife ASC");
	while($vinfo=mysqli_fetch_array($vsec))
	{
		if($info['vezife_id']==$vinfo['id'])
			{echo '<option selected value="'.$vinfo['id'].'">'.$vinfo['vezife'].'</option>';}
		else{echo '<option value="'.$vinfo['id'].'">'.$vinfo['vezife'].'</option>';}
	}

echo'
	</select>
	Ise baslama tarixi:<br>
	<input type="date" name="tarix" class="form-control"  value="'.$info['tarix'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="cfoto" value="'.$info['foto'].'">
	<button type="submit" class="btn btn-primary btn-sm" name="update">Yenile</button>
	</form></div>';
}

if(isset($_POST['update']))
{
	$ad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['ad']))));
	$soyad=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['soyad']))));
	$telefon=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['telefon']))));
	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));
	$maas=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['maas']))));
	$sobe_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sobe_id']))));
	$vezife_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['vezife_id']))));

	if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($maas) && !empty($_POST['tarix']))
	{
		if($_FILES['foto']['size']<1024)
		{$unvan=$_POST['cfoto'];}			
			
			else
			 {include 'upload.php';}

		if(!isset($error))
		{
			$yenile=mysqli_query($con,"UPDATE staff SET
							ad='".$ad."',
							soyad='".$soyad."',
							telefon='".$telefon."',
							email='".$email."',
							maas='".$maas."',
							sobe_id='".$sobe_id."',
							vezife_id='".$vezife_id."',
							tarix='".$_POST['tarix']."',
							foto='".$unvan."'

							WHERE id='".$_POST['id']."'");
			if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Melumat ugurla yenilendi!</div>';}
			else
			{echo '<div class="alert alert-danger" role="alert">Melumat yenilenmedi!</div>';}
		}
	}
	else
	{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa brend melumatlarini tam daxil edin!</div>';}
}

if(isset($_POST['sil']))
{
	echo '
	<div class="alert alert-warning role="alert">
	Bu iscini silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
}

if(isset($_POST['d1']))
{
	$sil=mysqli_query($con,"DELETE FROM staff WHERE id='".$_POST['id']."' ");

	if($sil==true)
	{echo'<div class="alert alert-success" role="alert">Isci ughurla silindi!</div>';}
	else
	{echo'<div class="alert alert-danger" role="alert">Iscini silmek mumkun olmadi!</div>';}
}


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
	$order=' ORDER BY maas ASC ';
}
elseif($_GET['f5']=='DESC')
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY maas DESC ';
}
else
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f6']=='ASC')
{
	$f6= '<a href="?f6=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
	$order=' ORDER BY sobe ASC ';
}
elseif($_GET['f6']=='DESC')
{
	$f6= '<a href="?f6=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY sobe DESC ';
}
else
{
	$f6= '<a href="?f6=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f7']=='ASC')
{
	$f7= '<a href="?f7=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
	$order=' ORDER BY vezife ASC ';
}
elseif($_GET['f7']=='DESC')
{
	$f7= '<a href="?f7=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY vezife DESC ';
}
else
{
	$f7= '<a href="?f7=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if(!isset($_GET['f1']) && !isset($_GET['f2']) && !isset($_GET['f3']) && !isset($_GET['f4']) && !isset($_GET['f5']) && !isset($_GET['f6']) && !isset($_GET['f7']))
{$order=' ORDER BY id DESC ';}




$sec=mysqli_query($con,"SELECT
			sobe.sobe,
			vezife.vezife,
			staff.foto,
			staff.ad,
			staff.soyad,
			staff.telefon,
			staff.email,
			staff.maas,
			staff.id,
			staff.tarix,
			staff.vezife_id
			FROM sobe,vezife,staff
			WHERE 
			sobe.id=vezife.sobe_id AND
			vezife.id=staff.vezife_id AND
			staff.user_id='" . $_SESSION['user_id'] . "'
			".$axtar.$order);
$say=mysqli_num_rows($sec);
echo'<div class="alert alert-primary" role="alert">Anbarda <b>'.$say.'</b> isci var</div>';


$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Foto</th>
<th>Ad '.$f1.'</th>
<th>Soyad '.$f2.'</th>
<th>Telefon '.$f3.'</th>
<th>Email '.$f4.'</th>
<th>Maas '.$f5.'</th>
<th>Sobe '.$f6.'</th>
<th>Vezife '.$f7.'</th>
<th>Teyin vaxti</th>
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
	echo '<td>'.$info['maas'].'</td>';
	echo '<td>'.$info['sobe'].'</td>';
	echo '<td>'.$info['vezife'].'</td>';
	echo '<td>'.$info['tarix'].'</td>';


	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<button type="submit" name="edit" class="btn btn-outline-success btn-sm"><i class="bi bi-pencil-square"></i></button>
	<button type="submit" name="sil" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
	<a href="docs.php"<button type="submit" name="doc" class="btn btn-outline-primary btn-sm"><i class="bi bi-book"></i></button></a>

</form>
</td>';
echo'</tr>';
}


echo '</tbody>
	</table>';
?>