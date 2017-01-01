<?php 

	require('cabecera.inc.php'); 

	//Si el usuario ha iniciado sesión y además es administrador
	if (isset($_SESSION["user"]) && ($_SESSION["tipo"] == 2)) {

		//BORRADO DE COMENTARIOS Y COLABORACIONES
		if (isset($_POST['Donde'])) {
			$a1 = $_POST['Cual'];
			$a1 = mysql_real_escape_string($a1);
			if ($_POST['Donde'] == 'comentario')
				$sql10 = "DELETE FROM Comentario WHERE id = '$a1' ;";
			else
				$sql10 = "DELETE FROM Colaboraciones WHERE id = '$a1' ;";
				
			$result10 = mysql_query($sql10, $con);
			exit();
		} 

		//BORRADO DE USUARIOS
		if (isset($_POST['Usuarios'])) {
			$a1 = $_POST['Usuarios'];
			$a1 = mysql_real_escape_string($a1);
			echo "$a1";
			//BORRAMOS LAS APORTACIONES DE OTROS A OBRAS PRODUCIDAS POR EL USUARIO BORRADO
			$sql = "SELECT titulo FROM Relato WHERE autor = '$a1' ;";
			$result20 = mysql_query($sql, $con);
			$num_rows = mysql_num_rows($result20);
			for ($i =0; $i < $num_rows; $i++) {
				$titulo = mysql_result($result20, $i, 0);
				$titulo = mysql_real_escape_string($titulo);
				$sql10 = "DELETE FROM Relato WHERE titulo = '$titulo' ;";
				$sql11 = "DELETE FROM Colaboraciones WHERE relato = '$titulo' ;";
				$sql12 = "DELETE FROM Comentario WHERE relato = '$titulo' ;";
				$sql13 = "DELETE FROM Votaciones WHERE relato = '$titulo' ;";
				$result11 = mysql_query($sql11, $con);
				$result12 = mysql_query($sql12, $con);
				$result13 = mysql_query($sql13, $con);
				$result10 = mysql_query($sql10, $con);
			}
			//BORRAMOS TODO LO PRODUCIDO POR EL USUARIO BORRADO
			$sql14 = "DELETE FROM Usuario WHERE id = '$a1' ;";
			$sql15 = "DELETE FROM Colaboraciones WHERE user = '$a1' ;";
			$sql16 = "DELETE FROM Comentario WHERE autor = '$a1' ;";
			$sql17 = "DELETE FROM Votaciones WHERE usuario = '$a1' ;";
			$sql18 = "DELETE FROM Relato WHERE autor = '$a1' ;";
			$result15 = mysql_query($sql15, $con);
			$result16 = mysql_query($sql16, $con);
			$result17 = mysql_query($sql17, $con);
			$result18 = mysql_query($sql18, $con);
			$result14 = mysql_query($sql14, $con);
			exit();
		}
		
		//BORRADO DE RELATOS
		if (isset($_POST['Relatos'])) {
			$a1 = $_POST['Relatos'];
			$a1 = mysql_real_escape_string($a1);
			$sql10 = "DELETE FROM Relato WHERE titulo = '$a1' ;";
			$sql11 = "DELETE FROM Colaboraciones WHERE relato = '$a1' ;";
			$sql12 = "DELETE FROM Comentario WHERE relato = '$a1' ;";
			$sql13 = "DELETE FROM Votaciones WHERE relato = '$a1' ;";
			$result11 = mysql_query($sql11, $con);
			$result12 = mysql_query($sql12, $con);
			$result13 = mysql_query($sql13, $con);
			$result10 = mysql_query($sql10, $con);
			exit();
		}
		
		//Montamos la pagina
		echo"<div id=\"page\">
		<div id=\"page-bgtop\">
		<div id=\"page-bgbtm\">
			<div id=\"content\">";
			
				//Tabla para usuarios
				echo "<div class=\"cuadro_tabla\">
					<h2>Usuarios</h2>
					<table>
						<tbody id=\"tabla\">
						<TR>
						   <TH>Usuario</TH>
						   <TH>Relatos</TH>
						   <TH>¿Eliminar?</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT id FROM Usuario;"; //Pedimos todos los usuarios
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while($i < $num_rows) {
								$autor = mysql_result($result2, $i, 0);
								$autor = mysql_real_escape_string($autor);
								$autor = htmlspecialchars($autor);
								$sql3 = "SELECT * FROM Relato WHERE autor = '$autor' ;";
								$result3 = mysql_query($sql3, $con);
								$relatos = mysql_num_rows($result3);
								$relatos = htmlspecialchars($relatos);
								//Rellenamos la tabla con enlaces a los usuarios y el numero de relatos
								echo "<TR>
									   <TD><a href=\"user.php?id=$autor\">$autor</a></TD>
									   <TD>$relatos</TD>";
								if (isset ($_SESSION["tipo"]) && ($_SESSION["tipo"] == 2)) 
									echo "<TD><img class=\"basura\" name=\"Usuarios\" src=\"img/basura.png\" alt=\"¿Borrar?\" width=\"22\" height=\"22\"/></TD>";
								else
									echo "<TD>solo un administrador puede eliminar</TD>";
								echo "</TR>";
								$i = $i+1;
							}
						echo "</tbody>
					</table>	
						&nbsp;
							
				</div>";	
				
				
				//Tabla para relatos
				echo "<div class=\"cuadro_tabla\">
					<h2>Relatos</h2>
					<table>
						<TR>
						   <TH>Relato</TH>
						   <TH>Nota media</TH>
						   <TH>¿Eliminar?</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT titulo FROM Relato;";// Pedimos todos los relatos
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while($i < $num_rows) {
								$relato = mysql_result($result2, $i, 0);
								$relato = htmlspecialchars($relato);
								$relato = mysql_real_escape_string($relato);
								//Pedimos la media de las valoraciones de ese relato
								$sql10 = "SELECT AVG(valor) FROM Votaciones WHERE relato = '$relato';";
								$result10 = mysql_query($sql10, $con);
								$voto = mysql_fetch_array($result10);
								$numvotos = mysql_num_rows($result10);
								//Rellenamos la tabla con los datos del relato
								echo "<TR>
										<TD><a href=\"relato.php?id=$relato\">$relato</a></TD>";
								if 	(isset($voto[0]))
									echo "<TD>$voto[0]</TD>";  
								else 
									echo "<TD>0</TD>";
								
								//Sólo se muestra la basura si el usuario es de tipo administrador
								if (isset ($_SESSION["tipo"]) && ($_SESSION["tipo"] == 2)) 
									echo "<TD><img class=\"basura\" name=\"Relatos\" src=\"img/basura.png\" alt=\"¿Borrar?\" width=\"22\" height=\"22\"/></TD>";
								else
									echo "<TD>solo un administrador puede eliminar</TD>";
									
								echo "</TR>";
								$i = $i+1;
							}
					echo "</table>	
						&nbsp;
							
				</div>";	
				
				//Tabla para comentarios
				echo "<div class=\"cuadro_tabla\">
					<h2>Comentarios</h2>
					<table>
						<TR>
						   <TH>Relato</TH>
						   <TH>Autor del comentario</TH>
						   <TH>Comentario</TH>
						   <TH>¿Eliminar?</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT relato, autor, contenido,id FROM Comentario;"; //Pedimos todos los comentarios
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while($i < $num_rows) {
								$relato = mysql_result($result2, $i, 0);
								$autor = mysql_result($result2, $i, 1);
								$contenido = mysql_result($result2, $i, 2);
								$relato = htmlspecialchars($relato);
								$autor = htmlspecialchars($autor);
								$contenido = substr($contenido,0,50).'...';  
								$contenido = htmlspecialchars($contenido);
								$id = mysql_result($result2, $i, 3);
								echo "<TR>
										<TD><a href=\"relato.php?id=$relato\">$relato</a></TD>";	
								echo "<TD><a href=\"user.php?id=$autor\">$autor</a></TD>";  
								echo "<TD>$contenido</TD>";
								
								//Sólo se muestra la basura si el usuario es de tipo administrador
								if (isset ($_SESSION["tipo"]) && ($_SESSION["tipo"] == 2)) 
									echo "<TD><img class=\"basuraR\" name=\"$id\" src=\"img/basura.png\" alt=\"¿Borrar?\" width=\"22\" height=\"22\"/></TD>";
								else
									echo "<TD>solo un administrador puede eliminar</TD>";
									
								echo "</TR>";
								$i = $i+1;
							}
					echo "</table>	
						&nbsp;
				</div>";
				
				//Tabla para colaboracion
				echo "<div class=\"cuadro_tabla\">
					<h2>Colaboraciones</h2>
					<table>
						<TR>
						   <TH>Relato</TH>
						   <TH>Colaborador</TH>
						   <TH>Comentario</TH>
						   <TH>¿Eliminar?</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT relato, user, contenido,id FROM Colaboraciones;";
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while($i < $num_rows) {
								$relato = mysql_result($result2, $i, 0);
								$autor = mysql_result($result2, $i, 1);
								$contenido = mysql_result($result2, $i, 2);
								$relato = htmlspecialchars($relato);
								$autor = htmlspecialchars($autor);
								$contenido = substr($contenido,0,50).'...';  
								$contenido = htmlspecialchars($contenido);
								$id = mysql_result($result2, $i, 3);
								echo "<TR>
										   <TD><a href=\"relato.php?id=$relato\">$relato</a></TD>";	
								echo "<TD><a href=\"user.php?id=$autor\">$autor</a></TD>";  
								echo "<TD>$contenido</TD>";
								
								//Sólo se muestra la basura si el usuario es de tipo administrador
								if (isset ($_SESSION["tipo"]) && ($_SESSION["tipo"] == 2)) 
									echo "<TD><img class=\"basuraC\" name=\"$id\" src=\"img/basura.png\" alt=\"¿Borrar?\" width=\"22\" height=\"22\"/></TD>";
								else
									echo "<TD>solo un administrador puede eliminar</TD>";
									
								echo "</TR>";
								$i = $i+1;
							}
					echo "</table>	
						&nbsp;
				</div>";
				
				
				//Tabla para Actividad Reciente. Montamos tabla a tabla.
				//publicaciones
				echo "<div class=\"cuadro_tabla\">
					<h2>Actividad Reciente</h2>
					<table>
						<TR>
						   <TH>Publicaciones</TH>
						   <TH>Fecha/Hora</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT autor, titulo, fecha FROM Relato ORDER BY fecha DESC;"; //Relatos mas recientes
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while (($i < $num_rows) && ($i < 6)) {
								$autor = mysql_result($result2, $i, 0);
								$titulo = mysql_result($result2, $i, 1);
								$autor = htmlspecialchars($autor);
								$titulo = htmlspecialchars($titulo);
								$fecha = mysql_result($result2, $i, 2);
								echo "<TR>
										<TD><a href=\"user.php?id=$autor\">$autor</a> publico $titulo</TD>
										<TD>$fecha</TD>
									  </TR>";
								$i = $i+1;
							}
					//comentarios
					echo "</table>	
					<table>
						<TR>
						   <TH>Comentarios</TH>
						   <TH>Fecha/Hora</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT autor, relato, fecha FROM Comentario ORDER BY fecha DESC;"; //Comentarios mas recientes
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while (($i < $num_rows) && ($i < 6)) {
								$autor = mysql_result($result2, $i, 0);
								$titulo = mysql_result($result2, $i, 1);
								$autor = htmlspecialchars($autor);
								$titulo = htmlspecialchars($titulo);
								$fecha = mysql_result($result2, $i, 2);
								echo "<TR>
										   <TD><a href=\"user.php?id=$autor\">$autor</a> comento $titulo</TD>
										   <TD>$fecha</TD>
										</TR>";
								$i = $i+1;
							}
					//colaboraciones
					echo "</table>
					<table>
						<TR>
						   <TH>Colaboraciones</TH>
						   <TH>Fecha/Hora</TH>
						</TR>";
							$i = 0;
							$sql2 = "SELECT user, relato, fecha FROM Colaboraciones ORDER BY fecha DESC;"; //Colaboraciones mas recientes
							$result2 = mysql_query($sql2, $con);
							$num_rows = mysql_num_rows($result2);
							while (($i < $num_rows) && ($i < 6)) {
								$autor = mysql_result($result2, $i, 0);
								$titulo = mysql_result($result2, $i, 1);
								$autor = htmlspecialchars($autor);
								$titulo = htmlspecialchars($titulo);
								$fecha = mysql_result($result2, $i, 2);
								echo "<TR>
										   <TD><a href=\"user.php?id=$autor\">$autor</a> colaboro en $titulo</TD>
										   <TD>$fecha</TD>
										</TR>";
								$i = $i+1;
							}
					echo"</table>
							&nbsp;				
				</div>	

				<div style=\"clear: both;\">&nbsp;</div>
			
			</div>";
			// end #content
		
			require('lateral.inc.php');
				
			echo "<div style=\"clear: both;\">&nbsp;</div>
				</div>
			</div>
		</div>";
		// end #page

		require('pie.inc.php'); 
	
	} else //Si el usuario no es administrador se le muestra un mensaje de acceso prohibido.
		echo "<p class='accesoRestringido'>ACCESO PROHIBIDO. NO SE PERMITE LA VISUALIZACION DE ESTA PAGINA A USUARIOS QUE NO DESEMPEÑEN EL ROL DE ADMINISTRADOR</p>";
?>