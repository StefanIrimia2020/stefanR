<?php
  session_start();
  if (!isset($_SESSION['id'])){
    header("Location: ../");
  }

  require_once('../mysql_connect.php');
  if ((isset($_GET['data'])) and (isset($_GET['id'])) and (is_numeric($_GET['id']))){
    $data = $_GET['data'];
    $id = $_GET['id'];
  }
  else{
   // verific daca s-a trimis formularul pe baza campului hidden
    if (isset($_POST['id'])){
      $id = $_POST['id'];
      $data = $_POST['data'];
      switch($data){
        case 'post':
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
            // se actualizeaza articolul
            $query = "UPDATE articole SET titlu = '$titlu', articol = '$articol', categorie = $categorie, tags = '$tags'";
            if ($target !=  null){
              // sterg imaginea veche
              $q = "SELECT imagine FROM articole WHERE id = $id AND imagine IS NOT NULL";
              $result = mysqli_query($link,$q);
              if ($original = mysqli_fetch_assoc($result)){
                unlink("../" . $original['imagine']);
              }
              $query .= ", imagine = '$target'";
            }
            else{
              if (isset($_POST['sterge_poza'])){
                $query .= ", imagine = NULL";
                $q = "SELECT imagine FROM articole WHERE id = $id AND imagine IS NOT NULL";
                $result = mysqli_query($link,$q);
                if ($original = mysqli_fetch_assoc($result)){

                  unlink("../" . $original['imagine']);
                }
              }
            }
            $query .= " WHERE id = $id";

            mysqli_query($link,$query);
            if (mysqli_affected_rows($link)==1){
              $succes = "Articol modificat";
            }
            else{
              $errors[] = "Articolul nu a fost modificat";
            }
          }
          break;
        case 'category':
          $titlu = $_POST['titlu'];
          if (empty($titlu)){
            $errors[] = "Completati titlul categoriei";
          }

          if (!isset($errors)){
            $query = "UPDATE categorii SET nume = '$titlu' WHERE id = $id";
            mysqli_query($link,$query);
            if (mysqli_affected_rows($link)==1){
              $succes = "Categorie modificata";
            }
            else{
              $errors[] = "Categoria nu a fost modificata";
            }
          }
        default:
          header("Location: index.php?error=Invalid data");
      }
    }
    else{
      header("Location: index.php?error=Invalid data");
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
      <?php
        switch ($data){
          case 'post':
            $query = "SELECT * FROM articole WHERE id = $id";
            $result = mysqli_query($link,$query);
            if ($articol = mysqli_fetch_assoc($result)){
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
              <form action="edit.php" method="POST" enctype="multipart/form-data">
                <p>
                  <label for="titlu">Titlu*</label>
                  <input type="text" name="titlu" id="titlu" value="<?php echo $articol['titlu'];?>"/>
                </p>
                <p>
                  <label for="articol">Articol*</label>
                  <textarea name="articol" id="articol"><?php echo $articol['articol'];?></textarea>
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
                        if ($articol['categorie'] == $cat['id']){
                          $selected = "selected=\"selected\"";
                        }
                        else{
                          $selected = "";
                        }
                        echo "\t\t\t\t\t<option value=\"{$cat['id']}\" {$selected}>{$cat['nume']}</option>\n";
                      }
                    ?>
                  </select>
                  <a href="add_category.php" title="Adauga categorie">Adauga categorie</a>
                </p>
                <p>
                  <label for="tags">Etichete*</label>
                  <input type="text" name="tags" id="tags" value="<?php echo $articol['tags'];?>" />
                </p>
                <?php
                  if (isset($articol['imagine'])){
                    echo "<p>\n<img src=\"../{$articol['imagine']}\" class=\"preview shift\" />\n</p>\n";
                  }
                ?>
                <p>
                  <label for="poza">Imagine</label>
                  <input type="file" name="poza" id="poza" />
                </p>
                <p class="shift">
                  sau <input type="checkbox" name="sterge_poza" value="true" /> Sterge imaginea
                </p>
                <p class="shift">
                  Imaginea curenta va fi stearsa daca se va incarca una noua!
                </p>
                <p>
                  <input type="hidden" name="id" value="<?php echo $id;?>" />
                  <input type="hidden" name="data" value="<?php echo $data;?>" />
                  <input type="submit" value="Editeaza" class="shift" />
                </p>
              </form>
              <?php
            }
            else{
              echo "<p class=\"error\">Articolul nu exista</p>";
            }
            break;
          case 'category':
            $query = "SELECT * FROM categorii WHERE id = $id";
            $result = mysqli_query($link,$query);
            if ($categorie = mysqli_fetch_assoc($result)){
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
              <form action="edit.php" method="POST">
                <p>
                  <label for="titlu">Titlu*</label>
                  <input type="text" name="titlu" id="titlu" value="<?php echo $categorie['nume'];?>"/>
                </p>
                <p>
                  <input type="hidden" name="id" value="<?php echo $id;?>" />
                  <input type="hidden" name="data" value="<?php echo $data;?>" />
                  <input type="submit" value="Editeaza" class="shift" />
                </p>
              </form>
              <?php
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
