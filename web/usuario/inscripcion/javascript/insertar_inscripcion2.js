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
	 	 if(jugador1[0].checked && document.getElementById('id_jugador1_buscado')){//JUGADOR 1 BUSCADO
			 var id_j1_bus = document.getElementById('id_jugador1_buscado');
			 if(id_j1_bus.value != ''){
				 var form1 = 'id_jugador1='+id_j1_bus.value;
				 for(var i=0; i<10; i++){//si es por busqueda pongo los campos de insercióna  null
					 formulario.modificaEstado(i,null);
				 }
				 formulario.modificaEstado(23,'ok');//añadimos a esta posicion para controlar la busqueda
			 }
			 else{
				 formulario.modificaEstado(23,'error');
			 }
		 }
		 if(jugador2[0].checked && document.getElementById('id_jugador2_buscado')){//JUGADOR 2 BUSCADO
			 var id_j2_bus = document.getElementById('id_jugador2_buscado');
			 if(id_j2_bus.value != ''){
				 var form2 = 'id_jugador2='+id_j2_bus.value;
				 for(var i=10; i<20; i++){//si es por busqueda pongo los campos de insercióna  null
					 formulario.modificaEstado(i,null);
				 }
				 formulario.modificaEstado(24,'ok');//añadimos a esta posicion para controlar la busqueda
			 }
			 else{
				 formulario.modificaEstado(24,'error');
			 }
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
					   setTimeout ("window.location.reload();", 3000);
				   }
				 });
		 }//fin if obtenTotal
		 else{
			error_respuesta('respuesta','Por favor revise el formulario.');
		 }
		return false; // Evitar ejecutar el submit del formulario.
	 });
	});
	
	var consulta;                                                                     
    //hacemos focus al campo de búsqueda
    //$("#busqueda").focus();                                                                                                
    //comprobamos si se pulsa una tecla
    $("#busqueda").keyup(function(e){                             
    	//obtenemos el texto introducido en el campo de búsqueda
        consulta = $("#busqueda").val();                                                                 
        //hace la búsqueda                                                                         
        $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: "b="+consulta+"&jugador=1",
                    dataType: "html",
                    /*beforeSend: function(){
                          //imagen de carga CREAR IMAGEN DE CARGA GIF CON PELOTAS DE TENIS
                          $("#resultado").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                    },
                    error: function(){
						  $("#resultado").append("Ha ocurrido un error, disculpe las molestias");
                          //alert("error petición ajax");
                    },*/
                    success: function(data){                                                    
                          $("#resultado").empty();
                          $("#resultado").append(data);
						  mostrar('#resultado'); 
						  mostrar('#seleccionado');                                               
                    }
         });//fin ayax                                                                         
	});//fin busqueda
	$("#busqueda2").keyup(function(e){                             
    	//obtenemos el texto introducido en el campo de búsqueda
        consulta = $("#busqueda2").val();                                                                 
        //hace la búsqueda                                                                         
        $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: "b="+consulta+"&jugador=2",
                    dataType: "html",
                    beforeSend: function(){
                    	//imagen de carga CREAR IMAGEN DE CARGA GIF CON PELOTAS DE TENIS
                          $("#resultado2").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                    },
                    error: function(){
						  $("#resultado2").append("Ha ocurrido un error, disculpe las molestias");
                          //alert("error petición ajax");
                    },
                    success: function(data){                                                    
                          $("#resultado2").empty();
                          $("#resultado2").append(data);
						  mostrar('#resultado2'); 
						  mostrar('#seleccionado2');                                                
                    }
         });//fin ayax                                                                         
	});//fin busqueda	
	
});//fin ready

function seleccionar(id_jugador,datos,jugador){
	//alert(id_jugador);
	if(jugador == 1){
		document.getElementById('seleccionado').innerHTML = '<input type="radio" id="id_jugador1_buscado" name="id_jugador1_buscado" value="'+id_jugador+'" checked >'+datos;
		document.getElementById('busqueda').value = '';
		ocultar('#resultado');
	}
	else{
		document.getElementById('seleccionado2').innerHTML = '<input type="radio" id="id_jugador2_buscado" name="id_jugador2_buscado" value="'+id_jugador+'" checked >'+datos;
		document.getElementById('busqueda2').value = '';
		ocultar('#resultado2');
	}
}

function comprueba(elemento){
	//alert(elemento.value);
	if(elemento.value == 'insertar1'){
		mostrar('#jug1_col1');
		mostrar('#jug1_col2');
		mostrar('#jug1_col3');
		ocultar('#div_bus1');
		ocultar('#resultado');
		ocultar('#seleccionado');
		//document.getElementById('id_jugador1').value = '';
	}
	else if(elemento.value == 'insertar2'){
		mostrar('#jug2_col1');
		mostrar('#jug2_col2');
		mostrar('#jug2_col3');
		ocultar('#div_bus2');
		ocultar('#resultado2');
		ocultar('#seleccionado2');
		//document.getElementById('id_jugador2').value = '';
	}
	else if(elemento.value == 'buscar1'){
		ocultar('#jug1_col1');
		ocultar('#jug1_col2');
		ocultar('#jug1_col3');
		mostrar('#div_bus1');
		document.getElementById('id_jugador1').value = '';
	}
	else{//buscar2
		ocultar('#jug2_col1');
		ocultar('#jug2_col2');
		ocultar('#jug2_col3');
		mostrar('#div_bus2');
		//ocultar('#resultado2');
		document.getElementById('id_jugador2').value = '';
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

