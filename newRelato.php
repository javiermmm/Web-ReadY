<?php 

require('cabecera.inc.php'); 

$error=false;
$contenido = "...TEXT...";

//Si el usuario ha mandado el relato
if (isset($_POST['titulo'])){
	
	//Nos quedamos los datos
	$titulo = $_POST['titulo'];
	$contenido = $_POST['contenido'];
	$autor = $_SESSION["user"];

	//Pedimos los datos del relato a la BD
	$sql="SELECT * FROM Relato WHERE titulo='$titulo';";
	$result = mysql_query($sql, $con);
	$num_rows = mysql_num_rows($result);
	
	//Si ese relato no esta, se inserta.
	if ($num_rows == 0){
		$sql="INSERT INTO Relato (titulo,autor,contenido) VALUES ('$titulo','$autor','$contenido');";
		//Si va todo bien redirigimos
		if (mysql_query($sql,$con)) {
			header("Location: index.php");
		}
		//y si no damos error
		else
			die('Error: ' . mysql_error());		
	}
	else { //Si ya existe un relato con ese nombre se notifica
			$error=true;
			$mensaje="Selecciona otro titulo para tu relato.";
	}
}
?>

<div id="page">
	<div id="page-bgtop">
		<div id="page-bgbtm">
			<div id="content">
				<div class="post">
					<form method="post">
						<p>&nbsp;</p>
						<div class="recogeDato">
							<table>
								<tr>
									<td>Titulo: </td><td><input class=":required"type="text"name="titulo"size="30"></td>
								</tr>
								
								<tr>
									<td>Contenido</td><td><textarea name="contenido" id="contenido" cols="60" rows="12"><?php echo $contenido; ?></textarea></td>
								</tr>
								<tr>
									<td><input type="submit"></td>
								</tr>
								<?php
									if ($error) {
										echo "<tr>";
											echo "<td>" . $mensaje . "</td>";
										echo "</tr>";
									} 
								?>
							</table>
						</div>
					</form>
				</div>
					
				<div style="clear: both;">&nbsp;</div>
				
			</div>
			
			<?php require('lateral.inc.php'); ?>
			<div style="clear: both;">&nbsp;</div>
			
		</div>
	</div>
</div>


<?php require('pie.inc.php'); ?>	
