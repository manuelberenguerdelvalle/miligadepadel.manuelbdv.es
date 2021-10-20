// JavaScript Document
formulario = new formularioGeneralVeinticinco('error','error','null','error','error','error','null','error','error','null','error','error','null','error','error','error','null','error','error','null','null','error','error','error','error');
//del 0-9 jugador 1, del 10-
$(document).ready(function(){
	var inicial = document.getElementById('inicial').value;
	if(inicial == 'A'){
		$('#form_paypal').attr("action", "http://miligadepadel.manuelbdv.es");//inicializamos presencial
	}
	localizacion();
	localizacion2();
	$(function(){
		$("#btn_condiciones").click(function(){
			if( $('#btn_condiciones').prop('checked') ) {
				$('#content_popup').bPopup();
			}
		 });
	});
	$(function(){	
	 $("#btn_enviar").click(function(){	
	 	var jugador1 = document.getElementsByName('jugador1');
		var jugador2 = document.getElementsByName('jugador2');
	 	 if(jugador1[0].checked){//JUGADOR 1 LOGIN
			 var j1_email = document.getElementById('l_email1').value;
			 var j1_pass = document.getElementById('l_password1').value;
			 comprueba_login(j1_email,j1_pass,1);
			 var form1 = 'email_j1='+j1_email;
			 for(var i=0; i<10; i++){//si es por busqueda pongo los campos de insercióna  null
				 formulario.modificaEstado(i,null);
			 }
		 }
		 if(jugador2[0].checked){//JUGADOR 2 LOGIN
			 var j2_email = document.getElementById('l_email2').value;
			 var j2_pass = document.getElementById('l_password2').value;
			 comprueba_login(j2_email,j2_pass,2);
			 var form2 = 'email_j2='+j2_email;
			 for(var i=10; i<20; i++){//si es por busqueda pongo los campos de insercióna  null
				 formulario.modificaEstado(i,null);
			 }
		 }
		 if(jugador1[1].checked){//JUGADOR 1 INSERTADO
			var insercion1 = 0;
				var form1 = $("#formulario1").serialize();
				insercion1 = 1;
				formulario.modificaEstado(21,'null');//añadimos a esta posicion para controlar la busqueda
				formulario.modificaEstado(22,'null');//añadimos a esta posicion para controlar la busqueda
		 }
		 if(jugador2[1].checked){//JUGADOR 2 INSERTADO
			 var insercion2 = 0;
				var form2 = $("#formulario2").serialize();
				insercion2 = 1;
				formulario.modificaEstado(23,'null');//añadimos a esta posicion para controlar la busqueda
				formulario.modificaEstado(24,'null');//añadimos a esta posicion para controlar la busqueda
		 }

		//TIENE ACTIVO OPCION PAGO ONLINE, COMPROBAMOS TIPO PAGO Y PRECIO
		 var tipo_pago = document.getElementById('tipo_pago').value;
		 var precio = document.getElementById('precio').value;
		 if(inicial == 'A' && tipo_pago > 0 && precio > 0){
			 //alert('entra if');
			var pos_recibir_pago = document.getElementById('recibir_pago').options.selectedIndex; //posicion
	     	var valor_recibir_pago = document.getElementById('recibir_pago').options[pos_recibir_pago].value; // valor
		 }
		 else{
			 //alert('entra else');
			 var valor_recibir_pago = 'M';
		 }
		 
		//SOLO SE DEBE EJECUTAR EN EL CASO DE PAGO ONLINE
		 if(tipo_pago > 0 && precio > 0 && inicial == 'A' && valor_recibir_pago == 'O'){
			 //alert('ejecuto codigo de pago online');
			 //AÑADIMOS LOS EMAILS EN LA DESCRIPCION DEL PAGO
			 var nuevo = document.getElementById('item_name').value;
			 var l_email1 = document.getElementById('l_email1').value;
			 if(l_email1 != ''){nuevo += ' - '+l_email1;}
			 else{nuevo += ' - '+document.getElementById('email1').value;}
			 var l_email2 = document.getElementById('l_email2').value;
			 if(l_email2 != ''){nuevo += ' - '+l_email2;}
			 else{nuevo += ' - '+document.getElementById('email2').value;}
			 document.getElementById('item_name').value = nuevo;
		 }

		comprueba_opcionales(insercion1,insercion2); 
		//alert(formulario.obtenTotal());
		 if(formulario.obtenTotal()){
			var fec_captur = document.getElementById('fec_captur').value;
			var dataString = 'fec_captur='+fec_captur+'&tipo_pago='+tipo_pago+'&'+form1+'&'+form2;
			//alert(dataString);
			var url = "crear_inscripcion.php"; // El script a dónde se realizará la petición.
			$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {   
					   //var action = $("#form_paypal").attr("action");
					   //alert(data);
					   //$("#actualizacionTexto").html(data);
					   $("#actualizacionTexto").html('');
					   if(data == '0'){//0
					   	   ocultar('#btn_enviar');
						   mostrar("#actualizacion");
						   ocultar("#imagenError");
						   mostrar("#imagenOk");
						   if( tipo_pago == 0 || precio == 0){//
						   		$("#actualizacionTexto").html('La inscripcion se ha realizado correctamente.'); // Mostrar la respuestas del script PHP.
								setTimeout ("document.location.href='http://miligadepadel.manuelbdv.es';", 6000);
						   }
						   else{
							 	$("#actualizacionTexto").html('Cargando siguiente paso, por favor espere.'); // Mostrar la respuestas del script PHP. 
							}
							//var action = document.getElementById("form_paypal").action;
							//alert(tipo_pago);
							 if(tipo_pago != 0 && precio > 0){
									$("#form_paypal").submit();
							}//ifn pago
					   }
					   else{ 
					   		mostrar("#actualizacion");
						   	ocultar("#imagenOk");
						   	mostrar("#imagenError");
					   		if(data == '-11'){//-1 jugador 1
						   		$("#actualizacionTexto").html('Error, el jugador 1 ya esta inscrito en esta liga.'); // Mostrar la respuestas del script PHP.
					   		}
							else if(data == '-12'){//-1 jugador 2
						   		$("#actualizacionTexto").html('Error, el jugador 2 ya esta inscrito en esta liga.'); // Mostrar la respuestas del script PHP.
					   		}
							else if(data == '-13'){//-1 jugador 1 y 2
						   		$("#actualizacionTexto").html('Error, los jugadores ya estan inscritos en esta liga.'); // Mostrar la respuestas del script PHP.
					   		}
							else if(data == '-21'){//-2 error al crear jugador 1
						   		$("#actualizacionTexto").html('Error, el e-mail o dni del jugador 1 ya esta registrado.'); // Mostrar la respuestas del script PHP.
					   		}
							else if(data == '-22'){//-2 error al crear jugador 2
						   		$("#actualizacionTexto").html('Error, el e-mail  o dni del jugador 2 ya esta registrado.'); // Mostrar la respuestas del script PHP.
					   		}
							else if(data == '-23'){//-2 error al crear jugador 1 y 2
						   		$("#actualizacionTexto").html('Error, el e-mail  o dni de los jugadores/as 1 y 2 ya estan registrados.'); // Mostrar la respuestas del script PHP.
					   		}
							else{//-3 error mismo jugador 1 y 2
						   		$("#actualizacionTexto").html('Error, el e-mail  o dni de los jugadores/as 1 y 2 son el mismo.'); // Mostrar la respuestas del script PHP.
					   		}
						}//fin else
						
				   }//fin success
			});
		 }//fin if obtenTotal
		 else{
			//error_respuesta('respuesta','Por favor revise el formulario.');
			$("#actualizacionTexto").html('Por favor revise el formulario.');
		 }
		return true; // Evitar ejecutar el submit del formulario.
	 });
	});
});//fin ready

