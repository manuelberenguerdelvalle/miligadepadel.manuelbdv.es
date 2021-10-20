<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/arbitro.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$tipo_pago = $_SESSION['tipo_pago'];
if ( $pagina != 'gestion_arbitro' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	if($tipo_pago != 0){
		//relleno con estos datos para que borre directamente sin hacer consulta por id
		$arbitro = new Arbitro($id,'el','resto','es','para','rellenar','campos','borra','id');
		$arbitro->borrar();
	}
}//fin else

?>