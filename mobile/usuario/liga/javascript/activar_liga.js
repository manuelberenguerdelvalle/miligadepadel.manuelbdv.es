// JavaScript Document
function activar(liga){
	if(liga > 0){
		var url = "activar.php"; // El script a dónde se realizará la petición.
		var dataString = "comprobar_liga="+liga;
		$("#respuesta").html('<img class="cargando" src="../../../images/28.gif">'); // Mostrar la respuestas del script PHP.
		$("#respuesta2").html('Activando, por favor espere...'); // Mostrar la respuestas del script PHP
		$("#btn_enviar").hide();
		$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //$("#formulario").submit();
					   //alert(data);
					   if(data != ''){
							setTimeout ("window.location.reload();", 2000);
					   }
				   }
		});//fin ajax
	}//fin if
}//fin funcion