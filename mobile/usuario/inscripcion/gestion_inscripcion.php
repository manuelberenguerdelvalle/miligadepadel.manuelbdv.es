<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_email.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/notificacion.php");
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
$_SESSION['pagina']  = 'gestion_inscripcion';
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$nombre_liga = $liga->getValor('nombre');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_division = $division->getValor('num_division');
if(isset($_GET["id"])){
	$opcion = substr(decodifica($_GET["id"]), 12, 1);
	$_SESSION['opcion'] = $opcion;	
}
/*
LAS DEVOLUCIONES ONLINE SON IMPOSIBLES, LOS JUGADORES NO VAN A TENER RETORNO AUTOMATICO NI CUENTA PREMIERE
else if(isset($_GET["tx"])){//ELIMINACION DE INSCRIPCION PAGADA CON CUENTA PAYPAL, DEVOLUCION
			$opcion = $_SESSION['opcion'];
			 $resultado = paypal_PDT_request($_GET["tx"],obten_identPaypal());
			 $datos_rec = array();
			 $datos_rec = obten_arrayResPaypal($resultado);
			 if($datos_rec[0] == 'SUCCESS' && obten_resDespuesIgual($datos_rec[15]) == $_GET["tx"]){//SI ES CORRECTO CONTINUO
			 	if(obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','transaccion',$_GET["tx"],'','','','','','','') == 0){
				//if(obten_transaccionPagoAdmin($_GET["tx"]) == 0){
					 $tx = $_GET["tx"];
					 $divide = array();
					 $divide = explode('-',$_GET["item_number"]);//pago_admin con el DEV-id_pago_admin
					 $id_pago_admin = limpiaTexto($divide[1]);
					 $_SESSION['mensaje_pago'] = 'Gracias por su pago. Su transacción ha finalizado y le hemos enviado un recibo de su compra por correo electrónico.<br>Puede acceder a su cuenta para ver los detalles de esta transacción.';
					 //$id_inscripcion = obten_resDespuesIgual($datos_rec[23]);
					 $pago_admin = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','','');
					 $equipo = new Equipo($pago_admin->getValor('equipo'),'','','','','','','','','');
					 $inscripcion = new Inscripcion('',$equipo->getValor('division'),$equipo->getValor('liga'),'','','',$equipo->getValor('jugador1'),'','','','','','','','','','','','',$equipo->getValor('jugador2'),'','','','','','','','','','','','');
					 $datos_jugadores = $inscripcion->getValor('nombre1').' '.$inscripcion->getValor('apellidos1').'-'.$inscripcion->getValor('nombre2').' '.$inscripcion->getValor('apellidos2');
					 $tarjeta = '';
					 /*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
						$tarjeta = obten_resDespuesIgual($datos_rec[28]); //guardo receipt_id	
					 }*/
/*
					 if(obten_resDespuesIgual($datos_rec[14]) != ''){//guardo el email verdadero introducido por cuenta paypal, y si es por tajeta y no esta vacio el del formulario
					 	$emisor = obten_resDespuesIgual($datos_rec[14]);
					  }
					  else{
						  $emisor = $pago_admin->getValor('receptor');
					  }
					 //devolucion = pago_admin pero con emisor y receptor invertidos y equipo a -1
					 $devolucion = new Pago_admin('',$pago_admin->getValor('liga'),$pago_admin->getValor('division'),$pago_admin->getValor('bd'),-1,$pago_admin->getValor('precio'),$pago_admin->getValor('modo_pago'),'S',$pago_admin->getValor('emisor'),$pago_admin->getValor('usuario'),$emisor,date('Y-m-d H:i:s'),$tx,$tarjeta,$datos_jugadores,$pago_admin->getValor('jugador1'),$pago_admin->getValor('jugador2'),'E');
					 $devolucion->insertar();
					 //$id_devolucion = obten_idUltimoUnicasLiga('id_pago_admin','pago_admin','liga',$inscripcion->getValor('liga'),'division',$inscripcion->getValor('division'),'equipo',$equipo->getValor('id_equipo'),'','');
                     $id_devolucion = obten_consultaUnCampo('unicas_liga','id_pago_admin','pago_admin','liga',$inscripcion->getValor('liga'),'division',$inscripcion->getValor('division'),'equipo','-1','','','');
					 //notificaciones para los jugadores
					 $notificacion1 = new Notificacion('',$pago_admin->getValor('jugador1'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'pago_recibido.php',date('Y-m-d H:i:s'),'N');
					 $notificacion1->insertar();
					 $notificacion2 = new Notificacion('',$pago_admin->getValor('jugador2'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'pago_recibido.php',date('Y-m-d H:i:s'),'N');
					 $notificacion2->insertar();
					 //ENVIAR CORREO AL RECEPTOR JUGADORES
					 include_once ("../../funciones/f_conexion_email.php");
					$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
					$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
					//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
					$mail->addAddress($inscripcion->getValor('email1'));//enviarmos a los receptores
					$mail->addAddress($inscripcion->getValor('email2'));//enviarmos a los receptores
					if($inscripcion->getValor('email1') != $pago_admin->getValor('emisor') && $inscripcion->getValor('email2') != $pago_admin->getValor('emisor')){
						$mail->addAddress($pago_admin->getValor('emisor'));//enviarmos al receptor que es el emisor
					}
					$asunto = utf8_decode('Devolución de la Inscripción en la Liga <'.$nombre_liga.' division '.$num_division.'> reembolsado correctamente!');
					$mail->Subject = $asunto;
					/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
						$cuerpo = '<br><br>Detalles:<br><br>';
						$cuerpo .= 'El administrador ha cancelado tu inscripción en la liga '.$nombre_liga.' division '.$num_division.' y ha reembolsado el precio de tu inscripción en la misma cuenta con la que efectuaste el pago.<br><br>';
						$cuerpo .= 'Devolucion nº: '.$id_devolucion.'.<br><br>';
						$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
						$cuerpo .= 'Descripción : '.obten_resDespuesIgual($datos_rec[24]).'.<br><br>';
						$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
						$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				    }
				    else{//AQUI PAGO CON CUENTA PAYPAL
						
				    }*/
