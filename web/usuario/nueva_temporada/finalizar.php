<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$liga = unserialize($_SESSION['liga']);
//$opcion = $_SESSION['opcion'];
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');
//if(!isset($tipo_pago)){$tipo_pago = $liga->getValor('tipo_pago');}
//if ( $pagina != 'gestion_temporada' || $tipo_pago == 0 ){
if ( $pagina != 'gestion_temporada' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
		include_once ("../../funciones/f_recoger_post.php");
		if($id_liga == $comprobar_liga){
			realiza_updateGeneral('session','liga','bloqueo = "S"','id_liga',$id_liga,'','','','','','','','','');
			$_SESSION['liga_finalizada'] =  $id_liga;
			echo 0;
		}
		$db2 = new MySQL('session');//LIGA PADEL
		$c2 = $db2->consulta("SELECT id_liga FROM liga WHERE usuario = '$id_usuario' AND bloqueo = 'N' LIMIT 1; ");
		while($r2 = $c2->fetch_array(MYSQLI_ASSOC)){
			$new_id_liga = $r2['id_liga'];
		}
		$nuevaLiga = new Liga($new_id_liga,'','','','','','','','','','','','','','','','');
		$_SESSION['liga'] = serialize($nuevaLiga);
		$new_id_division = obten_consultaUnCampo('session','id_division','division','liga',$new_id_liga,'num_division',1,'','','','','');
		$nuevaDivision = new Division($new_id_division,'','','','','','','','');
		$_SESSION['division'] = serialize($nuevaDivision);
}

?>