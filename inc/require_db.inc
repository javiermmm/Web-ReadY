<?php
/**
 * Fragmento a colocar para impedir mostrar paginas si la BD
 * no esta funcionando. Asume que ya se ha mostrado el 'header'
 * 
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */
if (is_string($mdb2)) { 
?>		
<h3>Error</h2> 

<pre class='error'>
<?php echo $mdb2; ?>
</pre>

<p>
No estás conectado con la BD, así que el tablón está vacío y no se puede 
publicar nada en él. Lo único que puedes hacer es hablar con el administrador,
o (si eres el administrador), 
<a href="inicializa.php">arreglar la configuración de la BD</a>.
</p>

<?php 
	include('inc/footer.inc');
	exit();
} 
?>