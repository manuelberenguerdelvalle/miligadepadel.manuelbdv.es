// JavaScript Document
function enviar_email_admin(admin,tipo,nombre){
		var datos = 'admin='+admin+'&tipo='+tipo+'&nombre='+nombre;
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

function enviar_email_admin2(equipo,tipo,bd,nom_liga,num_division){
		var datos = 'equipo='+equipo+'&tipo='+tipo+'&bd='+bd+'&nom_liga='+nom_liga+'&num_division='+num_division;
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
