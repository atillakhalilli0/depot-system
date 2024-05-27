<?php
//images/foto.jpeg

$unvan = 'images/'.basename($_FILES['foto']['name']);
//JPEG Jpeg jpeg
$tip = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
if($tip!='jpeg' && $tip!='jpg' && $tip!='png' && $tip!='gif')
{$error = '<div class="alert alert-warning" role="alert">Yalnız <b>jpeg,jpg,png,gif</b> fayl formatlarına icazə verilir</div>';}

if($_FILES['foto']['size']>10485760)
{$error = '<div class="alert alert-warning" role="alert">Yalnız <b>10 Mb</b> fayl həcminə icazə verilir</div>';}
if(isset($error))
{echo $error;}
else
{
 $unvan = 'images/'.time().'.'.$tip;
 move_uploaded_file($_FILES['foto']['tmp_name'], $unvan);
}
?>