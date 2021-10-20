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
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
//header("Content-Type: text/html;charset=UTF-8");
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
	
	function quitarCespeciales($cad){
		for($i=0; $i<strlen($cad); $i++){			
			if($cad[$i] == '?'){
				$cad[$i] = utf8_decode('ñ');
				//echo 'ñ';
			}
		}//fin for
		return $cad;
	}
	
	echo quitarCespeciales($nombre1).'-'.quitarCespeciales($apellidos1);
	
	
	$descripcion_noticia = '';

}//fin if

?>