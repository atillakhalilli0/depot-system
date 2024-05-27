<?php
include'header.php';
$tarix=date('Y-m-d H:i:s');
error_reporting(0);


echo '<div class="container">';



if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$axtar=" AND (client_id LIKE '%".$_POST['sorgu']."%' or brand_id LIKE '%".$_POST['sorgu']."%' OR product_id LIKE '%".$_POST['sorgu']."%' OR miqdar LIKE '%".$_POST['sorgu']."%')";
}

if(isset($_POST['d']))
{	
	$client_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['client_id']))));
	$product_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['product_id']))));
	$miqdar=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['miqdar']))));

	if(!empty($client_id) && !empty($product_id) && !empty($miqdar))
	{
		$yoxla=mysqli_query($con,"SELECT * FROM orders WHERE user_id='".$_SESSION['user_id']."' ");
		{
			$daxilet=mysqli_query($con,"INSERT INTO orders(client_id,product_id,miqdar,tarix,user_id) 
					VALUES ('".$client_id."','".$product_id."','".$miqdar."','".$tarix."','".$_SESSION['user_id']."')");
		
				if($daxilet==true)
					{echo '<div class="alert alert-success" role="alert">Sifarisiniz ugurla alindi!</div>';}
				else
					{echo '<div class="alert alert-danger" role="alert">Sifarisiniz alinmadi!</div>';}
		}
	}
	else{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa sifarisi duzgun daxil edin!</div>';}
}

if(!isset($_POST['edit']))
{
	echo '
	<div class="alert alert-primary" role="alert">
	<form method="post">
	Musteri:<br>
	<select name="client_id" class="form-control">
	<option value="">Musteri secin</option>';

	$sec=mysqli_query($con,"SELECT * FROM clients WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY ad ASC");

	while($info=mysqli_fetch_array($sec))
	{
		echo'<option value="'.$info['id'].'">'.$info['ad'].' '.$info['soyad'].'</option>';
	}
	echo '
	</select>

	Mehsul:<br>
	<select name="product_id" class="form-control">
		<option value="">Mehsul secin</option>';

	$sec=mysqli_query($con,"SELECT 
						brands.brend,
						products.id,
						products.adi,
						products.miqdar
						FROM brands,products 
						WHERE brands.id=products.brand_id AND
						products.user_id='". $_SESSION['user_id'] ."' ORDER BY products.adi ASC");

	while($info=mysqli_fetch_array($sec))
	{
		echo'<option value="'.$info['id'].'">'.$info['brend'].' - '.$info['adi'].' ['.$info['miqdar'].']</option>';
	}
	echo'
	</select>

	Miqdar:<br>
	<input type="text" class="form-control" name="miqdar">
	<button type="submit" class="btn btn-primary btn-sm"name="d">Daxil et</button>

</form></div>';}

if(isset($_POST['edit']))
{
	$sec=mysqli_query($con,"SELECT * FROM orders WHERE id='".$_POST['id']."' ");
	$info=mysqli_fetch_array($sec);

	echo'
	<div class="alert alert-primary" role="alert">
	<form method="post">
	Musteri:<br>
	<select name="client_id" class="form-control">';

	$csec=mysqli_query($con,"SELECT * FROM clients WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY ad ASC");
	while($cinfo=mysqli_fetch_array($csec))
	{
		if($info['client_id']==$cinfo['id'])
			{echo '<option selected value="'.$cinfo['id'].'">'.$cinfo['ad'].' '.$cinfo['soyad'].'</option>';}
		else{echo '<option value="'.$cinfo['id'].'">'.$cinfo['ad'].' '.$cinfo['soyad'].'</option>';}
	}

	echo'
	</select>

	Mehsul:<br>
	<select name="product_id" class="form-control">';

	$psec=mysqli_query($con,"SELECT * FROM products WHERE user_id='". $_SESSION['user_id'] ."' ORDER BY adi ASC");
	while($pinfo=mysqli_fetch_array($psec))
	{
		if($info['product_id']==$pinfo['id'])
			{echo '<option selected value="'.$pinfo['id'].'">'.$pinfo['adi'].'</option>';}
		else{echo '<option value="'.$pinfo['id'].'">'.$pinfo['adi'].'</option>';}
	}

echo'
	</select>

Miqdar:<br>
	<input type="text" class="form-control" name="miqdar" value="'.$info['miqdar'].'">
	<input type="hidden" name="id" value="'.$info['id'].'">
<button type="submit" class="btn btn-primary btn-sm"name="update">Yenile</button>
</form></div>';
}

if(isset($_POST['update']))
{
	$client_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['client_id']))));
	$product_id=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['product_id']))));
	$miqdar=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['miqdar']))));
	
	if(!empty($_POST['miqdar']))
		{$yenile=mysqli_query($con,"UPDATE orders SET
							client_id='".$client_id."',
							product_id='".$product_id."',
							miqdar='".$miqdar."'

							WHERE id='".$_POST['id']."'");
	if($yenile==true)
			{echo '<div class="alert alert-success" role="alert">Sifarisiniz ugurla yenilendi!</div>';}
		else
			{echo '<div class="alert alert-danger" role="alert">Sifarisiniz yenilenmedi!</div>';}
	}
	else
		{echo '<div class="alert alert-warning" role="alert">Zehmet olmasa sifarisinizi duzgun daxil edin!</div>';}
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
	{$sil=mysqli_query($con,"DELETE FROM orders WHERE id='".$_POST['id']."' ");

	if($sil==true)
		{echo'<div class="alert alert-success" role="alert">Sifarisiniz ughurla silindi!</div>';}
	else
		{echo'<div class="alert alert-danger" role="alert">Sifarisinizi silmek mumkun olmadi!</div>';}
	}
	

if($_GET['f1']=='ASC')
{
	$f1= '<a href="?f1=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY ad,soyad ASC ';
}
elseif($_GET['f1']=='DESC')
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY ad,soyad DESC ';
}
else
{
	$f1= '<a href="?f1=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f2']=='ASC')
{
	$f2= '<a href="?f2=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY brend ASC ';
}
elseif($_GET['f2']=='DESC')
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY brend DESC ';
}
else
{
	$f2= '<a href="?f2=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}


if($_GET['f3']=='ASC')
{
	$f3= '<a href="?f3=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY mehsul ASC ';
}
elseif($_GET['f3']=='DESC')
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY mehsul DESC ';
}
else
{
	$f3= '<a href="?f3=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f4']=='ASC')
{
	$f4= '<a href="?f4=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY sifarish ASC ';
}
elseif($_GET['f4']=='DESC')
{
	$f4= '<a href="?f4=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY sifarish DESC ';
}
else
{
	$f4= '<a href="?f4=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if($_GET['f5']=='ASC')
{
	$f5= '<a href="?f5=DESC#cedvel"><i class="bi bi-sort-alpha-down-alt"></i></a>';
		$order=' ORDER BY tarix ASC ';
}
elseif($_GET['f5']=='DESC')
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
	$order=' ORDER BY tarix DESC ';
}
else
{
	$f5= '<a href="?f5=ASC#cedvel"><i class="bi bi-sort-alpha-down"></i></a>';
}

