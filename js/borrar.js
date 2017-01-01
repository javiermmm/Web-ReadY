$(function(){
	$("img.basura")
	.css("cursor", "pointer")
	.click(function(){
		var n = this.name;
	   var tr = $(this).closest("tr");
	   var ob = tr.children().first().text();
	  if (confirm("borrando " + tr.children().first().text()))
	  {
		  tr.remove();
		  if (n=="Usuarios"){
			$.post("administrador.php", {Usuarios: ob});
			location.href="administrador.php";
		}
		  else if (n=="Relatos"){
			$.post("administrador.php", {Relatos: ob});
			location.href="administrador.php";
			}
		  
		
		}
	});
});

$(function(){
	$("img.basuraR")
	.css("cursor", "pointer")
	.click(function(){
		var n = this.name;
		var d = "comentario";
		 var tr = $(this).closest("tr");
	    if (confirm("El Comentario se eliminara definitivamente de la pagina \n"))
	  {
				tr.remove();	  
				$.post("administrador.php", {Donde: d, Cual: n});
					
		
		}
	});
});

$(function(){
	$("img.basuraC")
	.css("cursor", "pointer")
	.click(function(){
		var n = this.name;
		var d = "colaboracion";
		var tr = $(this).closest("tr");
	    if (confirm("La Colaboracion se eliminara definitivamente de la pagina \n"))
	  {
		 tr.remove();	  
				$.post("administrador.php", {Donde: d, Cual: n});
					
		
		}
	});
});