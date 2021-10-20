// JavaScript Document
function enviar(id,tipo_sancion,descripcion,operacion,partido){
	if(partido == ''){
		var dataString = 'id_jugador='+id+'&tipo='+tipo_sancion+'&descripcion='+descripcion+'&operacion='+operacion;
	}
	else{
		var dataString = 'id_equipo='+id+'&tipo='+tipo_sancion+'&descripcion='+descripcion+'&operacion='+operacion+'&partido='+partido;
	}
	//alert(dataString);
		var url = "actualiza_sancion.php"; // El script a dónde se realizará la petición.
		$.ajax({
		   type: "POST",
		   url: url,
		   data: dataString, // Adjuntar los campos del formulario enviado.
		   success: function(data)
		   {
			   //alert(data);
			    if(operacion == "suma"){var op = "Insertar";}
				else{var op = "Eliminar";}
				if(partido == ""){//faltas
					if(tipo_sancion == 0){var msj = 'Falta leve';}//LEVE
					else{var msj = 'Falta grave';}//GRAVE
				}
				else{//sanciones partidos
					if(tipo_sancion == 0){var msj = 'Sanción partido';}//LEVE
					else{var msj = 'Expulsión equipo';}//GRAVE
				}
			   if(data == '-1'){//error
				   swal({   
					title: "Error al "+op+" "+msj,   
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
					title: op+" "+msj+" realizada correctamente",   
					text: "Se creará una nueva noticia que será publicada en el tablón de noticias con la modificación realizada.",   
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
	return false;
}
function falta(operacion,tipo,id_jugador){
	if(tipo == 'leve'){var tipo_sancion = 0;}//LEVE
	else{var tipo_sancion = 1;}//GRAVE
	if(operacion == 'resta'){//RESTA
		var valor = document.getElementById(tipo+'_'+id_jugador).value;
		if(Number(valor) > 0){
			swal({
				title: "Eliminar falta "+tipo+".",
				text: 'Por favor, Introduce la razón de eliminación de la falta '+tipo+': ',
				type: 'input',
				showCancelButton: true,
				closeOnConfirm: true,
				animation: "slide-from-top"
				}, 
				function(inputValue){
					if (inputValue === false){return false;}
					if (inputValue === "") {
						swal.showInputError("Por favor, es necesario indicar una razón para eliminarla.");
						return false;
					}
					enviar(id_jugador,tipo_sancion,inputValue,operacion,'');
				});
		}//fin if confirmar que valor > 0 
	}//fin if operacion
	else{//SUMA
		swal({
				title: "Añadir falta "+tipo+".",
				text: 'Por favor, Introduce la razón de la falta '+tipo+': ',
				type: 'input',
				showCancelButton: true,
				closeOnConfirm: true,
				animation: "slide-from-top"
				}, 
				function(inputValue){
					if (inputValue === false){return false;}
					if (inputValue === "") {
						swal.showInputError("Por favor, es necesario indicar una razón.");
						return false;
					}
					enviar(id_jugador,tipo_sancion,inputValue,operacion,'');
			});
	}
}

function sancion(operacion,tipo,id_equipo){
	if(tipo == 'partido'){//PARTIDO
		var tipo_sancion = 0;
		var id_select = 'partidos_sancion'+id_equipo;
		var posicion = document.getElementById(id_select).options.selectedIndex; //posicion contrario
		var nuevo_valor = document.getElementById(id_select).options[posicion].value; // valor contrario
		if(nuevo_valor != 0){//compruebo que no sea 0
			if(operacion == 'resta'){//RESTA
				var partido_sancionado = document.getElementById('partido_'+id_equipo).value;
				if(partido_sancionado == '' || partido_sancionado == null){partido_sancionado = 0;}
				if(Number(nuevo_valor) > Number(partido_sancionado)){//si entra aquí redefino
					nuevo_valor = partido_sancionado;//asigno el máximo posible
				}
				if(nuevo_valor != 0){//se vuelve a comparar
					swal({
					title: "Eliminar partidos de sanción.",
					text: 'Por favor, Introduce la razón de la eliminación de los partidos sancionados: ',
					type: 'input',
					showCancelButton: true,
					closeOnConfirm: true,
					animation: "slide-from-top"
					}, 
					function(inputValue){
						if (inputValue === false){return false;}
						if (inputValue === "") {
							swal.showInputError("Por favor, es necesario indicar una razón.");
							return false;
						}
						enviar(id_equipo,tipo_sancion,inputValue,operacion,nuevo_valor);
					});
				}//fin de nuevo_valor2
			}//fin resta
			else{//SUMA
				swal({
					title: "Añadir partidos de sanción.",
					text: 'Por favor, Introduce la razón de los partidos sancionados: ',
					type: 'input',
					showCancelButton: true,
					closeOnConfirm: true,
					animation: "slide-from-top"
					}, 
					function(inputValue){
						if (inputValue === false){return false;}
						if (inputValue === "") {
							swal.showInputError("Por favor, es necesario indicar una razón.");
							return false;
						}
						enviar(id_equipo,tipo_sancion,inputValue,operacion,nuevo_valor);
				});
			}//fin suma
		}//fin nuevo_valor
	}// FIN PARTIDO
	else{//EXPULSION
		var tipo_sancion = 1;
		var partido_expulsado = document.getElementById('expulsion_'+id_equipo).value;
			if(operacion == 'resta'){//RESTA
				if(partido_expulsado == 1){//se puede restar
					swal({
					title: "Anular la expulsión del equipo.",
					text: 'Por favor, Introduce la razón de anulación de la expulsión del equipo: ',
					type: 'input',
					showCancelButton: true,
					closeOnConfirm: true,
					animation: "slide-from-top"
					}, 
					function(inputValue){
						if (inputValue === false){return false;}
						if (inputValue === "") {
							swal.showInputError("Por favor, es necesario indicar una razón.");
							return false;
						}
						enviar(id_equipo,tipo_sancion,inputValue,operacion,nuevo_valor);
					});
				}//fin de nuevo_valor2
			}//fin resta
			else{//SUMA
				if(partido_expulsado == 0){//se puede sumar
					swal({
						title: "Realizar expulsión del equipo.",
						text: 'Por favor, Introduce la razón de la expulsión del equipo: ',
						type: 'input',
						showCancelButton: true,
						closeOnConfirm: true,
						animation: "slide-from-top"
						}, 
						function(inputValue){
							if (inputValue === false){return false;}
							if (inputValue === "") {
								swal.showInputError("Por favor, es necesario indicar una razón.");
								return false;
							}
							enviar(id_equipo,tipo_sancion,inputValue,operacion,nuevo_valor);
					});
				}
			}//fin suma
	}//FIN EXPULSION
}


