<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../../class/usuario_publi.php");
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
	$texto = 'La actualización se ha realizado correctamente.';
	new MySQL("unicas");//UNICAS
	if(isset($email) && $email != '' && $email != $usuario_publi->getValor('email') ){//modificar email
		if(nombreEmailRepetidoPubli($email) == 0){
			$usuario_publi->setValor('email',$email);
		}
		else{
			$texto .= ' Email repetido.';
		}
	}
	if(isset($telefono) && $telefono != '' && $telefono != $usuario_publi->getValor('telefono') ){$usuario_publi->setValor('telefono',$telefono);}
	if(isset($antpassword) && $antpassword != '' && $antpassword == $usuario_publi->getValor('password') && $password != $usuario_publi->getValor('password') ){
		if($password == $repassword){$usuario_publi->setValor('password',$password);}
	}
	if(isset($cif) && $cif != '' && $cif != $usuario_publi->getValor('cif')){$usuario_publi->setValor('cif',$cif);}
	if(isset($direccion) && $direccion != '' && $direccion != $usuario_publi->getValor('direccion') ){$usuario_publi->setValor('direccion',$direccion);}
	if(isset($pais) && $pais != '' && $pais != $usuario_publi->getValor('pais') ){$usuario_publi->setValor('pais',$pais);}
	if(isset($provincia) && $provincia != '' && $provincia != $usuario_publi->getValor('provincia') ){$usuario_publi->setValor('provincia',$provincia);}
	if(isset($ciudad) && $ciudad != '' && $ciudad != $usuario_publi->getValor('ciudad') ){$usuario_publi->setValor('ciudad',$ciudad);}
	$usuario_publi->modificar();
	$_SESSION['usuario_publi'] = serialize($usuario_publi);
	unset($usuario_publi);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>