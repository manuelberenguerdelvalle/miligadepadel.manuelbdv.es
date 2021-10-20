// JavaScript Document
formulario = new formularioGeneralQuince("null","null","null","null","null","null","null","null","null","null","null","null","null","null","null");
$(document).ready(function(){
	//comprueba(true);
	$(function(){
	 $("#btn_enviar").click(function(){
		 //alert('hola');
		 //comprueba(false);
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			var url = "actualiza_puntuacion.php"; // El script a dónde se realizará la petición.
			var dataString = $("#formulario").serialize();
			//alert(dataString);
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //$("#formulario").submit();
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 2000);
				   }
				 });
		 }//fin obten_total
		return false; // Evitar ejecutar el submit del formulario.
	 });//fin btn_enviar
	});//fin function
});//FIN DEL READY





