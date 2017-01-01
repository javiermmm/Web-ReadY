	$(function() {
		$("#stars-wrapper2").stars({
			inputType: "select",
			split: 2,
			oneVoteOnly: true,
			
			callback: function() { 
			   $.post("relato.php", {id: id_relato, value: $(".ui-stars-star-on").length/2, usuario: id_user});
			   var pagina="relato.php?id="+id_relato;
			   location.href=pagina;
} 		});
	});
