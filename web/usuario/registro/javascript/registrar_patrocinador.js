// JavaScript Document
formulario = new formularioUsuario();
$(document).ready(function(){
	localizacion();
	$(function(){
		$("#condiciones").click(function(){
			//alert('hola');
			formulario.modificaEstado(7,'null');
			check('condiciones',13);
			if( $('#condiciones').prop('checked') ) {
				$('#content_popup').bPopup();
			}
		 });
	});//fin function
	$(function(){
		$(".inputRegistrar").click(function(){
			if(formulario.obtenTotal()){
				ocultar('.inputRegistrar');
				//return false; // Evitar ejecutar el submit del formulario.
			}//fin obten_total
		 });
	});//fin function	
});