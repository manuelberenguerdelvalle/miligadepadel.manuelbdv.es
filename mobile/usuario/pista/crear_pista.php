<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/pista.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style>
.contenedor {
	width:35% !important;
	height:95% !important;
	border-radius:7px;
	background-color:#c5fbc6;
	margin-left:15%;
	float:left;
}
</style>
<link rel="stylesheet" type="text/css" href="../../css/respuesta.css" />
<?php
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$tipo_pago = $_SESSION['tipo_pago'];
if ( $pagina != 'gestion_pista' && $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$texto = 'La pista se ha insertado correctamente.';
	if($tipo_pago != 0){
		$pista = new Pista(NULL,$id_liga,ucfirst($nombre),$direccion,$cp);
		$pista->insertar();
	}
	echo '<div class="contenedor"><img class="actualizacion_img" src="../../../images/ok.png" /><span class="actualizacion_texto">'.utf8_decode($texto).'</span></div>';
	unset($pista);
}

?>