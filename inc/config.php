<?php
/** 
 * Fichero de configuracion para Tablon
 *
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */

// Array que contiene toda la configuracin de Tablon
$config = array();
	 
/*
 * Localizacion del fichero password-configuracion, usado para acceder
 * a la pagina de inicializacion
 */
$config['pass_config'] = 'inc/password-configuracion.txt';
$config['pass_config_len'] = 16;

/*
 * Base de datos a usar; estos valores se usan para establecer la conexion
 * con la BD; los marcadores BD_CONFIG_* permiten a inicializa.php substituir
 * los valores cuando se configura la BD.
 */
#BD_CONFIG_START
$config['bd_pass']   = 'secreto! no se lo digas a nadie!';
$config['bd_user']   = 'Tablon';
$config['bd_name']   = 'Tablon';
$config['bd_server'] = 'localhost';
#BD_CONFIG_END

/*
 * Directorio de instalacion (ruta absoluta al directorio que contiene 'index.php')
 * Debe usar '/' para directorios y acabar en '/' (aunque estes en Windows)
 */
$config['ruta'] = '/home/tw/Tablon/www/';

/*
 * Util para mostrar que nos envian; no usar en produccion
 */
$config['debug'] = true;

/*
 * Modifica la ruta de las cookies de esta aplicacion
 * el tiempo que tardan en caducar, y su nombre
 * mas en http://www.php.net/manual/en/session.configuration.php
 */
$config['cookie_path'] = '/Tablon/';
$config['cookie_timeout'] = 60 * 60 * 1; // 1h en segundos
$config['cookie_name'] = 'TABLONID';

