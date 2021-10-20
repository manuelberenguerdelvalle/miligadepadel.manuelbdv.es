// JavaScript Document
$(document).ready(function(){
	localizacion();
	comprueba(true);
	$(function(){
	 $("#btn_enviar").click(function(){
		 //alert('hola');
		 comprueba(false);
		 /*var hay_imagen = document.getElementById('logo').value;
		 if(hay_imagen != ''){
			subirImagen();
		 	document.getElementById('logo').value = '';
		 }*/
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			var url = "actualiza_liga.php"; // El script a dónde se realizará la petición.
			var dataString = $("#formulario").serialize();
			//alert(dataString);
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //$("#formulario").submit();
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 2000);
				   }
				 });
		 }//fin obten_total
		return false; // Evitar ejecutar el submit del formulario.
	 });//fin btn_enviar
	});//fin function

});//FIN DEL READY

function comprueba(estado){
	var posicion = document.getElementById('tipo_pago').options.selectedIndex; //posicion
	var valor = document.getElementById('tipo_pago').options[posicion].value; // valor
	var vista = document.getElementById('vista');
	var idayvuelta = document.getElementById('idayvuelta');
	var movimientos = document.getElementById('movimientos');
	if(valor == 0){
		vista.disabled = estado;
		vista.style.backgroundColor = '#b2b2b2';
		idayvuelta.disabled = estado;
		idayvuelta.style.backgroundColor = '#b2b2b2';
		movimientos.disabled = estado;
		movimientos.style.backgroundColor = '#b2b2b2';
	}
}

function setLimita(dni,telefono){
	var posicion = document.getElementById('tipo_pago').options.selectedIndex; //posicion
	//var valor = document.getElementById('tipo_pago').options[posicion].text; //texto
	var valor = document.getElementById('tipo_pago').options[posicion].value; //valor
	if(valor > 0){//si es de pago
		if( (dni == '' || dni == 0) && (telefono == '' || telefono == 0) ){
			swal("Error al seleccionar Liga Premium", "Debe tener rellenado el dni y el teléfono en 'Mi cuenta' para crear una Liga Premium.", "error");
			valor = 0;
		}
		else if(dni == '' || dni == 0){
			swal("Error al seleccionar Liga Premium", "Debe tener rellenado el dni en 'Mi cuenta' para crear una Liga Premium.", "error");
			valor = 0;
		}
		else if(telefono == '' || telefono == 0){
			swal("Error al seleccionar Liga Premium", "Debe tener rellenado el teléfono en 'Mi cuenta' para crear una Liga Premium.", "error");
			valor = 0;
		}
		else{
		}
	}
	var vista = document.getElementById('vista');
	var idayvuelta = document.getElementById('idayvuelta');
	var movimientos = document.getElementById('movimientos');
	if(valor == 0){
		vista.selectedIndex = 0;
		vista.disabled = true;
		vista.style.backgroundColor = '#b2b2b2';
		idayvuelta.selectedIndex = 1;
		idayvuelta.disabled = true;
		idayvuelta.style.backgroundColor = '#b2b2b2';
		movimientos.selectedIndex = 0;
		movimientos.disabled = true;
		movimientos.style.backgroundColor = '#b2b2b2';
	}
	else{
		vista.disabled = false;
		vista.style.backgroundColor = '#EDEDFC';
		idayvuelta.disabled = false;
		idayvuelta.style.backgroundColor = '#EDEDFC';
		movimientos.disabled = false;
		movimientos.style.backgroundColor = '#EDEDFC';
	}
}
function eliminar(id_usuario,id_liga){
	if(id_usuario != '' && id_liga != ''){
		swal({   
			title: "Está seguro de que desea eliminar esta liga?",   
			text: "Todo el contenido asociado a esta liga será eliminado",   
			type: "warning",   showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Eliminar",   
			cancelButtonText: "Cancelar",   
			closeOnConfirm: true,   
			closeOnCancel: false }, 
			function(isConfirm){   
				if (isConfirm) {
					var url = "eliminar_liga.php"; // El script a dónde se realizará la petición.
					var dataString = 'id_usuario='+id_usuario+'&id_liga='+id_liga;
					//alert(dataString);
					$.ajax({
						   type: "POST",
						   url: url,
						   data: dataString, // Adjuntar los campos del formulario enviado.
						   success: function(data)
						   {
							   //swal("Arbitro Eliminado", "El arbitro se ha eliminado correctamente.", "success");
							   swal({   
								title: "Liga Eliminada",   
								text: "La liga se ha eliminado correctamente.",   
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
					swal("Cancelado", "La liga no se ha eliminado.", "error"); 
				}//fin function
		});//fin swal
	}//fin id_usuario y id_liga
}//fin eliminar

