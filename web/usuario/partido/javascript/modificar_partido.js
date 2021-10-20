// JavaScript Document
function enviar(id_partido){
	var form_res_local = document.getElementById('form_res_local'+id_partido);
	var form_res_visit = document.getElementById('form_res_visit'+id_partido);
	var form_sup = document.getElementById('form_sup'+id_partido);
	var form_inf = document.getElementById('form_inf'+id_partido);
	var dataString = 'id_partido='+id_partido;
	dataString += '&'+ $(form_res_local).serialize();
	dataString += '&'+ $(form_res_visit).serialize();
	dataString += '&'+ $(form_sup).serialize();
	dataString += '&'+ $(form_inf).serialize();
	//alert(dataString);
	//alert(form_sup.elements[0].value);
	if(existeFecha(form_sup.elements[0].value)){//valido que la fecha sea correcta
		var url = "actualiza_partido.php"; // El script a dónde se realizará la petición.
		$.ajax({
		   type: "POST",
		   url: url,
		   data: dataString, // Adjuntar los campos del formulario enviado.
		   success: function(data)
		   {
			   //var respuesta = "#respuesta"+id; 
			   //$(respuesta).html(data); // Mostrar la respuestas del script PHP.
			   //alert(data);
			   swal("Datos Actualizados", "El partido se ha actualizado correctamente.", "success");
			   setTimeout ("window.location.reload();", 2500);
		   }
		});
	}
	else{//si la fecha no es correcta
		form_sup.elements[0].style.backgroundColor = '#f6d0d0';
	}
	return false;
}
function cambiar_jornada(jornada){
	//alert(jornada);
	var url = "modificar_partido.php"; // El script a dónde se realizará la petición.
	var dataString = "modificar_partido.php?jornada="+jornada;
	if(!isNaN(jornada)){//isNaN devuelve true si no es numero
		cargar(".contenido",dataString);
		window.location.reload();
	}
	return false;
}
