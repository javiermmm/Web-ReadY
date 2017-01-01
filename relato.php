<?php require('cabecera.inc.php');

	// Votos de manitas
	if (isset($_POST['idComentario'])) {
		$a1 = $_POST['idComentario'];
		if ($_POST['polaridad'] == 'mas')
			$sql10="UPDATE Comentario SET positivos=positivos+1  WHERE ID=$a1;";
		else
			$sql10="UPDATE Comentario SET negativos=negativos+1 WHERE ID=$a1;";
		$result10 = mysql_query($sql10, $con);
		exit();
	}
	
	//Votacion del relato
	if (isset($_POST['value'])) {
		$a1 = $_POST['usuario'];
		$a2 = $_POST['id'];
		$a3 = $_POST['value'];
		$a1 = mysql_real_escape_string($a1);
		$a2 = mysql_real_escape_string($a2);
		//comprobamos si ese usuario ya ha comentado ese relato
		$sql10="SELECT * FROM Votaciones WHERE usuario = '$a1' AND relato = '$a2';";
		$result10 = mysql_query($sql10, $con);
		$num_rows = mysql_num_rows($result10);
		
		if($num_rows == 0) //Si no habia votado insertamos el voto
			$sql11= "INSERT INTO Votaciones (relato, usuario, valor) VALUES ('$a2', '$a1', '$a3');";
		else //Si ya habia votado, modificamos su anterior voto
			$sql11= "UPDATE Votaciones SET  valor =  '$a3' WHERE relato = '$a2' AND usuario = '$a1' ;" ;
			
		$result11 = mysql_query($sql11, $con);
		exit();
	}
	
	//Cuando llega el nombre del relato lo recogemos en una variable
	if (isset($_GET['id'])) {
		$login = $_GET['id'];
		$login = mysql_real_escape_string($login);
	}
	
	//Si el usuario esta logueado
	if (isset($_SESSION["user"])) {
		$usuario = $_SESSION["user"];
		$usuario = mysql_real_escape_string($usuario);
		$user=$_SESSION["user"];
		$user = mysql_real_escape_string($user);

		//Si el usuario manda un comentario
		if (isset($_POST["comentarios"])) {
			//lo recogemos y lo insertamos en la BD
			$comentario=$_POST["comentarios"];
			$comentario = mysql_real_escape_string($comentario);
			$sql3="INSERT INTO Comentario (relato,autor,contenido) VALUES ('$login','$user','$comentario');";
			$result3 = mysql_query($sql3, $con);
		}
	}
	
	//Pedimos los datos del relato que se muestra
	$result = mysql_query("SELECT * FROM Relato WHERE titulo = '$login' ;", $con);
	$row = mysql_fetch_array($result);

	//Pedimos los comentarios, ordenados por fecha del relato que estamos viendo
	$sql2="SELECT * FROM Comentario WHERE relato = '$login' ORDER BY  fecha DESC;";
	$result2 = mysql_query($sql2, $con);
	$num_row=mysql_num_rows($result2);
	
	// VALORACION DEL RELATO
	$sql10="SELECT AVG(valor) FROM Votaciones WHERE relato = '$login';";
	$result10 = mysql_query($sql10, $con);
	$voto = mysql_fetch_array($result10);
	$numvotos=mysql_num_rows($result10);
		
		
