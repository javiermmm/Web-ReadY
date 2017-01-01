<?php
/** 
 * Fichero con includes y funciones basicos para "Tablon"
 * - permite cargar la BD
 * - permite escribir la BD
 *
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */

// funciones de manejo de BD
require_once 'inc/db.php';
 
// funciones de autenticacion
require_once 'inc/auth.php';
 
// configuracion
require_once 'inc/config.php';

// -------------- preparacion de la sesion / bd / etcetera

/* 
 * inicializa la sesion (incorpora la cookie a la respuesta,
 * y si la peticion tenia cookie, almacena sus valores en 
 * el array superglobal _SESSION) 
 */
session_set_cookie_params($config['cookie_timeout'], $config['cookie_path']);
session_name($config['cookie_name']);
session_start();
if ( ! isset($_SESSION['user'])) {
	// inicializa una sesion vacia	
	$_SESSION['user'] = null;
	$_SESSION['role'] = null;
}

// intenta conectarse a la BD (recibe un objeto conexion o, si falla, el error)
$mdb2 = db_connect(db_url(
	$config['bd_user'], $config['bd_pass'], 
	$config['bd_name'], $config['bd_server']), true);


