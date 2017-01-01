<?php require('cabecera.inc.php'); ?>
	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
		<div class="cuadro_tabla">
			&nbsp;
						&nbsp;
		<?php
			if (isset($_POST["search-text"]) || isset($_GET["keyword"])) {

				if (isset ($_GET)) {
					if ($_GET['pg'] == 1)
						$_SESSION['pg'] = 1;
					if ($_GET['pg'] == 2)
						$_SESSION['pg'] = 2;
					if ($_GET['pg'] == 3)
						$_SESSION['pg'] = 3;
				}
				
				if (isset($_POST["search-text"]))
					$v = $_POST["search-text"];
				if (isset($_GET["keyword"]))
					$v = $_GET["keyword"];
				
				$v = htmlspecialchars($v);
				
				//Pedimos los usuarios cuyo ID (nombre) conincide con el patron algo+busqueda+algo y los mostramos ordenados por fecha de registro
				$sql_usuario = "SELECT ID FROM Usuario WHERE ID LIKE '%$v%' ORDER BY 'registro';";
				$result_usuario = mysql_query($sql_usuario, $con);
				$num_rows_usuario = mysql_num_rows($result_usuario);
				
				//Pedimos los relatos cuyo titulo o contenido incluyan el patron algo+busqueda+algo y los mostramos ordenados por fecha
				$sql_relato = "SELECT titulo, contenido FROM Relato WHERE titulo LIKE '%$v%' OR contenido LIKE '%$v%' ORDER BY 'fecha';";
				$result_relato = mysql_query($sql_relato, $con);
				$num_rows_relato = mysql_num_rows($result_relato);
				
				//Pedimos los comentarios cuyo relato, autor o contenido conincide con el patron algo+busqueda+algo y los mostramos ordenados por fecha
				$sql_comentario = "SELECT relato, autor, contenido FROM Comentario WHERE contenido LIKE '%$v%' ORDER BY 'fecha';";
				$result_comentario = mysql_query($sql_comentario, $con);
				$num_rows_comentario = mysql_num_rows($result_comentario);

				//NOTA: No buscamos en las colaboraciones porque son privadas para los usuarios
				
				echo "<p class='resultados_busqueda'>RESULTADOS DE LA BÚSQUEDA: $num_rows_usuario usuarios, $num_rows_relato relatos, $num_rows_comentario comentarios</p>";
				 
				 //Tabla para usuarios
				if ($_SESSION['pg'] == 1) {	//si estamos en la pagina de usuarios...
					if ($num_rows_usuario > 0) { //...y hay usuarios que mostrar, entronces montamos una tabla con los resultados
						echo "<h1>Usuarios que contiene la palabra clave \"$v\"</h1>";
						$j = 0;
						echo "<table class='buscar'>
							<tbody id=\"tabla\">	";	 
						echo "<TR>
								<TH>Usuario</TH>
							  </TR>";
						while($j < $num_rows_usuario) {
							$usuario = mysql_result($result_usuario, $j, 0);
							$usuario = strtolower($usuario);
							$usuario = htmlspecialchars($usuario);							
							echo "<TR>
							   <TD><a href=\"user.php?id=$usuario\">$usuario</a></TD>";
							echo "</TR>";
							$j = $j+1;
						}
						echo"</tbody>
						</table>";
					} //Si no hay usuarios que mostrar se notifica
					else {
						echo "<p>No se han encontrado Usuarios con la palabra clave \"$v\"</p>";
					}
					//Se muestran los enlaces para los resultados de la busqueda
					echo "<a class='pagresul' href=\"Buscar.php?pg=1&keyword=$v\">Ver Usuarios encontrados</a>
						  <a class='pagresul' href=\"Buscar.php?pg=2&keyword=$v\">Ver Relatos encontrados</a>
						  <a class='pagresul' href=\"Buscar.php?pg=3&keyword=$v\">Ver Comentarios encontrados</a>";
				}
				
				//Tabla para relatos
				if ($_SESSION['pg'] == 2) { //si estamos en la pagina de relatos...
					if ($num_rows_relato > 0){ //...y hay relatos que mostrar, entronces montamos una tabla con los resultados
						echo "<h1>Relatos que contiene la palabra clave \"$v\"</h1>";
						$j=0;
						echo "<table class='buscar'>
						<tbody id=\"tabla\">	";	 
						echo "<TR>
						<TH>Relato</TH>
						<TH>Contenido</TH>
						</TR>";
						while($j < $num_rows_relato) {
							$relato = mysql_result($result_relato, $j, 0);
							$relato = strtolower($relato);
							$relato = htmlspecialchars($relato);
							$contenido = mysql_result($result_relato, $j, 1);
							$contenido = htmlspecialchars($contenido);
							$contenido = substr($contenido, 0, 75);
							echo "<TR>
							   <TD><a href=\"relato.php?id=$relato\">$relato</a></TD>
							   <TD>$contenido [...]</TD>";
							echo "</TR>";
							$j= $j+1;
						}
						echo"</tbody>
						</table>";
					}
					else { //Si no hay usuarios que mostrar se notifica
						echo "<p>No se han encontrado Relatos con la palabra clave \"$v\"</p>";
					}
					//Se muestran los enlaces para los resultados de la busqueda
					echo "<a class='pagresul' href=\"Buscar.php?pg=1&keyword=$v\">Ver Usuarios encontrados</a>
						  <a class='pagresul' href=\"Buscar.php?pg=2&keyword=$v\">Ver Relatos encontrados</a>
						  <a class='pagresul' href=\"Buscar.php?pg=3&keyword=$v\">Ver Comentarios encontrados</a>";
				}
				
				//Tabla para comentarios
				if ($_SESSION['pg'] == 3) { //si estamos en la pagina de comentarios...
					if ($num_rows_comentario > 0) { //...y hay comentarios que mostrar, entronces montamos una tabla con los resultados
						echo "<h1>Comentarios que contiene la palabra clave \"$v\"</h1>";
						$j=0;
						echo "<table class='buscar'>
						<tbody id=\"tabla\">	";	 
						echo "<TR>
						<TH>Relato</TH>
						<TH>autor del comnetario</TH>
						<TH>Comentario</TH>
						</TR>";
						while($j < $num_rows_comentario) {
							$relato = mysql_result($result_comentario, $j, 0);
							$autor = mysql_result($result_comentario, $j, 1);
							$comentario = mysql_result($result_comentario, $j, 2);
							$comentario = strtolower($comentario);
							$comentario = htmlspecialchars($comentario);
							$relato = htmlspecialchars($relato);
							$autor = htmlspecialchars($autor);
							$comentario = substr($comentario, 0, 37);
							echo "<TR>
								<TD><a href=\"relato.php?id=$relato\">$relato</a></TD> 
								<TD><a href=\"user.php?id=$autor\">$autor</a></TD>  
								<TD>$comentario [...]</TD>"; 
							echo "</TR>";
							$j= $j+1;
						}
						echo"</tbody>
						</table>";
					}
					else { //Si no hay usuarios que mostrar se notifica
						echo "<p>No se han encontrado Comentarios con la palabra clave \"$v\"</p>";
					}
					//Se muestran los enlaces para los resultados de la busqueda
					echo "<a class='pagresul' href=\"Buscar.php?pg=1&keyword=$v\">Ver Usuarios encontrados</a>
						  <a class='pagresul' href=\"Buscar.php?pg=2&keyword=$v\">Ver Relatos encontrados</a>
						  <a class='pagresul' href=\"Buscar.php?pg=3&keyword=$v\">Ver Comentarios encontrados</a>";
				}
			}
		?>
		<div style="clear: both;">&nbsp;</div>
		</div>
		</div>
		<!-- end #content -->
		
		<?php require('lateral.inc.php'); ?>
		
		<div style="clear: both;">&nbsp;</div>
	</div>
	</div>
	</div>
	<!-- end #page -->

<?php require('pie.inc.php'); ?>	