if(!isset($_GET['f1']) && !isset($_GET['f2']) && !isset($_GET['f3']) && !isset($_GET['f4']) && !isset($_GET['f5']))
{$order=' ORDER BY id DESC ';}


if(isset($_POST['tesdiq']))
{
	if($_POST['sifarish']<=$_POST['stok'])
	{
		$product_update=mysqli_query($con,"UPDATE products SET miqdar=miqdar-".$_POST['sifarish']." WHERE id='".$_POST['product_id']."'");
		if($product_update==true)
		{
			$order_update=mysqli_query($con,"UPDATE orders SET tesdiq=1 WHERE id='".$_POST['id']."' ");
			if($order_update==true)
			{
				echo '<div class="alert alert-success" role="alert">Sifaris ugurla tesdiq edildi!</div>';
			}
			else {echo '<div class="alert alert-warning" role="alert">Sifarisi tesdiq etmek mumkun olmadi!</div>';
			$product_update=mysqli_query($con,"UPDATE products SET miqdar=miqdar+".$_POST['sifarish']." WHERE id='".$_POST['product_id']."'");}
		}
	}
	else {echo '<div class="alert alert-warning" role="alert">Sifarisi tesdiq etmek ucun kifayet qeder mehsul yoxdur!</div>';}
}

if(isset($_POST['legv']))
{
	if($_POST['sifarish']<=$_POST['stok'])
	{
		$product_cancel=mysqli_query($con,"UPDATE products SET miqdar=miqdar+".$_POST['sifarish']." WHERE id='".$_POST['product_id']."'");
		if($product_cancel==true)
		{
			$order_cancel=mysqli_query($con,"UPDATE orders SET tesdiq=0 WHERE id='".$_POST['id']."' ");
			if($order_cancel==true)
			{
				echo '<div class="alert alert-success" role="alert">Sifaris ugurla legv edildi!</div>';
			}
			else {echo '<div class="alert alert-warning" role="alert">Sifarisi legv etmek mumkun olmadi!</div>';
			$product_cancel=mysqli_query($con,"UPDATE products SET miqdar=miqdar-".$_POST['sifarish']." WHERE id='".$_POST['product_id']."'");}
		}
	}
}

