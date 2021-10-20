<?php
session_start();
////////////////////////////////////////////////////////////////////////////////
//PANEL USUARIO
////////////////////////////////////////////////////////////////////////////////
//ARBITROS
function utilizando_arbitro($id_division,$id_arbitro){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_partido) as cuenta FROM partido WHERE division = '$id_division' AND (arbitro_principal = '$id_arbitro' OR arbitro_auxiliar = '$id_arbitro' OR arbitro_adjunto = '$id_arbitro' OR arbitro_silla = '$id_arbitro' OR arbitro_ayudante = '$id_arbitro'); ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['cuenta'];
}

function insertar_regNuevaTemporada($liga,$division,$equipo,$posicion,$precio){
	$db = new MySQL('session');//LIGA PADEL
	$db->consulta("INSERT INTO `nueva_temporada` (`id_nueva_temporada`,`liga`,`nueva`,`division`,`equipo`,`posicion`,`respuesta`,`precio`) VALUES (NULL,'$liga',NULL,'$division','$equipo','$posicion',NULL,'$precio');");
}

////////////////////////////////////////////////////////////////////////////////
//PANEL JUGADOR
////////////////////////////////////////////////////////////////////////////////
function nombreEmailRepetido($email){//comprueba si ya está el nombre de email repetido
	$db = new MySQL('unicas');//UNICAS LIGA
	$consulta = $db->consulta("SELECT COUNT(id_jugador) as cuenta FROM jugador WHERE email='$email'; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['cuenta'];
}
function obten_arrayPerteneceAequipos($id_jugador){//comprueba si ya está el nombre de email repetido
	$db = new MySQL('session');//LIGA PADEL
	$equipos = array();
	$i=0;
	$consulta = $db->consulta("SELECT id_equipo FROM equipo WHERE jugador1='$id_jugador' OR jugador2='$id_jugador'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$equipos[$i] = $resultados['id_equipo'];
		$i++;
	}
	return $equipos;
}
////////////////////////////////////////////////////////////////////////////////
//PANEL USUARIO PUBLI
////////////////////////////////////////////////////////////////////////////////
function nombreEmailRepetidoPubli($email){//comprueba si ya está el nombre de email repetido
	$db = new MySQL('unicas');//UNICAS LIGA
	$consulta = $db->consulta("SELECT COUNT(id_jugador) as cuenta FROM usuario_publi WHERE email='$email'; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['cuenta'];
}
?>