/*
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'El administrador ha cancelado tu inscripción en la liga '.$nombre_liga.' division '.$num_division.' y ha reembolsado el precio de tu inscripción en la misma cuenta con la que efectuaste el pago.<br><br>';
					$cuerpo .= 'Devolucion nº: '.$id_devolucion.'.<br><br>';
					$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
					$cuerpo .= 'Descripción : '.obten_resDespuesIgual($datos_rec[6]).'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[28]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
					
					$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es!<br>",$cuerpo);
					$mail->msgHTML($body);
					$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
					$mail->send();
					//ENVIAR CORREO AL EMISOR EL ADMINISTRADOR
					include_once ("../../funciones/f_conexion_email2.php");
					$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
					$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
					//$mail2->addAddress('manu_oamuf@hotmail.com');
					$email_usuario  = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$pago_admin->getValor('usuario'),'','','','','','','');
					$mail2->addAddress($email_usuario);//enviamos al email principal del administrador
					if($email_usuario != obten_resDespuesIgual($datos_rec[14])){//si es diferente envio a los dos
						$mail2->addAddress(obten_resDespuesIgual($datos_rec[14]));//enviarmos al emisor que es el usuario
					}
					$mail2->AddBCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',6,'','','','','','',''));//EN COPIA AL QUE ENVIA EL PAGO
					$mail2->Subject = $asunto;
					/*if(obten_resDespuesIgual($datos_rec[26]) == $_GET["item_number"]){//AQUI ENTRA SI ES PAGO CON TARJETA
						$cuerpo = '<br><br>Detalles:<br><br>';
						$cuerpo .= 'Has cancelado una inscripción en la liga '.$nombre_liga.' division '.$num_division.' y se ha reembolsado el precio de la inscripción en la misma cuenta que realizaron el pago.<br><br>';
						$cuerpo .= 'Devolucion nº: '.$id_devolucion.'.<br><br>';
						$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[6]),0,21).'.<br><br>';
						$cuerpo .= 'Descripción : '.obten_resDespuesIgual($datos_rec[24]).'.<br><br>';
						$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[1]).' EUR (con I.V.A).<br><br>';
						$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
				    }
				    else{//AQUI PAGO CON CUENTA PAYPAL
						
				    }*/
/*
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Has cancelado una inscripción en la liga '.$nombre_liga.' division '.$num_division.' y se ha reembolsado el precio de la inscripción en la misma cuenta que realizaron el pago.<br><br>';
					$cuerpo .= 'Devolucion nº: '.$id_devolucion.'.<br><br>';
					$cuerpo .= 'Fecha: '.substr(obten_resDespuesIgual($datos_rec[2]),0,21).'.<br><br>';
					$cuerpo .= 'Descripción : '.obten_resDespuesIgual($datos_rec[6]).'.<br><br>';
					$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[28]).' EUR (con I.V.A).<br><br>';
					$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
					$mail2->msgHTML($body);//el mismo body
					$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
					$mail2->send();
					//$equipo->getValor('jugador1').'-'.$equipo->getValor('jugador2')
					$pago_admin->setValor('equipo',0); //si esta a 0, es eliminado
					$pago_admin->setValor('estado','D');//si esta a D, es eliminado
					$pago_admin->modificar();
					$inscripcion->borrar();
					$equipo->borrar();
				}//fin buscar transaccion
			 }//fin SUCCESS
			 else{
				$_SESSION['mensaje_pago_error'] = 'El pago no se ha realizado correctamente. Pruebe de nuevo a realizar la operación, si el problema persiste revise su cuenta PayPal o informenos a través del formulario de contacto. Disculpe las molestias.';
			 }//fin else
}//fin if tx*/
else if($_SESSION['opcion'] != '' && $_SESSION['opcion'] >= 0 && $_SESSION['opcion'] <= 1){//si ya existe la opcion 
	$opcion = $_SESSION['opcion'];	
}
else{
	header ("Location: ../cerrar_sesion.php");
}

if($opcion == 0){
	$carga_pagina = 'modificar_inscripcion.php';
}
else if($opcion == 1){
	$carga_pagina = 'insertar_inscripcion.php';
}
else{
	header ("Location: ../cerrar_sesion.php");
}
//SE GUARDA EN SESSION
$_SESSION['cuenta_paypal'] = $usuario->getValor('cuenta_paypal');
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['email'] = $email;
$_SESSION['bd'] =  $usuario->getValor('bd');

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
    	<div class="nombre_usuario"> <a href="../cerrar_sesion.php" >Salir</a></div>
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
