<?php
/*
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
$opcion = $_SESSION['opcion'];
if ($pagina != 'gestion_pago'){
	header ("Location: ../cerrar_sesion.php");
}
//verificamos pago online SOLO SE PUEDE HACER SI ES MI CUENTA POR EL IDENTIFICADOR DE PAYPAL QUE SE NECESITA
//obten_identPaypal()
 if(isset($_POST['id_pago_admin']) && !empty($_POST['id_pago_admin'])){//comprobamos pago online
	$id_pago_admin = limpiaTexto($_POST['id_pago_admin']);
	$id_trans = limpiaTexto3($_POST['texto']);
	if(obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','transaccion',$id_trans,'','','','','','','') == 0){
		$pago_admin = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','','');
		$_SESSION['bd'] = $pago_admin->getValor('bd');
		$nombre_liga = obten_consultaUnCampo('session','nombre','liga','id_liga',$pago_admin->getValor('liga'),'','','','','','','');
		$num_division = obten_consultaUnCampo('session','num_division','division','id_division',$pago_admin->getValor('division'),'','','','','','','');
		$inscripcion = new Inscripcion('',$pago_admin->getValor('division'),$pago_admin->getValor('liga'),'','','',$pago_admin->getValor('id_jugador1'),'','','','','','','','','','','','',$pago_admin->getValor('id_jugador2'),'','','','','','','','','','','','');
		$equipo = new Equipo('',$inscripcion->getValor('id_jugador1'),'',$inscripcion->getValor('id_jugador2'),'',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),'','','');
		//if(strtotime(date('Y-m-d H:i:s')) < (strtotime($pago_admin->getValor('fecha')) + pasar_segundos(3)) ){//si estamos dentro de los 3 días que tenemos información
			$res = paypal_PDT_request($id_trans,obten_identPaypal());
			$datos_rec = array();
			$datos_rec = obten_arrayResPaypal($res);
			for($i=0; $i<count($datos_rec); $i++){
				echo $datos_rec[$i].'<br>';
			}
			/*
			if(obten_resDespuesIgual($datos_rec[25]) == 'Completed' || obten_resDespuesIgual($datos_rec[24]) == 'Completed' ){//completada
				if($pago_admin->getValor('receptor') == obten_resDespuesIgual($datos_rec[17]) && $pago_admin->getValor('precio') == obten_resDespuesIgual($datos_rec[28])){//pago ok
					$pago_admin->setValor('pagado','S'); //si esta a 0, es eliminado
					$pago_admin->setValor('transaccion',$id_trans);
					$pago_admin->setValor('emisor',obten_resDespuesIgual($datos_rec[14]));
				 	$pago_admin->setValor('fecha',date('Y-m-d H:i:s')); 
					$pago_admin->modificar();
					$equipo->setValor('pagado','S');
				 	$equipo->modificar();
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
						$mail->AddBCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',6,'','','','','','',''));//EN COPIA AL SISTEMA
						//$mail->addCC();//en copia al receptor
						$asunto = utf8_decode('Pago de Inscripción en la Liga <'.$nombre_liga.' división '.$num_division.'> realizado correctamente!');
						$mail->Subject = $asunto;
						$cuerpo = '<br><br>Detalles:<br><br>';
						$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
						$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
						$cuerpo .= 'Descripción : Inscripción en Liga '.$nombre_liga.' división '.$num_division.'.<br><br>';
						$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[28]).' EUR (con I.V.A).<br><br>';
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
						$asunto = utf8_decode('Pago recibido correctamente en tu liga <'.$nombre_liga.' división '.$num_division.'>.');
						$mail2->Subject = $asunto;
						$cuerpo = '<br><br>Detalles:<br><br>';
						$cuerpo .= 'Jugadores : '.utf8_encode($inscripcion->getValor('nombre1')).' '.utf8_encode($inscripcion->getValor('apellidos1')).' y '.utf8_encode($inscripcion->getValor('nombre2')).' '.utf8_encode($inscripcion->getValor('apellidos2')).'.<br><br>';
						$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
						$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
						$cuerpo .= 'Descripción : Inscripción en Liga '.$nombre_liga.' división '.$num_division.'.<br><br>';
						$cuerpo .= 'Precio : '.obten_resDespuesIgual($datos_rec[28]).' EUR (con I.V.A).<br><br>';
						$cuerpo .= 'Pago realizado a través de PayPal.<br><br>';
						
						$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es!<br>",$cuerpo);
						$mail2->msgHTML($body);//el mismo body
						$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail2->send();
						echo '0';
				}//fin if emisor receptor
				else{
					echo '1';
				}
			}//fin if estado
			else{
				echo '1';
			}
		//}//fin if dias
	}//FIN CONSULTAR TRANSACCION
	else{
		echo '1';
	}
}//fin if

*/
