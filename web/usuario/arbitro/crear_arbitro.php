<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/arbitro.php");
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
if ( $pagina != 'gestion_arbitro' && $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$texto = 'El árbitro se ha insertado correctamente.';
	if($tipo_pago != 0){
		if($dni == ''){$dni == NULL;}
		if($telefono == ''){$telefono == NULL;}
		$arbitro = new Arbitro(NULL,$id_liga,$dni,$telefono,ucwords($nombre),ucwords($apellidos),$cp,$direccion,$tipo);
		$arbitro->insertar();
	}
	echo '<div class="contenedor"><img class="actualizacion_img" src="../../../images/ok.png" /><span class="actualizacion_texto">'.utf8_decode($texto).'</span></div>';
	unset($arbitro);
}

?>