<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
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
$jugador = unserialize($_SESSION['jugador']);
if ( $pagina != 'gestion_datos' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	if(isset($id_jugador) && $id_jugador == $jugador->getValor('id_jugador')){//modificar email
		$texto = 'La Eliminacion se ha realizado correctamente, cerrando sesion...';
		$num_bdligas = numero_de_BDligas();
		for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
			if($i == 1){
				$_SESSION['bd'] = 'admin_liga';
			}
			else{
				$_SESSION['bd'] = 'admin_liga'.$i;
			}
			realiza_deleteGeneral('session','inscripcion','id_jugador1',$id_jugador,'','','','','','','','','');
			realiza_deleteGeneral('session','inscripcion','id_jugador2',$id_jugador,'','','','','','','','','');
			realiza_deleteGeneral('session','equipo','jugador1',$id_jugador,'','','','','','','','','');
			realiza_deleteGeneral('session','equipo','jugador2',$id_jugador,'','','','','','','','','');
			
		}//FIN FOR BD
		$foto = '../../../../../fotos_jugador/'.$id_jugador.'.jpg';
		if(file_exists($foto)){
			unlink($foto);
		}
		$foto = '../../../fotos_jugador/'.$id_jugador.'.jpg';
		if(file_exists($foto)){
			unlink($foto);
		}
		$jugador->borrar();
		unset($jugador,$_SESSION['jugador']);
		$_SESSION['pagina'] = 'eliminado';
		echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
	}
}
	
?>