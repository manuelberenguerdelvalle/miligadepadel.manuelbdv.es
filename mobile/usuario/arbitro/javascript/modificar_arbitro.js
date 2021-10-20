// JavaScript Document
function enviar(id){
	var frm = document.getElementById(id);
	if(verificar_formulario(frm)){
		if(frm.elements[0].value != ''){
			var url = "actualiza_arbitro.php"; // El script a dónde se realizará la petición.
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
			error_respuesta('respuesta'+id,'Por favor introduzca un nombre para el arbitro.');
		}
	}
	else{
		error_respuesta('respuesta'+id,'Por favor revise el formulario, carácteres no admitidos.');
	}
	return false;
}
function eliminar(id){
	//alert(id);
	swal({   
	title: "Desea eliminar esta arbitro?",   
	text: "El arbitro se eliminará completamente del sistema",   
	type: "warning",   showCancelButton: true,   
	confirmButtonColor: "#DD6B55",   
	confirmButtonText: "Eliminar",   
	cancelButtonText: "Cancelar",   
	closeOnConfirm: true,   
	closeOnCancel: false }, 
	function(isConfirm){   
		if (isConfirm) {
			var url = "eliminar_arbitro.php"; // El script a dónde se realizará la petición.
			var dataString = "id="+id;
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   setTimeout ("window.location.reload();", 500);
				   }
				 });    
	   } 
	   else {     
	   		swal("Cancelado", "El arbitro no se ha eliminado.", "error");   
			}
	});
}