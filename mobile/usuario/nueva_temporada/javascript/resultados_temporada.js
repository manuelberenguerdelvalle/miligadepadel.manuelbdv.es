// JavaScript Document
//$(document).ready(function(){
//});//FIN DEL READY
function eliminar(id,posicion,equipo){
	if(id != '' && posicion != '' && equipo != ''){
		swal({   
			title: "Está seguro de que desea eliminar este equipo?",   
			text: "Los equipos posteriores subirán una posición",   
			type: "warning",   showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Eliminar",   
			cancelButtonText: "Cancelar",   
			closeOnConfirm: true,   
			closeOnCancel: false }, 
			function(isConfirm){   
				if (isConfirm) {
					var url = "eliminar.php"; // El script a dónde se realizará la petición.
					var dataString = 'id_nueva_temporada='+id+'&posicion='+posicion+'&equipo='+equipo;
					//alert(dataString);
					$.ajax({
						   type: "POST",
						   url: url,
						   data: dataString, // Adjuntar los campos del formulario enviado.
						   success: function(data)
						   {
							   //alert(data);
							   swal({   
								title: "Equipo Eliminado",   
								text: "Se actualizará la clasificación.",   
								type: "success",   
								showCancelButton: false,   
								confirmButtonColor: "#69fb5a",   
								confirmButtonText: "OK",   
								closeOnConfirm: true }, 
								function(){   
									setTimeout ("window.location.reload();", 500);
								});
						   }
					});//fin ajax    
			   	} 
			   	else {     
					swal("Cancelado", "El equipo no se ha eliminado.", "error"); 
				}//fin function
		});//fin swal
	}//fin id_usuario y id_liga
}//fin eliminar

function generar(id_liga){
	if(id_liga != ''){
			var url = "confirmar.php"; // El script a dónde se realizará la petición.
			var dataString = 'id_liga='+id_liga;
			$("#respuesta").html('<img class="cargando" src="../../../images/28.gif"><label class="texto">Creando Nueva Temporada, Por favor espere...</label>'); // Mostrar la respuestas del script PHP.
			$("#btn_enviar").hide();
			//alert(dataString);
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //alert(data);
							   swal({   
								title: "La Nueva Temporada ha sido generada.",   
								text: "Puede revisar todas los datos de la nueva liga.",   
								type: "success",   
								showCancelButton: false,   
								confirmButtonColor: "#69fb5a",   
								confirmButtonText: "OK",   
								closeOnConfirm: true }, 
								function(){   
									setTimeout ("window.location.reload();", 500);
								});
				   }
			});//fin ajax    
	}//fin id_usuario y id_liga
}//fin generar


