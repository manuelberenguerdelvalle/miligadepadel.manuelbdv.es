<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/partido.php");
include_once ("../../../class/disputa.php");
include_once ("../../../class/notificacion.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$id_liga = $_SESSION['id_liga'];
$id_division = $_SESSION['id_division'];
if ( $pagina != 'ver_liga' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$email = limpiaTexto3($_POST['email']);//el email lo vuelvo a recoger para que no elimine caracteres
	//el password si me vale con el limpiaTexto
	if(!empty($id_partido) && !empty($email) && !empty($password)){
		$partido = new Partido($id_partido,'','','','','','','','','','','','','','','','','','','','','','','','','');
		$local = $partido->getValor('local');
		$visitante = $partido->getValor('visitante');
		$id_jugador = obten_consultaUnCampo('unicas','id_jugador','jugador','email',$email,'password',$password,'','','','','');
		//comprobar jugador en equipo local
		//comprobar jugador en equipo visitante
		$encontrado = obten_consultaUnCampo('session','id_equipo','equipo','id_equipo',$local,'jugador1',$id_jugador,'','','','','');
		if($encontrado == ''){$encontrado = obten_consultaUnCampo('session','id_equipo','equipo','id_equipo',$local,'jugador2',$id_jugador,'','','','','');}
		if($encontrado == ''){$encontrado = obten_consultaUnCampo('session','id_equipo','equipo','id_equipo',$visitante,'jugador1',$id_jugador,'','','','','');}
		if($encontrado == ''){$encontrado = obten_consultaUnCampo('session','id_equipo','equipo','id_equipo',$visitante,'jugador2',$id_jugador,'','','','','');}
	}
	if($encontrado != ''){//encontrado
		$disputa = new Disputa(NULL,$id_division,$id_partido,date('Y-m-d H:i:s'),$id_jugador,'N',$texto);
		$disputa->insertar();
		$notificacion = new Notificacion(NULL,obten_consultaUnCampo('session','usuario','liga','id_liga',$id_liga,'','','','','','',''),$id_liga,$id_division,'modificar_disputa.php',date('Y-m-d H:i:s'),'N');
		$notificacion->insertar();
		echo '0';
	}//fin ifjugador o datos de acceso incorrectos
	else{//vacio no hago nada
		echo '1';//error
	}
}//fin else

?>