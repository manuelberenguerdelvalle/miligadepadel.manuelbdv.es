<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_email.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario_publi.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/publicidad_gratis.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
comprobar_pagina_publicidad($pagina);
$_SESSION['pagina'] = 'gestion_pago';
$usuario_publi = unserialize($_SESSION['usuario_publi']);
if( $_SESSION['conexion_usuario_publi'] != obten_ultimaConexion_publicidad($usuario_publi->getValor('id_usuario_publi')) ){//modificacion
	header ("Location: ../cerrar_sesion.php");
}

if(isset($_GET["id"])){
	$opcion = substr(decodifica($_GET["id"]), 12, 1);
	$_SESSION['opcion'] = $opcion;	
}
else if(isset($_GET["tx"])){
	 	$opcion = $_SESSION['opcion'];
		 $resultado = paypal_PDT_request($_GET["tx"],obten_identPaypal());
		 $datos_rec = array();
		 $datos_rec = obten_arrayResPaypal($resultado);
		 
		 $pos = obten_posicion($datos_rec,'txn_id');
		 if($pos != -1){$txn_id = obten_resDespuesIgual($datos_rec[$pos]);}
		 
		 $pos = obten_posicion($datos_rec,'payment_status');
		 if($pos != -1){$payment_status = obten_resDespuesIgual($datos_rec[$pos]);}
		 
		 if($datos_rec[0] == 'SUCCESS' && $txn_id == $_GET["tx"] && $payment_status == 'Completed'){//SI ES CORRECTO CONTINUO
			//if(obten_transaccionPagoWeb($_GET["tx"]) == 0){
			if(obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','transaccion',$_GET["tx"],'','','','','','','') == 0){
				 $item_number = limpiaTexto($_GET["item_number"]);
				 $_SESSION['mensaje_pago'] = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico.<br>Puede acceder a su cuenta para ver los detalles de esta transacción.';
				 $pago_web = new Pago_web($item_number,'','','','','','','','','','','','','','','','');
				 $tipo = $pago_web->getValor('tipo');
				 $pago_web->setValor('fecha_limite',date('Y-m-d H:i:s'));
				 $pago_web->setValor('transaccion',$txn_id);
				 $pago_web->setValor('pagado','S');
				  /*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
				 	$pago_web->setValor('tarjeta',obten_resDespuesIgual($datos_rec[28])); //guardo receipt_id
					$asunto = utf8_decode('El Pago nº '.$item_number.' de '.obten_resDespuesIgual($datos_rec[24]).' ha sido realizado correctamente.');
				 }
				 else{//AQUI SI ES PAGO CON CUENTA PAYPAL
					 $asunto = utf8_decode('El Pago nº '.$item_number.' de '.obten_resDespuesIgual($datos_rec[7]).' ha sido realizado correctamente.');
				 }*/
				 
				 $pos = obten_posicion($datos_rec,'item_name');
		 		 if($pos != -1){$item_name = obten_resDespuesIgual($datos_rec[$pos]);}
				 
				 $asunto = utf8_decode('El Pago nº '.$item_number.' de '.$item_name.' ha sido realizado correctamente.');
				 
				 $pos = obten_posicion($datos_rec,'payer_email');
		 		 if($pos != -1){$payer_email = obten_resDespuesIgual($datos_rec[$pos]);}
				 
				 if($payer_email != ''){//guardo el email verdadero introducido por cuenta paypal, y si es por tajeta y no esta vacio el del formulario
					$pago_web->setValor('emisor',$payer_email); 
				 }
				 $pago_web->modificar();
				 $publicidad_modificar = new Publicidad_gratis('','',$pago_web->getValor('id_pago_web'),'','','','','','','','','');
				 $publicidad_modificar->setValor('pagado','S');
				 //REPROGRAMAR LAS FECHAS
				 $nueva_fecha_fin = resto_suscripcion($publicidad_modificar->getValor('fecha'),$publicidad_modificar->getValor('fecha_fin'));//calcula el tiempo que ha pasado desde que se creo la publicidad hasta el pago, al redondear siempre sale un día más.
				 $publicidad_modificar->setValor('fecha_fin',$nueva_fecha_fin);
				 $publicidad_modificar->modificar();
				 
				 //ENVIAR CORREO AL PAGADOR PATROCINADOR
				 include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$mail->addAddress($pago_web->getValor('emisor'));//enviarmos al emisor
				if($pago_web->getValor('emisor') != $usuario_publi->getValor('email')){$mail->addCC($usuario_publi->getValor('email'));}//si es el mismo correo que el de la web no envio copia//enviamo al correo real de pago
				//$mail->addCC();//en copia al receptor
				$mail->Subject = $asunto;
				/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
				 	$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Pago nº: '.$item_number.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : '.obten_resDespuesIgual($datos_rec[24]).'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				 }
				else{//AQUI PAGO CON CUENTA PAYPAL
				}*/
				$pos = obten_posicion($datos_rec,'mc_gross');
		 		if($pos != -1){$mc_gross = obten_resDespuesIgual($datos_rec[$pos]);}
				
				$cuerpo = '<br><br>Detalles:<br><br>';
				$cuerpo .= 'Pago nº: '.$item_number.'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
				$cuerpo .= 'Descripción : '.$item_name.'.<br><br>';
				$cuerpo .= 'Precio : '.$mc_gross.' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				$body = email_jugadorAdmin("<br>¡Gracias por contratar nuestros servicios en miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
				
				//ENVIAR CORREO AL RECEPTOR WEB
				include_once ("../../funciones/f_conexion_email2.php");
				$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$mail2->addAddress($pago_web->getValor('receptor'));//enviarmos al receptor
				$mail2->addCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',5,'','','','','','',''));//EN COPIA AL BACKUP DE CORREOS
				$mail2->Subject = $asunto;
				$mail2->msgHTML($body);//el mismo body
				$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail2->send();
				
		 	}//fin buscar transaccion
		 }//fin SUCCESS
		 else{
			 $_SESSION['mensaje_pago_error'] = 'El pago no se ha realizado correctamente. Pruebe de nuevo a realizar la operación, si el problema persiste revise su cuenta PayPal o informenos a través del formulario de contacto. Disculpe las molestias.';
		 }//fin else
}//fin tx
else if($_SESSION['opcion'] != '' && $_SESSION['opcion'] >= 0 && $_SESSION['opcion'] <= 1){//si ya existe la opcion INSERTAR EN TODOS LOS GESTION PARA EL REDIRECCIONAMIENTO 
	$opcion = $_SESSION['opcion'];	
}
else{
	header ("Location: ../cerrar_sesion.php");
}

if($opcion == 0){
	$carga_pagina = 'modificar_pago_enviado.php';
}
else if($opcion == 1){
	$carga_pagina = 'modificar_pago_recibido.php';
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
	ligasydivisiones("<?php echo $carga_pagina; ?>");
});
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<div class="superior">
    	<div class="nombre_usuario">
        	<?php echo ucwords($usuario_publi->getValor('nombre')); ?> <a href="../cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="cuenta"><a href="../../paginas/basicas/contacto.php" target="_blank">Contacto</a></div>
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
