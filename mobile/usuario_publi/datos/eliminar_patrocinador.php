<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario_publi.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:7px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:15%;
	color:#006;
}
.actualizacion img{
	width:2%;
	margin-top:1%;
	margin-right:1%;
}
</style>
<?php
session_start();
$pagina = $_SESSION['pagina'];
$usuario_publi = unserialize($_SESSION['usuario_publi']);
if ( $pagina != 'gestion_datos' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	if(isset($id_usuario_publi) && $id_usuario_publi == $usuario_publi->getValor('id_usuario_publi')){//modificar email
		$texto = 'La Eliminacion se ha realizado correctamente, cerrando sesion...';
		realiza_deleteGeneral('unicas','publicidad_gratis','usuario_publi',$id_usuario_publi,'','','','','','','','','');
		$usuario_publi->borrar();
		unset($usuario_publi,$_SESSION['usuario_publi']);
		$_SESSION['pagina'] = 'eliminado';
		echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
	}
}
	
?>