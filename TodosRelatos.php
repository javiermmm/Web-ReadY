<?php require('cabecera.inc.php'); ?>
<?php
	//Pedimos todos los relato de la BD
	$sql2="SELECT * FROM Relato;";
	
	//Establecemos el numero de relatos que se van a ver por pagina
	$intervalo = 3;
	
	//Ejecutamos la SQL
	$result2 = mysql_query($sql2, $con);
	$num_rows = mysql_num_rows($result2);
	
	//Calculamos el numero de paginas que va a haber segun los relatos existentes y el intervalo en que se muestran
	$num_paginas = $num_rows / $intervalo;
?>

	<div id="page">
	<div id="page-bgtop">
	<div id="page-bgbtm">
		<div id="content">
<?php

	//Si me llega una pagina que ver
	if (isset ($_GET['pag'])) {
		$pag_actual = $_GET['pag']; //me quedo la pagina actual
		$cota_inf = ($pag_actual * $intervalo) - $intervalo; //calculo la primera fila de la tabla de la BD que quiero mostrar en esta pagina
		//Pido las intervalo filas de la tabla relato, a partir de la fila cota_inf. El primer resultado que se da es la fila cota_inf+1.
		$sql="SELECT * FROM Relato ORDER BY fecha DESC LIMIT $cota_inf,$intervalo;";
		$result = mysql_query($sql, $con);
		$i=0;
		$num_rows=mysql_num_rows($result);
		while($i < $num_rows) {  //Para cada fila (relato) devuelto
			//nos quedamos sus datos
			$titulo = mysql_result($result, $i, 0);
			$autor = mysql_result($result, $i, 1);
			$fecha = mysql_result($result, $i, 2);
			$contenido = mysql_result($result, $i, 3);
			$contenido = htmlspecialchars($contenido);
			$autor = htmlspecialchars($autor);
			$titulo = htmlspecialchars($titulo);
			$titulo = mysql_real_escape_string($titulo);
			//Contamos los comentarios que tiene
			$sql3="SELECT * FROM Comentario WHERE relato = '$titulo' ;";
			$result3 = mysql_query($sql3, $con);
			$comentarios = mysql_num_rows($result3);
			 
			//Mostramos el relato
			echo "<div class=\"post\">
						<h2 class=\"title\"><a href='relato.php?id=$titulo'>$titulo</a>  </h2>
						<div class=\"entry\">
							<p class=\"meta\">Posted by <a href='user.php?id=$autor'>$autor</a> on $fecha
							&nbsp;&bull;&nbsp; Comments $comentarios </a> &nbsp;&nbsp;</p>
							<p>
							$contenido	
							</p>
						</div>
					</div>";
					
			$i= $i+1;
		}
	}
	
	//Con esta parte ponemos el link a la pagina anterior
	echo "<div class=\"postLinks\">";
	echo "<table class='LinksTable'><tr>";
	if ($pag_actual > 1) {
		$pag_actual = $pag_actual-1;
		echo "<td class='navLink'><a href='TodosRelatos.php?pag=$pag_actual'>Anterior</a></td>";
		$pag_actual = $pag_actual+1;
	}
	
	//Asi vamos mostrando los enlaces a los numeros de pagina de los relatos
	for ($j=0; $j<$num_paginas; $j++) {
		$numPag = $j;
		$numPag++;
		echo "<td><a href='TodosRelatos.php?pag=$numPag'>$numPag</a></td>";
	}
	
	//Con esta parte ponemos el link a la pagina siguiente
	if ($pag_actual < $num_paginas){
		$pag_actual = $pag_actual+1;
		echo "<td class='navLink'><a href='TodosRelatos.php?pag=$pag_actual'>Siguiente</a></td>";
	}
	
	echo "</tr></table>";
	echo "</div>";
?>			
			
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