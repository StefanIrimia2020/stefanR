<?php
session_start();
if (!isset($_SESSION['id'])){
	header("Location: ../");
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
		<h1>Lista categorii</h1>
<?php
	// selectez toate categoriile si le afisez
	require_once('../mysql_connect.php');
	$query = "SELECT * FROM categorii ORDER BY nume ASC";
	$result = mysqli_query($link,$query);
	if (mysqli_num_rows($result) > 0){
?>
<table>
	<tr>
		<th scope="col">Categorie</th>
		<th scope="col">Data adaugare</th>
		<th scope="col">Editare</th>
		<th scope="col" class="last-cell">Stergere (*)</th>
	</tr>
<?php
	while ($categorie = mysqli_fetch_assoc($result)){
		echo "\t<tr>\n";
		echo "\t\t<td>{$categorie['nume']}</td>\n";
		echo "\t\t<td><span class=\"data\">" . date("d.m.Y", strtotime($categorie['data_adaugare'])) . "<span><br /><span class=\"ora\">" . date("H:i", strtotime($categorie['data_adaugare'])) . "<span></td>\n";
		echo "\t\t<td><a href=\"edit.php?data=category&id={$categorie['id']}\">Editeaza</a></td>\n";
		echo "\t\t<td class=\"last-cell\"><a href=\"delete.php?data=category&id={$categorie['id']}\">Sterge</td>\n";
		echo "\t</tr>\n";
	}
?>
</table>
<?php
	}
	else{
		echo "\t\t<p>Nu exista categorii</p>\n";
	}
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
