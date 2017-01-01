<?php require('cabecera.inc.php'); ?>
<?php 
	$ok=true;
	$cambio= false;
	
	if (isset($_POST['newpass'])){ //Si el usuario ha introducido una nueva contraseña
		if ($_POST['newpass'] == $_POST['newpass2']) { //Y la contraseña esta bien introducida
			
			$user= $_SESSION['user'];
			$sql="SELECT pass FROM Usuario WHERE ID='$user';"; //Pido la contraseña actual de ese usuario
			$result = mysql_query($sql, $con);
			$row = mysql_fetch_array($result);
			$pass= sha1($_POST['pass'].'Ready85674Jessica'); //Codifico la contraseña actual que ha introducido
	
			if ($row[0] == $pass) { //Si coincide (ha introducido bien la contraseña actual)
				$newpass= sha1($_POST['newpass'].'Ready85674Jessica'); //Codifico la nueva contraseña
				$sql= "UPDATE Usuario SET  pass =  '$newpass' WHERE ID = '$user' ;" ; //La actualizo en la BD
				$result = mysql_query($sql, $con);
				$cambio = true; //ha cambiado la contraseña
			} else 
				$ok= false;
		} else
			$ok= false;
	}
?>

	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
		<div class="post">
			<form id="form1" name="form1" method="post" action="cambiar_pass.php">
				<p>&nbsp;</p>
				<div class="recogeDato">
				
				<?php 
					if ($ok == false) //Si ha habido algun problema
						echo "<p>Compruebe que las contraseñas estan bien escritas</p>"; 
				
					
					if ($cambio == false) // Si todavia no se ha hecho el cambio
						echo "<table>
							<tr><td>Contraseña vieja</td><td><input class=\":required\" type=\"password\" name=\"pass\" width=\"20\"></td></tr>
							<tr><td>Contraseña nueva</td><td><input id=\"newpass\" class=\":required\" type=\"password\" name=\"newpass\" width=\"20\"></td></tr>
							<tr><td>Contraseña nueva</td><td><input class=\":same_as;newpass\" type=\"password\" name=\"newpass2\" width=\"20\"></td></tr>
						</table>
						<button type=\"submit\" name=\"form\" value=\"cambio\">Enviar</button>";
					else { //Si ya se ha hecho el cambio
						echo "&nbsp;";
						echo "¡¡¡SE HA REALIZADO EL CAMBIO CORRECTAMENTE!!!";
						echo "&nbsp;";
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