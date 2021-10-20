<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../../class/jugador.php");
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
	$texto = 'La actualización se ha realizado correctamente.';
	new MySQL("unicas");//UNICAS LIGA
	if(isset($email) && $email != '' && $email != $jugador->getValor('email') ){//modificar email
		if(nombreEmailRepetido($email) == 0){
			$jugador->setValor('email',$email);
		}
		else{
			$texto .= ' Email repetido.';
		}
	}
	if(isset($telefono) && $telefono != '' && $telefono != $jugador->getValor('telefono') ){$jugador->setValor('telefono',$telefono);}
	if(isset($antpassword) && $antpassword != '' && $antpassword == $jugador->getValor('password') && $password != $jugador->getValor('password') ){
		if($password == $repassword){$jugador->setValor('password',$password);}
	}
	if(isset($dni) && $dni != '' && $dni != $jugador->getValor('dni')){$jugador->setValor('dni',$dni);}
	$fecha_nac = $anyo.'-'.$mes.'-'.$dia;
	if($fecha_nac != $jugador->getValor('fec_nac') && $jugador->getValor('fec_nac') == '0000-00-00'){$jugador->setValor('fec_nac',$fecha_nac);}
	if($zona_juego != $jugador->getValor('zona_juego') && $zona_juego != ''){$jugador->setValor('zona_juego',$zona_juego);}
	if(isset($direccion) && $direccion != '' && $direccion != $jugador->getValor('direccion') ){$jugador->setValor('direccion',$direccion);}
	if(isset($pais) && $pais != '' && $pais != $jugador->getValor('pais') ){$jugador->setValor('pais',$pais);}
	if(isset($provincia) && $provincia != '' && $provincia != $jugador->getValor('provincia') ){$jugador->setValor('provincia',$provincia);}
	if(isset($ciudad) && $ciudad != '' && $ciudad != $jugador->getValor('ciudad') ){$jugador->setValor('ciudad',$ciudad);}
	$jugador->modificar();
	$_SESSION['jugador'] = serialize($jugador);
	unset($jugador);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>