<?php
  session_start();
  if (!isset($_SESSION['id'])){
    header("Location: ../");
  }
  if ((isset($_GET['data'])) and (isset($_GET['id'])) and (is_numeric($_GET['id']))){
    $data = $_GET['data'];
    $id = $_GET['id'];
    require_once('../mysql_connect.php');
  }
  else{
    header("Location: index.php?error=Invalid data");
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
      <?php
        switch ($data){
          case 'post':
            $query = "SELECT * FROM articole WHERE id = $id";
            $result = mysqli_query($link,$query);
            if ($articol = mysqli_fetch_assoc($result)){
              if (!isset($_GET['confirm'])){
                echo "<p class=\"message\">Doriti sa stergeti articolul {$articol['titlu']}? Operatia este ireversibila!</p>";
                echo "<a href=\"delete.php?data=$data&id=$id&confirm=ok\" class=\"button\">Sterge</a>";
                echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
              }
              else{
                $query = "DELETE FROM articole WHERE id = $id";
                mysqli_query($link,$query);
                if (mysqli_affected_rows($link) == 1){
                  echo "<p class=\"succes\">Articolul {$articol['titlu']} a fost sters cu succes!</p>";
                  echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
                }
                else{
                  echo "<p class=\"error\">Articolul nu a putut fi sters. Va rugam incercati mai tarziu!</p>";
                  echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
                }
              }
            }
            else{
              echo "<p class=\"error\">Articolul nu exista</p>";
            }
            break;
          case 'category':
            $query = "SELECT * FROM categorii WHERE id = $id";
            $result = mysqli_query($link,$query);
            if ($categorie = mysqli_fetch_assoc($result)){
              if (!isset($_GET['confirm'])){
                echo "<p class=\"message\">Doriti sa stergeti categoria {$categorie['nume']}? Operatia este ireversibila!</p>";
                echo "<a href=\"delete.php?data=$data&id=$id&confirm=ok\" class=\"button\">Sterge</a>";
                echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
              }
              else{
                $query = "DELETE FROM categorii WHERE id = $id";
                mysqli_query($link,$query);
                if (mysqli_affected_rows($link) == 1){
                  echo "<p class=\"succes\">Categoria {$categorie['nume']} a fost stearsa cu succes!</p>";
                  echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
                  $query = "UPDATE articole SET categorie = NULL WHERE categorie = $id";
                  mysqli_query($link,$query);
                }
                else{
                  echo "<p class=\"error\">Categoria nu a putut fi stearsa. Va rugam incercati mai tarziu!</p>";
                  echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
                }
              }
            }
            else{
              echo "<p class=\"error\">Categoria nu exista</p>";
            }
            break;

          default:
            echo "<p class=\"error\">Date invalide</p>";
            echo "<a href=\"index.php\" class=\"button\">Inapoi</a>";
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
