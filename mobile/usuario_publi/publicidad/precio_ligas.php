<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_desplegables.php");
include_once ("../../../class/usuario_publi.php");
session_start();
$usuario_publi = unserialize($_SESSION['usuario_publi']);
header("Content-Type: text/html;charset=ISO-8859-1");
$rpta="";
$ciudad = $_POST["ciudad"];
$suscripcion = intval($_POST["suscripcion"]);
if($suscripcion >= 1 && $suscripcion <= 3){
	$ligas = array();
	$ligas = obten_ligasGratisDistintasBds(numero_de_BDligas(),$ciudad);
	$precio = obten_plus_publicidad_gratuita($suscripcion,count($ligas));
	if($usuario_publi->getValor('comercial') == 'S'){
			$precio = $precio*0.5;
	}
	echo round($precio, 1);
}
else{
	echo '--Elige--';
}
?>