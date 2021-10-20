// JavaScript Document
/*function enviar(datos_enviar){
	//alert('-'+datos_enviar+'-');
	var dataString = datos_enviar;	
	var url = "actualiza_disputa.php"; // El script a dónde se realizará la petición.
		$.ajax({
		   type: "POST",
		   url: url,
		   data: dataString, // Adjuntar los campos del formulario enviado.
		   success: function(data)
		   {
			   /*if(data == -1){
				   swal("Error en la Actualización", "Ha habido un error en la actualización, por favor inténtelo de nuevo.", "error");
				}
				//$("#respuesta").html(data);
				//alert(data);
				setTimeout ("window.location.reload();", 3000);
		   }
		});
}
function email(id_disputa){
	var datos = 'id_disputa='+id_disputa;
	var ids = ['1_'+id_disputa,'2_'+id_disputa,'3_'+id_disputa,'4_'+id_disputa];
	var ids_ok = ['l_j1','l_j2','v_j1','v_j2'];
	var cont = 0;
	for(var i=0; i<4; i++){
		if(document.getElementById(ids[i]).checked == true){
			cont++;
			datos += '&'+ids_ok[i]+'='+document.getElementById(ids[i]).value;
		}
	}
	if(cont > 0){//HAY DESTINATARIOS
		swal({
			title: "Enviar Em@il.",
			text: 'Por favor, Introduce el Texto para los destinatarios: ',
			type: 'input',
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
				swal("Correo electrónico enviado correctamente.", 'Los jugadores seleccionados recibirán el correo electrónico enviado.', "success");
				datos += '&texto='+inputValue;
				enviar(datos);
		});//fin swal
	}
	else{//NO HAY NINGUN DESTINATARIO
		swal("Por favor, debe seleccionar al menos un jugador.", 'Para poder contestar a esta disputa, debe seleccionar al menos uno de los cuatro jugadores, gracias.', "error");
	}
	return false;
}
*/