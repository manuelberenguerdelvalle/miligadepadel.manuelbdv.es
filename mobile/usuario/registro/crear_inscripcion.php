<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/notificacion.php");
include_once ("../../../class/jugador.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/puntos.php");
include_once ("../../../class/puntuacion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$id_liga = limpiaTexto3($_SESSION["id_liga"]);
$id_division = limpiaTexto3($_SESSION["id_division"]);
$pass = limpiaTexto3($_SESSION["pass"]);
$tipo_pago = limpiaTexto3($_SESSION["tipo_pago"]);
$precio = limpiaTexto3($_SESSION["precio"]);
$genero_liga = limpiaTexto3($_SESSION["genero"]);
$nombre = limpiaTexto3($_SESSION["nombre"]);
$num_division = limpiaTexto3($_SESSION["num_division"]);
$id_usuario = limpiaTexto3($_SESSION["usuario"]);
$tipo_pago = limpiaTexto($_POST['tipo_pago']);
//obtener cuenta paypal usuario
$cuenta_paypal  = obten_consultaUnCampo('unicas_liga','cuenta_paypal','usuario','id_usuario',$id_usuario,'','','','','','','');
if($_SESSION['recibir_pago'] == 'O' || $_SESSION['recibir_pago'] == 'A'){	
	$_SESSION['recibir_pago'] = 'P';
}
else{
	$_SESSION['recibir_pago'] = 'M';
}
if ( $pagina == 'inscribir_equipo' ){
	$j1_ok = 0;
	$j2_ok = 0;
	include_once ("../../funciones/f_recoger_post.php");//NECESITA F_GENERAL MELONNNNNNNNNNNNNNNN
	$descripcion_noticia = '';
	if(!empty($email_j1)){//si existe el id_jugador del jugador1 entramos por login
		if( ($email_j1 != $email_j2 && $email_j1 != $email2) || $_SESSION['id_jugador1'] > 0){//que no sea el mismo
			if(obten_consultaUnCampo('unicas','id_jugador','jugador','email',$email_j1,'','','','','','','') == $_SESSION['id_jugador1']){
				$jugador1 = new Jugador($_SESSION['id_jugador1'],'','','','','','','','','','','','','','','');
				$id_jugador1 = $jugador1->getValor('id_jugador');
				$dni1 = $jugador1->getValor('dni');
				$nombre1 = $jugador1->getValor('nombre');
				$apellidos1 = $jugador1->getValor('apellidos');
				$password1 = $jugador1->getValor('password');
				$direccion1 = $jugador1->getValor('direccion');
				$fec_nac1 = $jugador1->getValor('fec_nac');
				$zona_juego1 = $jugador1->getValor('zona_juego');
				$ciudad = $jugador1->getValor('ciudad');
				$provincia = $jugador1->getValor('provincia');
				$pais = $jugador1->getValor('pais');
				$telefono1 = $jugador1->getValor('telefono');
				$email1 = $jugador1->getValor('email');
				$genero1 = $jugador1->getValor('genero');
				$j1_ok = 1;
			}
		}//fin jug1 y jug2 diferentes
		else{//jugadores iguales
			$j1_ok = -1;
			$j2_ok = -1;
		}
	}
	else{//datos introducidos del jugador 1
		if(!empty($nombre1) && !empty($apellidos1) && !empty($email1)){
			$jugador_encontrado = buscar_jugador($nombre1,$apellidos1,$email1,$telefono1,$dni1);//comprobar duplicado
			if($jugador_encontrado == 0){//jugador no encontrado es correcto
				$fec_nac1 = $anyo1.'-'.$mes1.'-'.$dia1;
				$j1_ok = 1;
			}
			else{//obtengo el id del jugador encontrado, NO INSCRIBO, VER OPCION DE ENVIAR ERROR
				/*$jugador1 = new Jugador($jugador_encontrado,'','','','','','','','','','','','','','','');
				$id_jugador1 = $jugador1->getValor('id_jugador');
				$dni1 = $jugador1->getValor('dni');
				$nombre1 = $jugador1->getValor('nombre');
				$apellidos1 = $jugador1->getValor('apellidos');
				$password1 = $jugador1->getValor('password');
				$direccion1 = $jugador1->getValor('direccion');
				$fec_nac1 = $jugador1->getValor('fec_nac');
				$ciudad = $jugador1->getValor('ciudad');
				$provincia = $jugador1->getValor('provincia');
				$pais = $jugador1->getValor('pais');
				$telefono1 = $jugador1->getValor('telefono');
				$email1 = $jugador1->getValor('email');
				$genero1 = $jugador1->getValor('genero');*/
			}//fin else
		}//fin comprobacion datos
	}
	if(!empty($email_j2)){//si existe el dni del jugador2 entramos por login
		if( ($email_j2 != $email_j1 && $email_j2 != $email1) || $_SESSION['id_jugador2'] > 0){//que no sea el mismo
			if(obten_consultaUnCampo('unicas','id_jugador','jugador','email',$email_j2,'','','','','','','') == $_SESSION['id_jugador2']){
				$jugador2 = new Jugador($_SESSION['id_jugador2'],'','','','','','','','','','','','','','','');
				$id_jugador2 = $jugador2->getValor('id_jugador');
				$dni2 = $jugador2->getValor('dni');
				$nombre2 = $jugador2->getValor('nombre');
				$apellidos2 = $jugador2->getValor('apellidos');
				$password2 = $jugador2->getValor('password');
				$direccion2 = $jugador2->getValor('direccion');
				$fec_nac2 = $jugador2->getValor('fec_nac');
				$zona_juego2 = $jugador2->getValor('zona_juego');
				$ciudad2 = $jugador2->getValor('ciudad');
				$provincia2 = $jugador2->getValor('provincia');
				$pais2 = $jugador2->getValor('pais');
				$telefono2 = $jugador2->getValor('telefono');
				$email2 = $jugador2->getValor('email');
				$genero2 = $jugador2->getValor('genero');
				$j2_ok = 1;
			}
		}//fin jug1 y jug2 diferentes
		else{//jugadores iguales
			$j1_ok = -1;
			$j2_ok = -1;
		}
	}
	else{//datos introducidos del jugador 2
		if(!empty($nombre2) && !empty($apellidos2) && !empty($email2)){
			$jugador_encontrado = buscar_jugador($nombre2,$apellidos2,$email2,$telefono2,$dni2);//comprobar duplicado
			if($jugador_encontrado == 0){//jugador no encontrado es correcto
				$fec_nac2 = $anyo2.'-'.$mes2.'-'.$dia2;
				$j2_ok = 1;	
			}
			else{//obtengo el id del jugador encontrado, NO INSCRIBO, VER OPCION DE ENVIAR ERROR
				/*$jugador2 = new Jugador($jugador_encontrado,'','','','','','','','','','','','','','','');
				$id_jugador2 = $jugador2->getValor('id_jugador');
				$dni2 = $jugador2->getValor('dni');
				$nombre2 = $jugador2->getValor('nombre');
				$apellidos2 = $jugador2->getValor('apellidos');
				$password2 = $jugador2->getValor('password');
				$direccion2 = $jugador2->getValor('direccion');
				$fec_nac2 = $jugador2->getValor('fec_nac');
				$ciudad2 = $jugador2->getValor('ciudad');
				$provincia2 = $jugador2->getValor('provincia');
				$pais2 = $jugador2->getValor('pais');
				$telefono2 = $jugador2->getValor('telefono');
				$email2 = $jugador2->getValor('email');
				$genero2 = $jugador2->getValor('genero');*/
				//echo 'encontrado2';	
			}//fin else
		}//fin comprobacion
	}
	if($j1_ok == 1 && $j2_ok == 1){
		//si entra aqui todo bien
		if(!empty($id_liga) && !empty($id_division) && !empty($nombre1) && !empty($nombre2) && !empty($apellidos1) && !empty($apellidos2) && !empty($email1) && !empty($email2)){
			if(isset($id_jugador1) && !empty($id_jugador1)){$jug1_enc = busca_jugadorEnOtroEquipo($id_liga,$id_jugador1);}//si existe es porque el jugador1 va por login
			if(isset($id_jugador2) && !empty($id_jugador2)){$jug2_enc = busca_jugadorEnOtroEquipo($id_liga,$id_jugador2);}//si existe es porque el jugador2 va por login
			if(!isset($jugador1)){//si existe es buscado o encontrado NO INSERTO
				if($jug2_enc == ''){//si no hay error en el otro inserto
					$nombre1 = quitarCespeciales($nombre1);
					$nombre1 = quitarAcentos($nombre1);
					$apellidos1 = quitarCespeciales($apellidos1);
					$direccion1 = quitarCespeciales($direccion1);
					$email1 = strtolower($email1);
					/*$password1 = quitarCespeciales($password1);
					$email1 = quitarCespeciales($email1);*/
					$jugador1 = new Jugador(NULL,$dni1,ucwords($nombre1),ucwords($apellidos1),$password1,$direccion1,$fec_nac1,$zona_juego1,$ciudad,$provincia,$pais,$telefono1,$email1,$genero1,0,'J');
					$jugador1->insertar();
					$id_jugador1 = obten_consultaUnCampo('unicas','id_jugador','jugador','email',$email1,'','','','','','','');
				}
			}
			if(!isset($jugador2)){//si existe es buscado o encontrado NO INSERTO
				if($jug1_enc == ''){//si no hay error en el otro inserto
					$nombre2 = quitarCespeciales($nombre2);
					$nombre2 = quitarAcentos($nombre2);
					$apellidos2 = quitarCespeciales($apellidos2);
					$direccion2 = quitarCespeciales($direccion2);
					$email2 = strtolower($email2);
					/*$password2 = quitarCespeciales($password2);
					$email2 = quitarCespeciales($email2);*/
					$jugador2 = new Jugador(NULL,$dni2,ucwords($nombre2),ucwords($apellidos2),$password2,$direccion2,$fec_nac2,$zona_juego2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2,0,'J');
					$jugador2->insertar();
					$id_jugador2 = obten_consultaUnCampo('unicas','id_jugador','jugador','email',$email2,'','','','','','','');
				}
			}
			if($jug1_enc == '' && $jug2_enc == ''){//si no están en ningún equipo de la misma liga
				if($tipo_pago == 0){//si es gratis inserto pagada
					$inscripcion = new Inscripcion(NULL,$id_division,$id_liga,'M',0,'S',$id_jugador1,$dni1,ucwords($nombre1),ucwords($apellidos1),$password1,$direccion1,$fec_nac1,$ciudad,$provincia,$pais,$telefono1,$email1,$genero1,$id_jugador2,$dni2,ucwords($nombre2),ucwords($apellidos2),$password2,$direccion2,$fec_nac2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2);
				}
				else{//de pago
					if($precio == 0){
						$inscripcion = new Inscripcion(NULL,$id_division,$id_liga,'M',0,'S',$id_jugador1,$dni1,ucwords($nombre1),ucwords($apellidos1),$password1,$direccion1,$fec_nac1,$ciudad,$provincia,$pais,$telefono1,$email1,$genero1,$id_jugador2,$dni2,ucwords($nombre2),ucwords($apellidos2),$password2,$direccion2,$fec_nac2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2);
					}
					else{
						$inscripcion = new Inscripcion(NULL,$id_division,$id_liga,$_SESSION['recibir_pago'],$precio,'N',$id_jugador1,$dni1,ucwords($nombre1),ucwords($apellidos1),$password1,$direccion1,$fec_nac1,$ciudad,$provincia,$pais,$telefono1,$email1,$genero1,$id_jugador2,$dni2,ucwords($nombre2),ucwords($apellidos2),$password2,$direccion2,$fec_nac2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2);
					}
				}
				$inscripcion->insertar();//inserto inscripcion
				$notificacion = new Notificacion('',$id_usuario,$id_liga,$id_division,'modificar_inscripcion.php',date('Y-m-d H:i:s'),'N');
				$notificacion->insertar();
				//$_SESSION['id_inscripcion'] = obten_idUltimoSession('id_inscripcion','inscripcion','liga',$id_liga,'division',$id_division,'id_jugador1',$id_jugador1,'id_jugador2',$id_jugador2,''); 
				$_SESSION['id_inscripcion'] = obten_consultaUnCampo('session','id_inscripcion','inscripcion','liga',$id_liga,'division',$id_division,'id_jugador1',$id_jugador1,'id_jugador2',$id_jugador2,'');
				$seg_j1 = obten_consultaUnCampo('unicas','id_seguro','seguro','jugador',$id_jugador1,'','','','','','','');
				$seg_j2 = obten_consultaUnCampo('unicas','id_seguro','seguro','jugador',$id_jugador2,'','','','','','','');
				if($tipo_pago == 0){//gratis
					$equipo = new Equipo(NULL,$id_jugador1,$seg_j1,$id_jugador2,$seg_j2,$id_liga,$id_division,'S',0,obten_fechaHora());
				}
				else{//de pago
					if($precio == 0){
						$equipo = new Equipo(NULL,$id_jugador1,$seg_j1,$id_jugador2,$seg_j2,$id_liga,$id_division,'S',0,obten_fechaHora());
					}
					else{
						$equipo = new Equipo(NULL,$id_jugador1,$seg_j1,$id_jugador2,$seg_j2,$id_liga,$id_division,'N',0,obten_fechaHora());
					}
				}
				$equipo->insertar();//inserto equipo
				if($tipo_pago > 0){//si no es gratis
					$id_equipo = obten_consultaUnCampo('session','id_equipo','equipo','liga',$id_liga,'division',$id_division,'jugador1',$id_jugador1,'jugador2',$id_jugador2,'');
					//$id_equipo = obten_idUltimoEquipo($id_liga,$id_division,$id_jugador1,$id_jugador2);//id del equipo insertado
					$_SESSION['id_equipo'] = $id_equipo;
					if($precio == 0){
						$pago_admin = new Pago_admin(NULL,$id_liga,$id_division,$_SESSION['bd'],$id_equipo,0,'M','S',$cuenta_paypal,$id_usuario,$email1,$_POST['fec_captur'],'','','',$id_jugador1,$id_jugador2,'R');
					}
					else{
						$pago_admin = new Pago_admin(NULL,$id_liga,$id_division,$_SESSION['bd'],$id_equipo,$precio,$_SESSION['recibir_pago'],'N',$cuenta_paypal,$id_usuario,$email1,$_POST['fec_captur'],'','','',$id_jugador1,$id_jugador2,'R');
					}
					$pago_admin->insertar();//inserto el pago
					$_SESSION['id_pago_admin'] = obten_consultaUnCampo('unicas_liga','id_pago_admin','pago_admin','liga',$id_liga,'division',$id_division,'equipo',$id_equipo,'usuario',$id_usuario,'');
					//$_SESSION['id_pago_admin'] = obten_idUltimoUnicasLiga('id_pago_admin','pago_admin','liga',$id_liga,'division',$id_division,'equipo',$id_equipo,'','');
				}
				$id_puntuacion = obten_consultaUnCampo('session','id_puntuacion','puntuacion','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'aplicacion','L','');
				if($id_puntuacion > 0){//si a insertado en algun momento en puntuaciones
					//solo es posible insertar/eliminar puntuaciones al actualizar partido si es partido de grupo, o si es la final
					$puntuacion = new Puntuacion($id_puntuacion,'','','','','','','','','','','','','','','','','');
					$tipo = 0;
					$tipo_puntuacion = 'inscripcion';
					$id_partido = 0;
					$hay_puntos1 = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$id_jugador1,'');
					$hay_puntos2 = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$id_jugador2,'');
					if($id_jugador1 > 0 && $hay_puntos1 == 0 && $puntuacion->getValor($tipo_puntuacion) > 0){//si no es temporal
						//insertamos si no hay puntos
						$puntosj1 = new Puntos('',$id_usuario,$id_jugador1,$_SESSION['bd'],$id_liga,$id_division,$id_partido,obten_fechahora(),$puntuacion->getValor($tipo_puntuacion),$tipo);
						$puntosj1->insertar();
					}//fin j1
					if($id_jugador2 > 0 && $hay_puntos2 == 0 && $puntuacion->getValor($tipo_puntuacion) > 0){//si no es temporal
						//insertamos si no hay puntos
						$puntosj2 = new Puntos('',$id_usuario,$id_jugador2,$_SESSION['bd'],$id_liga,$id_division,$id_partido,obten_fechahora(),$puntuacion->getValor($tipo_puntuacion),$tipo);
						$puntosj2->insertar();
					}//fin j2
					unset($puntosj1,$puntosj2);
				}//fin de puntuacion
				//$descripcion_noticia .= utf8_decode('Un nuevo equipo se ha inscrito compuesto por '.$nombre1.' '.substr($apellidos1,0,1).'. y '.$nombre2.' '.substr($apellidos2,0,1).'.');
				$descripcion_noticia .= 'Un nuevo equipo se ha inscrito compuesto por '.$nombre1.' '.substr($apellidos1,0,1).'. y '.$nombre2.' '.substr($apellidos2,0,1).'.';
				//ENVIAR CORREO INSCRIPCION
				 include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				//$mail->addAddress('manu_oamuf@hotmail.com');
				$mail->addAddress($jugador1->getValor('email'));//enviarmos al primer jugador
				$asunto = utf8_decode('Inscripción en la Liga <'.$_SESSION['nombre'].' división '.$_SESSION['num_division'].'>.');
				$mail->Subject = $asunto;
				$cuerpo = '<br><br>Datos de acceso a la Liga:<br><br>';
				$cuerpo .= 'Liga: '.$_SESSION['nombre'].' división '.$_SESSION['num_division'].'<br><br>';
				if($vista == 1){$cuerpo .= 'Contraseña: '.$pass.' (Actualmente la necesitará para acceder a los datos de su Liga).<br><br>';}
				else{$cuerpo .= 'Contraseña: '.$pass.' (En un futuro puede necesitarla para acceder a los datos de su Liga).<br><br>';}
				$cuerpo .= 'Datos de acceso al Panel de Jugador:<br><br>';
				$cuerpo .= 'Email: '.$jugador1->getValor('email').'<br><br>';
				$cuerpo .= 'Contraseña: '.$jugador1->getValor('password').'<br><br>';
				$cuerpo .= 'Para acceder a tu menú personal, selecciona Jugador, e introduce el e-mail y contraseña en la página de inicio de miligadepadel.manuelbdv.es<br><br>';
				$cuerpo .= 'Mantenga este e-mail en un lugar seguro dónde sólo usted pueda acceder. No borre este e-mail para disponer en cualquier momento de sus datos. Gracias.<br><br>';
				$body = email_jugadorAdmin("<br>¡Gracias por inscribirte en una Liga de miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
				//ENVIAR CORREO AL RECEPTOR
				include_once ("../../funciones/f_conexion_email2.php");
				$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				//$mail2->addAddress('manu_oamuf@hotmail.com');
				$mail->addAddress($jugador2->getValor('email'));//enviarmos al segundo jugador
				$mail2->Subject = $asunto;
				$cuerpo = '<br><br>Datos de acceso a la Liga:<br><br>';
				$cuerpo .= 'Liga: '.$_SESSION['nombre'].' división '.$_SESSION['num_division'].'<br><br>';
				if($vista == 1){$cuerpo .= 'Conraseña: '.$pass.' (Actualmente la necesitará para acceder a los datos de su Liga).<br><br>';}
				else{$cuerpo .= 'Conraseña: '.$pass.' (En un futuro puede necesitarla para acceder a los datos de su Liga).<br><br>';}
				$cuerpo .= 'Datos de acceso al Panel de Jugador:<br><br>';
				$cuerpo .= 'Email: '.$jugador2->getValor('email').'<br><br>';
				$cuerpo .= 'Contraseña: '.$jugador2->getValor('password').'<br><br>';
				$cuerpo .= 'Para acceder a tu menú personal, selecciona Jugador, e introduce el e-mail y contraseña en la página de inicio de miligadepadel.manuelbdv.es<br><br>';
				$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail2->msgHTML($body);//el mismo body
				$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail2->send();
				echo '0';
			}
			else{//si está duplicado
				if($jug1_enc != '' && $jug2_enc != ''){echo '-13';}
				else if($jug2_enc != ''){echo '-12';}
				else{echo '-11';}
			}
		}
		if(!empty($descripcion_noticia)){
			$resumen_noticia = utf8_decode('Nuevo equipo inscrito.');
			$fecha_noticia = obten_fechahora();
			$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,$fecha_noticia,'');
			$noticia->insertar();
			unset($noticia);
		}
		unset($inscripcion,$equipo,$pago_admin,$noticia,$jugador1,$jugador2,$notificacion);
	}//fin if $j1_ok $j2_ok
	else{
		if($j1_ok == 0 && $j2_ok == 1){echo '-21';}//email del jugador 1 ya registrado
		else if($j1_ok == 1 && $j2_ok == 0){echo '-22';}//email del jugador 2 ya registrado
		else if($j1_ok == 0 && $j2_ok == 0){echo '-23';}//email de los jugadores 1 y 2 ya registrados
		else{echo '-33';}//jugadores 1 y 2 son iguales
	}//fin else
}//fin if

?>