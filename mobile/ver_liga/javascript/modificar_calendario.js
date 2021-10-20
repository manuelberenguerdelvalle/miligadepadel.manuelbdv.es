// JavaScript Document
formulario = new formularioLiga("error","error","null","null","null");
function enviar(id_partido){
	var form_res_local = document.getElementById('form_res_local'+id_partido);
	var form_res_visit = document.getElementById('form_res_visit'+id_partido);
	var form_sup = document.getElementById('form_sup'+id_partido);
	var form_inf = document.getElementById('form_inf'+id_partido);
	var dataString = 'id_partido='+id_partido;
	dataString += '&'+ $(form_res_local).serialize();
	dataString += '&'+ $(form_res_visit).serialize();
	
	var email = document.getElementById("email"+id_partido).value;
	var password = document.getElementById("password"+id_partido).value;
	if(email != '' && email.indexOf("@") != -1){
		formulario.modificaEstado(0,'ok');
		dataString += '&email='+email;
	}
	else{
		formulario.modificaEstado(0,'error');
	}
	
	if(password != ''){
		formulario.modificaEstado(1,'ok');
		dataString += '&password='+password;
	}
	else{
		formulario.modificaEstado(1,'error');
	}
	//dataString += '&'+ $(form_sup).serialize();
	//dataString += '&'+ $(form_inf).serialize();
	//alert(dataString);
	//alert(formulario.obtenTotal());
	if(formulario.obtenTotal()){//valido que la fecha sea correcta
		var url = "../php/actualiza_partido.php"; // El script a dónde se realizará la petición.
		$.ajax({
		   type: "POST",
		   url: url,
		   data: dataString, // Adjuntar los campos del formulario enviado.
		   success: function(data)
		   {
			   //var respuesta = "#respuesta"+id; 
			   //$(respuesta).html(data); // Mostrar la respuestas del script PHP.
			   //alert(data);
			    if(data == 0){
					swal({   
					title: "Partido actualizado correctamente",   
					text: "Puede ver la actualizacion reflejada en la clasificacion.",   
					type: "success",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: false }, 
					function(){   
						setTimeout ("window.location.reload();", 1);
					});
				}
				else{
					swal({   
					title: "Error en la modificacion",   
					text: "Compruebe que su email y pass son correctas, o que es jugador de este partido.",   
					type: "error",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: false }, 
					function(){   
						setTimeout ("window.location.reload();", 1);
					});
				}
		   }
		});
	}
	else{//si la fecha no es correcta
		//form_sup.elements[0].style.backgroundColor = '#f6d0d0';
		swal({   
					title: "Error en la modificacion",   
					text: "Compruebe: -email y pass son correctas, -es jugador de este partido, -los sets introducidos son correctos.",   
					type: "error",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: false }, 
					function(){   
						setTimeout ("window.location.reload();", 1);
		});
	}
	return false;
}//fin enviar



function abrir_ticket(id_partido){
	//alert(id_partido);
	var email = document.getElementById("email"+id_partido).value;
	var password = document.getElementById("password"+id_partido).value;
	if(email != '' && email.indexOf("@") != -1 && password != ''){
		formulario.modificaEstado(0,'ok');
		formulario.modificaEstado(1,'ok');
		document.getElementById('email'+id_partido).style.backgroundColor = '#EDEDFC';
		document.getElementById('password'+id_partido).style.backgroundColor = '#EDEDFC';
		var dataString = 'email='+email+'&password='+password+'&id_partido='+id_partido;
		if(formulario.obtenTotal()){
			swal({
				title: "Enviar Ticket al administrador.",
				text: 'Por favor, Introduce el Texto para el Administrador: ',
				type: 'input',
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
					dataString += '&texto='+inputValue;
					//alert(dataString);
					var url = "../php/crear_ticket.php"; // El script a dónde se realizará la petición.				
					
					$.ajax({
					   type: "POST",
					   url: url,
					   data: dataString, // Adjuntar los campos del formulario enviado.
					   success: function(data)
					   {
							if(data == 0){
								swal({   
								title: "El ticket ha sido enviado correctamente",   
								text: "El administrador contestara su peticion.",   
								type: "success",   
								showCancelButton: false,   
								confirmButtonColor: "#69fb5a",   
								confirmButtonText: "OK",   
								closeOnConfirm: false }, 
								function(){   
									setTimeout ("window.location.reload();", 1);
								});
							}//fin if
							else{
								swal({   
								title: "Error al crear el ticket",   
								text: "Compruebe que su email y pass son correctas, o que ha jugado este partido.",   
								type: "error",   
								showCancelButton: false,   
								confirmButtonColor: "#69fb5a",   
								confirmButtonText: "OK",   
								closeOnConfirm: false }, 
								function(){   
									setTimeout ("window.location.reload();", 1);
								});
							}//fin else
					   }//fin success
					});//fin ajax				
			});//fin swal
		}//fin if total
	}//fin if
	else{
		if(email == '' || email.indexOf("@") == -1){document.getElementById('email'+id_partido).style.backgroundColor = '#fcc9c9';}//email vacio
		else{document.getElementById('email'+id_partido).style.backgroundColor = '#EDEDFC';}
		if(password == ''){document.getElementById('password'+id_partido).style.backgroundColor = '#fcc9c9';}//password vacio
		else{document.getElementById('password'+id_partido).style.backgroundColor = '#EDEDFC';}
	}//fin else
}//fin abrir ticket