<?php

function nombreEmailRepetido($email){//comprueba si ya está el nombre de email repetido
	$db = new MySQL('unicas_liga');//UNICAS LIGA
	$consulta = $db->consulta("SELECT email FROM usuario WHERE email='$email'; ");
	$cont = $consulta->num_rows;
	return $cont;
}//ELIMINAR

function modificaEmail($actual,$nuevo){//modificar el email de usuario
	$db = new MySQL('unicas_liga');//UNICAS LIGA
	$consulta = $db->consulta("UPDATE usuario SET email = '$nuevo' WHERE email = '$actual'; ");
}

function hay_ligaPago($id_usuario){//obtiene si el usuario tiene ligas de pago
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_liga) FROM liga WHERE usuario = '$id_usuario' AND tipo_pago > 0; ");
	$num = $consulta->num_rows;
	return $num;
}

function obtenAdminPagoRecibido($id){//obtiene si el administrados ha recibido algún pago a su cuenta paypal
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT receptor FROM pago_web WHERE usuario = '$id' AND pagado = 'S'; ");
	$num = $consulta->num_rows;
	return $num;
}//ELIMINAR
?>
