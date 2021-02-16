<h1>Categorii</h1>
<?php
// afisare ultimele 5 categorii
require_once('../mysql_connect.php');
$query = "SELECT * FROM categorii ORDER BY data_adaugare DESC LIMIT 0,5";
$result = mysqli_query($link,$query);
echo "<ul>\n";
echo "\t<li>Fara categorie</li>\n";
if ($total = mysqli_num_rows($result) > 0){
	while ($categorie = mysqli_fetch_assoc($result)){
		echo "\t<li>{$categorie['nume']}</li>\n";
	}
	if ($total > 5){
		echo "<li>Toate categoriile</li>\n";
	}
}
echo "</ul>\n";
// sfarsit afisare ultimele categorii
?>

<h1>Ultimele articole</h1>
<?php
// afisare ultimele 5 categorii
require_once('../mysql_connect.php');
$query = "SELECT * FROM articole ORDER BY data_adaugare DESC LIMIT 0,5";
$result = mysqli_query($link,$query);
echo "<ul>\n";
if ($total = mysqli_num_rows($result) > 0){
	while ($articol = mysqli_fetch_assoc($result)){
		if (strlen($articol['titlu']) > 20){
			echo "\t<li>" . substr($articol['titlu'],0,20) . "...</li>\n";
		}
		else{
			echo "\t<li>{$articol['titlu']}</li>\n";
		}
	}
	if ($total > 5){
		echo "<li>Toate articolele</li>\n";
	}
}
else{
	echo "<li>Nu exista articole</li>";
}
echo "</ul>\n";
// sfarsit afisare ultimele categorii
?>