?>
	<script>
		id_relato = <?php echo "'$login';" ?> 
		id_user = <?php echo "'$usuario';" ?>
		
		$(function() {
			$(".writecomment").hide();
		})
		
		$(function() {
			$(".botonComenta").click(function() {
				$(".writecomment").show("fast");
				$(".botonComenta").hide("fast");
			})
		})

		$(function() {
			$(".enviaComentario").click(function() {
				$(".writecomment").hide("fast");
				$(".botonComenta").show("fast");
			})
		})
	</script>
	
	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
			<div class="post">
				<h2 class="title"><?php $x = htmlspecialchars($row[0]);	  echo $x?></h2>
				<div class="entry">
				<p class="meta">Posted by <?php echo "<a href='user.php?id=$row[1]'>$row[1]</a>"; ?> on <?php echo $row[2]  ?>
					&nbsp;&bull;&nbsp; <a href="#readcomment" class="comments">Comments (<?php echo $num_row; ?>)</a> &nbsp;&nbsp;</p>
					<p>
					<?php 
					$rel = htmlspecialchars($row[3]);	 	 
					echo $rel  ?>
					</p>
					<p>&nbsp;</p>
					<p> Valoracion : 
					
					<?php 
					
					if 	(isset($voto[0]))
						echo $voto[0];  
					else 
						echo "Se el primero en valorar este relato";?> </p>
				</div>
				
				<?php			
					if (isset($_SESSION["user"]) &&($_SESSION["user"] != $row[1])) {
						$x = htmlspecialchars($row[0]);  
						$y = htmlspecialchars($row[1]);
						echo "<u><b><a href=\"colaborar.php?id=$x\">Colabora con $y</a></u></b>"; 
						echo "<p>&nbsp;</p>";		
						echo "<h2>Valora este relato  </h2>
						<div class=\"writevaluation\">
							<form method=\"post\"  id=\"ratings\" >
									Rating: <span id=\"stars-cap\"></span>
									
						<script type=\"text/javascript\">
									</script>

									<div id=\"stars-wrapper2\">
										<select name=\"selrate\" class=\"stars\">
											<option value=\"0.5\">Malo</option>
											<option value=\"1\">Malo</option>
											<option value=\"1.5\">No está mal</option>
											<option value=\"2\">No está mal</option>
											<option value=\"2.5\" selected=\"selected\">Decente</option>
											<option value=\"3\">Decente</option>
											<option value=\"3.5\">Bueno</option>
											<option value=\"4\">Bueno</option>
											<option value=\"4.5\">Genial</option>
											<option value=\"5\">Genial</option>
										</select>
										
									</div>
								</form>
						</div>";
					}
					
					echo "<p>&nbsp;</p>";
						
					$sql4="SELECT * FROM Colaboraciones WHERE relato = '$login' ORDER BY  fecha DESC;";
					$result4 = mysql_query($sql4, $con);
					$num_rows=mysql_num_rows($result4);
					
					$propietario=((isset($_SESSION["user"] ))&& ($_SESSION["user"] == $row[1]));
					$admin = ((isset ($_SESSION["tipo"]) )&& ($_SESSION["tipo"] == 2) ) ;
					$existe= (( isset($num_rows))&&($num_rows>0) ) ;
					if ((($propietario) || ($admin)) && ($existe)) {
						if ($num_rows >0) {
							echo "<h2>Colaboraciones</h2>
							<div class=\"readcomment\">
								<ul>";
							$i=0;
							$num_rows=mysql_num_rows($result4);
							while($i < $num_rows) {
								$relato = mysql_result($result4, $i, 1);
								$autor = mysql_result($result4, $i, 0);
								$fecha = mysql_result($result4, $i, 3);
								$contenido = mysql_result($result4, $i, 2);
								$id = mysql_result($result4, $i, 4);
								$contenido = htmlspecialchars($contenido);
								$autor = htmlspecialchars($autor);
								$relato = htmlspecialchars($relato);
								$contenido = mysql_real_escape_string($contenido);
								echo "<li class=\"comment\">
									<p>$contenido</p>
									<p>Posted by <a href='user.php?id=$autor'>$autor</a> on $fecha</p>
									</li>";
								$i= $i+1;
							}
							echo "</ul>
							</div>";
						}
					}
				?>			
				<?php
					$num_rows=mysql_num_rows($result2);
					if ($num_rows>0) {
						echo "<h2>Comentarios</h2>
						<div class=\"readcomment\">
						<ul>";
						$i=0;
						
						while($i < $num_rows) {
							$relato = mysql_result($result2, $i, 0);
							$autor = mysql_result($result2, $i, 1);
							$fecha = mysql_result($result2, $i, 2);
							$id = mysql_result($result2, $i, 4);
							$contenido = mysql_result($result2, $i, 3);
							$positivos = mysql_result($result2, $i, 5);
							$negativos = mysql_result($result2, $i, 6);
							$contenido = htmlspecialchars($contenido);
							$autor = htmlspecialchars($autor);
							$relato = htmlspecialchars($relato);
							$contenido = mysql_real_escape_string($contenido);
							echo "<li class=\"comment\">
								<p>$contenido</p>
								<p>Posted by <a href='user.php?id=$autor'>$autor</a> on $fecha</p>
								<p>";
								if (isset ($_SESSION["user"])) {
									echo "<img class=\"valoracionMas\" name=\"$id\" src=\"img/positivo.png\" alt=\"valoracion\"/>
									<img class=\"valoracionMenos\" name=\"$id\" src=\"img/negativo.png\" alt=\"valoracion\"/>";
									echo"<p>Valoracion total: $positivos buenas y $negativos malas</p>";
								}
								echo "</p>
							</li>";
							$i= $i+1;
						}
						echo "</ul>
						</div>";
					}
				?>		
				
				
				<p>&nbsp;</p>
				<?php
					if (isset($_SESSION["user"])) {
						echo
						"<input type=submit class='botonComenta' value='Comenta este relato'/>
						<p>&nbsp;</p>
						<div class=\"writecomment\">
							<h2>Comentar</h2>
							<form id=\"form1\" name=\"comentar\" method=\"post\" action='relato.php?id=$login'>
								<p>&nbsp;</p>
								<p>Deja tu opinion</p>
								<p>
									<textarea name=\"comentarios\" id=\"comentarios\" cols=\"70\" rows=\"12\">...Texto...</textarea>
								</p>
								<p>&nbsp;</p>
								<p>
									<a href=\"mailto:readyrelatos@gmail.com\"><input type=\"submit\" class='enviaComentario' name=\"button\" id=\"button\" value=\"Enviar comentario\" /></a>
								</p>
							</form>
						</div>";
					}
				?>
				
				<p>&nbsp;</p>
				
			</div>

			<div style="clear: both;">&nbsp;</div>
		
		</div>
		<!-- end #content -->
		
		<?php require('lateral.inc.php'); ?>
		
		<div style="clear: both;">&nbsp;</div>
	</div>
	</div>
	</div>
	<!-- end #page -->

<?php require('pie.inc.php'); ?>	
