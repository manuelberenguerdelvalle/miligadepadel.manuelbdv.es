// JavaScript Document
formulario = new formularioIndex();
$(document).ready(function(){
	localizacion_ligas();
	/*$.post("index/reproductor.php", { elegido: 1 }, function(data){
		$("#reproductor").html(data);
	});	*/
	/*$.post("index/recuperarpass.php", { elegido: 1 }, function(data){
		$("#recuperarpass").html(data);
	});	*/
	$.post("index/canales.php", { elegido: 2 }, function(data){
		$("#canales").html(data);
	});	

	$(function(){
	 $("#btn_enviar").click(function(){
		 if(formulario.obtenTotal()){
			//var url = "v/valida_usuario.php"; // El script a dónde se realizará la petición.
			var url = "v/v/valida_usuario.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //$("#respuesta").html(data); // Mostrar la respuestas del script PHP.
						//$("#form").attr("target", "NewAction");
					   
					   if(data == 1){//si no queremos redireccionar cambiar las condiciones por el comentado
					   		if(document.formulario.tipo[0].checked){//Administrador
								$("#formulario").attr("action", "usuario/inicio/inicio.php");
							}
							else if(document.formulario.tipo[1].checked){//Jugador
								//alert("El segundo valor está seleccionado");
								$("#formulario").attr("action", "jugador/inicio/inicio.php");
							}
							else{//Publicidad
								$("#formulario").attr("action", "usuario_publi/inicio/inicio.php");
							}
							$("#formulario").submit();
					   }else{
							 var texto = 'Error en el inicio de sesi&oacute;n';
							 $("#respuesta").html(texto); // Mostrar la respuestas del script PHP.
					   }
				   }
				 });
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});

});//fin readey
function recuperar_pass(){
		var tipo = 0;
		if(document.getElementById('radio1').checked){tipo = 0;}
		else if(document.getElementById('radio2').checked){tipo = 1;}
		else if(document.getElementById('radio3').checked){tipo = 2;}
		else{}
		var datos = 'tipo='+tipo;
		swal({
				title: "Recuperar Password.",
				text: "Por favor, introduce el e-mail de registro: ",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: false,
				animation: "slide-from-top"
				}, 
				function(inputValue){
				if (inputValue === false){return false;}
				if (inputValue === "") {
					swal.showInputError("Por favor, es necesario introducir texto.");
					return false;
				}
					datos += '&texto='+inputValue;
					//alert(datos);
					$.ajax({
						type:"POST",
						url:"v/v/recuperar_pass.php",
						data:datos,
						success: function(data){
							//alert(data);
							if(data == '1'){
								swal("Em@il de recuperacion enviado correctamente.", "Por favor, revise la bandeja de su em@ail.", "success");
								/*swal({   
									title: "Em@il de recuperacion enviado correctamente.",   
									text: "Por favor, revise la bandeja de su em@ail.",   
									type: "success",   
									showCancelButton: false,   
									confirmButtonColor: "#69fb5a",   
									confirmButtonText: "OK",   
									closeOnConfirm: false }, 
									function(){   
										setTimeout ("window.location.reload();", 1);
								});*/
								//setTimeout ("window.location.reload();", 3000);
							}
							else{
								swal("Error de verificacion", "El em@il introducido no esta registrado.", "error");
								/*swal({   
									title: "Error de verificacion.",   
									text: "El em@il introducido no es correcto o no esta registrado.",   
									type: "error",   
									showCancelButton: false,   
									confirmButtonColor: "#69fb5a",   
									confirmButtonText: "OK",   
									closeOnConfirm: false }, 
									function(){   
										setTimeout ("window.location.reload();", 1);
								});*/
							}
						}
					});//fin ajax
		});//fin swal		
}
