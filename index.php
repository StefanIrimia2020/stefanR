<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BLOG - M BLOG</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	background-color: #09F;
	background-image: url(images/background.png);
}
</style
></head>

<body background="images/background.png">
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
  <div class="content col3">
<?php
//Zona de continut principal
  require_once('mysql_connect.php');
  $query = "SELECT * FROM articole ORDER BY data_adaugare DESC";
  $result = mysqli_query($link,$query);
  $total = mysqli_num_rows($result);
  $nr = 3;
  if (isset($_GET['page'])){
    $page = $_GET['page'];
  }
  else{
    $page = 1;
  }
  $start = ($page-1)*$nr;
  $nr_pag = ceil($total/$nr);
  $query = "SELECT * FROM articole ORDER BY data_adaugare DESC LIMIT $start,$nr";
  $result = mysqli_query($link,$query);
  if (mysqli_num_rows($result) > 0){
    $i = 1;
    while($articol = mysqli_fetch_assoc($result)){
      if ($i%3 == 0){
        $class = " last";
      }
      else{
        $class = "";
      }
      echo "<div class=\"column$class\">\n";
      echo "<h1><a href=\"post.php?post={$articol['id']}\">{$articol['titlu']}</a></h1>\n";
      if ($articol['imagine']){
        echo "<p><a href=\"post.php?post={$articol['id']}\"><img src=\"{$articol['imagine']}\" class=\"thumb\" /></a></p>";
      }
      if (strlen($articol['articol'])>200){
        echo "<p>" . substr($articol['articol'],0,200) . "...</p>\n";
      }
      else{
        echo "<p>{$articol['articol']}</p>\n";
      }
      echo "<a href=\"post.php?post={$articol['id']}\" class=\"more\" title=\"Detalii\">Citeste mai mult...</a>\n";
      echo "</div>\n";
      if (($i%3 == 0) or ($i==mysqli_num_rows($result))){
        echo "<div class=\"separator\"></div>";
      }
      $i++;
    }
    if ($nr_pag > 1){
      echo "<div class=\"paginare\">";
      echo "<p>Navigheaza la pagina: ";
      $next = $page + 1;
      $prev = $page - 1;
      if ($prev >= 1){
        echo "<a href=\"index.php?page=$prev\">Anterioara</a> ";
      }
      for ($i = 1; $i <= $nr_pag; $i++){
        echo "<a href=\"index.php?page=$i\">$i</a> ";
      }
      if ($next <= $nr_pag){
        echo "<a href=\"index.php?page=$next\">Urmatoare</a> ";
      }
    echo "</div>";
    }
  }
  else{
    echo "\t\t<div class=\"column full-width\">Nu exista articole</div>";
  }
?>
  </div>
  <div class="sidebar">
  <?php
	//Zona sidebar
	include 'sidebar.php';

	?>
  </div>
  <div class="clear"></div>
</div>
<div class="footer">
&copy;2020 - M BLOG
</div>
</body>
</html>