/*function seleccionar(id_jugador,datos,jugador){
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
}*/
function comprueba_login(email,pass,jugador){
	var dataString = "email="+email+"&pass="+pass+"&jugador="+jugador;
	//alert(dataString);
	$.ajax({
        type: "POST",
        url: "buscar_jugador.php",
        data: dataString,
        dataType: "html",
        error: function(){
			//$("#resultado"+jugador).append("Ha ocurrido un error, disculpe las molestias");
			$("#resultado"+jugador).html("<span class='error'>Ha ocurrido un error, disculpe las molestias</span>");
        	//alert("error petición ajax");
        },
        success: function(data){
			//alert(data); 
			if(data > 0){
				if(jugador == 1){formulario.modificaEstado(21,'ok');formulario.modificaEstado(22,'ok');}
				else{formulario.modificaEstado(23,'ok');formulario.modificaEstado(24,'ok');}
				//$("#resultado"+jugador).append("Login del jugador "+jugador+" correcto.");
				$("#resultado"+jugador).html("<span class='ok'>Login del jugador "+jugador+" correcto.</span>");
			}
			else if(data == -1){
				if(jugador == 1){formulario.modificaEstado(21,'error');formulario.modificaEstado(22,'error');}
				else{formulario.modificaEstado(23,'error');formulario.modificaEstado(24,'error');}
				//$("#resultado"+jugador).append("Login de los jugadores 1 y 2 repetidos.");
				$("#resultado"+jugador).html("<span class='error'>Login de los jugadores 1 y 2 repetidos.</span>");
			} 
			else{
				if(jugador == 1){formulario.modificaEstado(21,'error');formulario.modificaEstado(22,'error');}
				else{formulario.modificaEstado(23,'error');formulario.modificaEstado(24,'error');}
				//$("#resultado"+jugador).append("Login del jugador "+jugador+" incorrecto o no existe.");
				$("#resultado"+jugador).html("<span class='error'>Login del jugador "+jugador+" incorrecto o no existe.</span>");
			}                                                                                           
        }
   });//fin ayax
}
function comprueba(elemento){
	//alert(elemento.value);
	if(elemento.value == 'insertar1'){
		mostrar('#jug1_col1');
		mostrar('#jug1_col2');
		mostrar('#jug1_col3');
		ocultar('#div_bus1');
		ocultar('#resultado');
	}
	else if(elemento.value == 'insertar2'){
		mostrar('#jug2_col1');
		mostrar('#jug2_col2');
		mostrar('#jug2_col3');
		ocultar('#div_bus2');
		ocultar('#resultado2');
	}
	else if(elemento.value == 'buscar1'){
		ocultar('#jug1_col1');
		ocultar('#jug1_col2');
		ocultar('#jug1_col3');
		mostrar('#div_bus1');
	}
	else{//buscar2
		ocultar('#jug2_col1');
		ocultar('#jug2_col2');
		ocultar('#jug2_col3');
		mostrar('#div_bus2');
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

function comprueba_pago(valor){	
	var id = 'recibir_pago';
	var posicion = document.getElementById(id).options.selectedIndex; //posicion contrario
	var valor = document.getElementById(id).options[posicion].value; // valor contrario
	var dataString = 'recibir_pago='+valor;
	$.ajax({
        type: "POST",
        url: "recibir_pago.php",
        data: dataString,
        dataType: "html",
        /*error: function(){
			//$("#resultado"+jugador).append("Ha ocurrido un error, disculpe las molestias");
			$("#resultado"+jugador).html("<span class='error'>Ha ocurrido un error, disculpe las molestias</span>");
        	//alert("error petición ajax");
        },*/
        success: function(data){
			//alert(id+'-'+posicion+'-'+valor);                                                                                          
        }
   });//fin ayax
    if(valor == 'O'){//online
    	$('#form_paypal').attr("action", "https://www.paypal.com/cgi-bin/webscr");
	}
	else{//presencial
		$('#form_paypal').attr("action", "http://miligadepadel.manuelbdv.es");
	}
}