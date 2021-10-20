<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/notificacion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
comprobar_pagina_jugador($pagina);
$_SESSION['pagina'] = 'gestion_pago';
if($_SESSION['conexion_jugador'] != obten_ultimaConexion_jugador($_SESSION['id_jugador'])){//modificacion
	header ("Location: ../cerrar_sesion.php");
}
$jugador = unserialize($_SESSION['jugador']);
$genero = $jugador->getValor('genero');
$_SESSION['nombre'] = $jugador->getValor('nombre');
$_SESSION['apellidos'] = $jugador->getValor('apellidos');
if(isset($_GET["id"])){
	$opcion = substr(decodifica($_GET["id"]), 12, 1);
	$_SESSION['opcion'] = $opcion;	
}
else if(isset($_GET["tx"])){
//RECOGEMOS LOS DATOS DE LA URL
//http://miligadepadel.manuelbdv.es/web/jugador/pago/gestion_pago.php?tx=775964678X149042W&st=Completed&amt=0%2e50&cc=EUR&cm=&item_number=23
	/*
	$opcion = $_SESSION['opcion'];
		 $resultado = paypal_PDT_request($_GET["tx"],obten_identPaypal());
		 $datos_rec = array();
		 $datos_rec = obten_arrayResPaypal($resultado);
	*/
	if($_SESSION['tx'] == '' &&  $_SESSION['st'] == '' && $_SESSION['item_number'] == ''){
		$_SESSION['tx'] = limpiaTexto($_GET["tx"]);
		$_SESSION['st'] = limpiaTexto($_GET["st"]);
		$_SESSION['item_number'] = limpiaTexto($_GET["item_number"]);
		$_SESSION['amt'] = limpiaTexto($_GET["amt"]);
		 //if($datos_rec[0] == 'SUCCESS' && obten_resDespuesIgual($datos_rec[15]) == $_GET["tx"]){//SI ES CORRECTO CONTINUO
		 if($_SESSION['st'] == 'Completed' && $_SESSION['tx'] != '' && $_SESSION['item_number'] != ''){//SI ES CORRECTO CONTINUO
			if(obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','transaccion',$_SESSION['tx'],'','','','','','','') == 0){
				 $id_pago_admin = limpiaTexto($_GET["item_number"]);
				 $_SESSION['mensaje_pago'] = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico. Puede acceder a su cuenta para ver los detalles de esta transacción.';
				 $pago_admin = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','','');
				 $_SESSION['bd'] = $pago_admin->getValor('bd');
				 $pago_admin->setValor('pagado','S');
				 $pago_admin->setValor('transaccion',$_SESSION['tx']);
				 /*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
				 	$pago_admin->setValor('tarjeta',obten_resDespuesIgual($datos_rec[28])); //guardo receipt_id	
				 }
				 if(obten_resDespuesIgual($datos_rec[15]) != ''){//guardo el email verdadero introducido por cuenta paypal, y si es por tajeta y no esta vacio el del formulario
						
				  }*/
				 //$pago_admin->setValor('emisor',obten_resDespuesIgual($datos_rec[15]));
				 $pago_admin->setValor('fecha',date('Y-m-d H:i:s')); 
				 $pago_admin->modificar();
				 $equipo = new Equipo($pago_admin->getValor('equipo'),'','','','','','','','','');
				 $equipo->setValor('pagado','S');
				 $equipo->modificar();
				 $inscripcion = new Inscripcion('',$equipo->getValor('division'),$equipo->getValor('liga'),'','','',$equipo->getValor('jugador1'),'','','','','','','','','','','','',$equipo->getValor('jugador2'),'','','','','','','','','','','','');
				 //OBTENER EL NOMBRE DE LA LIGA Y EL NUM DE DIVISION.
				 $inscripcion->setValor('pagado','S');
				 $inscripcion->modificar();
				 $notificacion = new Notificacion('',$pago_admin->getValor('usuario'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'modificar_pago_recibido.php',date('Y-m-d H:i:s'),'N');
				 $notificacion->insertar();//NOTIFICACION PARA EL USUARIO
				 $nombre = utf8_encode(obten_consultaUnCampo('session','nombre','liga','id_liga',$pago_admin->getValor('liga'),'','','','','','',''));
				 $num_division = obten_consultaUnCampo('session','num_division','division','id_division',$pago_admin->getValor('division'),'','','','','','','');
				 $email_admin = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$pago_admin->getValor('usuario'),'','','','','','','');
				 //ENVIAR CORREO AL PAGADOR
				 include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
				$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al emisor
				$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al emisor
				if($inscripcion->getValor('email1') != $pago_admin->getValor('emisor') && $inscripcion->getValor('email2') != $pago_admin->getValor('emisor')){
					$mail->addAddress($pago_admin->getValor('emisor'));//enviarmos al emisor
				}
				//$mail->addCC();//en copia al receptor
				$mail->AddBCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',6,'','','','','','',''));//EN COPIA AL QUE ENVIA EL PAGO
				$asunto = utf8_decode('Pago de Inscripción en la Liga <'.$nombre.' división '.$num_division.'> realizado correctamente!');
				$mail->Subject = $asunto;
				/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : Inscripción en Liga '.$nombre.' división '.$num_division.'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				}
				else{//AQUI PAGO CON CUENTA PAYPAL
					
				}*/
				$cuerpo = '<br><br>Detalles:<br><br>';
				$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
				$cuerpo .= 'Descripción : Inscripción en Liga '.$nombre.' división '.$num_division.'.<br><br>';
				$cuerpo .= 'Precio : '.$_SESSION['amt'].' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				$body = email_jugadorAdmin("<br>¡Gracias por inscribirte en una Liga de miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
				//ENVIAR CORREO AL RECEPTOR
				include_once ("../../funciones/f_conexion_email2.php");
				$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$mail2->addAddress($email_admin);//enviarmos al correo del administrador
				$mail2->addAddress($pago_admin->getValor('receptor'));//enviarmos al correo paypal del administrador
				$asunto = utf8_decode('Pago recibido correctamente en tu liga '.$nombre.' división '.$num_division.'.');
				$mail2->Subject = $asunto;
				/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
					//A MODIFICAR
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Jugadores : '.utf8_encode($inscripcion->getValor('nombre1')).' '.utf8_encode($inscripcion->getValor('apellidos1')).' y '.utf8_encode($inscripcion->getValor('nombre2')).' '.utf8_encode($inscripcion->getValor('apellidos2')).'.<br><br>';
					$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : Inscripción en Liga '.$nombre.' división '.$num_division.'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				}
				else{
					
				}*/
				$cuerpo = '<br><br>Detalles:<br><br>';
				$cuerpo .= 'Jugadores : '.utf8_encode($inscripcion->getValor('nombre1')).' '.utf8_encode($inscripcion->getValor('apellidos1')).' y '.utf8_encode($inscripcion->getValor('nombre2')).' '.utf8_encode($inscripcion->getValor('apellidos2')).'.<br><br>';
				$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
				$cuerpo .= 'Descripción : Inscripción en Liga '.$nombre.' división '.$num_division.'.<br><br>';
				$cuerpo .= 'Precio : '.$_SESSION['amt'].' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail2->msgHTML($body);//el mismo body
				$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail2->send();
		 	}//fin buscar transaccion
		 }//fin SUCCESS
		 else{//repetido
			 $_SESSION['mensaje_error'] = 'El pago no se ha realizado correctamente. Pruebe de nuevo a realizar la operación, si el problema persiste revise su cuenta PayPal o informenos a través del formulario de contacto. Disculpe las molestias.';
		 }//fin else
	}//fin comprobar session
	else{
		$_SESSION['mensaje_pago'] = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico. Puede acceder a su cuenta para ver los detalles de esta transacción.';
	}
}//fin if tx
else if($_SESSION['opcion'] != '' && $_SESSION['opcion'] >= 0 && $_SESSION['opcion'] <= 1){//si ya existe la opcion INSERTAR EN TODOS LOS GESTION PARA EL REDIRECCIONAMIENTO 
	$opcion = $_SESSION['opcion'];	
}
else{
	header ("Location: ../cerrar_sesion.php");
}
if($opcion == 0){
	$carga_pagina = 'pago_enviado.php';
}
else if($opcion == 1){
	$carga_pagina = 'pago_recibido.php';
}
else{
	header ("Location: ../cerrar_sesion.php");
}
cabecera_inicio();
incluir_general(1,1);
?>
<link rel="stylesheet" type="text/css" href="../../css/panel_usuario.css" />
<script src="../../javascript/selects_principales.js" type="text/javascript"></script>
<script src="../../javascript/pace.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	cargar("#menuIzq","../menu_izq.php");
	cargar(".contenido","<?php echo $carga_pagina; ?>");
});
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<div class="superior">
    	<div class="nombre_usuario">
        	<?php echo $jugador->getValor('nombre'); ?> <a href="../cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="desplegable_liga">&nbsp;</div>
        <div class="desplegable_division">&nbsp;</div>
        <div class="cuenta">&nbsp;</div>
        <div class="cuenta"><a href="">&nbsp;</a></div>
        <div class="traductor"><div id="google_translate_element"></div></div>
    </div>
    <div id="menuIzq" class="menuIzq">

    </div>
    <div class="contenido">
		
    </div>
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>
