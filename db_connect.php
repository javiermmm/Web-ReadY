<?php

//establecemos conexion con la BD
$con = mysql_connect("localhost","ReadY","jessica");

//Si hay algun error lo anulamos
if (!$con)
	die('Could not connect: ' . mysql_error());
	
//elegimos la BD a la que nos conectamos
mysql_select_db("ReadY", $con);

?>