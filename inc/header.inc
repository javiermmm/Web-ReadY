<?php
/**
 * Cabecera comun a todas las paginas html de Tablon; carga jQuery,
 * especifica tipo de archivo, codificacion; coloca titulo,
 * deja la pagina dentro del "body", intenta registrar el usuario
 * si es que el formulario de login contiene algo
 * 
 * Usa 
 *   - pageTitle: titulo de la pagina
 *   - pageExtraCSS: CSSs adicionales (via src)
 *   - pageExtraScripts: scripts adicionales (via src)
 *   - pageScript: script a ejecutar al comienzo de la pagina
 *   - _SESSION['user'] (common.php) para mostrar cosas en la 
 *     caja de login, que pueden ser 
 *     un error de 'BD no disponible' -- si config['abierto'] == false
 * 	   un formulario -- si tablonIsOnline == true y no hay usuario
 *     el usuario, con enlace a su perfil y enlace de logout -- si hay usuario
 * 
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */
 
// procesa peticion de login
if ( ! $_SESSION['user'] && ! is_string($mdb2)
	&& isset($_POST['submit']) && $_POST['submit'] == 'login'
	&& isset($_POST['login'], $_POST['pass'])) {
		
	if (auth_check_login($mdb2, $_POST['login'], $_POST['pass'])) {
		$user = auth_get_user($mdb2, $_POST['login']);
		$_SESSION['user'] = $user->login;
		$_SESSION['role'] = $user->role;
	} else {
		$errorDeLogin = true;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title><?php echo $pageTitle; ?></title>

	<link rel="stylesheet" type="text/css" href="css/main.css"/>
<?php
	if (isset($pageExtraCSS)) {		
		foreach ($pageExtraCSS as $css) {
			echo "\t" .	'<link rel="stylesheet" type="text/css" href="' .
				$css . '" />' . "\n";
		}
	}
?>	

	<script src="js/jquery-1.5.1.min.js"></script>
	<script src="js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="js/valida.js"></script>
<?php
	if (isset($pageExtraScripts)) {		
		foreach ($pageExtraScripts as $script) {
			echo "\t<script src=\"" . $script . "\"></script>\n";
		}
	}
?>	
	<script>
	$(document).ready(function(){
		Validador.asocia("form");
<?php if (isset($pageScript)) echo $pageScript . "\n"; ?>
	});
	</script>
</head>
<body>
<div id="header">
	<a id="cajaMain" href="index.php"></a>
	<div id="cajaLogin">
<?php if ($_SESSION['user']): ?>
		Registrado como 
		<a href="user.php"><?php echo $_SESSION['user']; ?></a>
		[ <?php echo $_SESSION['role']; ?> ] 
		( <a href="logout.php">Salir</a> )
<?php elseif (! is_string($mdb2)): ?>
		<form id="formularioLogin" method="POST">
			<fieldset>
				<label for="login">Login</label>
				<input id="login" name="login" class=":required" type="text"/>
				<label for="pass">Pass</label>
				<input id="pass" name="pass" class=":required" type="password"/>
				<button name="submit" value="login" type="submit">Entrar</button>
				<a href="registro.php">No tengo</a>
<?php if (isset($errorDeLogin)): ?>
				<span class='errorLogin'>Login o contraseña incorrectos</span>
<?php endif; ?>
			</fieldset>
		</form>
<?php else: ?>		
		<a href="inicializa.php">BD no activa</a>
		( <a href="logout.php">Salir</a> )		
<?php endif; ?>
	</div>
</div>
<div id="principal">
