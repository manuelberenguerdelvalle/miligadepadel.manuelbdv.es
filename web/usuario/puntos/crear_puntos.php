<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/puntos.php");
include_once ("../../../class/jugador.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$liga = unserialize($_SESSION['liga']);
$division = unserialize($_SESSION['division']);
$opcion = $_SESSION['opcion'];
$id_usuario = $_SESSION['id_usuario'];

$bd_usuario = $_SESSION['bd'];
$tipo_pago = $liga->getValor('tipo_pago');

if ( $pagina != 'gestion_puntos' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$res = -1;
	if($tipo_pago > 0){//si es gratis compruebo no hay puntos
		$id_liga = $liga->getValor('id_liga');
		$id_division = $division->getValor('id_division');
		if($id_jugador > 0 && $puntos != 0){
			if($puntos > 0){$op = ' sumado ';}
			else{$op = ' restado ';}
			$jugador = new Jugador($id_jugador,'','','','','','','','','','','','','','','');
			$descripcion_noticia = 'El administrador/a ha'.$op.$puntos.' puntos al jugador/a '.$jugador->getValor("nombre").' '.$jugador->getValor("apellidos").'.';
			$fecha = obten_fechahora();
			$puntos = new Puntos('',$id_usuario,$id_jugador,$bd_usuario,$id_liga,$id_division,0,$fecha,$puntos,-1);
			$puntos->insertar();
			$resumen_noticia = utf8_decode('Sección: Puntos -> Ver/Modificar.');
			//$descripcion_noticia = utf8_decode($descripcion_noticia);
			$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,$fecha,'');
			$noticia->insertar();
			unset($noticia);
			$res = '0';//ok
		}
	}//fin tipo_pago
	echo $res;
}

?>