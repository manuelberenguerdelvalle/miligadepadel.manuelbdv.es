<?php
//----------------------------------------USUARIO--------------------------------------------------------------
function crear_conexion($id_usuario,$ip){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("INSERT INTO `conexiones` (`id` ,`usuario` ,`inicio` ,`fin` ,`ip`) VALUES (NULL ,  '$id_usuario',  '".date('Y-m-d H:i:s')."', NULL,  '$ip'); ");//Inserta conexion a la bd
}

function obten_ultimaConexion($id_usuario){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id FROM conexiones WHERE usuario = '$id_usuario' ORDER BY id DESC; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultados['id'];
}

function cerrar_conexion($id){
	$db = new MySQL('session');//LIGA PADEL
	$fecha = obten_fechahora();
	$consulta = $db->consulta("UPDATE conexiones SET  fin =  '$fecha' WHERE  id = '$id'; ");
}
//----------------------------------------JUGADOR--------------------------------------------------------------
function crear_conexion_jugador($id_jugador,$ip){
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("INSERT INTO `conexiones_jugadores` (`id` ,`jugador` ,`inicio` ,`fin` ,`ip`) VALUES (NULL ,  '$id_jugador',  '".date('Y-m-d H:i:s')."', NULL,  '$ip'); ");
}

function obten_ultimaConexion_jugador($id_jugador){
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT id FROM conexiones_jugadores WHERE jugador = '$id_jugador' ORDER BY id DESC; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultados['id'];
}

function cerrar_conexion_jugador($id){
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("UPDATE conexiones_jugadores SET fin =  '".date('Y-m-d H:i:s')."' WHERE  id = '$id'; ");
}
//----------------------------------------PUBLICIDAD--------------------------------------------------------------
function crear_conexion_publicidad($usuario_publi,$ip){
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("INSERT INTO `conexiones_usuario_publi` (`id` ,`usuario_publi` ,`inicio` ,`fin` ,`ip`) VALUES (NULL ,  '$usuario_publi',  '".date('Y-m-d H:i:s')."', NULL,  '$ip'); ");
}

function obten_ultimaConexion_publicidad($usuario_publi){
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT id FROM conexiones_usuario_publi WHERE usuario_publi = '$usuario_publi' ORDER BY id DESC; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultados['id'];
}

function cerrar_conexion_publicidad($id){
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("UPDATE conexiones_usuario_publi SET fin =  '".date('Y-m-d H:i:s')."' WHERE  id = '$id'; ");
}
?>