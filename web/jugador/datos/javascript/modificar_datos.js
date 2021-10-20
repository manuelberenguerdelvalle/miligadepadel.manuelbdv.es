// JavaScript Document
$(document).ready(function(){
	$(function(){
	 $("#btn_enviar").click(function(){ 
		 //alert(formulario.obtenTotal());
		 if(document.getElementById('antpassword').value == ''){
			 formulario.modificaEstado(4,'null');
			 formulario.modificaEstado(5,'null');
			 formulario.modificaEstado(6,'null');
			 document.getElementById('password').value = '';
			 document.getElementById('repassword').value = '';
		 }
		 if(formulario.obtenTotal()){
			var url = "actualiza_datos.php"; // El script a dónde se realizará la petición.
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
		 }//fin if formulario
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});//fin btn_enviar

});//fin readey


	function eliminar(id_jugador){ 
		 //alert(formulario.obtenTotal());
		 //alert($("#formulario").serialize());
		//alert(id_jugador);
		var dataString = 'id_jugador='+id_jugador;
		var url = "eliminar_jugador.php"; // El script a dónde se realizará la petición.
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