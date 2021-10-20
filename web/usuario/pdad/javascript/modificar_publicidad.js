	$(function(){
	 $("#btn_enviar").click(function(){
		//alert('llego');
		var retorno = false;
		var estado_radiob = false;
		var estado_url = false;
		var radio = document.getElementsByName("posicion");   
		for(var i=0; i<radio.length; i++){// Recorremos todos los valores del radio button para encontrar el seleccionado
			if(radio[i].checked){
				var valor_radiob = radio[i].value;
				estado_radiob = true;
			}
		}
		var url = document.getElementById("url").value;
		if(url != ''){
			var w = url.indexOf("www.");
			var s = url.indexOf("https://");
			var n = url.indexOf("http://");
			if(w != -1 || s != -1 || n != -1){//si encuentro alguno de estos indicios de web entro
				estado_url = true;
			}
		}
		if(estado_radiob == true && estado_url == true){
			var id = document.getElementById(valor_radiob).value;
			var dataString = 'url='+url+'&id_publicidad='+id;
			//alert(dataString);
			$.ajax({
				type:"POST",
				url:"actualiza_publicidad.php",
				data:dataString,
				success: function(data){
					//alert('hola'+data);
					//$("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					swal({   
					title: "Modificación realizada correctamente",   
					text: "La modificación se ha realizado correctamente",   
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
			retorno = true;
		}
		else{
			if(estado_radiob == false){
				swal("Error al insertar la publicidad.", "Por favor, debe escoger una posición de publicidad.", "error");
			}
			else{
				swal("Error al insertar la publicidad.", "Por favor, debe introducir una dirección web válida.", "error");
			}
			retorno = false;
		}

		return false; // Evitar ejecutar el submit del formulario.
	 });
	});//fin BOTON
	
	function insertar_url(url){
		//alert('hola');
		document.getElementById('url').value = url;
	}
