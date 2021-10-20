// JavaScript Document
$(function(){
	$("#btn_enviar").click(function(){
		var texto = document.getElementById('reglas').value;
		var url = "actualiza_regla.php"; // El script a dónde se realizará la petición.
		//var dataString = eliminarInyeccion(texto);
		var dataString = 'reglas='+texto;
		//alert(dataString);
		$.ajax({
			   type: "POST",
			   url: url,
			   data: dataString, // Adjuntar los campos del formulario enviado.
			   success: function(data)
			   {
				   $(respuesta).html(data); // Mostrar la respuestas del script PHP.
				   setTimeout ("window.location.reload();", 3500);
			   }
		});
		return false; // Evitar ejecutar el submit del formulario.
	});
});
