<?php
/** 
 * Acceso y conexion a la BD; requiere PEAR::MDB2
 *
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */

// PEAR no es PHP5; desactiva warnings de 'este PHP4 es feo'
error_reporting(E_ALL & ~E_DEPRECATED);

// incluye la BD
require_once 'MDB2.php';

/*
 * Devuelve una URL de conexion a BD; por defecto, 
 * type = mysqli, server = localhost
 */ 
function db_url($user, $pass, $dbName, 
    $server = 'localhost', $type = 'mysqli') {	

	// 'mysqli://juan_ejemplo:ejemplito@localhost/ejemplo'
	//  type     user         pass      server    dbName
	$dburl = $type . '://' . 
		$user . ($pass ? ':' . $pass : '') . '@' . 
		$server . '/' . $dbName;	
	return $dburl;
}

/*
 * Conecta a la BD usando un objeto-conexion;
 * Devuelve conexion, o, en caso de fallo,
 *     si continue == false (defecto), muere mostrando el error
 *     si continue == true, devuelve el error como cadena -- uso mediante
 *        is_string(valor_devuelto) // es un mensaje de error
 *        is_object(valor_devuelto) // es un objeto
 */
function db_connect($dburl, $continue = false) {
	$mdb2 = MDB2::connect($dburl);
	
	if (PEAR::isError($mdb2)) {
		$err = $mdb2->getMessage() . ', ' . $mdb2->getDebugInfo();
		if ($continue) {
			return $err; 
		} else {
			die($err);
		}
	} else {
		// carga el modulo de consultas extendias, que quedan habilitadas
		return $mdb2;
	}			
}

/*
 * Carga una BD de fichero SQL (generado, por ejemplo, con la funcion
 * export de phpmyadmin)
 * 
 * Devuelve array con 
 *     status => true si todo bien, false si hay fallos
 *     message => mensaje de progreso o error
 */
function db_load($mdb2, $file) {
	
	// prepare to wait a long time
	set_time_limit(0); 

	// prepare return
	$ret = array();
		
	// write database - using direct MySQLi to avoid 'single-query-per-exec' limitation
	$result = $mdb2->connection->multi_query(file_get_contents($file));
	if 	( ! $result) {
	    $ret = array(
			'message' => $mdb2->connection->error, 
			'status'  => false);
	} else {
	    $ret = array(
			'message' => 'BD importada correctamente <br />',
			'status'  => true);
	}
	return $ret;
}


/*
 * Guarda una BD a un fichero SQL; similar (espero) a los de 
 * phpmyadmin
 * 
 * adaptado de http://davidwalsh.name/backup-mysql-database-php
 * 
 * Devuelve array con 
 *     status => true si todo bien, false si hay fallos
 *     message => mensaje de progreso o error
 */
function db_save($mdb2, $file) {
	
	// prepare to wait a long time
	set_time_limit(0); 

	// prepare return
	$ret = array();
		
	// write database - using direct MySQLi to avoid 'single-query-per-exec' limitation
	$result = $mdb2->connection->multi_query(file_get_contents($file));
	if 	( ! $result) {
	    $ret = array(
			'message' => $mdb2->connection->error, 
			'status'  => false);
	} else {
	    $ret = array(
			'message' => 'BD importada correctamente <br />',
			'status'  => true);
	}
	return $ret;
}


/*
 * Crea una BD y un usuario con permisos solo para esa BD; 
 * solo valido para MySQL; 
 * la conexion debe tener los permisos oportunos
 * 
 * Devuelve array con 
 *     status => true si todo bien, false si hay fallos
 *     message => mensaje de progreso o error
 */
function db_create($mdb2, $dbname, $dbuser, $dbpass) {
		
	// prepare return
	$ret = array('status' => false, 'message' => '');
	
	// execute CREATE query
	$affected = $mdb2->exec("CREATE DATABASE IF NOT EXISTS $dbname; ");
	if (PEAR::isError($affected)) {
    	$ret['message'] .= $affected->getMessage() . ': ' . $affected->getDebugInfo();
		return $ret;
	} else {
		$ret['message'] .=  "BD $dbname creada o ya existia <br />";
	}
	
	// execute USER queries; MDB2 does not allow multiple queries in one statement
	$queries = array( 
		"CREATE USER '$dbuser'@'localhost' IDENTIFIED BY '$dbpass'; ",
		"GRANT ALL ON $dbname.* TO '$dbuser'@'localhost'; ",
		"FLUSH PRIVILEGES; ");
	foreach ( $queries as $query ) {
		$affected = $mdb2->exec($query);
		if (PEAR::isError($affected)) {
			$ret['message'] .= $affected->getMessage() . ': ' . $affected->getDebugInfo();
			return $ret;
		}		
	}
	 
 	$ret['message'] .=  "Usuario $dbuser creado <br />";
	$ret['status'] = true;
	return $ret;		
}
	
/**
 * Borra una BD y, opcionalmente, un usuario asociado
 * la conexion debe tener los permisos oportunos
 * 
 * OJO: esto es peligroso; cuidado con el nombre de la BD y del usuario...
 * Devuelve array con 
 *     status => true si todo bien, false si hay fallos
 *     message => mensaje de progreso o error
 */
function db_destroy($mdb2, $dbname, $dbuser) {
		
	// prepare return
	$ret = array('status' => false, 'message' => '');
	
	// execute DROP DATABASE query
	$affected = $mdb2->exec("DROP DATABASE $dbname ;");
	if (PEAR::isError($affected)) {
    	$ret['message'] .= $affected->getMessage() . ': ' . $affected->getDebugInfo();
		return $ret;
	} else {
		$ret['message'] .=  'BD eliminada <br />';
	}
	
	// execute DROP USER queries
	$queries = array( 
		"DROP USER '$dbuser'@'localhost'; " ,
		"FLUSH PRIVILEGES");
	foreach ( $queries as $query ) {
		$affected = $mdb2->exec($query);
		if (PEAR::isError($affected)) {
			$ret['message'] .= $affected->getMessage() . ': ' . $affected->getDebugInfo();
			return $ret;
		}		
	}
	$ret['message'] .=  'Usuario eliminado <br />';
	$ret['status'] = true;
	return $ret;		
}

function db_die_if_error($error) {
	if (PEAR::isError($error)) {
		die($error->getMessage() . ': ' . $error->getDebugInfo());
	}	
}
