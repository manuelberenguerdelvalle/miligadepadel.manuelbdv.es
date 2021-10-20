<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/pago_web.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:10px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:25%;
	color:#006;
}
.actualizacion img{
	width:10%;
	margin-top:1%;
	margin-right:1%;
}
</style>
<?php
session_start();
$pagina = $_SESSION['pagina'];
$liga = unserialize($_SESSION['liga']);
$opcion = $_SESSION['opcion'];
$id_usuario = $_SESSION['id_usuario'];
if($_SESSION['cuenta_paypal'] != ''){$email_ins = $_SESSION['cuenta_paypal'];}
else{$email_ins = $_SESSION['email'];}
$bd_usuario = $_SESSION['bd'];
$tipo_pago = $liga->getValor('tipo_pago');
$fecha_creacion = $liga->getValor('fec_creacion');//fecha de creacion de la liga, para generar los pagos
//cargo las variables si están deshabilitadas (no se envían disabled)
if(!isset($genero)){$genero = $liga->getValor('genero');}
if(!isset($pais)){$pais = $liga->getValor('pais');}
if(!isset($provincia)){$provincia = $liga->getValor('provincia');}
if(!isset($ciudad)){$ciudad = $liga->getValor('ciudad');}
if(!isset($idayvuelta)){$idayvuelta = $liga->getValor('idayvuelta');}
//if(!isset($tipo_pago)){$tipo_pago = $liga->getValor('tipo_pago');}
if ( $pagina != 'gestion_liga' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
		$descripcion_noticia = '';
		$texto = 'La actualización se ha realizado correctamente.';
		if($tipo_pago == 0){//si es gratis compruebo 
			if($vista == 1){$vista = 0;}
			if($idayvuelta == 'S'){$idayvuelta = 'N';}
			if($movimientos > 0){$movimientos = 0;}
		}
		$id_liga = $liga->getValor('id_liga');
		$division_1 = new Division('','','',$id_liga,'',1,'','','');
		$id_division = $division_1->getValor('id_division');//id_divison num 1, la que entra con la liga
		$nombre = ucfirst(mb_strtolower($nombre));
		$nombre_repetido = obten_consultaUnCampo('session','COUNT(id_liga)','liga','nombre',$nombre,'','','','','','','');
		if( ($liga->getValor('nombre') != $nombre) && ($nombre_repetido == 0) ){
			if($nombre != ''){
				$liga->setValor('nombre',$nombre);
				$descripcion_noticia .= utf8_decode('El nombre de la Liga ha cambiado a '.$nombre.'. ');
			}
		}
		if( ($pass != '') && ($liga->getValor('pass') != $pass) ){
			$liga->setValor('pass',$pass);
			//ENVIAR CORREO A TODOS LOS JUGADORES DE LA LIGA
			$id_jugador = array();
			$id_jugador = obten_idJugadores($id_liga);//obtiene los id de jugadores
			if(count($id_jugador) > 0 && $id_jugador[0] != ''){
				include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$asunto = utf8_decode('La contraseña de la Liga de Padel <'.utf8_encode($liga->getValor("nombre")).'> ha sido modificada por el administrador.');
				$mail->Subject = $asunto;
				$cuerpo = '<br><br>Datos de acceso a la Liga:<br><br>';
				$cuerpo .= 'Liga: '.utf8_encode($liga->getValor("nombre")).'.<br><br>';
				$cuerpo .= 'Nueva Contraseña: '.$liga->getValor("pass").'.<br><br>';
				$cuerpo .= '&nbsp;<br><br>';
				for($j=0; $j<count($id_jugador); $j++){
					$mail->AddBCC(obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jugador[$j],'','','','','','',''));//añadimos al jugador
				}
				$body = email_jugadorAdmin("<br>¡Gracias por utilizar miligadepadel.manuelbdv.es, te esperamos pronto!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
			}
			$descripcion_noticia .= utf8_decode('La contraseña de la Liga ha sido modificada. ');
		}
		if( ($auto_completar != '') && ($liga->getValor('auto_completar') != $auto_completar) ){
			$liga->setValor('auto_completar',$auto_completar);
			$descripcion_noticia .= utf8_decode('Los partidos ');
			if($auto_completar == 'N'){$descripcion_noticia .= utf8_decode('no');}
			else{$descripcion_noticia .= utf8_decode('si');}
			$descripcion_noticia .= utf8_decode(' pueden ser autocompletados por los jugadores. ');
		}
		if( ($vista != '') && ($liga->getValor('vista') != $vista) ){
			$liga->setValor('vista',$vista);
			$descripcion_noticia .= utf8_decode('La visibilidad de la Liga ha sido modificada a ');
			if($vista == 0){$descripcion_noticia .= utf8_decode('pública. ');}
			else{$descripcion_noticia .= utf8_decode('privada. ');}
		}
		if( ($genero != '') && ($liga->getValor('genero') != $genero) ){
			$liga->setValor('genero',$genero);
			if($genero == 'M'){$descripcion_noticia .= utf8_decode('Los participantes han cambiado a ser masculinos. ');}
			else if($genero == 'F'){$descripcion_noticia .= utf8_decode('Las participantes han cambiado a ser femeninas. ');}
			else{$descripcion_noticia .= utf8_decode('Los participantes han cambiado a ser mixtos. ');}
		}
		if( ($pais != '') && ($liga->getValor('pais') != $pais) ){
			$liga->setValor('pais',$pais);
			$descripcion_noticia .= utf8_decode('El país ha sido modificado. ');
		}
		if( ($provincia != '') && ($liga->getValor('provincia') != $provincia) ){
			$liga->setValor('provincia',$provincia);
			$descripcion_noticia .= utf8_decode('La provincia ha sido modificada. ');
		}
		if( ($ciudad != '') && ($liga->getValor('ciudad') != $ciudad) ){
			$liga->setValor('ciudad',$ciudad);
			$descripcion_noticia .= utf8_decode('La ciudad donde se disputa la Liga ha sido modificada. ');
		}
		if( ($idayvuelta != '') && ($liga->getValor('idayvuelta') != $idayvuelta) ){
				/*
				if($idayvuelta == 'N'){//Aquí entra si antes estaba a 'S' y ahora pasa 'N'
					$id_pago_web = obten_consultaUnCampo('unicas','id_pago_web','pago_web','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'tipo','I','ORDER BY id_pago_web DESC');
					$pago = new Pago_web($id_pago_web,'','','','','','','','','','','','','','','','');
					$pago->borrar();
				}
				else{//Antes era 'N' y ahora es 'S'
					$pago = new Pago_web(NULL,$bd_usuario,$id_liga,$id_division,'I',NULL,10,'P','N',cuenta_admin(),$email_ins,$id_usuario,obten_fechahora(),fecha_suma($fecha_creacion,'','',3,'','',''),'','','E');
					$pago->insertar();
				}
				*/
				$liga->setValor('idayvuelta',$idayvuelta);
				$descripcion_noticia .= utf8_decode('La liga va a disponer ');
				if($idayvuelta == 'S'){$descripcion_noticia .= utf8_decode('de ida y vuelta. ');}
				else{$descripcion_noticia .= utf8_decode('solo ida. ');}
		}
		if( ($movimientos != '') && ($liga->getValor('movimientos') != $movimientos) ){
			$liga->setValor('movimientos',$movimientos);
			$descripcion_noticia .= utf8_decode('Ascensos/descensos: '.$movimientos.'. ');
		}
		if( ($estilo != '') && ($liga->getValor('estilo') != $estilo) ){
			$liga->setValor('estilo',$estilo);
		}
		$tipo_pago_ant = $liga->getValor('tipo_pago');
		if($tipo_pago_ant != $tipo_pago && $tipo_pago != ''){//si cambia el tipo pago
			$pagado = $liga->getValor('pagado');
			$partidos = obten_equipos($tipo_pago);
			$precio = obten_precio($tipo_pago);
			if($tipo_pago == 0){//si el anterior tipo es de pago y cambia a gratis, se borra el pago
				if($pagado == 'N'){//si la liga no esta pagada
					if($liga->getValor('estilo') > 1){$liga->setValor('estilo',0);}
					$divisiones_pagadas = array();
					$divisiones_pagadas = obtenPagoDivisionesPagadas($bd_usuario,$id_liga);
					$num_divisiones_pagadas = count($divisiones_pagadas);
					if($num_divisiones_pagadas == 0){// Entra aqui si las divisiones no están pagadas
						$id_pago_web = obten_consultaUnCampo('unicas','id_pago_web','pago_web','bd',$bd_usuario,'liga',$id_liga,'division',$id_division,'tipo','L','ORDER BY id_pago_web DESC');
						$pago = new Pago_web($id_pago_web,'','','','','','','','','','','','','','','','');
						$pago->borrar();
						$liga->setValor('tipo_pago',$tipo_pago);
						modificaDivisiones($id_liga,'S',$partidos);
						//LAS DIVISIONES Y LOS PAGOS SE DEJAN EN LA BD, POR SI LUEGO SE CAMBIA DE NUEVO A LIGA DE PAGO, SOLO ES CAMBIAR ESTADOS.
						$division_1->setValor('max_equipos',$partidos);
						$division_1->modificar();
						$descripcion_noticia .= utf8_decode('El tipo de Liga ha sido modificada a gratuito con un máximo de '.$partidos.' equipos. ');
					}
					else{//si ha pagado otras divisiones no hay cambio
						$texto.= 'El tipo de liga no se ha modificado porque ya ha comprado divisiones extras.';
					}
				}
				else{//si la liga está pagada no hay cambio
					$texto.= 'El tipo de liga no se ha modificado porque ya ha realizado un pago para esta liga.';
				}
			}
			else if($tipo_pago_ant == 0){//si el anterior tipo es gratis y pasa a ser de pago se genera el pago
				//MODIFICAR EL MAXIMO DE PARTIDOS PARA LA DIVISION 1 GRATIS
				$pago = new Pago_web(NULL,$bd_usuario,$id_liga,$id_division,'L',NULL,obten_precio($tipo_pago),'P','N',cuenta_admin(),$email_ins,$id_usuario,obten_fechahora(),fecha_suma($fecha_creacion,'','',3,'','',''),'','','E');
				$pago->insertar();
				$liga->setValor('tipo_pago',$tipo_pago);
				modificaDivisiones($id_liga,'N',$partidos);
				$division_1->setValor('max_equipos',$partidos);
				$division_1->modificar();
				$descripcion_noticia .= utf8_decode('El tipo de Liga ha sido modificada a premier con un máximo de '.$partidos.' equipos. ');
			}
			else{//si el anterior pago cambia a otro pago
				if($pagado == 'N'){
					//COMPROBAR SI TIENE DIVISIONES EXTRAS Y MODIFICARLAS CON LOS PAGOS TAMBIEN DE DIVISION
					$divisiones_pagadas = array();
					$divisiones_pagadas = obtenPagoDivisionesPagadas($bd_usuario,$id_liga);
					$num_divisiones_pagadas = count($divisiones_pagadas);
					if($num_divisiones_pagadas == 0){// Entra aqui si las divisiones no están pagadas
						$id_pago_web = obten_consultaUnCampo('unicas','id_pago_web','pago_web','bd',$bd_usuario,'liga',$id_liga,'division',$id_division,'tipo','L','ORDER BY id_pago_web DESC');
						$pago = new Pago_web($id_pago_web,'','','','','','','','','','','','','','','','');
						$precio = obten_precio($tipo_pago);
						$pago->setValor('precio',$precio);
						$pago->modificar();
						$liga->setValor('tipo_pago',$tipo_pago);
						modificaDivisiones($id_liga,'N',$partidos);
						$division_1->setValor('max_equipos',$partidos);
						$division_1->modificar();
						$descripcion_noticia .= utf8_decode('El tipo de Liga ha sido modificada a premier con un máximo de '.$partidos.' equipos. ');
					}
					else{//si ha pagado otras divisiones no hay cambio
						$texto.= 'El tipo de liga no se ha modificado porque ya ha comprado divisiones extras.';
					}
				}
				else{
					$texto.= 'El tipo de liga no se ha modificado porque ya ha realizado un pago para esta liga.';
				}//si esta pagado no entra
			}//fin else
		}//fin de tipo pagos
		$liga->modificar();
		$_SESSION['liga'] = serialize($liga);
		if($descripcion_noticia != ''){
			$resumen_noticia = utf8_decode('Sección: Liga -> Ver/Modificar.');
			$fecha_noticia = obten_fechahora();
			$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,$fecha_noticia,'');
			$noticia->insertar();
			unset($noticia);
		}
		unset($liga,$division_1,$pago);
		echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>