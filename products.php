<?php

include'header.php';
echo '<div class="container">';

error_reporting(0);
$tarix=date('Y-m-d H:i:s');

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" AND (brend LIKE '%".$_POST['sorgu']."%' or adi LIKE '%".$_POST['sorgu']."%' OR al LIKE '%".$_POST['sorgu']."%' OR sat LIKE '%".$_POST['sorgu']."%' OR miqdar LIKE '%".$_POST['sorgu']."%')";
}

if(isset($_POST['d']))
{
	$brand_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['brand_id']))));
	$adi=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['adi']))));
	$al=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['al']))));
	$sat=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sat']))));
	$miqdar=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['miqdar']))));

	if(!empty($adi) && !empty($al) && !empty($sat) && !empty($miqdar))
	{
		$yoxla=mysqli_query($con,"SELECT * FROM products WHERE user_id='".$_SESSION['user_id']."' ");
		{include 'upload.php';
		
				if(!isset($error))
				{
					$daxilet=mysqli_query($con,"INSERT INTO products (adi,al,sat,miqdar,brand_id,foto,tarix,user_id) 
					VALUES ('".$adi."','".$al."','".$sat."','".$miqdar."','".$brand_id."','".$unvan."','".$tarix."','".$_SESSION['user_id']."')");
		
				if($daxilet==true)
					{echo '<div class="alert alert-success" role="alert">Mehsul ugurla bazaya yerlesdirildi!</div>';}
				else
					{echo '<div class="alert alert-danger" role="alert">Mehsul bazaya daxil edilmedi!</div>';}
				}}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa mehsulu duzgun daxil edin!</div>';}
}

if(!isset($_POST['edit']))
{
	echo '
	<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
	Brend:<br>
	<select name="brand_id" class="form-control">
	<option value="">Brend secin</option>';

	$sec=mysqli_query($con,"SELECT * FROM brands WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY brend ASC");

	while($info=mysqli_fetch_array($sec))
	{
		echo'<option value="'.$info['id'].'">'.$info['brend'].'</option>';
	}
	echo'
	</select>
	Mehsulun adi:<br>
	<input type="text" class="form-control" name="adi">
	Mehsulun fotosu:<br>
	<input type="file" class="form-control" name="foto">
	Alish:<br>
	<input type="text" class="form-control" name="al">
	Satish:<br>
	<input type="text" class="form-control" name="sat">
	Miqdar:<br>
	<input type="text" class="form-control" name="miqdar">
	<button type="submit" class="btn btn-primary btn-sm"name="d">OK</button>
	</form> </div>';
}

if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM products WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);
echo'
	<div class="alert alert-primary role="alert">
<form method="post" enctype="multipart/form-data">	
	Brend:<br>
	<select name="brand_id" class="form-control">';

	$bsec=mysqli_query($con,"SELECT * FROM brands WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY brend ASC");
	while($binfo=mysqli_fetch_array($bsec))
	{
		if($info['brand_id']==$binfo['id'])
			{echo '<option selected value="'.$binfo['id'].'">'.$binfo['brend'].'</option>';}
		else
			{echo '<option value="'.$binfo['id'].'">'.$binfo['brend'].'</option>';}
	}

	echo '
	</select>
	Ad:<br>
	<input type="text" class="form-control" name="adi" value="'.$info['adi'].'">
	Mehsulun fotosu:<br>
	<img style="width:75px; height:60px;" src="'.$info['foto'].'">
	<input type="file" class="form-control" name="foto">
	Alish:<br>
	<input type="text" class="form-control" name="al" value="'.$info['al'].'">
	Satish:<br>
	<input type="text" class="form-control" name="sat" value="'.$info['sat'].'">
	Miqdar:<br>
	<input type="text" class="form-control" name="miqdar" value="'.$info['miqdar'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="foto" value="'.$info['foto'].'">		
<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
</form>
</div>';
}

