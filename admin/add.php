<?php
session_start();
if (!isset($_SESSION['id'])){
	header("Location: ../");
}
// verific daca s-a trimis formularul pe baza campului hidden
if (isset($_POST['new'])){
	// preiau datele: campuri obligatorii titlu, articol, etichete
	$titlu = $_POST['titlu'];
	$articol = $_POST['articol'];
	$categorie = $_POST['categorie'];
	$tags = $_POST['tags'];

	if (empty($titlu)){
		$errors[] = "Completati titlul articolului";
	}

	if (empty($articol)){
		$errors[] = "Completati continutul articolului";
	}

	if (empty($tags)){
		$errors[] = "Completati etichetele";
	}

	if ($_FILES['poza']['error'] == 0){
		$type = $_FILES['poza']['type'];
		$size = $_FILES['poza']['size'];
		$ok = true;
		// verific dimensiune (max 1MB)
		if ($size > 1000000){
			$errors[] = "Fisierul este prea mare (max. 1MB)";
			$ok = false;
		}

		// verific tipul (imagine)
		if (strpos($type,'image/') !== 0){
			$errors[] = "Fisierul nu este o imagine";
			$ok = false;
		}

		// daca nu au fost erori pentru fisier, incarc pe server
		if ($ok){
			$target = 'uploads/' . mt_rand(1000,9999) . "_" . strtolower(basename($_FILES['poza']['name']));
			if (!move_uploaded_file($_FILES['poza']['tmp_name'], '../' . $target)){
				$errors[] = "Fisierul nu a putut fi incarcat";
			}
		}


	}
	else{
		$target = null;
	}

	if (!isset($errors)){
		require_once('../mysql_connect.php');
		// se insereaza articolul
		// inserarea se va modifica cand va exista imagine si utilizator autentificat
		if ($target == null){
			$query = "INSERT INTO articole VALUES(NULL,DEFAULT,'$titlu','$articol',$categorie,'$tags',NOW(),NULL)";
		}
		else{
			$query = "INSERT INTO articole VALUES(NULL,DEFAULT,'$titlu','$articol',$categorie,'$tags',NOW(),'$target')";
	


		}
		mysqli_query($link,$query);
		if (mysqli_affected_rows($link)==1){
			$succes = "Articol adaugat";
		}
		else{
			$errors[] = "Articolul nu a putut fi adaugat";
		}
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
		<h1>Adauga articol</h1>
<?php
// afisez erorile sau mesajul de succes
if (isset($errors)){
	echo "\t\t<div class=\"error\">\n";
	foreach($errors as $error){
		echo "\t\t\t<p>$error</p>\n";
	}
	echo "\t\t</div>\n";
}
if (isset($succes)){
	echo "\t\t<p class=\"succes\">$succes</p>\n";
}
?>
		<form action="add.php" method="POST" enctype="multipart/form-data">
			<p>
				<label for="titlu">Titlu*</label>
				<input type="text" name="titlu" id="titlu" />
			</p>
			<p>
				<label for="articol">Articol*</label>
				<textarea name="articol" id="articol"></textarea>
			</p>
			<p>
				<label for="categorie">Categorie</label>
				<select name="categorie" id="categorie">
					<option value="NULL">(Fara categorie)</option>
					<?php
						require_once('../mysql_connect.php');
						$query = "SELECT * FROM categorii ORDER BY nume ASC";
						$result = mysqli_query($link,$query);
						// daca exista categorii, le afisez ca optiuni
						// daca nu exista, nu fac nimic
						while($cat = mysqli_fetch_assoc($result)){
							echo "\t\t\t\t\t<option value=\"{$cat['id']}\">{$cat['nume']}</option>\n";
						}
					?>
				</select>
				<a href="add_category.php" title="Adauga categorie">Adauga categorie</a>
			</p>
			<p>
				<label for="tags">Etichete*</label>
				<input type="text" name="tags" id="tags" />
			</p>
			<p>
				<label for="poza">Imagine</label>
				<input type="file" name="poza" id="poza" />
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
	//Zona sidebar
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
