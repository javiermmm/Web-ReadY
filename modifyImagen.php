<?php
$x=$_GET["id"];

if (is_uploaded_file($_FILES['im']['tmp_name'])  )
{
//recojo la imagen
$imagen = $_FILES['im']['name'];
//Obtengo el nombre de la imagen y la extensión de la foto
//$imagen1 = explode(".",$imagen);
$imagen1 = jpg;
//var_dump($imagen1);
//Genero un nombre aleatorio con números y se asigno la extensión botenido anteriormente
//$x = $_SESSION['user'];
echo $x;
$imagen2 = $x.".".$imagen1;
//var_dump($imagen2);
//$imagen2 = $_SESSION['user']".".$imagen1[1];
//Coloco la iamgen del usuario en la carpeta correspondiente con el nuevo nombre
//Asigno a la foto permisos
$ruta="./data/".$imagen2;
var_dump($ruta);
if (rename($_FILES['im']['tmp_name'], $ruta)) 	
	//var_dump($_FILES);
	header ("Location:  user.php?id=$x");
//chmod($ruta,0777);
//A partir de aqui sólo si quiero eliminar una foto
//$resultArchivos = mysql_query("Selecciono el nombre de la foto antigua");
//$rowArchivos= mysql_fetch_array($resultArchivos);
//unlink("carpeta/".$rowArchivos[0]);
}
?>
