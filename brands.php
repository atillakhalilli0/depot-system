<?php
include'header.php';
error_reporting(0);

echo'<div class="container">';
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" AND (brend LIKE '%".$_POST['sorgu']."%' OR tarix LIKE '%".$_POST['sorgu']."%')";
}
else
{$axtar="";}


if(isset($_POST['d']))
{	
	$brands=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['brands']))));
	


	if(!empty($brands))
	{	
		$yoxla=mysqli_query($con,"SELECT * FROM brands WHERE brend='".$brands."' AND user_id='".$_SESSION['user_id']."' ");

		if(mysqli_num_rows($yoxla)==0)
		{
			include'upload.php';

			if(!isset($error))
			{
				$daxilet=mysqli_query($con,"INSERT INTO brands (brend,foto,tarix,user_id) 
				VALUES ('".$brands."','".$unvan."','".$tarix."','".$_SESSION['user_id']."')");

			if($daxilet==true)
				{echo '<div class="alert alert-success" role="alert">Melumat ugurla bazaya yerlesdirildi!</div>';}
			else
				{echo '<div class="alert alert-danger" role="alert">Melumat bazaya daxil edilmedi!</div>';}
			}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Bu brend artiq bazada movcuddur!</div>';}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa brendi duzgun daxil edin!</div>';}
}



if(!isset($_POST['edit']))
{
	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
	Brend:<br>
	<input type="text" class="form-control" name="brands">
	Logo:<br>
	<input type="file" class="form-control" name="foto">
	<button type="submit" class="btn btn-primary btn-sm" name="d">OK</button>
	</form>
	</div>';
}

if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM brands WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

	echo'
	<div class="alert alert-primary role="alert">
	<form method="post" enctype="multipart/form-data">
	Brend:<br>
	<input type="text" class="form-control" name="brands" value="'.$info['brend'].'">
	Logo:<br>
	<img style="width:75px; height:60px;" src="'.$info['foto'].'">
	<input type="file" class="form-control" name="foto">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="cfoto" value="'.$info['foto'].'">	
	<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
	</form> </div>';
}

if(isset($_POST['update']))
{	
	$brands=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['brands']))));

	if(!empty($brands))
	{
		if($_FILES['foto']['size']<1024)
		{$unvan=$_POST['cfoto'];}
		else	
		{include 'upload.php';}

		if(!isset($error))
		{
			$yenile=mysqli_query($con,"UPDATE brands SET
					brend='".$brands."',
					foto='".$unvan."'

					WHERE id='".$_POST['id']."'");
				if($yenile==true)
				{echo '<div class="alert alert-success" role="alert">Brend ugurla yenilendi!</div>';}
				
				else
				{echo '<div class="alert alert-danger" role="alert">Brend yenilenmedi!</div>';}
		}			
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa brend melumatlarini tam daxil edin!</div>';}
}

if(isset($_POST['sil']))
{
	$yoxla=mysqli_query($con,"SELECT * FROM products WHERE brand_id='".$_POST['id']."'");

	if(mysqli_num_rows($yoxla)==0)
	{
	echo '
	<div class="alert alert-warning role="alert">
	<b>'. $_POST['brands'].'</b> adli brendi silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
	}
	else {echo '<div class="alert alert-danger" role="alert">Bu brendden aktiv sifaris movcuddur!</div>';}
}

if(isset($_POST['d1']))
	{
		$sil=mysqli_query($con,"DELETE FROM brands WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Brend ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Brendi silmek mumkun olmadi!</div>';}
	}
	else{'';}


if($_GET['f1']=='ASC')
{
	$f1= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY brend ASC ';
}
elseif($_GET['f1']=='DESC')
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY brend DESC ';
}
else
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f2']=='ASC')
{
	$f2= '<a href="?f2=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY tarix ASC ';
}
elseif($_GET['f2']=='DESC')
{
	$f2= '<a href="?f2=AS#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY tarix DESC ';
}
else
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if(!isset($_GET['f1']) && !isset($_GET['f2']))
{$order=' ORDER BY id DESC ';}



$sec=mysqli_query($con,"SELECT * FROM brands WHERE user_id='".$_SESSION['user_id']."' ".$axtar.$order);

$say=mysqli_num_rows($sec);

echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> brend var</div>';

$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Logo</th>
<th>Brend '.$f1.'</th>
<th>Tarix '.$f2.'</th>
<th></th>
</thead>

<tbody>';

while($info=mysqli_fetch_array($sec))
{
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo'<td><img style="width:75px; height:60px;" src="'.$info['foto'].'"></td>';
	echo '<td>'.$info['brend'].'</td>';	
	echo '<td>'.$info['tarix'].'</td>';

	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="brands" value="'.$info['brend'].'">
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