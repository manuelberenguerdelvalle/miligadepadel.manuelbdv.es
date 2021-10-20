<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_cuenta.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:10px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:15%;
	color:#006;
}
.actualizacion img{
	width:10%;
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
	$texto = 'La actualización se ha realizado correctamente.';
	if(isset($email) && $email != '' && $email != $usuario->getValor('email') ){//modificar email
		if(obten_consultaUnCampo('unicas_liga','email','usuario','email',$email,'','','','','','','') == ''){
			modificaEmail($usuario->getValor('email'),$email);
		}
		else{
			$texto .= ' Email repetido.';
		}
	}
	if(isset($telefono) && $telefono != '' && $telefono != $usuario->getValor('telefono') ){$usuario->setValor('telefono',$telefono);}
	if(isset($antpassword) && $antpassword != '' && $antpassword == $usuario->getValor('password') && $password != $usuario->getValor('password') ){
		if($password == $repassword){$usuario->setValor('password',$password);}
	}
	if(isset($dni) && $dni != '' && $dni != $usuario->getValor('dni')){$usuario->setValor('dni',$dni);}
	if(isset($cuenta_paypal) && $cuenta_paypal != '' && $cuenta_paypal != $usuario->getValor('cuenta_paypal') ){
		if(obten_consultaUnCampo('unicas_liga','cuenta_paypal','usuario','cuenta_paypal',$cuenta_paypal,'','','','','','','') != ''){
			$texto .= ' Cuenta PayPal repetida.';
		}
		else{
			$usuario->setValor('cuenta_paypal',$cuenta_paypal);
		}
	}
	if(isset($direccion) && $direccion != '' && $direccion != $usuario->getValor('direccion') ){$usuario->setValor('direccion',utf8_decode($direccion));}
	if(isset($cp) && $cp != '' && $cp != $usuario->getValor('cp') ){$usuario->setValor('cp',$cp);}
	if(isset($pais) && $pais != '' && $pais != $usuario->getValor('pais') ){$usuario->setValor('pais',$pais);}
	if(isset($provincia) && $provincia != '' && $provincia != $usuario->getValor('provincia') ){$usuario->setValor('provincia',$provincia);}
	if(isset($ciudad) && $ciudad != '' && $ciudad != $usuario->getValor('ciudad') ){$usuario->setValor('ciudad',$ciudad);}
	if(isset($recibir_pago) && $recibir_pago == 'M' && $recibir_pago != $usuario->getValor('recibir_pago') ){$usuario->setValor('recibir_pago',$recibir_pago);}
	$usuario->modificar();
	$_SESSION['usuario'] = serialize($usuario);
	$db = new MySQL("unicas_liga");//UNICAS LIGA
	$consulta = $db->consulta("INSERT INTO  `historico_usuario` (`id_historico`,`fecha`,`id_usuario`,`email`,`bd`,`telefono`,`password`,`nombre`,`apellidos`,`dni`,`cuenta_paypal`,`direccion`,`cp`,`ciudad`,`provincia`,`pais`,`fec_registro`,`bloqueo`,`recibir_pago`) VALUES (NULL,'".date('Y-m-d H:i:s')."','".$usuario->getValor('id_usuario')."','".$usuario->getValor('email')."','".$usuario->getValor('bd')."','".$usuario->getValor('telefono')."','".$usuario->getValor('password')."','".$usuario->getValor('nombre')."','".$usuario->getValor('apellidos')."','".$usuario->getValor('dni')."','".$usuario->getValor('cuenta_paypal')."','".$usuario->getValor('direccion')."','".$usuario->getValor('cp')."','".$usuario->getValor('ciudad')."','".$usuario->getValor('provincia')."','".$usuario->getValor('pais')."','".$usuario->getValor('fec_registro')."','".$usuario->getValor('bloqueo')."','".$usuario->getValor('recibir_pago')."'); ");
	unset($usuario);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>