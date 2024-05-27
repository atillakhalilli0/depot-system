<?php

include 'header.php';
echo'
<div class="container">';



echo'
<div class="alert alert-primary" role="alert">
	<form method="post" enctype="multipart/form-data">
		Foto:<br>
		<input type="file" name="foto" class="form-control" value="'.$_SESSION['foto'].'">
		Ad:<br>
		<input type="text" name="ad" class="form-control" value="'.$_SESSION['ad'].'">
		Soyad:<br>
		<input type="text" name="soyad" class="form-control" value="'.$_SESSION['soyad'].'">
		Telefon:<br>
		<input type="text" name="telefon" class="form-control" value="'.$_SESSION['telefon'].'">
		Email:<br>
		<input type="text" name="email" class="form-control" value="'.$_SESSION['email'].'">
		Yeni parol:<br>
		<b>Eger parolunuzu deyismek istemirsizse bu xanani doldurmayin!</b>
		<input type="password" name="parol" class="form-control">
		Movcud parol:<br>
		<input type="password" name="mparol" class="form-control">
		<button type="submit" name="d" class="btn btn-success btn-sm">Deyisikleri yenile</button>
	</form>
</div>';

?>