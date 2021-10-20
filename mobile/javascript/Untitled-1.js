// JavaScript Document
$(document).ready(function() {
	$("#boton").click(function(event) {
		$("#capa").load("/demos/2013/03-jquery-load03.php",{valor1:'primer valor', valor2:'segundo valor'}, function(response, status, xhr) {
			if (status == "error") {
				var msg = "Error!, algo ha sucedido: ";
				$("#capa").html(msg + xhr.status + " " + xhr.statusText);
			}
		});
	});
});			