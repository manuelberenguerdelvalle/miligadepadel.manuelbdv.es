<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
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
$usuario = unserialize($_SESSION['usuario']);
if ( $pagina != 'gestion_cuenta' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	if(isset($id_usuario) && $id_usuario == $usuario->getValor('id_usuario')){//modificar email
		$texto = 'La Eliminacion se ha realizado correctamente, cerrando sesion...';
		$db = new MySQL('session');//LIGA
		$consulta = $db->consulta("SELECT id_liga FROM `liga` WHERE `usuario` = '$id_usuario'; ");
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//LIGAS GRATIS
				$num_divs = obten_consultaUnCampo('session','COUNT(id_division)','division','liga',$resultados['id_liga'],'','','','','','','');
				for($i = 1; $i <= $num_divs; $i++){//si tiene divisiones QUE NO SEA LA 1 para borrar
					$id_division = obten_consultaUnCampo('session','id_division','division','liga',$resultados['id_liga'],'num_division',$i,'','','','','');
					realiza_deleteGeneral('session','premio','division',$id_division,'','','','','','','','','');
					realiza_deleteGeneral('session','noticia','liga',$resultados['id_liga'],'division',$id_division,'','','','','','','');
					realiza_deleteGeneral('session','division','id_division',$id_division,'','','','','','','','','');
				}//fin for
				///deletes por liga
				realiza_deleteGeneral('session','arbitro','liga',$resultados['id_liga'],'','','','','','','','','');
				realiza_deleteGeneral('session','pista','liga',$resultados['id_liga'],'','','','','','','','','');
				//realiza_deleteGeneral('unicas','pago_web','liga',$resultados['id_liga'],'bd',$usuario->getValor('bd'),'','','','','','','');
		}//fin while
		realiza_deleteGeneral('session','liga','usuario',$id_usuario,'','','','','','','','','');
		$usuario->borrar();
		unset($usuario,$_SESSION['usuario']);
		$_SESSION['pagina'] = 'eliminado';
		echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
	}
}

?>