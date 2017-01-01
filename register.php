<?php require('cabecera.inc.php'); ?>

	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
		<div class="post">
			<form method="post" action="insertar.php">
				<p>&nbsp;</p>
				<div class="recogeDato">
					<table>
						<tr>
							<td>Nombre de Usuario: </td><td><input id="username" class=":required"type="text"name="user"size="8"maxlength="16"></td>
						</tr>
						
						<tr>
							<td>Contraseña</td><td><input id="pass" class=":required"type="password"name="pass"size="8"></td>
						</tr>
						
						<tr>
							<td>Repite Contraseña</td><td><input id="pass2" class=":same_as;pass"type="password"name="pass2"size="8"></td>
						</tr>
						
						<tr>
							<td>Correo electronico:  </td><td><input id="mail" class=":email :required"type="text"name="email"size="8"></td>
						</tr>
						
						<tr>
							<td><input type="submit"></td>
						</tr>
					</table>
					<?php
						//Si tratas de registrarte con un nombre de usuario que ya existe se notifica
						if (isset ($_GET['exito'])) {
							echo "<p class='YaRegistrado'>Ese usuario ya esta registrado. Intentalo de nuevo<p>";
						}
					?>
				</div>
			</form>
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