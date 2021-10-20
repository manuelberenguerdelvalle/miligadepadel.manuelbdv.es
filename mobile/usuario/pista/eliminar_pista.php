<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/pista.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina']; 
$opcion = $_SESSION['opcion'];
$tipo_pago = $_SESSION['tipo_pago'];
if ( $pagina != 'gestion_pista' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	if($tipo_pago != 0){
		$pista = new Pista($id,'inserto','para','rellenar','datos');
		$pista->borrar();
	}
}//fin else

?>