<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/arbitro.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style>
.contenedor {
	width:90% !important;
	height:95% !important;
	border-radius:7px;
	background-color:#c5fbc6;
	margin-left:5%;
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
if ( $pagina != 'gestion_arbitro' && $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$texto = 'La arbitro se ha actualizado correctamente.';
	if($tipo_pago != 0){
		$arbitro = new Arbitro($id,$id_liga,$dni,$telefono,'',$apellidos,$cp,$direccion,$tipo);
		if($nombre != ''){$arbitro->setValor('nombre',$nombre);}
		else{$arbitro->setValor('nombre','Sin nombre');}
		$arbitro->modificar();
	}
	echo '<div class="contenedor"><img class="actualizacion_img" src="../../../images/ok.png" /><span class="actualizacion_texto">'.utf8_decode($texto).'</span></div>';
	unset($arbitro);
}//fin else

?>