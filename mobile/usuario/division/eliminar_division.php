<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if ( $pagina != 'gestion_division' || $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	if($_POST['id_division'] == $_SESSION['id_division'] && $_POST['id_liga'] == $_SESSION['id_liga']){//compruebo que sea la misma
		$id_liga = $_SESSION['id_liga'];
		$id_division = $_SESSION['id_division'];
		realiza_deleteGeneral('session','noticia','liga',$id_liga,'division',$id_division,'','','','','','','');
		realiza_deleteGeneral('session','premio','division',$id_division,'','','','','','','','','');
		realiza_deleteGeneral('unicas','pago_web','usuario',$_SESSION['id_usuario'],'liga',$id_liga,'division',$id_division,'','','','','');
		realiza_deleteGeneral('session','division','id_division',$id_division,'','','','','','','','','');
		unset($_SESSION['division']);
	}//fin comprobacion post y session
}//fin else

?>