$sec = mysqli_query($con, "SELECT
			brands.brend,
			products.adi AS mehsul,
			products.al,
			products.sat,
			products.miqdar AS stok,
			clients.ad AS ad,
			clients.soyad,
			orders.miqdar AS sifarish,
			orders.tarix,
			orders.id,
			orders.tesdiq,
			orders.product_id
			FROM brands, products, clients, orders
			WHERE 
			brands.id = products.brand_id AND
			products.id = orders.product_id AND
			clients.id = orders.client_id AND
			orders.user_id='" . $_SESSION['user_id'] . "'
			" . $axtar . $order);


$say=mysqli_num_rows($sec);

$csec=mysqli_query($con,"SELECT * FROM clients WHERE user_id='".$_SESSION['user_id']."'");
$csay=mysqli_num_rows($csec);

$bsec=mysqli_query($con,"SELECT * FROM brands WHERE user_id='".$_SESSION['user_id']."'");
$bsay=mysqli_num_rows($bsec);

$psec=mysqli_query($con,"SELECT * FROM products WHERE user_id='".$_SESSION['user_id']."'");
$psay=mysqli_num_rows($psec);

$tmehsul=0;
$talish=0;
$tsatish=0;


while($pinfo=mysqli_fetch_array($psec))
{
	$tmehsul=$tmehsul+$pinfo['miqdar'];
	$talish=$talish+($pinfo['al']*$pinfo['miqdar']);
	$tsatish=$tsatish+($pinfo['sat']*$pinfo['miqdar']);
	$qazanc=(($pinfo['sat']-$pinfo['al'])*$pinfo['miqdar']) + $qazanc;
;}




echo'<div class="alert alert-primary" role="alert">
<center>
		   <b>Sifaris: </b> '.$say.' |
		   <b>Musteri: </b> '.$csay.' |
		   <b>Brend: </b> '.$bsay.' |
		   <b>Mehsul: </b> '.$psay.' |
		   <b>Alish: </b> '.$talish.' |
		   <b>Satish: </b> '.$tsatish.'|
		   <b>Qazanc: </b> '.$qazanc.' 
		   <br>
		   </center>
		   </div>';



$i=0;

echo'<table class="table">
 <thead class="table-dark">
<th>#</th>
<th>Musteri '.$f1.'</th>
<th>Brend '.$f2.'</th>
<th>Mehsul '.$f3.'</th>
<th>Miqdar '.$f4.'</th>
<th>Qazanc</th>
<th>Stok</th>
<th>Tarix '.$f5.'</th>
<th></th>
</thead>

<tbody>';

while($info=mysqli_fetch_array($sec))
{

	$qazanc=($info['sat']-$info['al']) * $info['sifarish'];
	$i++;
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo '<td>'.$info['ad'].' '.$info['soyad'].'</td>';
	echo '<td>'.$info['brend'].'</td>';
	echo '<td>'.$info['mehsul'].'</td>';
	echo '<td>'.$info['sifarish'].'</td>';
	echo '<td>'.$qazanc.'</td>';
	echo '<td>'.$info['stok'].'</td>';
	echo '<td>'.$info['tarix'].'</td>';
	
	echo '
	<td>
	<form method="post">
	<input type="hidden" name="id" value="'.$info['id'].'">
	<input type="hidden" name="product_id" value="'.$info['product_id'].'">
	<input type="hidden" name="stok" value="'.$info['stok'].'">
	<input type="hidden" name="sifarish" value="'.$info['sifarish'].'">';


	if($info['tesdiq']==0)
	{
		echo '
		<button type="submit" name="edit" class="btn btn-outline-success btn-sm" title="Redakte et"><i class="bi bi-pencil-square"></i></button>
		<button type="submit" name="sil" class="btn btn-outline-danger btn-sm" title="Sil"><i class="bi bi-trash-fill"></i></button>
		<button type="submit" name="tesdiq" class="btn btn-outline-primary btn-sm" title="Tesdiq et"><i class="bi bi-bag-check"></i></button>';
	}
	else
	{echo '<button type="submit" name="legv" class="btn btn-outline-danger btn-sm" title="Legv et"><i class="bi bi-bag-x"></i></button>';}

echo'</form></td>';


echo'</tr>';

}
echo '</tbody>
	</table>';

?>

</div>