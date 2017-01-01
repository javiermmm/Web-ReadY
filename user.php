<?php require('cabecera.inc.php'); ?>

<?php
	function solicita($mdb2) {
		$st = $mdb2->prepare('SELECT * FROM Usuario WHERE id = ?;',
				array('text'));
		db_die_if_error($st);
		$result = $st->execute(array($_GET['id']));
		db_die_if_error($result);
		return $result->fetchRow(MDB2_FETCHMODE_ASSOC);         
	}

	//nos guardamos los datos del usuario que estamos viendo
	$login = $_GET['id'];
	$login= mysql_real_escape_string($login);
	$result = mysql_query("SELECT * FROM Usuario WHERE id = '$login' ;", $con);
	$row = mysql_fetch_array($result);

	//Contamos cuantos relatos tiene el usuario
	$sql2="SELECT * FROM Relato WHERE autor = '$login' ;";
	$result2 = mysql_query($sql2, $con);
	$num_rows = mysql_num_rows($result2);
	
	//Pedimos los otros usuarios con los que ha colaborado el usuario
	$sql3="SELECT DISTINCT autor FROM Relato WHERE titulo IN (SELECT DISTINCT relato FROM Colaboraciones WHERE user = '$login');";
	$result3 = mysql_query($sql3, $con);
	$row2 = mysql_fetch_array($result3);

	//Calculamos la media de puntuacion que tiene este usuario
	$sql4="SELECT AVG(valor) FROM Votaciones WHERE relato IN (SELECT titulo FROM Relato WHERE autor = '$login');";
	$result4 = mysql_query($sql4, $con);
	$puntuacion = mysql_fetch_array($result4);
?>

	<script>
		id_relato = <?php echo "'$login';" ?> 
		id_user = <?php echo "'$usuario';" ?>
		
		$(function() {
			$(".cambiaImagen").hide();
		})
		
		$(function() {
			$(".desplegaImagen").css("cursor", "pointer").click(function() {
				$(".cambiaImagen").show("slow");
			})
		})

		$(function() {
			$(".enviaImagen").click(function() {
				$(".cambiaImagen").hide("slow");
			})
		})
	</script>

	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
			<div class="post">
				<h2 class="title"><?php $x = htmlspecialchars($row[0]);	  echo $x  ?></h2>
				<div class="entry">
					<?php 
						$imagen = "data/$x.jpg";
						if ( ! file_exists($imagen))
							$imagen = "img/usuario.png";
					?>
					<table>
						<tbody id="tabla">
							<TR>
							   <TD><img class=fotousuario src=<?php echo "$imagen"; ?> alt="Foto usuario" width="175" height="175"/></TD>
							   <TD>
									<p class="infouser">Informacion de usuario    </p>
									<p>Nombre: <?php echo $x  ?></p>
									<p>Fecha de registro: <?php echo $row[4]  ?></p>
									<p>Numero de relatos publicados: <?php echo $num_rows ?></p>
									<p>Puntuacion como escritor: 
									<?php 
										//Si hay puntuacion que mostrar, la mostramos, y si no mostramos un cero
										if (isset($puntuacion[0])){
											echo $puntuacion[0]; 
											echo "</p>";
										} else
											echo "0";
										
										//mostramos los nombres de los usuarios con los que ha colaborado el usuario que vemos
										$num_rows=mysql_num_rows($result3);
										echo "<p>Colaboraciones: $num_rows</p>";
										echo "<ul>";
										$i = 0;
										while($i < $num_rows) {
											$x = mysql_result($result3, $i, 0);
											$x = htmlspecialchars($x);
											$i= $i+1;
											echo "\t\t<li><a href='user.php?id=$x'>$x</a></li>\n";
										}
										
										echo "</ul>";
									?>
							   </TD>
							</TR>
					</table>		
				</div>
				
				<?php
					//si el usuario esta logueado
					if (isset($_SESSION["user"]))
					
						//si el usuario esta viendo su propia pagina de usuario o es un administrador
						if (($_SESSION["user"] == $login) || ($_SESSION["tipo"] == 2)) {
					
							//Montsamos el formulario para cambiar la imagen
							echo "<h1>&nbsp;</h1>
							<h4><a class=\"cambiapass\" href=\"cambiar_pass.php\">Cambiar Contraseña</a></h4>
							<h1>&nbsp;</h1>";
						
							echo"<h4 class='desplegaImagen'>Cambia aquí la imagen de tu perfil.</h4>
							<h1>&nbsp;</h1>";
							
							echo "<div class='cambiaImagen'>
									<p>Seleccione su nueva imagen.</p>
									<form method='post' action='modifyImagen.php?id=$login' enctype='multipart/form-data'>

									<input name='im' type='file' /><br><br>

									<input class='enviaImagen' name='enviar' type='submit' value='Cambiar Imagen' />
									<input name='limpiar' type='reset' value='Limpiar' />
									</form></center>
								</div>";
						}
				?>

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
