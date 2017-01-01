<?php

$login = $_POST['user'];
$pass = $_POST['pass'];

//Pedimos a la BD un usuario con el nombre introducido
$result = mysql_query("SELECT * FROM Usuario WHERE ID='$login';", $con);
$num_rows = mysql_num_rows($result);
echo "$num_rows";

if ($num_rows != 0) { //Si hay alguno que coincida
	$sql="SELECT pass, tipo FROM Usuario WHERE ID='$login';"; //Pedimos contraseña y tipo de ese usuario
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_array($result);
	echo "$row[0]";
	echo "$row[1]";
	echo "$pass";
	//Codificamos la contraseña introducida para compararlas
	$pass= sha1($pass.Ready85674Jessica);
	if ($row[0] != $pass){ //comprueba que las contraseñas sean iguales
		header("error: index.php/contraseña");
	} else 
		if ($row[1] == 2)//comprobamos si es un administrador quien intenta entrar
			header("Location: administrador.php");
		else {
			$_SESSION["user"] = $login;
			echo '<pre style="background-color: white">';
			echo 'sesion'; var_dump($_SESSION); 
			echo 'post'; var_dump($_POST); 
			echo 'get'; var_dump($_GET); 
			echo '</pre>';
		}
} else 
	echo '<pre style="background-color: white">';
	echo 'sesion'; var_dump($_SESSION); 
	echo 'post'; var_dump($_POST); 
	echo 'get'; var_dump($_GET); 
	echo '</pre>';