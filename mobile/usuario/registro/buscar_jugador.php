<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];

if ( $pagina != 'inscribir_equipo'){
	header ("Location: ../cerrar_sesion.php");
}
else {
	//eliminar posible inyeccion
	$email = limpiaTexto3($_POST['email']);
	$password = limpiaTexto($_POST['pass']);//EN REALIDAD ES EL NOMBRE DEL JUGADOR
	$genero = limpiaTexto($_SESSION['genero']);//GENERO DE LA LIGA
	$jugador = limpiaTexto($_POST['jugador']);
	if(!empty($email) && !empty($password) && !empty($genero)) {
		if($genero == 'A'){//SI ES MIXTO
			if($jugador == 1){$genero = 'M';}
			else{$genero = 'F';}
		}
		$email = quitarAcentos(utf8_decode($email));
		$password = quitarAcentos(utf8_decode($password));
		$db = new MySQL('unicas');
		$consulta = $db->consulta("SELECT id_jugador FROM jugador WHERE email = '".$email."' AND nombre = '".ucwords($password)."' AND genero = '".$genero."' ; ");
		if($consulta->num_rows == 0){
			$id_jugador = 0;
		}else{
			$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
			$id_jugador = $resultados['id_jugador'];
			if($jugador == 1){$_SESSION['id_jugador1'] = $id_jugador;}//guardo el jugador 1
			else{$_SESSION['id_jugador2'] = $id_jugador;}//guardo jugador2
		}//fin else
		echo $id_jugador;
	}	
}//fin else

?>