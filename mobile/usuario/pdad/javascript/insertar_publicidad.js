/*$(document).ready(function(){
	
});*/

formulario = new formularioLiga("error","null","null","null","null");
	$(function(){
	 $("#btn_enviar").click(function(){
		var retorno = false;
		var estado_radiob = false;
		var estado_url = false;
		var estado_publi = false;
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
		var publi = document.getElementById('nueva_publi').value;
		if(publi != ''){
			if(formulario.obtenTotal()){
				estado_publi = true;
			}
		}
		
		if(estado_radiob == true && estado_url == true && estado_publi == true){
			var formData=new FormData($('#formulario')[0]);
			formData.append("posicion", valor_radiob);
			formData.append("url", url);
			$("#respuesta").html('Procesando, por favor espere...'); // Mostrar la respuestas del script PHP
			$("#btn_enviar").hide();
			//alert(formData);
			$.ajax({
				url:'crear_publicidad.php',
				type:'POST',
				data:formData,
				cache:false,
				contentType:false,
				processData:false,
				success: function(data){
					swal({   
					title: "Solo queda un paso",   
					text: "Para activar la publicidad debe realizar el pago en la sección Pagos -> Enviados",   
					type: "success",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: true }, 
					function(){   
						//swal("Importante!", "Para completar la publicidad debe realizar el pago en la sección Pagos -> Enviados", "warning");
						setTimeout ("window.location.reload();", 500);
					});
					//$("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					/*swal("Publicidad insertada correctamente.", "Para completar la publicidad debe realizar el pago en la sección Pagos -> Enviados.", "success");
					setTimeout ("window.location.reload();", 5000);*/
				},
				error:function(){
					swal("Error al crear imagen.", "Ha habido un error al insertar la imagen, pruebe de nuevo.", "error");
				}
			});
			//alert(datos);
			retorno = true;
		}
		else{
			if(estado_radiob == false){
				swal("Error al insertar la publicidad.", "Por favor, debe escoger una posición de publicidad.", "error");
			}
			if(estado_url == false){
				swal("Error al insertar la publicidad.", "Por favor, debe introducir una dirección web válida.", "error");
			}
			else{
				swal("Error al insertar la publicidad.", "Por favor, debe escoger una imgagen con los formatos y tamaño permitidos.", "error");
			}
			retorno = false;
		}

		return false; // Evitar ejecutar el submit del formulario.
	 });
	});//fin BOTON
	function readURL(input) {
	
		if (input.files && input.files[0]) {
			var reader = new FileReader();
	
			reader.onload = function (e) {
				$('#vista_previa').attr('src', e.target.result);
			}
	
			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#nueva_publi").change(function(){
		compruebaImagen('nueva_publi',0,10000000);
		if(formulario.obtenTotal()){
			readURL(this);
		}
		
	});

