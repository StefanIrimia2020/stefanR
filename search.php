<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BLOG - M BLOG</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
	<div class="logo">
		<a href="index.php"><img src="images/logo.png" width="231" height="70" /></a>
    </div>
  <div class="nav">
        <ul>
            <li><a href="index.php">Prima pagina</a></li>
            <li><a href="search.php">Cauta</a></li>
            <?php
            if (isset($_SESSION['id'])){
            ?>
            <li><a href="admin/">Panou administrare</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php
            }
            else{
            ?>
            <li><a href="login.php">Autentificare</a></li>
            <?php
            }
            ?>
        </ul>
  </div>
  <div class="clear"></div>
  <div class="content">
    <div class="column full-width">
      <h1>Cautare</h1>
      <?php
        if ((isset($_GET['trimis'])) and (empty($_GET['cauta']))){
          echo "<p class=\"error\">Completati formularul</p>";
        }
      ?>
      <form action="search.php" method="GET">
        <p>
          <input type="text" name="cauta" placeholder="Cauta..." class="search" />
          <input type="submit" value="Cauta" />
          <input type="hidden" name="trimis" value="true" />
        </p>
        <?php
          require_once('mysql_connect.php');
          $query = "SELECT * FROM categorii ORDER BY nume ASC";
          $result = mysqli_query($link,$query);
          if (mysqli_num_rows($result) > 0){
            echo "<p>Cautare avansata:</p>";
            echo "<p><input type=\"checkbox\" name=\"categorie[]\" value=\"null\" /> (Fara categorie) ";
            while ($cat = mysqli_fetch_assoc($result)){
              echo "<input type=\"checkbox\" name=\"categorie[]\" value=\"{$cat['id']}\" /> {$cat['nume']} ";
            }
            echo "</p>";
          }
        ?>
      </form>
      <?php
        if ((isset($_GET['trimis'])) and (!empty($_GET['cauta']))){
          // afisez rezultatele daca s-a trimis formularul
          $cauta = mysqli_real_escape_string($link,$_GET['cauta']);
          $cuvinte = explode(' ',$cauta);
          // cautare simpla
          $query = "SELECT * FROM articole WHERE (";
          foreach($cuvinte as $cuvant){
            $query .= "tags LIKE '%$cuvant%' OR ";
          }
          $query = substr($query,0,-4);
          $query .= ")";
          // cautare complexa
          if (isset($_GET['categorie'])){
            // categoriile sunt un vector
            // adaug a doua conditie
            $query .= " AND (";
            foreach ($_GET['categorie'] as $cat){
              $query .= "categorie = $cat OR ";
            }
            $query = substr($query,0,-4);
            $query .= ")";
          }
          $query .= " ORDER BY data_adaugare DESC";
          $result = mysqli_query($link,$query);
          echo "<h2>Rezultate</h2>";
          if (mysqli_num_rows($result) > 0){
            while ($articol = mysqli_fetch_assoc($result)){
              echo "<div class=\"result\">\n";
              echo "<h3><a href=\"post.php?post={$articol['id']}\">{$articol['titlu']}</a></h3>";
              echo "<p>Publicat in: " . date("d.m.Y",strtotime($articol['data_adaugare'])) . ", " . date("H:i",strtotime($articol['data_adaugare'])) . "</p>";
              echo "</div>";
            }
          }
          else{
            echo "<p>Nu exista rezultate</p>";
          }

        }
      ?>
    </div>
  </div>
  <div class="sidebar">


  </div>
  <div class="clear"></div>
</div>
<div class="footer">
&copy;2020 - M BLOG
</div>
</body>
</html>
