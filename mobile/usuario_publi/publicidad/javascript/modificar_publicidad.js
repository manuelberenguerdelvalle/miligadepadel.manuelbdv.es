// JavaScript Document
formulario = new formularioLiga("null","null","null","null","null");

$(document).ready(function(){
	localizacion();
	$(function(){
	 $("#btn_enviar").click(function(){ 
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			 var provincia=$("#provincia").val();
			 var ciudad=$("#ciudad").val();
			 var url=$("#url").val();
			 var id_publicidad_gratis=$("#id_publicidad_gratis").val();
			 //alert(provincia+'-'+ciudad+'-'+suscripcion+'-precio:'+precio+'-'+url);
			 var formData=new FormData($('#formulario')[0]);
			 if(provincia != '' && provincia != 'null' && provincia != 'undefined'){formData.append("provincia", provincia);}
			 if(ciudad != '' && ciudad != 'null' && ciudad != 'undefined'){formData.append("ciudad", ciudad);}
			 formData.append("url", url);
			 formData.append("id_publicidad_gratis", id_publicidad_gratis);
			 $("#respuesta").html('Procesando, por favor espere...'); // Mostrar la respuestas del script PHP
			 $("#btn_enviar").hide();
			 $.ajax({
				url:'actualiza_publicidad.php',
				type:'POST',
				data:formData,
				cache:false,
				contentType:false,
				processData:false,
				success: function(data){
					//$("#respuesta").html(data);
					swal({   
					title: "Publicidad Actualizada",   
					text: "La publicidad ha sido actualizada correctamente.",   
					type: "success",   
					showCancelButton: false,   
					confirmButtonColor: "#69fb5a",   
					confirmButtonText: "OK",   
					closeOnConfirm: true }, 
					function(){   
						setTimeout ("window.location.reload();", 500);
					});
				},
				error:function(){
					swal("Error en la modificación.", "Ha habido un error, por favor pruebe de nuevo.", "error");
				}
			});
		 }//fin if formulario
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
	$("#nueva_publi").change(function(){
			compruebaImagen('nueva_publi',4,10000000);
			//alert('imagen'+formulario.inputs[4]);
			if(formulario.inputs[4] != 'error'){
				readURL(this);
			}
	});
	
	function readURL(input) {
		
			if (input.files && input.files[0]) {
				var reader = new FileReader();
		
				reader.onload = function (e) {
					$('#vista_previa').attr('src', e.target.result);
				}
		
				reader.readAsDataURL(input.files[0]);
			}
	}
});

function cambiar_publicidad(id_publicidad,provincia,ciudad,estado,url){
		//alert(id_publicidad);
		var dataString = "modificar_publicidad.php?id_publicidad="+id_publicidad+"&provincia="+provincia+"&ciudad="+ciudad+"&estado="+estado+"&url="+url;
		cargar(".contenido",dataString);
}

function localizacion() {
	// Parametros para la ciudad .click
	$("#provincia").change(function () {
   		$("#provincia option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				//$.post("../../desplegables/ciudades_ligas.php", { elegido: elegido }, function(data){
				$.post("ciudades_similares.php", { elegido: elegido }, function(data){
				$("#ciudad").html(data);
			});			
        });
   })
}//fin localizacion