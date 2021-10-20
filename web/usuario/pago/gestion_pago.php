<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/publicidad.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
$usuario = unserialize($_SESSION['usuario']);
$email = $usuario->getValor('email');
$id_usuario = $usuario->getValor('id_usuario');
if( $_SESSION['conexion'] != obten_ultimaConexion($id_usuario) ){
	header ("Location: ../cerrar_sesion.php");
}
comprobar_pagina($pagina);
$_SESSION['pagina']  = 'gestion_pago';
//echo phpinfo();
if(isset($_GET["id"])){
	$opcion = substr(decodifica($_GET["id"]), 12, 1);
	$_SESSION['opcion'] = $opcion;	
}
else if(isset($_GET["tx"])){//RECIBE EL PAGO DE PAYPAL
//aqui comprueba el pago porque es mi cuenta
	 	 $opcion = $_SESSION['opcion'];
		 $resultado = paypal_PDT_request($_GET["tx"],obten_identPaypal());
		 $datos_rec = array();
		 $datos_rec = obten_arrayResPaypal($resultado);
		 /*echo '--'.obten_identPaypal().'<br>';
		 for($i=0; $i<count($datos_rec); $i++){
			 echo '::'.$i.'::'.$datos_rec[$i].'<br>';
		 }*/
		 $pos = obten_posicion($datos_rec,'txn_id');
		 if($pos != -1){$txn_id = obten_resDespuesIgual($datos_rec[$pos]);}
		 
		 $pos = obten_posicion($datos_rec,'payment_status');
		 if($pos != -1){$payment_status = obten_resDespuesIgual($datos_rec[$pos]);}
		 
		 if($datos_rec[0] == 'SUCCESS' && $txn_id == $_GET["tx"] && $payment_status == 'Completed' ){//SI ES CORRECTO CONTINUO
			if(obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','transaccion',$_GET["tx"],'','','','','','','') == 0){
				 $item_number = limpiaTexto($_GET["item_number"]);
				 $_SESSION['mensaje_pago'] = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico.<br>Puede acceder a su cuenta para ver los detalles de esta transacción.';
				 $pago_web = new Pago_web($item_number,'','','','','','','','','','','','','','','','');
				 $tipo = $pago_web->getValor('tipo');
				 if($tipo == 'L'){//liga
					$liga_modificar = new Liga($pago_web->getValor('liga'),'','','','','','','','','','','','','','','','');
					$liga_modificar->setValor('bloqueo','N');
					$liga_modificar->setValor('pagado','S');
					$liga_modificar->modificar();
				 }
				 else if($tipo == 'D'){
					 $division_modificar = new Division($pago_web->getValor('division'),'','','','','','','','');
					 $division_modificar->setValor('bloqueo','N');
					 $division_modificar->modificar();
				 }
				 else if($tipo == 'P'){//publicidad
					if($pago_web->getValor('posicion_publi') != ''){//publicidad de pago
						$publicidad_modificar = new Publicidad('','',$pago_web->getValor('id_pago_web'),'','','','','','','','','','','','');
						$publicidad_modificar->setValor('pagado','S');
						$publicidad_modificar->setValor('fecha_fin',date('Y-m-d H:i:s'));
						$publicidad_modificar->modificar();
					}
				 }
				 else{//ida y vuelta
					//A LA HORA DE GENERAR EL CALENDARIO, SI IDA Y VUELTA ESTA ACTIVO, BUSCAR EL PAGO, SI NO ESTÁ, MOSTRAR MENSAJE
				 }
				 
				 /*if(obten_resDespuesIgual($datos_rec[16]) == $_GET["item_number"]){//entra si pago pendiente
				 	//$pago_web->setValor('tarjeta',$mc_gross); //guardo receipt_id
					$asunto = utf8_decode('El Pago nº '.$item_number.' de '.obten_resDespuesIgual($datos_rec[7]).' ha sido realizado correctamente.');
					$copia = obten_resDespuesIgual($datos_rec[15]);//email pago
					$pago_web->setValor('emisor',$copia); 
				 }
				 else{//entra pago completo
					 
				 }*/
				 $pos = obten_posicion($datos_rec,'item_name');
		 		 if($pos != -1){$item_name = obten_resDespuesIgual($datos_rec[$pos]);}
				 
				 $asunto = utf8_decode('El Pago nº '.$item_number.' de '.$item_name.' ha sido realizado correctamente.');
				 
				 $pos = obten_posicion($datos_rec,'payer_email');
		 		 if($pos != -1){$copia = obten_resDespuesIgual($datos_rec[$pos]);}
				 
				 if($copia != ''){$pago_web->setValor('emisor',$copia);}
					 
				 $pago_web->setValor('fecha_limite',date('Y-m-d H:i:s'));
				 $pago_web->setValor('transaccion',$txn_id); 
				 $pago_web->setValor('pagado','S');
				 $pago_web->modificar();
				 //ENVIAR CORREO AL EMISOR
				 include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$mail->addAddress($email);//enviarmos al correo titular de la web
				if($copia != $email){$mail->addCC($copia);}//si es el mismo correo que el de la web no envio copia//enviamo al correo real de pago
				$mail->Subject = $asunto;
				/*if(obten_resDespuesIgual($datos_rec[16]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
				 	$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Pago nº: '.$item_number.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[2]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : '.obten_resDespuesIgual($datos_rec[7]).'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[29]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				 }
				else{//AQUI PAGO CON CUENTA PAYPAL
					
				}*/
				$pos = obten_posicion($datos_rec,'mc_gross');
		 		if($pos != -1){$mc_gross = obten_resDespuesIgual($datos_rec[$pos]);}
				 
				$cuerpo = '<br><br>Detalles:<br><br>';
				$cuerpo .= 'Pago nº: '.$item_number.'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'<br><br>';
				$cuerpo .= 'Descripción : '.$item_name.'.<br><br>';
				$cuerpo .= 'Precio : '.$mc_gross.' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				
				$body = email_jugadorAdmin("<br>¡Gracias por contratar nuestros servicios en miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
				//ENVIAR CORREO AL RECEPTOR (WEB)
				include_once ("../../funciones/f_conexion_email2.php");
				$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$mail2->addAddress($pago_web->getValor('receptor'));//enviarmos al receptor
				$mail2->addCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',5,'','','','','','',''));//EN COPIA AL BACKUP DE CORREOS
				$mail2->Subject = $asunto;
				$mail2->msgHTML($body);//el mismo body
				$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail2->send();
				//compruebo liga
				if(empty($_SESSION['liga']) || !isset($_SESSION['liga'])){
					$id_liga = obten_consultaUnCampo('session','id_liga','liga','usuario',$email,'bloqueo','N','','','','','');
					if($id_liga != ''){
						$liga = new Liga($id_liga,'','','','','','','','','','','','','','','','');
						$_SESSION['liga'] = serialize($liga);
					}
				}
				//compruebo division
				if(empty($_SESSION['division']) || !isset($_SESSION['division'])){
					$id_division = obten_consultaUnCampo('session','id_division','division','liga',$id_liga,'num_division','1','','','','','');
					if($id_division != '' && $id_liga != ''){
						$division = new Division($id_division,'','','','','','','','');
						$_SESSION['division'] = serialize($division);
					}	
				}
		 	}//fin BUSCAR TRANSACCION
		 }//fin SUCCESS
		 else{
			 $_SESSION['mensaje_pago_error'] = 'El pago no se ha realizado correctamente. Pruebe de nuevo a realizar la operación, si el problema persiste revise su cuenta PayPal o informenos a través del formulario de contacto. Disculpe las molestias.';
		 }//fin else
}
else if($_SESSION['opcion'] != '' && $_SESSION['opcion'] >= 0 && $_SESSION['opcion'] <= 2){//si ya existe la opcion INSERTAR EN TODOS LOS GESTION PARA EL REDIRECCIONAMIENTO 
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
else if($opcion == 2){
	$carga_pagina = 'modificar_pago_bloqueado.php';
}
else{
	header ("Location: ../cerrar_sesion.php");
}

if(!empty($_SESSION['liga'])){
	$liga = unserialize($_SESSION['liga']);
	$id_liga = $liga->getValor("id_liga");
}
if(!empty($_SESSION['division'])){
	$division = unserialize($_SESSION['division']);
	$id_division = $division->getValor('id_division');
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
        	Bienvenido <?php echo ucfirst($usuario->getValor('nombre')); ?> <a href="../cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="desplegable_liga">
            <?php 
			if(!empty($_SESSION['liga'])){
				echo '<select name="ligas" id="ligas" class="inputText">';
				desplegable_liga($id_usuario,$id_liga);
				echo '</select>';
			}
			?>
        </div>
        <div class="desplegable_division">
        	<?php 
			if(!empty($_SESSION['liga'])){
				echo '<select name="divisiones" id="divisiones" class="inputText">';
				desplegable_division($id_liga,$id_division);
				echo '</select>';
			}
			?>	
        </div>
        <div class="cuenta"><a href="../cuenta/gestion_cuenta.php">Mi cuenta</a></div>
        <div class="cuenta"><a href="">Contacto</a></div>
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
