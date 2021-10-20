// JavaScript Document//se utiliza 14 de 15, el 15 = 'null'
formulario = new formularioGeneralQuince('null','null','null','null','null','null','null','null','null','null','null','null','null','null','null');
$(document).ready(function(){
	localizacion();
	$(function(){
	 $("#btn_enviar").click(function(){ 
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			 //alert($("#formulario").serialize());
			var url = "actualiza_cuenta.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
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
	});//fin btn_enviar
	
	
	
});

	function eliminar(id_usuario){ 
		 //alert(formulario.obtenTotal());
		 //alert($("#formulario").serialize());
		 //alert(id_usuario);
		var dataString = 'id_usuario='+id_usuario;
		var url = "eliminar_cuenta.php"; // El script a dónde se realizará la petición.
		$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //alert(data);
						   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					       setTimeout ("document.location.href='http://miligadepadel.manuelbdv.es';", 5000);			   
				   }
		});
	}//fin eliminar