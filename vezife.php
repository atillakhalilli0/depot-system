<?php
include'header.php';

error_reporting(0);

echo'<div class="container">';
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" AND (vezife LIKE '%".$_POST['sorgu']."%' OR tarix LIKE '%".$_POST['sorgu']."%')";
}
else
{$axtar="";}



if(isset($_POST['d']))
{	
	$sobe_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sobe_id']))));
	$vezife=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['vezife']))));

	if(!empty($vezife))
	{	
		$yoxla=mysqli_query($con,"SELECT * FROM vezife WHERE vezife='".$vezife."' AND user_id='".$_SESSION['user_id']."' ");

		if(mysqli_num_rows($yoxla)==0)
		{
			$daxilet=mysqli_query($con,"INSERT INTO vezife (sobe_id,vezife,tarix,user_id) 
			VALUES ('".$sobe_id."','".$vezife."','".$tarix."','".$_SESSION['user_id']."')");

			if($daxilet==true)
				{echo '<div class="alert alert-success" role="alert">Vezife ugurla bazaya yerlesdirildi!</div>';}
			else
				{echo '<div class="alert alert-danger" role="alert">Vezife bazaya daxil edilmedi!</div>';}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Bu vezife artiq bazada movcuddur!</div>';}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa vezife melumatlarini duzgun daxil edin!</div>';}
}


if(!isset($_POST['edit']))
{
	echo '
	<div class="alert alert-primary" role="alert">
	<form method="post">
	Sobe:<br>
	<select name="sobe_id" class="form-control">
	<option value="">Sobe secin</option>';

	$sec=mysqli_query($con,"SELECT * FROM sobe WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY sobe ASC");

	while($info=mysqli_fetch_array($sec))
	{
		echo'<option value="'.$info['id'].'">'.$info['sobe'].'</option>';
	}

		echo'
	</select>
	Vezife:<br>
	<input type="text" class="form-control" name="vezife">
	<button type="submit" class="btn btn-primary btn-sm"name="d">OK</button>
	</form> </div>';
}



if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM vezife WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);
echo'
	<div class="alert alert-primary role="alert">
<form method="post">	
	Brend:<br>
	<select name="sobe_id" class="form-control">';

	$ssec=mysqli_query($con,"SELECT * FROM sobe WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY sobe ASC");
	while($sinfo=mysqli_fetch_array($ssec))
	{
		if($info['sobe_id']==$sinfo['id'])
			{echo '<option selected value="'.$sinfo['id'].'">'.$sinfo['sobe'].'</option>';}
		else
			{echo '<option value="'.$sinfo['id'].'">'.$sinfo['sobe'].'</option>';}
	}
	echo '
	</select>
	Vezife:<br>
	<input type="text" class="form-control" name="vezife" value="'.$info['vezife'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">		
<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
</form>
</div>';
}


if(isset($_POST['update']))
{
	$sobe_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sobe_id']))));
	$vezife=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['vezife']))));

	if(!empty($vezife))
	{
			$yenile=mysqli_query($con,"UPDATE vezife SET
							sobe_id='".$sobe_id."',
							vezife='".$vezife."'

							WHERE id='".$_POST['id']."'");
		if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Vezife ugurla yenilendi!</div>';}
		else
			{echo '<div class="alert alert-danger" role="alert">Vezife yenilenmedi!</div>';}
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa vezifeni duzgun redakte edin!</div>';}
}

if(isset($_POST['sil']))
{
	$yoxla=mysqli_query($con,"SELECT * FROM staff WHERE vezife_id='".$_POST['id']."'");
	if(mysqli_num_rows($yoxla)==0)
	{
	echo '
	<div class="alert alert-warning role="alert">
	Bu vezifeni silmek isteyirsiniz?
	<form method="post">
	<input type="hidden" name="id" value="'.$_POST['id'].'">
	<button type="submit" class="btn btn-warning btn-sm"name="d1">HE</button>
	<button type="submit" class="btn btn-warning btn-sm"name="d2">YOX</button>
	</form> </div>';
	}
	else {echo '<div class="alert alert-danger" role="alert">Bu vezifede isci var!</div>';}
}

if(isset($_POST['d1']))
	{$sil=mysqli_query($con,"DELETE FROM vezife WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Vezife ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Vezife silinmedi!</div>';}
	}



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
		$order=' ORDER BY vezife ASC ';
}
elseif($_GET['f2']=='DESC')
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY vezife DESC ';
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
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY tarix DESC ';
}
else
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if(!isset($_GET['f1']) && !isset($_GET['f2']) && !isset($_GET['f3']))
{$order=' ORDER BY id DESC ';}




$sec=mysqli_query($con,"SELECT 
						sobe.sobe,
						vezife.id,
						vezife.vezife,
						Vezife.tarix
						FROM sobe,vezife 
						WHERE sobe.id=vezife.sobe_id
						AND	vezife.user_id='" . $_SESSION['user_id'] . "'".$axtar.$order);

$say=mysqli_num_rows($sec);

echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> vezife var<br></div>';



$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Sobe '.$f1.'</th>
<th>Vezife '.$f2.'</th>
<th>Tarix '.$f3.'</th>
<th></th>
</thead>

<tbody>';


while($info=mysqli_fetch_array($sec))
{
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo '<td>'.$info['sobe'].'</td>';
	echo '<td>'.$info['vezife'].'</td>';
	echo '<td>'.$info['tarix'].'</td>';

	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="vezife" value="'.$info['vezife'].'">
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