<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$liga = unserialize($_SESSION['liga']);
$opcion = $_SESSION['opcion'];
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');
$bd = $usuario->getValor('bd');
$fecha = obten_fechahora();
if ( $pagina != 'gestion_liga' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
		include_once ("../../funciones/f_recoger_post.php");
		if($id_liga == $comprobar_liga){
			$db2 = new MySQL('unicas_liga');//LIGA PADEL
			$c2 = $db2->consulta("INSERT INTO `prueba_gratis` (`id_prueba_gratis`,`liga`,`usuario`,`bd`,`fecha`) VALUES (NULL,  '$id_liga', '$id_usuario', '$bd', '$fecha');");
			realiza_updateGeneral('unicas','pago_web','pagado = "S",modo_pago = "G", fecha_limite = "'.$fecha.'"','liga',$id_liga,'bd',$bd,'usuario',$id_usuario,'','','','','');
			realiza_updateGeneral('session','liga','pagado = "S",tipo_pago = 1','id_liga',$id_liga,'','','','','','','','','');
			//return $r2['n'];
			echo 0;
		}
}

?>