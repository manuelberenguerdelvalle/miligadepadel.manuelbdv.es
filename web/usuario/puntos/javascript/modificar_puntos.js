// JavaScript Document
//$(document).ready(function(){
	function comprueba(cuadro){
		var cantidad = document.getElementById('cantidad').value;
		var aux = '';
		for(var i=0; i<cantidad; i++){
			aux = 'cuadro'+i;
			if(aux == cuadro){
				mostrar('#'+aux);
			}
			else{
				ocultar('#'+aux);
			}
			aux = '';
		}
	}//fin comprueba
//});//FIN DEL READY




