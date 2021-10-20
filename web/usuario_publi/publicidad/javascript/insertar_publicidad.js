// JavaScript Document
formulario = new formularioLiga("error","error","error","null","error");
$(document).ready(function(){
	localizacion();	
	$(function(){
	 $("#btn_enviar").click(function(){ 
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			 var provincia=$("#provincia").val();
			 var ciudad=$("#ciudad").val();
			 var suscripcion=$("#suscripcion").val();
			 var precio=$("#precio").val();
			 var url=$("#url").val();
			 //alert(provincia+'-'+ciudad+'-'+suscripcion+'-precio:'+precio+'-'+url);
			 var formData=new FormData($('#formulario')[0]);
			 formData.append("provincia", provincia);
			 formData.append("ciudad", ciudad);
			 formData.append("suscripcion", suscripcion);
			 formData.append("precio", precio);
			 formData.append("url", url);
			 $("#respuesta").html('Procesando, por favor espere...'); // Mostrar la respuestas del script PHP
			 $("#btn_enviar").hide();
			 //var url = "crear_publicidad.php"; // El script a dónde se realizará la petición.
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
						setTimeout ("window.location.reload();", 500);
					});
				},
				error:function(){
					swal("Error al crear imagen.", "Ha habido un error al insertar la imagen, pruebe de nuevo.", "error");
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


function localizacion() {
	// Parametros para la ciudad .click
	$("#provincia").change(function () {
   		$("#provincia option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				//$.post("../../desplegables/ciudades_ligas.php", { elegido: elegido }, function(data){
				$.post("ciudades_ligas.php", { elegido: elegido }, function(data){
				$("#ciudad").html(data);
				document.getElementById("suscripcion").selectedIndex = 0;
			});			
        });
   })
   $("#suscripcion").change(function () {
   		$("#suscripcion option:selected").each(function () {
			//alert($(this).val());
				suscripcion=$(this).val();
				/*var indice = document.getElementById("ciudad").selectedIndex;
				var ciudad = document.getElementById("ciudad").options[indice].value;*/
				ciudad=$("#ciudad").val();
				$.post("precio_ligas.php", { suscripcion: suscripcion, ciudad: ciudad }, function(data){
				$("#precio").val(data);
			});			
        });
   })
}//fin localizacion