<?php
error_reporting(0);
include 'header.php';
echo '<div class="container">';

$tarix=date('Y-m-d H:i:s');

if(isset($_POST['d']))
{
	$teyinat=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['teyinat']))));
	$mebleg=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['mebleg']))));



	if(!empty($teyinat) && !empty($mebleg))
	{
		$yoxla=mysqli_query($con,"SELECT * FROM orders WHERE user_id='".$_SESSION['user_id']."' ");
		{$daxilet=mysqli_query($con,"INSERT INTO xerc (teyinat,mebleg,tarix,user_id) VALUES ('".$teyinat."','".$mebleg."','".$tarix."','".$_SESSION['user_id']."')");
		
				if($daxilet==true)
					{echo '<div class="alert alert-success" role="alert">Melumat ugurla bazaya yerlesdirildi!</div>';}
				else
					{echo '<div class="alert alert-danger" role="alert">Melumat bazaya daxil edilmedi!</div>';}
			}}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa melumatlari duzgun daxil edin</div>';}
}

if(!isset($_POST['edit']))
	{	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post">
	Mebleg:<br>
	<input type="text" class="form-control" name="mebleg">
	Teyinat:<br>
	<input type="text" class="form-control" name="teyinat">
	<button type="submit" class="btn btn-primary btn-sm"name="d">OK</button>
</form> </div>';
}


if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM xerc WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

echo'
	<div class="alert alert-primary role="alert">
<form method="post">
	Mebleg:<br>
	<input type="text" class="form-control" name="mebleg" value="'.$info['mebleg'].'">
	Teyinat:<br>
	<input type="text" class="form-control" name="soyad" value="'.$info['teyinat'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
</form>
</div>';
}

if(isset($_POST['update']))
{
	$teyinat=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['teyinat']))));
	$mebleg=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['mebleg']))));

	if(!empty($teyinat) && !empty($mebleg))
	{
		$yenile=mysqli_query($con,"UPDATE xerc SET
							teyinat='".$teyinat."',
							mebleg='".$mebleg."'

							WHERE id='".$_POST['id']."'");
		if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Melumat ugurla yenilendi!</div>';}
		else
			{echo '<div class="alert alert-danger" role="alert">Melumat yenilenmedi!</div>';}
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa melumatlari tam daxil edin!</div>';}
}

if(isset($_POST['sil']))
{
	echo '
	<div class="alert alert-warning role="alert">
	Bu melumati silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
}

if(isset($_POST['d1']))
	{$sil=mysqli_query($con,"DELETE FROM xerc WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Melumat ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Melumati silmek mumkun olmadi!</div>';}
	}
	else{;}


if($_GET['f1']=='ASC')
{
	$f1= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY mebleg ASC ';
}
elseif($_GET['f1']=='DESC')
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY mebleg DESC ';
}
else
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f2']=='ASC')
{
	$f2= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY teyinat ASC ';
}
elseif($_GET['f2']=='DESC')
{
	$f2= '<a href="?f1=AS#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY teyinat DESC ';
}
else
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f3']=='ASC')
{
	$f3= '<a href="?f3=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY tarix ASC ';
}
elseif($_GET['f3']=='DESC')
{
	$f3= '<a href="?f3=AS#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY tarix DESC ';
}
else
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if(!isset($_GET['f1']) && !isset($_GET['f2']) && !isset($_GET['f3']))
{$order=' ORDER BY id DESC ';}


$sec=mysqli_query($con,"SELECT * FROM xerc WHERE user_id='".$_SESSION['user_id']."' ".$axtar.$order);

$say=mysqli_num_rows($sec);

echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> xerc var</div>';


$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Mebleg '.$f1.'</th>
<th>Teyinat '.$f2.'</th>
<th>Tarix '.$f3.'</th>
<th></th>
</thead>

<tbody>';


while($info=mysqli_fetch_array($sec))
{
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo '<td>'.$info['mebleg'].'</td>';
	echo '<td>'.$info['teyinat'].'</td>';
	echo '<td>'.$info['tarix'].'</td>';


	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="ad" value="'.$info['mebleg'].'">
	<input type="hidden" name="soyad" value="'.$info['teyinat'].'">
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

