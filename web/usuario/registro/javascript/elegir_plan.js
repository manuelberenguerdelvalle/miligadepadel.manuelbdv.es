// JavaScript Document
function setPartidos(){
		var posicion = document.getElementById('tipo_pago').options.selectedIndex; //posicion
		var valor = document.getElementById('tipo_pago').options[posicion].text; //valor
		if(valor == '30'){
			document.getElementById('partidos').value = 10;
		}
		else if(valor == '40'){
			document.getElementById('partidos').value = 15;
		}
		else{
			document.getElementById('partidos').value = 20;
		}
}
function enviar(){
	var dataString = $("#formulario_contacto").serialize();
	//var url = "../contacto/enviar_email.php";
	var url = "../../contacto/c/enviar_email.php";
	$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {   
				   		//alert(dataString);
					   /*$("#respuesta").html(data); // Mostrar la respuestas del script PHP.		   
					   setTimeout ("window.location.reload();", 2000);*/
				   }
	});
}