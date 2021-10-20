<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
session_start();
$pagina = $_SESSION['pagina'];
if ( $pagina != 'index' && $_SESSION['intentos'] >= 10){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
else {
	$tipo = limpiaTexto(htmlspecialchars($_POST["tipo"]));
	$email = limpiaTexto(htmlspecialchars($_POST["email"]));
	$password = limpiaTexto(htmlspecialchars($_POST["password"]));
	if($tipo == 0){//administrador
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$consulta = $db->consulta("SELECT id_usuario FROM `usuario` WHERE `email` = '$email' and `password` = '$password' and `bloqueo` = 'N' ;");
		$resultado = $consulta->num_rows;
		if($resultado == 0){$_SESSION['intentos']++;}
	}
	else if($tipo == 1){//jugador
		$db = new MySQL('unicas');//UNICAS LIGA
		$consulta = $db->consulta("SELECT id_jugador FROM `jugador` WHERE `email` = '$email' and `password` = '$password' ;");
		$resultado = $consulta->num_rows;
		if($resultado == 0){$_SESSION['intentos']++;}
	}
	else if($tipo == 2){//publicidad
		$db = new MySQL('unicas');//UNICAS LIGA
		$consulta = $db->consulta("SELECT id_usuario_publi FROM `usuario_publi` WHERE `email` = '$email' and `password` = '$password' and `bloqueo` = 'N' ;");
		$resultado = $consulta->num_rows;
		if($resultado == 0){$_SESSION['intentos']++;}
	}
	else{
		$resultado = 0;
	}
	echo $resultado;
}

?>