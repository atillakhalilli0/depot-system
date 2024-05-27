<?php
include'header.php';

error_reporting(0);

echo'<div class="container">';
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" AND (sobe LIKE '%".$_POST['sorgu']."%' OR tarix LIKE '%".$_POST['sorgu']."%')";
}
else
{$axtar="";}

if(isset($_POST['d']))
{	
	$sobe=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sobe']))));

	if(!empty($sobe))
	{	
		$yoxla=mysqli_query($con,"SELECT * FROM sobe WHERE sobe='".$sobe."' AND user_id='".$_SESSION['user_id']."' ");

		if(mysqli_num_rows($yoxla)==0)
		{
			$daxilet=mysqli_query($con,"INSERT INTO sobe (sobe,user_id,tarix) 
			VALUES ('".$sobe."','".$_SESSION['user_id']."','".$tarix."')");

			if($daxilet==true)
				{echo '<div class="alert alert-success" role="alert">Sobe ugurla bazaya yerlesdirildi!</div>';}
			else
				{echo '<div class="alert alert-danger" role="alert">Sobe bazaya daxil edilmedi!</div>';}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Bu sobe artiq bazada movcuddur!</div>';}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa sobe melumatlarini duzgun daxil edin!</div>';}
}


if(!isset($_POST['edit']))
{
	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post">
	Sobe:<br>
	<input type="text" class="form-control" name="sobe">
	<button type="submit" class="btn btn-primary btn-sm" name="d">OK</button>
	</form>
	</div>';
}

if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM sobe WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

	echo'
	<div class="alert alert-primary role="alert">
	<form method="post">
	Sobe:<br>
	<input type="text" class="form-control" name="sobe" value="'.$info['sobe'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">	
	<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
	</form> </div>';
}


if(isset($_POST['update']))
{	
	$sobe=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sobe']))));

	if(!empty($sobe))
	{
		$yenile=mysqli_query($con,"UPDATE sobe SET
				sobe='".$sobe."'

				WHERE id='".$_POST['id']."'");
			if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Sobe ugurla yenilendi!</div>';}
				
			else
			{echo '<div class="alert alert-danger" role="alert">Sobe yenilenmedi!</div>';}
	}			
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa sobe melumatlarini tam daxil edin!</div>';}
}


if(isset($_POST['sil']))
{
	$yoxla=mysqli_query($con,"SELECT * FROM vezife WHERE sobe_id='".$_POST['id']."'");

	if(mysqli_num_rows($yoxla)==0)
	{
	echo '
	<div class="alert alert-warning role="alert">
	<b>'. $_POST['sobe'].'</b> adli brendi silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
	}
	else {echo '<div class="alert alert-danger" role="alert">Bu sobede aktiv vezife movcuddur!</div>';}
}

if(isset($_POST['d1']))
	{
		$sil=mysqli_query($con,"DELETE FROM sobe WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Sobe ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Sobeni silmek mumkun olmadi!</div>';}
	}
	else{'';}


if($_GET['f1']=='ASC')
{
	$f1= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY sobe ASC ';
}
elseif($_GET['f1']=='DESC')
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY sobe DESC ';
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


$sec=mysqli_query($con,"SELECT * FROM sobe WHERE user_id='".$_SESSION['user_id']."' ".$axtar.$order);

$say=mysqli_num_rows($sec);

echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> sobe var</div>';


$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Sobe '.$f1.'</th>
<th>Tarix '.$f2.'</th>
<th></th>
</thead>

<tbody>';


while($info=mysqli_fetch_array($sec))
{
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo '<td>'.$info['sobe'].'</td>';	
	echo '<td>'.$info['tarix'].'</td>';

	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="sobe" value="'.$info['sobe'].'">
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