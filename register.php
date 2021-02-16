<?php
  session_start();
  if (isset($_POST['register'])){
    // Pasul 1. Se verifica daca formularul s-a completat corect

    require_once('mysql_connect.php');
    $user = mysqli_real_escape_string($link,$_POST['user']);
    $email = mysqli_real_escape_string($link,$_POST['email']);
    $parola = $_POST['parola'];
    $parola1 = $_POST['parola1'];

    if (empty($user)){
      $errors[] = "Numele de utilizator nu poate fi gol";
    }

    if ((empty($email)) or (!filter_var($email,FILTER_VALIDATE_EMAIL))){
      $errors[] = "Adresa de email invalida";
    }

    if ($parola != $parola1){
      $errors[] = "Parolele nu sunt identice";
    }

    // Pasul 2. Daca nu exista erori pana in acest punct, se verifica in BD pentru conturi duplicat

    if (!isset($errors)){
      $query = "SELECT * FROM utilizatori WHERE username = '$user' OR email = '$email'";
      $result = mysqli_query($link,$query);
      if (mysqli_num_rows($result) > 0){
        while($duplicat = mysqli_fetch_assoc($result)){
          if ($duplicat['username']==$user){
            $errors[] = "Numele de utilizator exista deja";
          }
          if ($duplicat['email']==$email){
            $errors[] = "Adresa de email exista deja";
          }
        }
      }
    }

    // Pasul 3. Daca nu am mai aparut erori (conturi duplicate), adaug utilizatorul
    if (!isset($errors)){
      $crypt = password_hash($parola,PASSWORD_BCRYPT);
      $query = "INSERT INTO utilizatori VALUES (NULL,'$user','$email','$crypt')";
      mysqli_query($link,$query);
      if (mysqli_affected_rows($link) == 1){
        $succes = "Utilizatorul a fost creat. Va puteti autentifica";
      }
      else{
        $errors[] = "Utilizatorul nu a fost adaugat!";
      }
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
      <h1>Inregistrare</h1>
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
      <form action="register.php" method="POST">
        <p>
          <label for="user">Nume utilizator*</label>
          <input type="text" name="user" id="user" />
        </p>
        <p>
          <label for="email">Email*</label>
          <input type="text" name="email" id="email" />
        </p>
        <p>
          <label for="parola">Parola*</label>
          <input type="password" name="parola" id="parola" />
        </p>
        <p>
          <label for="parola1">Repetati Parola*</label>
          <input type="password" name="parola1" id="parola1" />
        </p>
        <p>
          <input type="submit" value="Inregistrare" class="shift" />
          <input type="hidden" name="register" value="true" />
        </p>
      </form>
      <p class="message">Toate campurile marcate cu (*) sunt obligatorii</a></p>
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
