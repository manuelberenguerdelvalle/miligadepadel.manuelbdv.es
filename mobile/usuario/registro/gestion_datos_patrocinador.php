<?php
include_once("../../funciones/f_html.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once("../../funciones/f_email.php");
include_once("../../../class/mysql.php");
include_once("../../../class/usuario_publi.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina == 'registrar_patrocinador'){//AQUI ENTRO SI VIENEN DATOS DEL REGISTRO DE USUARIO
	include_once ("../../funciones/f_recoger_post.php");//recojo las variables post
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT id_usuario_publi FROM usuario_publi WHERE email='$email' ; ");
	if($consulta->num_rows == 0){//SI NO EXISTE EL USUARIO LO REGISTRO
		$email = strtolower($email);
		$usuario_publi = new Usuario_publi(NULL,$email,ucwords($nombre),ucwords($empresa),$telefono,$password,$cif,$email,$direccion,'',$pais,$provincia,$ciudad,date('Y-m-d H:i:s'),'N','N');
		$usuario_publi->insertar();
		$texto1 = 'Registro realizado Correctamente.';
		$texto2 = '- Se han enviado los datos de inscripci&oacute;n a tu correo.';
		$texto3 = '- Importante! Si no visualizas el correo en la  bandeja de entrada, revisa la carpeta spam o correo no deseado.';
		$imagen = '<img src="../../../images/ok.png" />';

		 include_once ("../../funciones/f_conexion_email.php");
		//Set who the message is to be sent from
		$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
		//Set an alternative reply-to address
		$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
		//Set who the message is to be sent to
		$mail->addAddress($email,$nombre);
		//$mail->addAddress('manu_oamuf@hotmail.com');
		$asunto = utf8_decode('¡Se ha registrado Correctamente, ya puedes patrocinar Ligas en ciudades!');
		$mail->Subject = $asunto;
		$mensaje = 'Email: '.$email.'<br><br>Contraseña: '.$password;
		$body = email_jugadorAdmin('Datos de Acceso al Panel de Patrocinador:',$mensaje);
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
}//fin else
cabecera_inicio();
incluir_general(0,0);
?>
<link rel="stylesheet" type="text/css" href="css/gestion_datos.css" />
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
        <div class="cuadro">
            <div class="okImg"> <?php echo $imagen; ?></div>
            <div class="okText"><?php echo $texto1; ?></div>
            <div class="okText2"><?php echo $texto2; ?></div>
            <div class="okText2"><?php echo $texto3; ?></div>
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


