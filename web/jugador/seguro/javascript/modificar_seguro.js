// JavaScript Document
$(document).ready(function(){	
	$(function(){
	 $("#btn_enviar").click(function(){
		 var fecha = document.getElementById('datepicker').value;
		 if(fecha == ''){
			formulario.modificaEstado(3,'error');
		 	mostrar('#datepickerCom');
		 }
		 else{
			 formulario.modificaEstado(3,'ok');
			 ocultar('#datepickerCom');
		 }
		 if(formulario.obtenTotal()){
			var url = "actualiza_seguro.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: $("#form").serialize(), // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //$("#formulario").submit();
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 2000);
				   }
				 });
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
});