if(isset($_POST['update']))
{
	$brand_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['brand_id']))));
	$adi=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['adi']))));
	$al=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['al']))));
	$sat=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['sat']))));
	$miqdar=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['miqdar']))));

	if(!empty($adi) && !empty($al) && !empty($sat) && !empty($miqdar))
	{
		if($_FILES['foto']['size']<1024)
		{$unvan=$_POST['cfoto'];}
		else	
		{include 'upload.php';}

		if(!isset($error))
		{
			$yenile=mysqli_query($con,"UPDATE products SET
							adi='".$adi."',
							al='".$al."',
							sat='".$sat."',
							miqdar='".$miqdar."',
							brand_id='".$brand_id."',
							foto='".$unvan."'

							WHERE id='".$_POST['id']."'");
		if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Mehsulunuz ugurla yenilendi!</div>';}
		else
			{echo '<div class="alert alert-danger" role="alert">Mehsulunuz yenilenmedi!</div>';}
		}
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa mehsulu tam daxil edin!</div>';}
}

if(isset($_POST['sil']))
{
	$yoxla=mysqli_query($con,"SELECT * FROM orders WHERE product_id='".$_POST['id']."'");
	if(mysqli_num_rows($yoxla)==0)
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
	else {echo '<div class="alert alert-danger" role="alert">Bu mehsuldan sifaris verilib!</div>';}
}

if(isset($_POST['d1']))
	{$sil=mysqli_query($con,"DELETE FROM products WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Mehsulunuz ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Mehsulunuzu silmek mumkun olmadi!</div>';}
	}





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
		$order=' ORDER BY adi ASC ';
}
elseif($_GET['f2']=='DESC')
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY adi DESC ';
}
else
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f3']=='ASC')
{
	$f3= '<a href="?f3=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY al ASC ';
}
elseif($_GET['f3']=='DESC')
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY al DESC ';
}
else
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f4']=='ASC')
{
	$f4= '<a href="?f4=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY sat ASC ';
}
elseif($_GET['f4']=='DESC')
{
	$f4= '<a href="?f4=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY sat DESC ';
}
else
{
	$f4= '<a href="?f4=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f5']=='ASC')
{
	$f5= '<a href="?f5=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY miqdar ASC ';
}
elseif($_GET['f5']=='DESC')
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY miqdar DESC ';
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

$sec = mysqli_query($con,"SELECT 
                        brands.brend,
                        products.id,
                        products.adi,
                        products.al,
                        products.sat,
                        products.miqdar,
                        products.foto,
                        products.tarix
                        FROM brands, products 
                        WHERE brands.id = products.brand_id AND
                        products.user_id='". $_SESSION['user_id'] ."'
                        ".$axtar.$order);


$say=mysqli_num_rows($sec);

echo'<div class="alert alert-primary" role="alert">Bazada <b>'.$say.'</b> mehsul var<br></div>';


$i=0;

echo'<table class="table" id="cedvel">
 <thead class="table-dark">
<th>#</th>
<th>Foto</th>
<th>Brend '.$f1.'</th>
<th>Mehsulun adi '.$f2.'</th>
<th>Alish '.$f3.'</th>
<th>Satish '.$f4.'</th>
<th>Miqdar '.$f5.'</th>
<th>Qazanc</th>
<th>Tarix '.$f6.'</th>
<th></th>
</thead>

<tbody>';


while($info=mysqli_fetch_array($sec))
{
	$qazanc=($info['sat']-$info['al'])*$info['miqdar'];
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo'<td><img style="width:75px; height:60px;" src="'.$info['foto'].'"></td>';	
	echo '<td>'.$info['brend'].'</td>';
	echo '<td>'.$info['adi'].'</td>';
	echo '<td>'.$info['al'].'</td>';
	echo '<td>'.$info['sat'].'</td>';
	echo '<td>'.$info['miqdar'].'</td>';
	echo '<td>'.$qazanc.'</td>';
	echo '<td>'.$info['tarix'].'</td>';

	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="adi" value="'.$info['adi'].'">
	<input type="hidden" name="al" value="'.$info['al'].'">
	<input type="hidden" name="sat" value="'.$info['sat'].'">
	<input type="hidden" name="miqdar" value="'.$info['miqdar'].'">
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