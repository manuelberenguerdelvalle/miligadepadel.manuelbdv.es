// JavaScript Document
function enviar(id){
	var frm = document.getElementById(id);
	if(verificar_formulario(frm)){
		if(frm.elements[0].value != ''){
			var url = "actualiza_pista.php"; // El script a dónde se realizará la petición.
			var formulario = "#"+id;
			var dataString = $(formulario).serialize();
			dataString += "&id="+id;
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   var respuesta = "#respuesta"+id; 
					   $(respuesta).html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 3000);
				   }
				 });
		}
		else{
			error_respuesta('respuesta'+id,'Por favor introduzca un nombre para la pista.');
		}
	}
	else{
		error_respuesta('respuesta'+id,'Por favor revise el formulario, carácteres no admitidos.');
	}
	return false;
}
function eliminar(id){
	swal({   
	title: "Desea eliminar esta pista?",   
	text: "La pista se eliminará completamente del sistema",   
	type: "warning",   showCancelButton: true,   
	confirmButtonColor: "#DD6B55",   
	confirmButtonText: "Eliminar",   
	cancelButtonText: "Cancelar",   
	closeOnConfirm: true,   
	closeOnCancel: false }, 
	function(isConfirm){   
		if (isConfirm) {
			var url = "eliminar_pista.php"; // El script a dónde se realizará la petición.
			var dataString = "id="+id;
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   /*swal("Pista Eliminada", "La pista se ha eliminado correctamente.", "success");
					   setTimeout ("window.location.reload();", 1000);*/
					   swal({   
						title: "Pista Eliminada",   
						text: "La pista se ha eliminado correctamente.",   
						type: "success",   
						showCancelButton: false,   
						confirmButtonColor: "#69fb5a",   
						confirmButtonText: "OK",   
						closeOnConfirm: true }, 
						function(){   
							setTimeout ("window.location.reload();", 500);
						});
				   }
				 });    
	   } 
	   else {     
	   		swal("Cancelado", "La pista no se ha eliminado.", "error");   
			}
	});
}
