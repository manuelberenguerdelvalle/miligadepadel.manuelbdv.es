// JavaScript Document
formulario = new formularioDivision("null","null","null","null","null","null","null","null");
$(document).ready(function(){
	//localizacion();
	$(function(){
	 $("#btn_enviar").click(function(){
		 regulariza();
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			var url = "actualiza_division.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 2000);
				   }
				 });
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
});

function regulariza(){
	//var precio = document.getElementById('precio').value;
	//var fecha = document.getElementById('datepicker').value; // valor
	var primero = document.getElementById('primero').value;
	if(primero == ''){
		formulario.modificaEstado(2,'null');
	}
	var segundo = document.getElementById('segundo').value;
	if(segundo == ''){
		formulario.modificaEstado(3,'null');
	}
	var tercero = document.getElementById('tercero').value;
	if(tercero == ''){
		formulario.modificaEstado(4,'null');
	}
	var cuarto = document.getElementById('cuarto').value;
	if(cuarto == ''){
		formulario.modificaEstado(5,'null');
	}
	var quinto = document.getElementById('quinto').value;
	if(quinto == ''){
		formulario.modificaEstado(6,'null');
	}
	var todos = document.getElementById('todos').value;
	if(todos == ''){
		formulario.modificaEstado(7,'null');
	}
}

function eliminar(id_liga,id_division){
	//alert('hola');
	if(id_liga != '' && id_division != ''){
		swal({   
			title: "Está seguro de que desea eliminar esta división?",   
			text: "Todo el contenido asociado a esta división será eliminado",   
			type: "warning",   showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Eliminar",   
			cancelButtonText: "Cancelar",   
			closeOnConfirm: true,   
			closeOnCancel: false }, 
			function(isConfirm){   
				if (isConfirm) {
					var url = "eliminar_division.php"; // El script a dónde se realizará la petición.
					var dataString = 'id_liga='+id_liga+'&id_division='+id_division;
					//alert(dataString);
					$.ajax({
						   type: "POST",
						   url: url,
						   data: dataString, // Adjuntar los campos del formulario enviado.
						   success: function(data)
						   {
							   //swal("Arbitro Eliminado", "El arbitro se ha eliminado correctamente.", "success");
							   swal({   
								title: "División Eliminada",   
								text: "La división se ha eliminado correctamente.",   
								type: "success",   
								showCancelButton: false,   
								confirmButtonColor: "#69fb5a",   
								confirmButtonText: "OK",   
								closeOnConfirm: true }, 
								function(){   
									setTimeout ("window.location.reload();", 1);
								});
						   }
					});//fin ajax    
			   	} 
			   	else {     
					swal("Cancelado", "La división no se ha eliminado.", "error"); 
				}//fin function
		});//fin swal
	}//fin id_liga y id_division
}//fin eliminar