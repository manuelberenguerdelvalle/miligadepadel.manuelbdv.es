// JavaScript Document
function enviar(id_jugador,tipo){
	var valor = document.getElementById('jugador'+id_jugador).value;
	if(Number(valor) != 0){
		//alert('id'+id_jugador+'-'+valor);
		if(tipo == 'restar'){
			var resta = valor-(valor*2);
			var dataString = 'id_jugador='+id_jugador+'&puntos='+resta;
		}
		else{
			var dataString = 'id_jugador='+id_jugador+'&puntos='+valor;
		}
		//alert(dataString);
		var url = "crear_puntos.php"; // El script a dónde se realizará la petición.
		$.ajax({
		   type: "POST",
		   url: url,
		   data: dataString, // Adjuntar los campos del formulario enviado.
		   success: function(data)
		   {
			   if(data == '-1'){//error
				   swal({   
					title: "Error al insertar puntos",   
					text: "Por favor, no es posible realizar la operación seleccionada.",   
					type: "error",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: true }, 
					function(){   
						setTimeout ("window.location.reload();", 500);
					});
				}
				else{//ok
					swal({   
					title: "Inserción de puntos realizada correctamente",   
					text: "Se publicará una nueva noticia en el tablón de noticias.",   
					type: "success",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: true }, 
					function(){   
						setTimeout ("window.location.reload();", 500);
					});
				}
		   }
		});
	}//fin de if
	
	return false;
}//fin enviar


