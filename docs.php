<?php
include'header.php';

error_reporting(0);

echo'<div class="container">';
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" AND (docs LIKE '%".$_POST['sorgu']."%' OR tarix LIKE '%".$_POST['sorgu']."%')";
}
else
{$axtar=" ";}


if(isset($_POST['d']))
{	
	$docs=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['docs']))));
	$about=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['about']))));


	if(!empty($docs) && !empty($about))
	{	
		$yoxla=mysqli_query($con,"SELECT * FROM docs WHERE docs='".$docs."' AND user_id='".$_SESSION['user_id']."' ");

		if(mysqli_num_rows($yoxla)==0)
		{
			include'upload.php';

			if(!isset($error))
			{
				$daxilet=mysqli_query($con,"INSERT INTO docs (docs,foto,about,tarix,user_id) 
				VALUES ('".$docs."','".$unvan."','".$about."','".$tarix."','".$_SESSION['user_id']."')");

			if($daxilet==true)
				{echo '<div class="alert alert-success" role="alert">Sened ugurla bazaya yerlesdirildi!</div>';}
			else
				{echo '<div class="alert alert-danger" role="alert">Sened bazaya daxil edilmedi!</div>';}
			}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Bu sened artiq bazada movcuddur!</div>';}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa senedi duzgun daxil edin!</div>';}
}


if(!isset($_POST['edit']))
{
	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
	Sened:<br>
	<input type="text" class="form-control" name="docs">
	Senedin fotosu:<br>
	<input type="file" class="form-control" name="foto">
	Elave melumat:<br>
	<input type="text" class="form-control" name="about">
	<button type="submit" class="btn btn-primary btn-sm" name="d">Elave et</button>
	</form>
	</div>';
}


if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM docs WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

	echo'
	<div class="alert alert-primary role="alert">
	<form method="post" enctype="multipart/form-data">
	Sened:<br>
	<input type="text" class="form-control" name="docs" value="'.$info['docs'].'">
	Senedin fotosu:<br>
	<img style="width:75px; height:60px;" src="'.$info['foto'].'">
	<input type="file" class="form-control" name="foto">
	Elave melumat:<br>
	<input type="text" class="form-control" name="about" value="'.$info['about'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="cfoto" value="'.$info['foto'].'">
	<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
	</form> </div>';
}

if(isset($_POST['update']))
{	
	$docs=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['docs']))));
	$about=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['about']))));

	if(!empty($docs) && !empty($about))
	{
		if($_FILES['foto']['size']<1024)
		{$unvan=$_POST['cfoto'];}
		else	
		{include 'upload.php';}

		if(!isset($error))
		{
			$yenile=mysqli_query($con,"UPDATE docs SET
					docs='".$docs."',
					foto='".$unvan."',
					about='".$about."'


					WHERE id='".$_POST['id']."'");
				if($yenile==true)
				{echo '<div class="alert alert-success" role="alert">Sened ugurla yenilendi!</div>';}
				
				else
				{echo '<div class="alert alert-danger" role="alert">Sened yenilenmedi!</div>';}
		}			
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa senedin melumatlarini tam daxil edin!</div>';}
}

if(isset($_POST['sil']))
{
	echo '
	<div class="alert alert-warning role="alert">
	<b>'. $_POST['docs'].'</b> adli senedi silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
}

if(isset($_POST['d1']))
	{
		$sil=mysqli_query($con,"DELETE FROM docs WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Sened ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Senedi silmek mumkun olmadi!</div>';}
	}
	

if($_GET['f1']=='ASC')
{
	$f1= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY docs ASC ';
}
elseif($_GET['f1']=='DESC')
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY docs DESC ';
}
else
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if(!isset($_GET['f1']))
{$order=' ORDER BY id DESC ';}

$sec=mysqli_query($con,"SELECT * FROM docs WHERE user_id='".$_SESSION['user_id']."' ".$axtar.$order);

$say=mysqli_num_rows($sec);

echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> sened var</div>';

$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Foto</th>
<th>Sened '.$f1.'</th>
<th>Haqqinda </th>
<th>Tarix </th>
<th></th>
</thead>

<tbody>';


while($info=mysqli_fetch_array($sec))
{
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo'<td><img style="width:75px; height:60px;" src="'.$info['foto'].'"></td>';
	echo '<td>'.$info['docs'].'</td>';	
	echo '<td>'.$info['about'].'</td>';
	echo '<td>'.$info['tarix'].'</td>';

	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
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