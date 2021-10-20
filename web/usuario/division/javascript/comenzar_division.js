// JavaScript Document
$(document).ready(function(){
	$(function(){
	 $("#btn_enviar").click(function(){
		 	//var frm = document.getElementById('formulario');
		if(comprobar_errores()){
			var dataString = $("#formulario").serialize();
			dataString += '&'+ $("#formulario_horas1").serialize();
			dataString += '&'+ $("#formulario_horas2").serialize();
			dataString += '&'+ $("#formulario_horas3").serialize();
			dataString += '&'+ $("#formulario_horas4").serialize();
			if(document.getElementById('pistas').checked){//se recogen los checkbox con array para serializarlos
				var id_pistas = document.getElementsByName('id_pistas');
				var cont = 0;
				for(var i=0; i<id_pistas.length; i++) {
					if(id_pistas[i].checked){
						dataString += '&id_pista'+cont+'='+id_pistas[i].value;
						cont++;
					}
				}
				dataString += '&num_id_pista='+cont;
				//alert(dataString);
			}
			if(document.getElementById('arbitros').checked){//se recogen los checkbox con array para serializarlos
				var id_arbitros = document.getElementsByName('id_arbitros');
				var cont = 0;
				for(var i=0; i<id_arbitros.length; i++) {
					if(id_arbitros[i].checked){
						dataString += '&id_arbitro'+cont+'='+id_arbitros[i].value;
						cont++;
					}
				}
				dataString += '&num_id_arbitro='+cont;
				//alert(dataString);
			}
			//alert(dataString);
			
			$("#respuesta").html('Este proceso puede tardar unos segundos, por favor espere.'); 
			$("#btn_enviar").hide();
			var url = "crear_calendario.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString,
				   /*beforeSend: function()
				   {
					 $("#respuesta").html('Este proceso puede tardar unos segundos, por favor espere.');  
					},*/
				   //data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   //alert(data);
					   $("#respuesta").html(''); 
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 3000);
				   }
				 });
		}
		
		return false; // Evitar ejecutar el submit del formulario.
	 	
	 });
	});
});

function comprueba(elemento){
	if(elemento.value == 'personalizado'){
		mostrar('.columna1');
		mostrar('.columna2');
		mostrar('.columna7');
		mostrar('.columna8');
		document.getElementById('modo').value = 1;
	}
	else{
		ocultar('.columna1');
		ocultar('.columna2');
		ocultar('.columna3');
		ocultar('.columna4');
		ocultar('.columna5');
		ocultar('.columna6');
		ocultar('.columna7');
		ocultar('.columna8');
		document.getElementById('lunes').checked=0;
		document.getElementById('martes').checked=0;
		document.getElementById('miercoles').checked=0;
		document.getElementById('jueves').checked=0;
		document.getElementById('viernes').checked=0;
		document.getElementById('sabado').checked=0;
		document.getElementById('domingo').checked=0;
		document.getElementById('modo').value = 0;
	}
}

function comprueba_linea(elemento){
	if(elemento.checked){
		mostrar('.columna3');
		mostrar('.columna4');
		mostrar('.columna5');
		mostrar('.columna6');
		var id1 = '#desde'+elemento.value+'1';
		var id2 = '#hasta'+elemento.value+'1';
		var id3 = '#desde'+elemento.value+'2';
		var id4 = '#hasta'+elemento.value+'2';
		mostrar(id1);
		mostrar(id2);
		mostrar(id3);
		mostrar(id4);
	}
	else{
		var id1 = '#desde'+elemento.value+'1';
		var id2 = '#hasta'+elemento.value+'1';
		var id3 = '#desde'+elemento.value+'2';
		var id4 = '#hasta'+elemento.value+'2';
		inicializar(id1);
		inicializar(id2);
		inicializar(id3);
		inicializar(id4);
		ocultar(id1);
		ocultar(id2);
		ocultar(id3);
		ocultar(id4);
	}
}
function comprueba_linea2(elemento){
	var nombre = elemento.name;
	var id = '.cont_'+nombre;
	//alert(id);
	if(elemento.checked){
		mostrar(id);
	}
	else{
		//inicializar(id1);
		ocultar(id);
	}
}
function comprobar_errores(){
	var dias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
	var retorno = true;
	for(var i=0; i<7; i++){
		if(document.getElementById(dias[i]).checked == true){
			var id1 = 'desde'+dias[i]+'1';
			var posicion1 = document.getElementById(id1).options.selectedIndex; //posicion contrario
			var valor1 = document.getElementById(id1).options[posicion1].value; // valor contrario
			var id2 = 'hasta'+dias[i]+'1';
			var posicion2 = document.getElementById(id2).options.selectedIndex; //posicion contrario
			var valor2 = document.getElementById(id2).options[posicion2].value; // valor contrario
			var id3 = 'desde'+dias[i]+'2';
			var posicion3 = document.getElementById(id3).options.selectedIndex; //posicion contrario
			var valor3 = document.getElementById(id3).options[posicion3].value; // valor contrario
			var id4 = 'hasta'+dias[i]+'2';
			var posicion4 = document.getElementById(id4).options.selectedIndex; //posicion contrario
			var valor4 = document.getElementById(id4).options[posicion4].value; // valor contrario
			if(valor1 != '' && valor2 == ''){
				error_hora(id2);
				retorno = false;
			}
			if(valor1 == '' && valor2 != ''){
				error_hora(id1);
				retorno = false;
			}
			if(parseInt(valor1) >= parseInt(valor2) && valor1 != '' && valor2 != ''){
				error_hora(id1);
				error_hora(id2);
				retorno = false;
			}
			if(valor3 != '' && valor4 == ''){
				error_hora(id4);
				retorno = false;
			}
			if(valor3 == '' && valor4 != ''){
				error_hora(id3);
				retorno = false;
			}
			if(parseInt(valor3) >= parseInt(valor4) && valor3 != '' && valor4 != ''){
				error_hora(id3);
				error_hora(id4);
				retorno = false;
			}
		}
	}
	return retorno;
}
function error_hora(id){
	document.getElementById(id).style.color = 'red';
}
function color_hora(id){
	id.style.color = '#181C83';
}
function inicializar(id){
	var nuevo = id.substring(1);
	document.getElementById(nuevo).selectedIndex = 0
	document.getElementById(nuevo).options[0].value = '';
}
function mostrar(id){
	$(id).show();
}

function ocultar(id){
	$(id).hide();
}
