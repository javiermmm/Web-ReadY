$(function(){
	$("img.valoracionMas")
	.css("cursor", "pointer")
	.click(function(){
		var n = this.name;  	   
		var pol = "mas";
		var pagina="relato.php?id="+id_relato;
	  if (confirm("Esta realizando un voto positivo " ))
	  {
			$.post("relato.php", {idComentario: n, polaridad:pol, idRelato:id_relato});
			location.href=pagina;
		}
	});
});

$(function(){
	$("img.valoracionMenos")
	.css("cursor", "pointer")
	.click(function(){
		var n = this.name;  	   
		var pol = "menos";
		var pagina="relato.php?id="+id_relato;
		
	  if (confirm("Esta realizando un voto negativo"))
	  {
			$.post("relato.php", {idComentario: n, polaridad:pol, idRelato:id_relato});
			location.href=pagina;
		}
	});
});
