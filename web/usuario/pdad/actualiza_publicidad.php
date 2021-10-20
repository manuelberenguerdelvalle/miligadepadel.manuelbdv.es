<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/publicidad.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if ( $pagina != 'gestion_publicidad' || $opcion != 0){
	header ("Location: ../cerrar_sesion.php");
}
else {
	//$url = $_POST['url'];
	//$id_publicidad = $_POST['id_publicidad'];
	$url = limpiaTexto3($_POST['url']);
	$id_publicidad = limpiaTexto($_POST['id_publicidad']);
	$publicidad = new Publicidad($id_publicidad,"","","","","","","","","","","","","","");
	$publicidad->setValor("url",$url);
	$publicidad->modificar();
	unset($publicidad);
}//fin else

?>