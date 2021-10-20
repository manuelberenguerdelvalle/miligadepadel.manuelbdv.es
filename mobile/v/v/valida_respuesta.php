<?php
include_once("../../funciones/f_html.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once("../../funciones/f_email.php");
include_once("../../../class/mysql.php");
include_once("../../../class/liga.php");
include_once("../../../class/division.php");
include_once("../../../class/pago_web.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();

if ( isset($_GET['id']) ){//SI EXISTE VERIFICACION POR ID
		$cadena = limpiaTexto(decodifica($_GET["id"]));
		$pos = strpos($cadena,'F');
		$bd = substr($cadena,12,1);
		$respuesta = substr($cadena,13,1);
		$equipo = substr($cadena,14,$pos-14);
		if($respuesta == 'S' || $respuesta == 'N'){//si la respuesta es correcta
			if($bd == 0){$_SESSION['bd'] = 'admin_liga';}
			else{$_SESSION['bd'] = 'admin_liga'.$bd;}
			$id_nt = obten_consultaUnCampo('session','id_nueva_temporada','nueva_temporada','equipo',$equipo,'','','','','','','');
			if($id_nt != ''){//si existe el equipo en nueva temporada
				$resp_bd = obten_consultaUnCampo('session','respuesta','nueva_temporada','id_nueva_temporada',$id_nt,'','','','','','','');
				if($resp_bd == ''){//respuesta vacia
					realiza_updateGeneral('session','nueva_temporada','respuesta = "'.$respuesta.'"','id_nueva_temporada',$id_nt,'','','','','','','','','');
					$texto1 = 'Respuesta realizada Correctamente.';
					if($respuesta == 'S'){
						$texto2 = '- Su respuesta se ha completado correctamente. Listo para comenzar!';
						$texto3 = '- Recibir&aacute; un e-mail en cuanto el administrador ponga en marcha la Nueva Temporada.';
					}
					else{//no continua
						$texto2 = '- Su respuesta se ha completado correctamente. Que pena que no desee continuar con la Nueva Temporada.';
						$texto3 = '- Hasta que el administrador no elimine su equipo podr&aacute; cambiar de opini&oacute;n, le esperamos!.';
					}
					$imagen = '<img src="../../../images/ok.png" />';
				}
				else{
					if($resp_bd != $respuesta){//diferente respuesta en bd con la nueva
						if($respuesta == 'S'){$txt_res = 'Si';}
						else{$txt_res = 'No';}
						if($resp_bd == 'S'){$txt_resbd = 'Si';}
						else{$txt_resbd = 'No';}
						realiza_updateGeneral('session','nueva_temporada','respuesta = NULL','id_nueva_temporada',$id_nt,'','','','','','','','','');
						$texto1 = 'Conflicto de respuestas.';
						$texto2 = '- La respuesta anterior recibida era ('.$txt_resbd.') y la actual es ('.$txt_res.'),';
						$texto3 = 'P&oacute;ngase en contacto con su compa&ntilde;ero de equipo, decidan y respondan si desean participar en la Nueva Temporada.';
						$imagen = '<img src="../../../images/error.png" />';
					}
					else{
						$texto1 = 'Respuesta realizada Correctamente.';
						if($respuesta == 'S'){
							$texto2 = '- Su respuesta se ha completado correctamente. Listo para comenzar!';
							$texto3 = '- Recibir&aacute; un e-mail en cuanto el administrador ponga en marcha la Nueva Temporada.';
						}
						else{//no continua
							$texto2 = '- Su respuesta se ha completado correctamente. Que pena que no desee continuar con la Nueva Temporada.';
							$texto3 = '- Hasta que el administrador no elimine su equipo podr&aacute; cambiar de opini&oacute;n, le esperamos!.';
						}
						$imagen = '<img src="../../../images/ok.png" />';
					}//misma respuesta
				}//fin respuesta con datos
			}//finexiste el id
			else{//eliminado por el admin
				$texto1 = 'Respuesta no procesada.';
				$texto2 = '- El motivo es que el administrador no ha contado con su equipo para la Nueva Temporada.';
				$texto3 = '- Puede que el motivo haya sido el tiempo en contestar si deseaba o no continuar la Nueva Temporada.';
				$imagen = '<img src="../../../images/error.png" />';
			}
		}//fin respuesta
		else{
			$texto1 = 'Respuesta err&oacute;nea.';
			$texto2 = '- Ha ocurrido un error.';
			$texto3 = '- Vuelva a intentarlo de nuevo, y si el error continua contacte con nosotros.';
			$imagen = '<img src="../../../images/error.png" />';
		}
	/*if ( $pagina == 'verificacion'){//para evitar el atras y procesar varias veces
		header ("Location: http://miligadepadel.manuelbdv.es");
	}
	else{
		$texto1 = 'Verificaci&oacute;n realizada Correctamente.';
		$texto2 = '- Su registro se ha completado.';
		$texto3 = '- Ya puede acceder al panel con su email y contrase&ntilde;a desde la p&aacute;gina de inicio.';
		$imagen = '<img src="../../../images/ok.png" />';
		/*$opcion = substr(decodifica($_GET["id"]), 12, 1);
		$tipo_pago = limpiaTexto(base64_decode(substr($_GET['id'], 16, 4 )));
		//$email = limpiaTexto(base64_decode(substr($_GET['id'], 20 )));
		$id_usuario = limpiaTexto(base64_decode(substr($_GET['id'], 20 )));
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$consulta = $db->consulta("SELECT id_verificacion FROM verificar_registro WHERE usuario = '$id_usuario' AND estado = 'N'; ");
		if($consulta->num_rows == 1){//SI EXISTE USUARIO SIN VERIFICAR, ENTONCES VERIFICO Y CREO LIGA
			//creo la variable session bd
			$bd = obten_consultaUnCampo('unicas_liga','bd','usuario','id_usuario',$id_usuario,'','','','','','','');
			$_SESSION['bd'] = $bd;
			$liga = new Liga(NULL,'Sin nombre',obten_fechahora(),NULL,NULL,NULL,$id_usuario,$tipo_pago,'N',NULL,NULL,'S',NULL,'N','M','N',0);
			$liga->insertar();
			$max_equipos = obten_equipos($tipo_pago);
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT id_liga FROM liga WHERE usuario='$id_usuario' ");
			$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
			$id_liga = $resultado['id_liga'];
			$division = new Division(NULL,obten_fechahora(),NULL,$id_liga,NULL,1,$max_equipos,'N','N');
			$division->insertar();
			$fecha = obten_fechahora();
			$ip = obten_ip();
			$db = new MySQL('unicas_liga');//UNICAS LIGA
			$consulta = $db->consulta("UPDATE `verificar_registro` SET `estado` =  'S', `fecha` = '$fecha',`ip` =  '$ip' WHERE `usuario` =  '$id_usuario';");
			if($tipo_pago != 0){//GENERAR PAGO
				$db = new MySQL('session');//LIGA PADEL
				$consulta = $db->consulta("SELECT id_division FROM division WHERE liga = '$id_liga'; ");
				$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
				$id_division = $resultado['id_division'];
				$email_ins = obten_consultaUnCampo('unicas_liga','cuenta_paypal','usuario','id_usuario',$id_usuario,'','','','','','','');
				$email = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$id_usuario,'','','','','','','');
				if($email_ins == ''){$email_ins = $email;}//si cuenta paypal es vacia asigno la del email
				$pago = new Pago_web(NULL,$bd,$id_liga,$id_division,'L',NULL,obten_precio($tipo_pago),'P','N',cuenta_admin(),$email_ins,$id_usuario,$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
				$pago->insertar();//INSERTAR PAGO
			}
			$_SESSION['pagina'] = 'verificacion';
			include_once ("../../funciones/f_conexion_email.php");
			//Set who the message is to be sent from
			$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
			//Set an alternative reply-to address
			$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
			//Set who the message is to be sent to
			
			$mail->addAddress($email);
			//$mail->addAddress('manu_oamuf@hotmail.com');
			
			//$mail->addCC('manu_oamuf@hotmail.com');//en copia al usuario
			//$mail->addAddress('manu_oamuf@hotmail.com', 'Manuel');
			//Set the subject line
			$asunto = utf8_decode('¡Registro Completado Correctamente! - Comienza ya tu Liga de Padel');
			$mail->Subject = $asunto;
			$body = email_conf_registro();
			$mail->msgHTML($body);
			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			$mail->send();
		
		}
		else{//SI NO HAY VERIFICACION HAY ALGUN ERROR
			
		}
		
	}//fin else*/
}//fin if get id
cabecera_inicio();
incluir_general(0,0);
?>
<link rel="stylesheet" type="text/css" href="valida_registro.css" />
<script language="javascript" type="text/javascript">
setTimeout ("document.location.href='http://miligadepadel.manuelbdv.es';", 25000);
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<!--<div class="izquierdo">&nbsp;</div>-->
    <div class="contenido">
    	<div class="paso">
        	<div class="atras">
            	<a href="http://miligadepadel.manuelbdv.es"><span class="botonAtras">INICIO</span></a>
            </div>
        	<div class="num_pasos">&nbsp;</div>
            <div class="num_pasos">&nbsp;</div>
            <div class="traductor"><div id="google_translate_element"></div></div>
        </div>
        <div class="okImg"> <?php echo $imagen; ?></div>
        <div class="okText"><?php echo $texto1; ?></div>
        <div class="okText2"><?php echo $texto2; ?></div>
        <div class="okText2"><?php echo $texto3; ?></div>
        <div class="cuadro">&nbsp;</div>
    </div>
    <!--<div class="derecho">&nbsp;</div>-->
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>