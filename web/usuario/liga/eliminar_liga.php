<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if ( $pagina != 'gestion_liga' || $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	if($_POST['id_usuario'] == $_SESSION['id_usuario'] && $_POST['id_liga'] == $_SESSION['id_liga']){//compruebo que sea la misma
		$id_liga = $_SESSION['id_liga'];
		$id_usuario = $_SESSION['id_usuario'];
		$db = new MySQL('session');//LIGA
		$consulta = $db->consulta("SELECT id_division FROM division WHERE liga = '$id_liga'; ");
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//LIGAS GRATIS
			realiza_deleteGeneral('session','noticia','liga',$id_liga,'','','','','','','','','');
			realiza_deleteGeneral('session','regla','liga',$id_liga,'','','','','','','','','');
			realiza_deleteGeneral('session','premio','division',$resultados['id_division'],'','','','','','','','','');
			realiza_deleteGeneral('session','arbitro','liga',$id_liga,'','','','','','','','','');
			realiza_deleteGeneral('session','pista','liga',$id_liga,'','','','','','','','','');
			realiza_deleteGeneral('session','division','id_division',$resultados['id_division'],'','','','','','','','','');
		}//fin WHILE LIGAS
		realiza_deleteGeneral('unicas','pago_web','usuario',$id_usuario,'liga',$id_liga,'','','','','','','');
		realiza_deleteGeneral('session','liga','id_liga',$id_liga,'','','','','','','','','');
		unset($_SESSION['liga'],$_SESSION['division']);
	}//fin comprobacion post y session
}//fin else

?>
