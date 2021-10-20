<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../../class/seguro.php");
session_start();
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
$pagina = $_SESSION['pagina'];
if ( $pagina != 'gestion_seguro' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$texto = $id_seguro.'-'.$licencia.'-'.$categoria.'-'.$federacion.'-'.$fecha_caducidad;
	if(!empty($id_seguro)){//MODIFICACION
		$seguro = new Seguro($id_seguro,'','','','');
		if($licencia != $seguro->getValor('licencia')){$seguro->setValor('licencia',$licencia);}
		if($categoria != $seguro->getValor('categoria')){$seguro->setValor('categoria',ucwords($categoria));}
		if($federacion != $seguro->getValor('federacion')){$seguro->setValor('federacion',ucwords($federacion));}
		if($fecha_caducidad != $seguro->getValor('fecha_caducidad')){$seguro->setValor('fecha_caducidad',insercion_fecha($fecha_caducidad));}
		$seguro->modificar();
		$texto = 'La actualización se ha realizado correctamente.';
	}
	else{//INSERCION
		$id_seguro = obten_consultaUnCampo('unicas','id_seguro','seguro','licencia',$licencia,'','','','','','','');
		if($id_seguro == ''){//comprobamos que no existe la licencia 
			$seguro = new Seguro('',$licencia,$categoria,$federacion,insercion_fecha($fecha_caducidad),$_SESSION['id_jugador']);
			$seguro->insertar();
			$texto = 'La actualización se ha realizado correctamente.';
		}
		else{
			$texto = 'La licencia no es correcta, por favor revise los datos y si el problema persiste contacte con nosotros.';
		}
	}
	unset($seguro);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>