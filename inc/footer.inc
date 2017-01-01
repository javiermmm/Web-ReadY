<?php
/**
 * Footer comun a todas las paginas html de Tablon
 *
 *
 * 
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */		 	
?>
</div> <!-- cierra div principal -->
<?php 
// mucho debug, pero solo si se habilita
if($config['debug']) {
	echo '_POST ';
	var_dump($_POST);
	echo '<br />_GET ';
	var_dump($_GET);	
	echo '<br />_FILES ';
	var_dump($_FILES);	
	echo '<br />_SESSION ';
	var_dump($_SESSION);
	echo '<br />';
}
?>
<div id="footer">
	<span class="tablon">Tablón</span> 
	es una aplicación de ejemplo desarrollada por manuel.freire@fdi.ucm.es
</div>
</body>

