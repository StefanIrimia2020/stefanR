<?php
session_start();
if (!isset($_SESSION['id'])){
	header("Location: ../");
}
// verific daca a fost trimis formularul, pe baza campului hidden
if (isset($_POST['new'])){
	$categorie = $_POST['titlu'];
	// verific ca a fost completata categoria
	if (!empty($categorie)){
		// daca nu este goala, verific daca nu exista deja in BD
		require_once('../mysql_connect.php');
		$query = "SELECT * FROM categorii WHERE nume = '$categorie'";
		$result = mysqli_query($link,$query) or die(mysqli_error($link));
		// daca am cel putin 1 rezultat, categoria exista deja si nu trebuie adaugata
		if (mysqli_num_rows($result) > 0){
			$error = "Categoria exista deja";
		}
		else{
			// daca nu exista, este inserata
			$query = "INSERT INTO categorii VALUES(NULL,'$categorie',NOW())";
			mysqli_query($link,$query);
			// verific daca a fost inserata
			if (mysqli_affected_rows($link) == 1){
				$succes = "Categoria a fost adaugata";
			}
			else{
				$error = "Categoria nu a putut fi adaugata";
			}
		}
	}
	else{
		$error = "Completati denumirea categoriei";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BLOG - M BLOG</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
	<div class="logo">
		<a href="index.php"><img src="../images/logo.png" width="231" height="70" /></a>
    </div>
  <div class="nav">
        <ul>
            <li><a href="index.php">Lista articole</a></li>
			<li><a href="categories.php">Lista categorii</a></li>
            <li><a href="add_category.php">Adauga categorie</a></li>
			<li><a href="add.php">Adauga articol</a></li>
        </ul>
  </div>
  <div class="clear"></div>
  <div class="content">
  	<div class="column full-width">
		<h1>Adauga categorie</h1>
<?php
// afisez erorile sau mesajul de succes
if (isset($error)){
	echo "\t\t<p class=\"error\">$error</p>\n";
}
if (isset($succes)){
	echo "\t\t<p class=\"succes\">$succes</p>\n";
}
?>
		<form action="add_category.php" method="POST">
			<p>
				<label for="titlu">Titlu*</label>
				<input type="text" name="titlu" id="titlu" />
			</p>
			<p>
				<input type="hidden" name="new" value="true" />
				<input type="submit" value="Adauga" class="shift" />
			</p>
		</form>

	<?php
	//Zona de continut principal

	?>
	</div>
  </div>
  <div class="sidebar">
  	<?php
	// sidebar-ul este comun tuturor fisierelor, el va fi creat intr-un fisier separat si inclus in toate paginile
	include "admin_sidebar.php";
	?>
  </div>
  <div class="clear"></div>
</div>
<div class="footer">
&copy;2020 - M BLOG
</div>
</body>
</html>
