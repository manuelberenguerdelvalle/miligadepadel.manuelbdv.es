<?php
function fecha_suma($fec,$anyos,$meses,$dias,$horas,$minutos,$segundos){//suma a la fecha
	$fecha = new DateTime($fec);
	$cambios = 'P';
	if($anyos != ''){
		$cambios .= $anyos.'Y';
	}
	if($meses != ''){
		$cambios .= $meses.'M';
	}
	if($dias != ''){
		$cambios .= $dias.'D';
	}
	if($horas != ''){
		$cambios .= $horas.'H';
	}
	if($minutos != ''){
		$cambios .= $minutos.'M';
	}
	if($segundos != ''){
		$cambios .= $segundos.'S';
	}
	$fecha->add(new DateInterval((string)$cambios));
	return $fecha->format('Y-m-d H:i:s');
}
function obtenRestoDiasSuscripcion($id_division){//devuelve el resto de días de margen de suscripción para que pueda cambiar de plan en la LIGA
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT fec_creacion FROM division WHERE id_division='$id_division'; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	$creacion = $resultado['fec_creacion'];
	$creacion_mastres = fecha_suma($creacion,'','',3,'','','');//fecha creación + 3 días
	$creacion_time = strtotime($creacion);//fecha cracion en timestamp
	$creacion_mastres_time = strtotime($creacion_mastres);//fecha creación + 3 días timestamp
	$ahora_time = strtotime(obten_fechahora());//ahora en timestamp
	if($creacion_mastres_time >= $ahora_time){//si entro aqui calculo los días restantes para ponerlos como min en el calendario
		$dia_time = ($creacion_mastres_time - $creacion_time)/3;// un día timestamp
		$time_temp = $ahora_time;//variable temporal donde voy incrementando día a día
		for($i=1; $i<=3; $i++){
			$time_temp += $dia_time;
			if($time_temp > $creacion_mastres_time){
				$resto = $i;
				//$i = 4;
				break;
			}
		}
	}
	else{
		$resto = 0;
	}
	return $resto;
}
//COMPROBAR ESTA FUNCION SI SE LLAMA FRECUENTEMENTE A ESTA SOLO DEL GRUPO
function obten_fechahora() {//Función que devuelve la fecha y hora
	$fecha = date('Y-m-d H:i:s');
	return $fecha;
}

function obten_hora() {//Función que devuelve la hora
	$hora = date('H:i:s');
	return $hora;
}

function crear_hora($hora){
	$pos = strpos($hora, '.');
	if($pos === false){//entra si tiene .50
		$h = $hora;
		$m = ':00';
	}
	else{
		$trozos = explode('.',$hora);
		$h = $trozos[0];
		$m = ':30';
	}
	$digitos = strlen($h);
	$nueva = '';
	if($digitos == 1){
		$nueva.= '0'.$h;
	}
	else{
		$nueva.= $h;
	}
	$nueva.= $m;
	return $nueva;
}

function obten_numDiaSemana($fecha){
	$dia = date('w',strtotime($fecha));
	if($dia == 0){
		$dia = 7;
	}
	return $dia;
}

function resto_suscripcion($ultima_rep,$fecha_fin){
	$sumar_dias = time() - strtotime($ultima_rep);
	$sumar_dias = $sumar_dias / 86400;
	$sumar_dias = intval(ceil($sumar_dias));
	$nueva_fecha_fin = fecha_suma($fecha_fin,'','',$sumar_dias,'','','');
	return $nueva_fecha_fin;
}

?>