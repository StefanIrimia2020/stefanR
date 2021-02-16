<?php
  if (isset($_POST['trimis'])){
    require_once('mysql_connect.php');
    $nume = mysqli_real_escape_string($link, $_POST['nume']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $comentariu = mysqli_real_escape_string($link, $_POST['comentariu']);
    $post_id = $_GET['post'];
    if ((!empty($nume)) and (!empty($email)) and (!empty($comentariu))){
      if (filter_var($email,FILTER_VALIDATE_EMAIL)){
        $query = "INSERT INTO comentarii VALUES(NULL,$post_id,'$nume','$email','$comentariu',NOW())";
        mysqli_query($link,$query);
        if (mysqli_affected_rows($link) == 1){
          $succes = "Comentariul a fost adaugat";
        }
        else{
          $error = "Comentariul nu a putut fi adaugat";
        }
      }
      else{
        $error = "Email invalid";
      }
    }
    else{
      $error = "Toate campurile sunt obligatorii";
    }
  }
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
            <li><a href="login.php">Autentificare</a></li>
        </ul>
  </div>
  <div class="clear"></div>
  <div class="content">
    <div class="column full-width">
  	<?php
      if ((isset($_GET['post'])) and (is_numeric($_GET['post']))){
        $id = $_GET['post'];
        require_once('mysql_connect.php');
        $query = "SELECT * FROM articole WHERE id = $id";
        $result = mysqli_query($link,$query);
        if ($articol = mysqli_fetch_assoc($result)){
          echo "<h1>{$articol['titlu']}</h1>";
          echo "<div class=\"details\">Publicat in: <span class=\"data\">" . date("d.m.Y", strtotime($articol['data_adaugare'])) . "<span> <span class=\"ora\">" . date("H:i", strtotime($articol['data_adaugare'])) . "<span></div>";
          if ($articol['imagine']){
            echo "<div class=\"thumb poza\"><img src=\"{$articol['imagine']}\" /></div>";
          }
          echo "<div class=\"article\">" . nl2br($articol['articol']) . "</div>";
          echo "<div class=\"separator\"></div>";
          ?>
          <div class="comentarii">
            <h2>Comenteaza</h2>
            <?php
            // afisez erorile sau mesajul de succes
            if (isset($error)){
              echo "\t\t<p class=\"error\">$error</p>\n";
            }
            if (isset($succes)){
              echo "\t\t<p class=\"succes\">$succes</p>\n";
            }
            ?>
            <form action="post.php?post=<?php echo $id; ?>" method="POST">
              <p>
                <label for="nume">Nume</label>
                <input type="text" name="nume" id="nume" />
              </p>
              <p>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" />
              </p>
              <p>
                <label for="comentariu">Comentariu</label>
                <textarea name="comentariu" id="comentariu"></textarea>
              </p>
              <p class="shift">
                <input type="hidden" name="trimis" value="true" />
                <input type="submit" value="Salveaza" />
              </p>
            </form>
          </div>
          <div class="separator"></div>
          <?php
            $query = "SELECT * FROM comentarii WHERE post_id = $id ORDER BY data_adaugare DESC";
            $result = mysqli_query($link,$query);
            if (mysqli_num_rows($result) > 0){

              echo "<div class=\"comentarii\">";
              echo "<h2>Comentarii</h2>";
              while($comentariu = mysqli_fetch_assoc($result)){
                echo "<div class=\"comentariu\">";
                echo "<p class=\"autor\">{$comentariu['nume']}</p>";
                echo "<p class=\"details\">Publicat in: <span class=\"data\">" . date("d.m.Y", strtotime($comentariu['data_adaugare'])) . "<span> <span class=\"ora\">" . date("H:i", strtotime($comentariu['data_adaugare'])) . "<span></p>";
                echo "<div class=\"clear\"></div>";
                echo "<p class=\"text\">{$comentariu['comentariu']}</p>";
                echo "</div>";
              }
              echo "</div>";
            }
            else{
              echo "<p>Nu exista comentarii! Fii primul care comenteaza la aceast articol</p>";
            }
        }
        else{
          echo "<p class=\"error\">Articol inexistent</p>";
        }
      }
      else{
        echo "<p class=\"error\">Articol inexistent</p>";
      }
    ?>
    </div>
  </div>
  <div class="sidebar">
    <?php
      include "sidebar.php";
    ?>
  </div>
  <div class="clear"></div>
</div>
<div class="footer">
&copy;2020 - M BLOG
</div>
</body>
</html>
