// JavaScript Document
function enviar_email_jugador(id_inscripcion){
		var datos = 'id_inscripcion='+id_inscripcion+'&modo=email';
		//alert(id_inscripcion);
		swal({
				title: "Enviar Em@il.",
				text: "Por favor, Introduce el Texto para el Equipo: ",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: true,
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
						url:"actualiza_inscripcion.php",
						data:datos,
						success: function(data){
							//alert(data);
							if(data == '1'){
								swal("Error al enviar el e-m@il", "Por favor, inténtelo de nuevo, gracias.", "error");
							}
							else{
								swal("Correo electrónico enviado correctamente.", "El email ha sido enviado correctamente al equipo seleccionado.", "success");
							}
						}
					});//fin ajax
		});//fin swal		
}
function solicitar_eliminacion(id_inscripcion){
		var datos = 'id_inscripcion='+id_inscripcion+'&modo=email_eliminacion';
		//alert(id_inscripcion);
		swal({
				title: "Solicitar eliminación.",
				text: "Por seguridad, tiene que solicitar la eliminación de una inscripción con pago realizado online, contactaremos con los jugadores para su verificación.",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: true,
				animation: "slide-from-top"
				}, 
				function(inputValue){
				if (inputValue === false){return false;}
				if (inputValue === "") {
					swal.showInputError("Por favor, es necesario introducir el motivo.");
					return false;
				}
					datos += '&texto='+inputValue;
					//alert(datos);
					$.ajax({
						type:"POST",
						url:"actualiza_inscripcion.php",
						data:datos,
						success: function(data){
							//alert(data);
							if(data == '1'){
								swal("Error al realizar la solicitud", "Por favor, inténtelo de nuevo, gracias.", "error");
							}
							else{
								swal("Solicitud enviada correctamente.", "La solicitud ha sido enviada correctamente para su verificación.", "success");
							}
						}
					});//fin ajax
		});//fin swal		
}
function eliminar_inscripcion(id_inscripcion){
	//alert(id_inscripcion);
	var datos = 'id_inscripcion='+id_inscripcion+'&modo=eliminacion';
		swal({
				title: "Eliminar Inscripción.",
				text: "Por favor, Introduce el motivo de la elimación de la inscripcion.",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: true,
				animation: "slide-from-top"
				}, 
				function(inputValue){
				if (inputValue === false){return false;}
				if (inputValue === "") {
					swal.showInputError("Por favor, es necesario introducir texto.");
					return false;
				}
					datos += '&motivo='+inputValue;
					//alert(datos);
					$.ajax({
						type:"POST",
						url:"actualiza_inscripcion.php",
						data:datos,
						success: function(data){
							//alert(data);
							if(data == '0'){
								setTimeout ("window.location.reload();", 0);
							}
							else{
								swal("Error al anular la inscripción", "Por favor, inténtelo de nuevo, gracias.", "error");
							}
						}
					});//fin ajax
		});//fin swal	
			
}

/*function eliminar_inscripcion_tarjeta(id_inscripcion){
		var datos = 'id_inscripcion='+id_inscripcion+'&modo=eliminacion_online';
		//alert(id_inscripcion);
		swal({
				title: "Eliminar Inscripción.",
				text: "El pago de esta inscripción se realizó online a través de PayPal, es necesario acceder a PayPal con su cuenta, seleccionar el pago recibido y pulsar 'emitir reembolso' para realizar la devolución, y obtendrá un código de transacción que debe insertarlo a continuación:",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: true,
				animation: "slide-from-top"
				}, 
				function(inputValue){
				if (inputValue === false){return false;}
				if (inputValue === "") {
					swal.showInputError("Por favor, es necesario introducir la transacción.");
					return false;
				}
				
					datos += '&texto='+inputValue;
					//alert(datos);
					$.ajax({
						type:"POST",
						url:"actualiza_inscripcion.php",
						data:datos,
						success: function(data){
							//alert(data);
							//swal(data);
							if(data == '0'){
								setTimeout ("window.location.reload();", 0);
								//swal("La inscripción ha sido anulada correctamente.", "Los jugadores recibirán notificación por e-mail.", "success");
							}
							else{
								swal("Error al anular la inscripción", "Por favor, inténtelo de nuevo, gracias.", "error");
							}
						}
					});//fin ajax

		});//fin swal	
			
}
*/
function pagar_inscripcion(id_inscripcion){
		var datos = 'id_inscripcion='+id_inscripcion+'&modo=marcar_pagado';
		//alert(id_inscripcion);
		swal({   
			title: "Marcar Inscripción como pagada.",   
			text: "Se va a proceder a marcar esta inscripción como pagada.",   
			type: "warning",   showCancelButton: true,   
			confirmButtonColor: "#55dd85",   
			confirmButtonText: "Aceptar",   
			cancelButtonText: "Cancelar",   
			closeOnConfirm: true,   
			closeOnCancel: false }, 
			function(isConfirm){   
				if (isConfirm) {
					var url = "actualiza_inscripcion.php"; // El script a dónde se realizará la petición.
					//var dataString = 'id_usuario='+id_usuario+'&id_liga='+id_liga;
					//alert(dataString);
					$.ajax({
						   type: "POST",
						   url: url,
						   data: datos, // Adjuntar los campos del formulario enviado.
						   success: function(data)
						   {
							   //swal("Arbitro Eliminado", "El arbitro se ha eliminado correctamente.", "success");
							   if(data == '0'){
									setTimeout ("window.location.reload();", 0);
									//swal("La inscripción ha sido anulada correctamente.", "Los jugadores recibirán notificación por e-mail.", "success");
								}
								else{
									swal("Error al anular la inscripción", "Por favor, inténtelo de nuevo, gracias.", "error");
								}
						   }
					});//fin ajax    
			   	} 
			   	else {     
					swal("Cancelado", "Inscripción no marcada como pagada.", "error"); 
				}//fin function
		});//fin swal		
}