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
$pagina = $_SESSION['pagina'];

if ( isset($_GET['id']) ){//SI EXISTE VERIFICACION POR ID
	if ( $pagina == 'verificacion'){//para evitar el atras y procesar varias veces
		header ("Location: http://miligadepadel.manuelbdv.es");
	}
	else{
		$texto1 = 'Verificaci&oacute;n realizada Correctamente.';
		$texto2 = '- Su registro se ha completado.';
		$texto3 = '- Ya puede acceder al panel con su email y contrase&ntilde;a desde la p&aacute;gina de inicio.';
		$imagen = '<img src="../../../images/ok.png" />';
		$id = limpiaTexto(base64_decode($_GET['id']));
		$tipo_pago = substr($id, 12, 1 );
		$pos = strpos($id, 'F');
		$id_usuario = substr($id, 13, $pos-13 );
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
			unset($consulta);
			$consulta = $db->consulta("UPDATE `usuario` SET `bloqueo` =  'N' WHERE `id_usuario` =  '$id_usuario';");
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
			$texto1 = 'Verificaci&oacute;n no realizada Correctamente.';
			$texto2 = '- Ha ocurrido un error.';
			$texto3 = '- Vuelva a intentarlo de nuevo, y si el error continua contacte con nosotros.';
			$imagen = '<img src="../../../images/error.png" />';
		}
		
	}//fin else
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