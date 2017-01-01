<?php
	//Pedimos los 5 usuarios mas nuevos
    $sql="SELECT id FROM Usuario ORDER BY registro DESC LIMIT 0,5;";
	$result = mysql_query($sql, $con);
	
	//Pedimos los 5 relatos mas nuevos
	$sql2="SELECT titulo FROM Relato ORDER BY fecha DESC LIMIT 0,5;";
	$result2 = mysql_query($sql2, $con);
?>

<div id="sidebar">

	<a href="https://twitter.com/share" class="twitter-share-button" data-text="¡Cómo mola esta web! bantu.fdi.ucm.es/ReadY" data-lang="es" data-size="large">Twittear</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	<div class="tweet"></div>
	<ul>
		<li>
			<h2>Usuarios más recientes</h2>
			<ul>
				<?php
					//Vamos mostrando uno a uno los resultados de los ultimos usuarios
					$i=0;
					$num_rows=mysql_num_rows($result);
					while (($i < $num_rows) && ($i < 15)) {
						 $x = mysql_result($result, $i, 0);
						 $x = htmlspecialchars($x);	 
						 $i= $i+1;
						 echo "\t\t<li><a href='user.php?id=$x'>$x</a></li>\n";
					}
				?>
			</ul>
		</li>
		<li>
			<h2>Relatos Nuevos</h2>
			<ul>
				<?php
					//Vamos mostrando uno a uno los resultados de los ultimos relatos
					$i=0;
					$num_rows=mysql_num_rows($result2);
					while(($i < $num_rows) && ($i < 15)) {
						 $x = mysql_result($result2, $i, 0);
						 $x = htmlspecialchars($x);	 
						 $i= $i+1;
						 echo "\t\t<li><a href='relato.php?id=$x'>$x</a></li>\n";
					}
				?>
			</ul>
		</li>
		<li>
			<h2>Webs amigas</h2>
			<ul>
				<li><a href="http://www.entrelectores.com/">Entrelectores</a></li>
				<li><a href="http://www.papelenblanco.com/">PapelEnBlanco</a></li>
				<li><a href="http://www.librosyliteratura.es/">Libros y Literatura</a></li>
			</ul>
		</li>
	</ul>
</div>
<!-- end #sidebar -->
