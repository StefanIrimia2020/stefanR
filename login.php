<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BLOG - M BLOG</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>


<?php
  session_start();
  if (isset($_POST['login'])){
    require_once('mysql_connect.php');
    $email = mysqli_real_escape_string($link,$_POST['email']);
    $parola = $_POST['parola'];
    // Pasul 1. Verific email-ul
    if ((empty($email)) or (!filter_var($email,FILTER_VALIDATE_EMAIL))){
      $errors[] = "Email invalid";
    }
    else{
      $query = "SELECT * FROM utilizatori WHERE email = '$email'";
      $result = mysqli_query($link,$query);
      if ($usr = mysqli_fetch_assoc($result)){
        $hash = $usr['parola'];
        if (password_verify($parola,$hash)){
          $succes = "Bine ai venit {$usr['username']}!";
          $_SESSION['id'] = $usr['id'];
          $_SESSION['username'] = $usr['username'];
        }
        else{
          $errors[] = "Parola incorecta!";
        }
      }
      else{
        $errors[] = "Adresa de email nu exista!";
      }
    }
  }
?>


<body>
<div class="wrapper">
	<div class="logo">
		<a href="index.php"><img src="images/logo.png" width="231" height="70" /></a>
    </div>
  <div class="nav">
        <ul id=>
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
      <h1>Autentificare</h1>
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
      <form action="login.php" method="POST">
        <p>
          <label for="email">Email</label>
          <input type="text" name="email" id="email" />
        </p>
        <p>
          <label for="parola">Parola</label>
          <input type="password" name="parola" id="parola" />
        </p>
        <p>
          <input type="submit" value="Login" class="shift" />
          <input type="hidden" name="login" value="true" />
        </p>
      </form>
      <p class="message">Nu aveti cont? Va puteti inregistra <a href="register.php">aici</a></p>
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