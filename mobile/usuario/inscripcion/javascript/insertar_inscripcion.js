// JavaScript Document
formulario = new formularioGeneralVeinticinco('error','error','null','error','error','error','null','error','error','null','error','error','null','error','error','error','null','error','error','null','null','null','null','error','error');
//del 0-9 jugador 1, del 10-
$(document).ready(function(){
	localizacion();
	localizacion2();
	$(function(){
	 $("#btn_enviar").click(function(){	
	 	var jugador1 = document.getElementsByName('jugador1');
		var jugador2 = document.getElementsByName('jugador2');
		var nombre1_rapido = document.getElementById('nombre1_rapido');
		var nombre2_rapido = document.getElementById('nombre2_rapido');
		var apellidos1_rapido = document.getElementById('apellidos1_rapido');
		var apellidos2_rapido = document.getElementById('apellidos2_rapido');
	 	 if(jugador1[0].checked && nombre1_rapido.value != '' && apellidos1_rapido.value != ''){//JUGADOR 1 RAPIDO
			var form1 = 'id_jugador1=0&nombre1_rapido='+nombre1_rapido.value+'&apellidos1_rapido='+apellidos1_rapido.value;
			for(var i=2; i<10; i++){//si es por busqueda pongo los campos de insercióna  null
				formulario.modificaEstado(i,null);
			}
			formulario.modificaEstado(23,'ok');//añadimos a esta posicion para controlar la busqueda
		 }
		 if(jugador2[0].checked && nombre2_rapido.value != '' && apellidos2_rapido.value != ''){//JUGADOR 2 RAPIDO
			var form2 = 'id_jugador2=0&nombre2_rapido='+nombre2_rapido.value+'&apellidos2_rapido='+apellidos2_rapido.value;
			for(var i=12; i<20; i++){//si es por busqueda pongo los campos de insercióna  null
				formulario.modificaEstado(i,null);
			}
			formulario.modificaEstado(24,'ok');//añadimos a esta posicion para controlar la busqueda
		 }
		 if(jugador1[1].checked){//JUGADOR 1 INSERTADO
				var insercion1 = 0;
				var form1 = $("#formulario1").serialize();
				insercion1 = 1;
				formulario.modificaEstado(23,'null');//añadimos a esta posicion para controlar la busqueda
		 }
		 if(jugador2[1].checked){//JUGADOR 2 INSERTADO
			 	var insercion2 = 0;
				var form2 = $("#formulario2").serialize();
				insercion2 = 1;
				formulario.modificaEstado(24,'null');//añadimos a esta posicion para controlar la busqueda
		 }
		comprueba_opcionales(insercion1,insercion2);
		//alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
		 	var dataString = form1+'&'+form2;
			//alert(dataString);
			var url = "crear_inscripcion.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {   
					   $("#respuesta").html(data); // Mostrar la respuestas del script PHP.		   
					   setTimeout ("window.location.reload();", 1000);
				   }
				 });
		 }//fin if obtenTotal
		 else{
			error_respuesta('respuesta','Por favor revise el formulario.');
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
	
});//fin ready

function comprueba(elemento){
	//alert(elemento.value);
	if(elemento.value == 'insertar1'){
		mostrar('#jug1_col1');
		mostrar('#jug1_col2');
		mostrar('#jug1_col3');
		ocultar('#jug1_col1_rapido');
		ocultar('#jug1_col2_rapido');
		ocultar('#jug1_col3_rapido');
		//document.getElementById('id_jugador1').value = '';
	}
	else if(elemento.value == 'insertar2'){
		mostrar('#jug2_col1');
		mostrar('#jug2_col2');
		mostrar('#jug2_col3');
		ocultar('#jug2_col1_rapido');
		ocultar('#jug2_col2_rapido');
		ocultar('#jug2_col3_rapido');
		//document.getElementById('id_jugador2').value = '';
	}
	else if(elemento.value == 'rapido1'){
		ocultar('#jug1_col1');
		ocultar('#jug1_col2');
		ocultar('#jug1_col3');
		mostrar('#jug1_col1_rapido');
		mostrar('#jug1_col2_rapido');
		mostrar('#jug1_col3_rapido');
	}
	else{//rapido2
		ocultar('#jug2_col1');
		ocultar('#jug2_col2');
		ocultar('#jug2_col3');
		mostrar('#jug2_col1_rapido');
		mostrar('#jug2_col2_rapido');
		mostrar('#jug2_col3_rapido');
	}
}

function comprueba_opcionales(insercion1,insercion2){
	if(insercion1 == 1){
		var direccion1 = document.getElementById('direccion1').value;
		var telefono1 = document.getElementById('telefono1').value;
		var dni1 = document.getElementById('dni1').value;
		if(direccion1 == ''){// si es vacio compruebo
			if(formulario.inputs[2] == 'error'){formulario.modificaEstado(2,null);}//lo pongo a null
		}
		else{//si tiene datos
			if(formulario.inputs[2] == 'error'){document.getElementById('direccion1Com').innerHTML = '&nbsp;Error';}//muestro error
		}
		if(telefono1 == ''){// si es vacio compruebo
			if(formulario.inputs[6] == 'error'){formulario.modificaEstado(6,null);}//lo pongo a null
		}
		else{//si tiene datos
			if(formulario.inputs[6] == 'error'){document.getElementById('telefono1Com').innerHTML = '&nbsp;Error';}//muestro error
		}
		if(dni1 == ''){// si es vacio compruebo
			if(formulario.inputs[9] == 'error'){formulario.modificaEstado(9,null);}//lo pongo a null
		}
		else{//si tiene datos
			if(formulario.inputs[9] == 'error'){document.getElementById('dni1Com').innerHTML = '&nbsp;Error';}//muestro error
		}
	}
	if(insercion2 == 1){
		var direccion2 = document.getElementById('direccion2').value;
		var telefono2 = document.getElementById('telefono2').value;
		var dni2 = document.getElementById('dni2').value;
		if(direccion2 == ''){// si es vacio compruebo
			if(formulario.inputs[12] == 'error'){formulario.modificaEstado(12,null);}//lo pongo a null
		}
		else{//si tiene datos
			if(formulario.inputs[12] == 'error'){document.getElementById('direccion2Com').innerHTML = '&nbsp;Error';}//muestro error
		}
		if(telefono2 == ''){// si es vacio compruebo
			if(formulario.inputs[16] == 'error'){formulario.modificaEstado(16,null);}//lo pongo a null
		}
		else{//si tiene datos
			if(formulario.inputs[16] == 'error'){document.getElementById('telefono2Com').innerHTML = '&nbsp;Error';}//muestro error
		}
		if(dni2 == ''){// si es vacio compruebo
			if(formulario.inputs[19] == 'error'){formulario.modificaEstado(19,null);}//lo pongo a null
		}
		else{//si tiene datos
			if(formulario.inputs[19] == 'error'){document.getElementById('dni2Com').innerHTML = '&nbsp;Error';}//muestro error
		}
	}
}