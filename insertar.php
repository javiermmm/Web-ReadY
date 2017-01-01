<?php
require('./db_connect.php');

  echo '<pre style="background-color: white">';
        echo 'sesion'; var_dump($_SESSION); 
        echo 'post'; var_dump($_POST); 
        echo 'get'; var_dump($_GET); 
	    echo '</pre>';
	
//recogemos los datos de registro
$login = $_POST['user'];
$pass = $_POST['pass'];
$pass2 = $_POST['pass2'];
$email = $_POST['email'];
$login = mysql_real_escape_string($login);
$pass = mysql_real_escape_string($pass);
$email = mysql_real_escape_string($email);
echo "$login";

//pedimos a la BD usuarios con ese nombre
$sql="SELECT * FROM Usuario WHERE ID='$login';"; 
$result = mysql_query("SELECT * FROM Usuario WHERE ID='$login';", $con);
var_dump($result);
$num_rows = mysql_num_rows($result);
var_dump($num_rows);
if ($num_rows == 0){ //Si no hay ninguno que coincida
	//Codificamos la contraseña
	$pass= sha1($pass.Ready85674Jessica);
	echo "$pass";
	//Insertamos en la BD
	$sql="INSERT INTO Usuario (ID, pass, mail, tipo) VALUES ('$login','$pass','$email', '1');";
	//Si error lo anulamos y si no redirigimos
	if (!mysql_query($sql,$con)) {
	  die('Error: ' . mysql_error());
	}
	else{
		header("Location: index.php");
	}
}
else {
	echo '<pre style="background-color: white">';
	echo 'Algo va mal'; 
	echo '</pre>';
	header("Location: register.php?exito=false");
}
?>