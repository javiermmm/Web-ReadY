<?php 
    require_once('./comun.php');
	require('./db_connect.php');
	
    function do_registro() {
		require('./db_connect.php');
	
		$login = $_POST['user'];
		$login = mysql_real_escape_string($login);
		$pass = $_POST['pass'];

		$result = mysql_query("SELECT * FROM Usuario WHERE ID='$login';", $con); //Consultamos si ya hay un usuario con ese nombre
		$num_rows = mysql_num_rows($result);
		
		if ($num_rows != 0) {// comprueba que no existe el usuario
		
			$sql = "SELECT pass, tipo FROM Usuario WHERE ID='$login';";
			$result = mysql_query($sql, $con);
			$row = mysql_fetch_array($result);
			$pass = sha1($pass.'Ready85674Jessica');
				
			if ($row[0] != $pass){ //comprueba que las contraseñas sean iguales
				return false;
			}
			else 
				if ($row[1] == 2) {//comprobamos si es un administrador quien intenta entrar
					$_SESSION["user"] = $login; 
					$_SESSION["tipo"] = 2; 
					return true;
				}
				else {
					$_SESSION["user"] = $login; 
					return true;
				}
		}
		else 
			return false;
	}
 
    $registrado = isset($_SESSION["user"]);
    
    if ( ! $registrado) { //Si no esta registrado
		//comprobamos posibles errores
        $error_login = ! isset($_POST["user"]) || empty($_POST["user"]) ||
                 ! isset($_POST["pass"]) || empty($_POST["pass"]);
             
		//Si no hay error al loguearse
        if ( ! $error_login && do_registro($_POST["user"], $_POST["pass"])) {
			$x = $_POST["user"];
			$x = htmlspecialchars($x);
            $_SESSION["user"] = $x;
            $registrado = true;
        }
    } else if ($registrado) { //Si ya está registrado, quizá quiera cambiar la contraseña
				
				//comprobamos algunos erores
				$error_cambio =  ! isset($_POST["pass_old"]) || empty($_POST["pass_old"]) ||
						  ! isset($_POST["pass_new"]) || empty($_POST["pass_new"]); 

				if ( ! $error_cambio) {
					unset($_SESSION["user"]);  
					$registrado = false;   			
				} 
			}
?>

<!DOCTYPE html>
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Zion Narrows  
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20102110

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- VALIDACION CON VANADIUM -->

<meta http-equiv="Content-Type" content="text/html"; charset="ISO-8859-15">
<script language="javascript" type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/vanadium_es.js"></script>
<link rel="stylesheet" type="text/css" href="css/vanadium.css"></link>




<!-- AÃ‘ADIR UN WIDGET (TWITTER)-->

<script language="javascript" type="text/javascript" src="js/jquery.tweet.js"></script>
<script language="javascript" type="text/javascript" src="js/tweetConfig.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.tweet.css"></link>




<!--     RATING    -->

<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.ui.stars.js"></script>
<script language="javascript" type="text/javascript" src="js/rating.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.18.custom.css"></link>
<link rel="stylesheet" type="text/css" href="css/jquery.ui.stars.css"></link>



<!--     BORRAR   FILAS DE TABLA-->

<script language="javascript" type="text/javascript" src="js/borrar.js"></script>




<!--     VOTOS MAS/MENOS-->

<script language="javascript" type="text/javascript" src="js/voto.js"></script>




<meta name="keywords" content=""></meta>
<meta name="description" content=""></meta>
<meta http-equiv="content-type" content="text/html"; charset="iso-8859-15"></meta>
<title>Read-y!   |   Portada</title>
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen"></link>

</head>
<body>

<div id="wrapper">
	<div id="header">
		<div id="logo">
			<h1><a href="index.php">Read-y!   </a></h1>
			<p> Tu portal de lectura </p>
		</div>
		<div id="search">
			<form method="post" action="Buscar.php?pg=1">
				<fieldset>
				<input type="text" name="search-text" id="search-text" size="15"></input>
				<input type="submit" id="search-submit" value="BUSCAR"></input>
				</fieldset>
			</form>
		</div>
	</div>
	
	
	<div id="menu">
		<ul>	
			<li><a href="index.php">Presentacion</a></li>
			<li><a href="TodosRelatos.php?pag=1">Relatos</a></li>
		<?php 
			if ($registrado){
				echo "<li><a href=\"newRelato.php\">Nuevo Relato</a></li>";	
				$usuario = $_SESSION['user'];
				echo "<li><a href=\"user.php?id=$usuario\">Mi Perfil</a></li>";	
				if (isset ($_SESSION["tipo"]) && ($_SESSION["tipo"] == 2)) 
					echo "<li><a href=\"administrador.php\">Administrar</a></li>";
				echo "</ul>";
				$usuario = $_SESSION["user"];
				echo "<div id=\"saludo\">Bienvenid@ de nuevo, $usuario
						<form id=\"form3\" name=\"form3\" method=\"post\" action=\"borra.php\">
							<p> <input class=\"salir\" type=\"submit\" name=\"form\" id=\"button\" value=\"salir\"></input>
							</p>
						</form>
					</div>" ;
			} else {
				echo "</ul>";
				echo "<div class=\"login\">";
				echo "<form id=\"form2\" name=\"form2\" method=\"post\" action=\"index.php\">";
					echo "<p>Usuario <input class=\":required\" name=\"user\" type=\"text\" id=\"Titular\" size=\"10\" maxlength=\"10\"></input>";
					   echo "Contraseña <input class=\":required\" name=\"pass\" type=\"password\" id=\"Titular\" size=\"10\" maxlength=\"10\"></input>";
					   echo "<input class\"entrar\" type=\"submit\" name=\"form\" id=\"button\" value=\"Entrar\"></input>";
					   echo "<a class=\"registrate\" href=\"register.php\">registrate</a>";
					echo "</p></form>";
				echo "</div>";
			}
		echo "</div>";
		
	?>
	<!-- end #header -->
	
	<!-- end #menu -->
