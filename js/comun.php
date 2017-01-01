<?php

session_start();

$config['bd_pass']   = 'jessica';
$config['bd_user']   = 'ReadY';
$config['bd_name']   = 'ReadY';
$config['bd_server'] = 'localhost';

function estado() {
   
	    echo '<pre style="background-color: white">';
        echo 'sesion'; var_dump($_SESSION); 
        echo 'post'; var_dump($_POST); 
        echo 'get'; var_dump($_GET); 
	    echo '</pre>';

}


// funciones de manejo de BD
require_once 'inc/db.php';
 
// funciones de autenticacion
require_once 'inc/auth.php';
 
// configuracion
require_once 'inc/config.php';



global $mdb2;
$mdb2 = db_connect(db_url(
	$config['bd_user'], $config['bd_pass'], 
	$config['bd_name'], $config['bd_server']), true);

/*$value = 'cualquier cosa';
setcookie("ReadYCookie", $value, time()+3600);  /* expira en una hora */

/* no se usa ?> para cerrar ficheros que no generan salida */