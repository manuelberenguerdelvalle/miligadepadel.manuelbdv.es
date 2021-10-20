// JavaScript Document
formulario = new formularioLiga("error","null","null","null","null");
$(document).ready(function(){
	//localizacion();
	$(function(){
	 $("#btn_enviar").click(function(){ 
	 	 var tipo_pago = document.getElementById('tipo_pago').value;
		 //alert(tipo_pago);
		 regulariza(tipo_pago);
		 enviar();
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
});

function regulariza(tipo_pago){//todas las imagenes las pongo a null para que pase ya que no es obligatorio
	if(tipo_pago == 0){var tam = 1;}
	else{var tam = 4;}
	for(var i=1; i<=tam; i++){
		var id = 'imagen'+i;
		var valor = document.getElementById(id).value;
		var extension = (valor.substring(valor.lastIndexOf("."))).toLowerCase();
		if(!extensionImagen(extension)){
			valor = '';
		}
		if(valor != ''){
			hay_fotos = true;
		}
		formulario.modificaEstado(i,'null');			
	}
}

function enviar(){
		//var formData = new FormData(document.getElementById("formulario"));
		$("#respuesta").html('<div style="margin-left:31%; margin-top:0.5%;"><img src="../../../images/28.gif" width="100"></div><div style="margin-left:15%;">Por favor espere, este proceso puede tardar entre 30 segundos y varios minutos, <br>dependiendo de la calidad de la imagen y la conexión a internet, gracias.</div>');
		$("#btn_enviar").hide();
		var formData=new FormData($('#formulario')[0]);
		$.ajax({
			url:'crea_noticia.php',
			type:'POST',
			data:formData,
			cache:false,
			contentType:false,
			processData:false,
			success: function(data){
				$("#respuesta").html(''); 
				$("#respuesta").html(data); // Mostrar la respuestas del script PHP.
				setTimeout ("window.location.reload();", 2500);
			},
			error:function(){
				$("#respuesta").html(''); 
				$("#respuesta").html('Ha habido un error por favor, vuelva a intentarlo.');
				setTimeout ("window.location.reload();", 2500);
			}
		});
}//fin conImagen