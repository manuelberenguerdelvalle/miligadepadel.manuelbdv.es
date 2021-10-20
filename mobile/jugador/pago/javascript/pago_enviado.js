// JavaScript Document
function enviar_email_admin(admin,tipo,nombre){
		var datos = 'admin='+admin+'&tipo='+tipo+'&nombre='+nombre;
		//alert(datos);
		swal({
				title: "Enviar Em@il.",
				text: "Por favor, Introduce el Texto para el Administrador: ",
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
						url:"actualiza_ligas.php",
						data:datos,
						success: function(data){
							//alert(data);
							if(data == '1'){
								swal("Error al enviar el e-m@il", "Por favor, inténtelo de nuevo, gracias.", "error")
							}
							else{
								swal("Correo electrónico enviado correctamente.", "El administrador recibirá el correo electrónico enviado.", "success");
							}
						}
					});//fin ajax
		});//fin swal		
}
function eliminar_inscripcion(id_pago_admin){
	//alert(id_inscripcion);
	var datos = 'id_pago_admin='+id_pago_admin;
	//alert (datos);
		swal({
				title: "Eliminación de Inscripción en Liga.",
				text: "Por favor, Introduce el motivo de la anulación de la inscripción.",
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
						url:"eliminar_inscripcion.php",
						data:datos,
						success: function(data){
							//alert(data);
							if(data == '0'){
								setTimeout ("window.location.reload();", 500);
								/*swal({   
									title: "La inscripción ha sido anulada correctamente.",   
									text: "Los jugadores recibirán notificación por e-mail.",   
									type: "success",   
									showCancelButton: false,   
									confirmButtonColor: "#69fb5a",   
									confirmButtonText: "OK",   
									closeOnConfirm: true }, 
									function(){   
										
									});*/
									//swal("La inscripción ha sido anulada correctamente.", "Los jugadores recibirán notificación por e-mail.", "success");
							}
							else{
								swal("Error al anular la inscripción", "Por favor, inténtelo de nuevo, gracias.", "error");
							}//fin else 
							
						}//fin success
					});//fin ajax
		});//fin swal	
}
/*
DE MOMENTO NO ES POSIBLE DEBIDO A QUE CADA CUENTA DE PAYPAL TIENE UN IDENTIFICADOR PARA COMPROBAR LOS PAGOS
function marcar_pagado(id_pago_admin){
		var datos = 'id_pago_admin='+id_pago_admin;
		//alert(id_pago_admin);
		swal({
				title: "Insertar Pago.",
				text: "Si realizaste el pago online de esta inscripción a través de PayPal, es necesario acceder a PayPal con su cuenta, seleccionar el pago enviado, y obtener el código de transacción e insertelo a continuación:",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: true,
				animation: "slide-from-top"
				}, 
				function(inputValue){
				if (inputValue === false){return false;}
				if (inputValue === "") {
					swal.showInputError("Por favor, es necesario la transacción.");
					return false;
				}
				
					datos += '&texto='+inputValue;
					//alert(datos);
					$.ajax({
						type:"POST",
						url:"verificar_pago.php",
						data:datos,
						success: function(data){
							alert(data);
							//swal(data);
							if(data == '0'){
								setTimeout ("window.location.reload();", 0);
								//swal("La inscripción ha sido anulada correctamente.", "Los jugadores recibirán notificación por e-mail.", "success");
							}
							else{
								swal("Error al insertar el pago", "Por favor, inténtelo de nuevo, gracias.", "error");
							}
						}
					});//fin ajax

		});//fin swal		
}*/