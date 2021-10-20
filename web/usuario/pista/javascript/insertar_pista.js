// JavaScript Document
//reutilizo el formulario, solo sirven los tres primeros campos
formulario = new formularioDivision("error","null","null","null","null","null","null","null");
$(document).ready(function(){
	$(function(){
	 $("#btn_enviar").click(function(){
		 var frm = document.getElementById('formulario');
		 regula_nulos(frm,1,3);
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			var url = "crear_pista.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 3000);
				   }
				 });
		 }//fin if formulario
		 else{
			error_respuesta('respuesta','Por favor revise el formulario.');
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
});