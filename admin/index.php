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
    <h1>Lista articole</h1>
<?php
  // selectez toate categoriile si le afisez
  require_once('../mysql_connect.php');
  $query = "SELECT * FROM articole ORDER BY data_adaugare DESC";
  $result = mysqli_query($link,$query);
  if (mysqli_num_rows($result) > 0){
?>
<table>
  <tr>
    <th scope="col">Articol</th>
    <th scope="col">Data adaugare</th>
    <th scope="col">Editare</th>
    <th scope="col" class="last-cell">Stergere (*)</th>
  </tr>
<?php
  while ($articol = mysqli_fetch_assoc($result)){
    echo "\t<tr>\n";
    echo "\t\t<td>";
    if (strlen($articol['titlu'])>35){
      echo "<h2><a href=\"../post.php?post={$articol['id']}\" target=\"_blank\">" . substr($articol['titlu'],0,35) . "...</a></h2>";
    }
    else{
      echo "<h2><a href=\"../post.php?post={$articol['id']}\" target=\"_blank\">{$articol['titlu']}</a></h2>";
    }
    if (strlen($articol['articol'])>45){
      echo "<p>" . substr($articol['articol'],0,45) . "...</p>";
    }
    else{
      echo "<p>{$articol['articol']}</p>";
    }
    echo "</td>\n";
    echo "\t\t<td><span class=\"data\">" . date("d.m.Y", strtotime($articol['data_adaugare'])) . "<span><br /><span class=\"ora\">" . date("H:i", strtotime($articol['data_adaugare'])) . "<span></td>\n";
    echo "\t\t<td><a href=\"edit.php?data=post&id={$articol['id']}\">Editeaza</a></td>\n";
    echo "\t\t<td class=\"last-cell\"><a href=\"delete.php?data=post&id={$articol['id']}\">Sterge</td>\n";
    echo "\t</tr>\n";
  }
?>
</table>
<?php
  }
  else{
    echo "\t\t<p>Nu exista articole</p>\n";
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
