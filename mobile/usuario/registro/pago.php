<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/notificacion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
if ($pagina != 'inscribir_equipo'){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
if(isset($_GET["tx"])){
////pago real		//http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php?tx=9YB08729TX536901X&st=Completed&amt=0.50&cc=EUR&cm=&item_number=87
//http://miligadepadel.manuelbdv.es/mobile/usuario/registro/pago.php?tx=4A196903XN640160K&st=Completed&amt=0%2e50&cc=EUR&cm=&item_number=Inscripcion%20-1-2016-05-04%2021%3a00%3a23
	$id_inscripcion = $_SESSION["id_inscripcion"];
	$id_equipo = $_SESSION["id_equipo"];
	$id_pago_admin = $_SESSION["id_pago_admin"];
	if($_SESSION['tx'] == '' &&  $_SESSION['st'] == '' && $_SESSION['item_number'] == ''){
		$_SESSION['tx'] = limpiaTexto($_GET["tx"]);
		$_SESSION['st'] = limpiaTexto($_GET["st"]);
		$_SESSION['item_number'] = limpiaTexto($_GET["item_number"]);
		$_SESSION['amt'] = limpiaTexto($_GET["amt"]);
		/*
		ESTO SOLO SI ALGUNA VEZ SOY MEDIDADOR DE PAGOS
		 $resultado = paypal_PDT_request($_GET["tx"],obten_identPaypal());
		 $datos_rec = array();
		 $datos_rec = obten_arrayResPaypal($resultado);
		 if($datos_rec[0] == 'SUCCESS' && obten_resDespuesIgual($datos_rec[15]) == $_GET["tx"]){//SI ES CORRECTO CONTINUO
		 */
		 if($_SESSION['st'] == 'Completed' && $_SESSION['tx'] != '' && $_SESSION['item_number'] != ''){//SI ES CORRECTO CONTINUO
			if(obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','transaccion',$_SESSION['tx'],'','','','','','','') == 0){
				 $mensaje = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico. Puede acceder a su cuenta para ver los detalles de esta transacción.';
				 $imagen = '<img src="../../../images/ok.png" />';
				 $pago_admin = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','','');
				 $pago_admin->setValor('pagado','S');
				 $pago_admin->setValor('transaccion',$_SESSION['tx']);
				 /*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
				 	$pago_admin->setValor('tarjeta',obten_resDespuesIgual($datos_rec[28])); //guardo receipt_id	
				 }
				 if(obten_resDespuesIgual($datos_rec[15]) != ''){//guardo el email verdadero introducido por cuenta paypal, y si es por tajeta y no esta vacio el del formulario
						
				  }*/
				 //$pago_admin->setValor('emisor',obten_resDespuesIgual($datos_rec[14]));
				 $pago_admin->setValor('fecha',date('Y-m-d H:i:s')); 
				 $pago_admin->modificar();
				 $equipo = new Equipo($id_equipo,'','','','','','','','','');
				 $equipo->setValor('pagado','S');
				 $equipo->modificar();
				 $inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
				 $inscripcion->setValor('pagado','S');
				 $inscripcion->modificar();
				 $notificacion = new Notificacion('',$pago_admin->getValor('usuario'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'modificar_pago_recibido.php',date('Y-m-d H:i:s'),'N');
				 $notificacion->insertar();
				 //ENVIAR CORREO AL PAGADOR JUGADORES DE LA LIGA
				 include_once ("../../funciones/f_conexion_email.php");
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
				
				$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al emisor
				$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al emisor
				if($inscripcion->getValor('email1') != $pago_admin->getValor('emisor') && $inscripcion->getValor('email2') != $pago_admin->getValor('emisor')){
					$mail->addAddress($pago_admin->getValor('emisor'));//enviarmos al emisor
				}
				$mail->AddBCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',6,'','','','','','',''));//EN COPIA BACKUP AL QUE ENVIA EL PAGO
				//$mail->addCC();//en copia al receptor
				$asunto = utf8_decode('Pago de Inscripción en la Liga <'.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'> realizado correctamente!');
				$mail->Subject = $asunto;
				/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : Inscripción en Liga '.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				}
				else{//AQUI PAGO CON CUENTA PAYPAL
					
				}*/
				$cuerpo = '<br><br>Detalles:<br><br>';
				$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
				$cuerpo .= 'Descripción : Inscripción en Liga '.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'.<br><br>';
				$cuerpo .= 'Precio : '.$_SESSION['amt'].' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				
				$body = email_jugadorAdmin("<br>¡Gracias por inscribirte en una Liga de miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
				//ENVIAR CORREO AL RECEPTOR ADMINISTRADOR DE LA LIGA
				include_once ("../../funciones/f_conexion_email2.php");
				$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$email_usuario  = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$pago_admin->getValor('usuario'),'','','','','','','');
				$mail2->addAddress($email_usuario);//enviamos al email principal del administrador
				if($email_usuario != $pago_admin->getValor('receptor')){//si es diferente envio a los dos
					$mail2->addAddress($pago_admin->getValor('receptor'));//enviarmos al correo paypal del administrador donde ha cobrado
				}
				$asunto = utf8_decode('Pago recibido correctamente en tu liga <'.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'>.');
				$mail2->Subject = $asunto;
				/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
					//A MODIFICAR
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Jugadores : '.utf8_encode($inscripcion->getValor('nombre1')).' '.utf8_encode($inscripcion->getValor('apellidos1')).' y '.utf8_encode($inscripcion->getValor('nombre2')).' '.utf8_encode($inscripcion->getValor('apellidos2')).'.<br><br>';
					$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : Inscripción en Liga '.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				}
				else{
					
				}*/
				$cuerpo = '<br><br>Detalles:<br><br>';
				$cuerpo .= 'Jugadores : '.utf8_encode($inscripcion->getValor('nombre1')).' '.utf8_encode($inscripcion->getValor('apellidos1')).' y '.utf8_encode($inscripcion->getValor('nombre2')).' '.utf8_encode($inscripcion->getValor('apellidos2')).'.<br><br>';
				$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
				$cuerpo .= 'Fecha: '.date('Y-m-d H:i:s').'.<br><br>';
				$cuerpo .= 'Descripción : Inscripción en Liga '.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'.<br><br>';
				$cuerpo .= 'Precio : '.$_SESSION['amt'].' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				
				$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail2->msgHTML($body);//el mismo body
				$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail2->send();
		 	}//fin buscar transaccion
			else{//repetido
			 	$mensaje = 'Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico. Puede acceder a su cuenta para ver los detalles de esta transacción.';
				$imagen = '<img src="../../../images/ok.png" />';
		 	}//fin else
		 }//fin SUCCESS
		 else{//repetido
			 $mensaje = 'El pago no se ha realizado correctamente. Pruebe de nuevo a realizar la operación, si el problema persiste revise su cuenta PayPal o informenos a través del formulario de contacto. Disculpe las molestias.';
			 $imagen = '<img src="../../../images/error.png" />';
		 }//fin else
	}//fin if coprobar sessions
	else{
		$mensaje = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico. Puede acceder a su cuenta para ver los detalles de esta transacción.';
		header ("Location: http://miligadepadel.manuelbdv.es");
	}
}//fin if tx
else{
	$mensaje = 'El pago no se ha procesado correctamente. Si ha realizado el pago correctamente en PayPal, el motivo de fallo es que su administrador de la liga no tiene configurada correctamente la cuenta de PayPal para retornar la información del pago, contacte con él y comentalé lo sucedido a través del menú de Jugador. Disculpe las molestias.';
	$imagen = '<img src="../../../images/error.png" />';
}//fin else
cabecera_inicio();
incluir_general(0,0);//(jquery,validaciones) 0=no activo, 1=activo
?>
<link rel="stylesheet" type="text/css" href="css/inscribir_equipo.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
setTimeout ("document.location.href='http://miligadepadel.manuelbdv.es';", 25000);
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<!--<div class="izquierdo">&nbsp;</div>-->
    <div class="contenido">
    	<div class="mensaje">
			<div class="caja_pago">
        	<?php echo $imagen; ?><label><?php echo htmlentities($mensaje); ?></label>
        	</div>
		</div>
        <div class="mensaje">
            <div class="atras"><a href="http://miligadepadel.manuelbdv.es"><span class="botonAtras">INICIO</span></a></div>
		</div>
    </div>
    <!--<div class="derecho">&nbsp;</div>-->
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>