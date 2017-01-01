<?php require('cabecera.inc.php'); ?>
<?php
	$login = $_GET['id'];
	$login= mysql_real_escape_string($login);
	
	//si el usuario se ha logueado
	if (isset($_SESSION["user"])) {
		$user=$_SESSION["user"];
		$user= mysql_real_escape_string($user);

		//si el usuario ha enviado su colaoracion
		if (isset($_POST["colaboracion"]) ) {
			$colaboracion=$_POST["colaboracion"];
			$colaboracion= mysql_real_escape_string($colaboracion);
			$sql3="INSERT INTO Colaboraciones (user, relato, contenido) VALUES ('$user', '$login','$colaboracion');"; //La metemos en la BD
			$result3 = mysql_query($sql3, $con);
		}
	}
	
	//Pedimos los datos del relato en el que se colabora
	$result = mysql_query("SELECT * FROM Relato WHERE titulo = '$login' ;", $con);
	$row = mysql_fetch_array($result);

	//Pedimos las colaboraciones que tenga ese relato
	$sql2="SELECT * FROM Colaboraciones WHERE relato = '$login' ORDER BY  fecha DESC;";
	$result2 = mysql_query($sql2, $con);
	$num_row=mysql_num_rows($result2);
?>
	
	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
			<div class="post">
				<h2 class="title"><?php $x = htmlspecialchars($row[0]);	  echo $x;  ?></h2>
				<div class="entry">
				<p class="meta">Posted by <?php $x = htmlspecialchars($row[1]); echo "\t\t<li><a href='user.php?id=$x'>$x</a></li>\n"; ?> on <?php echo $row[2]  ?>
					&nbsp;&bull;&nbsp; <a href="relato.php" class="comments">Comments (<?php echo $num_row; ?>)</a> &nbsp;&nbsp;</p>
					<p>
					<?php $x = htmlspecialchars($row[3]);  echo $x;  ?>
					</p>
				</div>
				
				<p>&nbsp;</p>
				<?php
				if (isset($_SESSION["user"])){
					echo
					"<h2>Colaborar</h2>
					<div class=\"writecomment\">
						<form id=\"form1\" name=\"colaboracion\" method=\"post\" action='colaborar.php?id=$login'>
							<p>&nbsp;</p>
							<p>Deja tu opinion</p>
							<p>
								<textarea name=\"colaboracion\" id=\"colaboracion\" cols=\"70\" rows=\"12\">...Texto...</textarea>
							</p>
							<p>&nbsp;</p>
							<p>
								<a><input type=\"submit\" name=\"button\" id=\"button\" value=\"Publicar\" /></a>
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