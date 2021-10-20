// JavaScript Document
formulario = new formularioDivision("null","null","null","null","null","null","null","null");
$(document).ready(function(){
	//localizacion();
	$(function(){
	 $("#btn_enviar").click(function(){
		 regulariza();
		 //alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			var url = "crear_division.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
					   setTimeout ("window.location.reload();", 3000);
				   }
				 });
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
});

function regulariza(){
	//var precio = document.getElementById('precio').value;
	//var fecha = document.getElementById('datepicker').value; // valor
	var primero = document.getElementById('primero').value;
	if(primero == ''){
		formulario.modificaEstado(2,'null');
	}
	var segundo = document.getElementById('segundo').value;
	if(segundo == ''){
		formulario.modificaEstado(3,'null');
	}
	var tercero = document.getElementById('tercero').value;
	if(tercero == ''){
		formulario.modificaEstado(4,'null');
	}
	var cuarto = document.getElementById('cuarto').value;
	if(cuarto == ''){
		formulario.modificaEstado(5,'null');
	}
	var quinto = document.getElementById('quinto').value;
	if(quinto == ''){
		formulario.modificaEstado(6,'null');
	}
	var todos = document.getElementById('todos').value;
	if(todos == ''){
		formulario.modificaEstado(7,'null');
	}
}