<?php
include_once("../../funciones/f_html.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once("../../funciones/f_email.php");
include_once("../../../class/mysql.php");
include_once("../../../class/usuario.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();

if ( isset($_POST['email']) ){//SI EXISTE POST
	$pagina = $_SESSION['pagina'];
	if ( ($pagina != 'registrar_usuario' && $pagina != 'gestion_datos') || ($_SESSION['tipo_pago'] < 0 || $_SESSION['tipo_pago'] > 3) ){
		header ("Location: ../cerrar_sesion.php");
	}
	else {
		$_SESSION['pagina']  = 'gestion_datos';
	}
	include_once ("../../funciones/f_recoger_post.php");//recojo las variables post
	$db = new MySQL('unicas_liga');//UNICAS LIGA
	if($_SESSION['tipo_pago'] == 0){
		$consulta = $db->consulta("SELECT id_usuario FROM usuario WHERE email='$email'; ");
	}
	else{
		$consulta = $db->consulta("SELECT id_usuario FROM usuario WHERE email='$email' OR dni='$dni'; ");
	}
	if($consulta->num_rows == 0){//SI NO EXISTE EL USUARIO LO REGISTRO
		if($_SESSION['tipo_pago'] == 0){
			$dni = '';
			$cuenta_paypal = '';
		}
		$nombre = ucwords($nombre);
		$apellidos = ucwords($apellidos);
		$email = strtolower($email);
		$cuenta_paypal = strtolower($cuenta_paypal);
		if(obten_consultaUnCampo('unicas_liga','cuenta_paypal','usuario','cuenta_paypal',$cuenta_paypal,'','','','','','','') != ''){//si ya existe la cuenta de paypal a vacio
			$cuenta_paypal = '';
		}
		$num_BDligas = numero_de_BDligas();//ESTE ES EL NUMERO QUE ME DICE POR QUE admin_liga voy, se abrirán más bases de datos según aumenten los registros de usuarios.
		//aqui es donde calculo cual es la bd de ligas actual, cuando una BD ya tiene muchos usuarios y datos, abro nueva BD
		if($num_BDligas == 1){$bd = 'admin_liga';}
		else{$bd = 'admin_liga'.$num_BDligas;}
		//---------------------------------------
		$usuario = new Usuario(NULL,$nombre,$apellidos,$email,$bd,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,obten_fechahora(),'S','M');
		$texto1 = 'Registro realizado Correctamente.';
		$texto2 = '- Se han enviado las instrucciones a tu email para realizar la verificaci&oacute;n del registro.';
		$texto3 = '- Importante! Si no visualizas el correo en la  bandeja de entrada, revisa la carpeta spam o correo no deseado.';
		$imagen = '<img src="../../../images/ok.png" />';
		$usuario->insertar();
		$id_usuario = obten_consultaUnCampo('unicas_liga','id_usuario','usuario','email',$email,'','','','','','','');
		//email_registro($destinatario,$link);
		//RECUERDA ENVIAR EL LINK CON EL ID, LOS 13 PRIMEROS NUMEROS ALEATORIOS Y FALSOS, DESPUES TIPO DE PAGO QUE SIEMPRE SERÁ LA MISMA CANTIDAD CODIFICADA, Y POR ULTIMO ID USUARIO QUE PUEDE VARIAR
		//$link = 'http://miligadepadel.manuelbdv.es/mobile/usuario/gestion_datos.php?id=MTIzNDU2Nzg5MA==';
		//$link = 'http://miligadepadel.manuelbdv.es/mobile/v/v/valida_registro.php?id=MTIzNDU2Nzg5MA==';
		//CAMBIAR A DINAMICO
		$cad = $_SESSION['tipo_pago'];
		$cad .= $id_usuario;
		$cad .= 'F';
		$largo = 100;
		$posicion = 13;
		$id = genera_id_url($largo,$cad,$posicion);
		$link = 'http://miligadepadel.manuelbdv.es/mobile/v/v/valida_registro.php?id='.$id;
		$fecha = obten_fechahora();
		$ip = obten_ip();
		$consulta = $db->consulta("INSERT INTO `verificar_registro` (`usuario` ,`estado` ,`fecha` ,`ip`) VALUES (
'$id_usuario', 'N', '$fecha', '$ip'); ");
		 include_once ("../../funciones/f_conexion_email.php");
		//Set who the message is to be sent from
		$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
		//Set an alternative reply-to address
		$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
		//Set who the message is to be sent to
		
		$mail->addAddress($email,$nombre);
		//$mail->addAddress('manu_oamuf@hotmail.com');
		
		//$mail->addCC('manuel.berdelva@gmail.com');//en copia al usuario
		//$mail->addAddress('manu_oamuf@hotmail.com', 'Manuel');
		//Set the subject line
		$asunto = utf8_decode('¡Solo te queda un paso! - Comienza a disfrutar de tu Liga de Padel');
		$mail->Subject = $asunto;
		$body = email_registro($link,$email,$password);
		$mail->msgHTML($body);
		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';
		$mail->send();
	}
	else{//SI EXISTE EL USUARIO LANZO EL ERROR
		$texto1 = 'Ya se encuentra registrado.';
		$texto2 = '- Puede iniciar sesi&oacute;n volviendo al inicio. Si no recuerda su contrase&ntilde;a pinche aqu&iacute; para recuperarla o si tiene otro problema contacte con nosotros pinchando aqu&iacute;.';
		$texto3 = '- Recuerde que para crear una nueva liga debe iniciar sesi&oacute;n menu liga --> Crear Nueva';
		$imagen = '<img src="../../../images/error.png" />';
	}
}
cabecera_inicio();
incluir_general(0,0);
?>
<link rel="stylesheet" type="text/css" href="css/gestion_datos.css" />
<script language="javascript" type="text/javascript">
	setTimeout ("document.location.href='http://miligadepadel.manuelbdv.es';", 30000);
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
        <div class="cuadro">
            <div class="okImg"> <?php echo $imagen; ?></div>
            <div class="okText"><?php echo $texto1; ?></div>
            <div class="okText2"><?php echo $texto2; ?></div>
            <div class="okText2"><?php echo $texto3; ?></div>
            <br />&nbsp;
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


