// JavaScript Document
//$(document).ready(function(){
	

//});//FIN DEL READY

$(function(){
	 $("#btn_enviar").click(function(){
		 //alert('hola');
		 if(recorrerForm()){
			var url = "generar.php"; // El script a dónde se realizará la petición.
			//var dataString = $("#formulario").serialize();
			//alert(dataString);
			$("#respuesta").html('<img class="cargando" src="../../../images/28.gif">'); // Mostrar la respuestas del script PHP.
			$("#respuesta2").html('Generando, por favor espere...'); // Mostrar la respuestas del script PHP
			$("#btn_enviar").hide();
			$.ajax({
					   type: "POST",
					   url: url,
					   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
					   success: function(data)
					   {
						   //$("#formulario").submit();
						   //alert(data);
						   if(data != ''){
								setTimeout ("window.location.reload();", 2000);
						   }
					   }
				 });//fin ajax
		 }//fin recorrer form
		return false; // Evitar ejecutar el submit del formulario.
	 });//fin btn_enviar
	});//fin function
	
	function recorrerForm(){
		var retorno = true;
		//var sAux="";
		var frm = document.getElementById("formulario");
		for (i=0;i<frm.elements.length;i++){
			/*sAux += "NOMBRE: " + frm.elements[i].name + " ";
			sAux += "TIPO :  " + frm.elements[i].type + " "; 
			sAux += "ID :  " + frm.elements[i].id + " "; 
			sAux += "VALOR: " + frm.elements[i].value + "\n" ;*/
			if(frm.elements[i].value == '' || frm.elements[i].value == '0' || frm.elements[i].value == 0){
				frm.elements[i].style.background = '#f8adad';
				retorno = false;
				break;
			}
			else{
				frm.elements[i].style.background = '#EDEDFC';
			}
		}
		//alert(sAux);
		return retorno;
	}
