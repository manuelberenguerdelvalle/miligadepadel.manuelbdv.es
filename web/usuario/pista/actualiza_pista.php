<?php
session_start();
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/pista.php");
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
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$tipo_pago = $_SESSION['tipo_pago'];
if ( $pagina != 'gestion_pista' && $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$texto = 'La pista se ha actualizado correctamente.';
	if($tipo_pago != 0){
		$pista = new Pista($id,$id_liga,'',$direccion,$cp);
		if($nombre != ''){$pista->setValor('nombre',ucfirst($nombre));}
		else{$pista->setValor('nombre','sin nombre');}
		$pista->modificar();
	}
	echo '<div class="contenedor"><img class="actualizacion_img" src="../../../images/ok.png" /><span class="actualizacion_texto">'.utf8_decode($texto).'</span></div>';
	unset($pista);
}//fin else

?>