// JavaScript Document
formulario = new formularioLiga("error","error","error","error","error");
$(document).ready(function(){
	localizacion();
	comprueba(true);
	$(function(){
	 $("#btn_enviar").click(function(){
		 //alert(formulario.obtenTotal());
		 comprueba(false);
		 if(formulario.obtenTotal()){
			var url = "crear_liga.php"; // El script a dónde se realizará la petición.
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

function comprueba(estado){
	var posicion = document.getElementById('tipo_pago').options.selectedIndex; //posicion
	var valor = document.getElementById('tipo_pago').options[posicion].value; // valor
	var vista = document.getElementById('vista');
	var idayvuelta = document.getElementById('idayvuelta');
	var movimientos = document.getElementById('movimientos');
	if(valor == 0){
		//document.getElementById('vista').disabled = estado;
		//document.getElementById('idayvuelta').disabled = estado;
		//document.getElementById('movimientos').disabled = estado;
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
		//document.getElementById('vista').options[0].value[1].selected = true;
		//document.getElementById('tipo_pago').options.selectedIndex = 1;
		/*document.getElementById('vista').selectedIndex = 0;
		document.getElementById('vista').disabled = true;
		document.getElementById('idayvuelta').selectedIndex = 1;
		document.getElementById('idayvuelta').disabled = true;
		document.getElementById('movimientos').selectedIndex = 0;
		document.getElementById('movimientos').disabled = true;*/
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
	return true;
}

