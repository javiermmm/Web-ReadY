<?php
/** 
 * Fichero con funciones para facilitar la autenticacion de usuarios en Tablon.
 * Tambien incorpora un poquito de numeros aleatorios
 *
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */

require_once('inc/db.php');

/* 
 * Genera cadenas hex. aleatorias en multiplos de 4 caracteres; y luego 
 * recorta hasta lo que se haya pedido
 */
function auth_aleat($chars) {
	$res = '';
	for ($i=0; $i<$chars; $i+=4) {			
		$res = sprintf("%s%04x", $res, mt_rand(0, 0xffff));
	}
	return substr($res, 0, $chars);
}	 

/*
 * Genera un hash(pass, salt)
 */
function auth_encripta($pass, $salt) {
	return sha1($pass . $salt);
}

/*
 * Verifica que hash(pass, salt) coincide con el argumento
 */
function auth_verifica($encriptado, $pass, $salt) {
	return auth_encripta($pass, $salt) == $encriptado;
}

/*
 * Escribe una clave aleatoria a un fichero, a no ser que el fichero exista;
 * si existe, lo deja como esta
 */
function auth_escribe_aleatorio($fichero, $chars) {
	if ( ! file_exists($fichero)) {
		file_put_contents($fichero, auth_aleat($chars));	
	}
}

/*
 * Verifica que el contenido dle fichero es el esperado 
 */
function auth_verifica_aleatorio($contenido, $fichero) {
	return file_get_contents($fichero) == $contenido;
}

/*
 * verifica un login de usuario con la BD
 * entradas externas: $login_ y $pass_ (existen, pero no sabes que valen)
 */
function auth_check_login($mdb2, $login_, $pass_) {
	$st = $mdb2->prepare('SELECT pass, salt FROM usuarios WHERE login = ?;',
		array('text'));
	if (PEAR::isError($st)) {
		die($st->getMessage() . ': ' . $st->getDebugInfo());
	}
	$result = $st->execute($login_);
	db_die_if_error($result);
	
	if ($result->numRows() == 0) {
		return false;
	} else {
		$usuario = $result->fetchRow(MDB2_FETCHMODE_OBJECT);
		return auth_verifica($usuario->pass, $pass_, $usuario->salt);
	}
}

/*
 * devuelve el usuario asociado con ese login, o null si no existe
 */
function auth_get_user($mdb2, $login_) {
	$st = $mdb2->prepare('SELECT * FROM usuarios WHERE login = ?;',
		array('text'));
	db_die_if_error($st);
	$result = $st->execute($login_);
	db_die_if_error($result);
	return $result->fetchRow(MDB2_FETCHMODE_OBJECT);
}

/*
 * inserta el usuario en la BD. No debe existir otro con el mismo login
 */
function auth_add_user($mdb2, $login_, $pass_, $role) {
	$st = $mdb2->prepare('INSERT INTO usuarios (login, pass, salt, role) ' .
	 	' VALUES (?, ?, ?, ?);', array('text', 'text', 'text', 'text'));
	db_die_if_error($st);
	$salt = auth_aleat(12);
	$pass = auth_encripta($pass_, $salt);
	$result = $st->execute(array($login_, $pass, $salt, $role));
	db_die_if_error($result);
	return  ! PEAR::isError($result);
}

