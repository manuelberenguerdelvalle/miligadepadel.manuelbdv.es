<?php
session_start();
function limpiaTexto($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql
	$caracteres = array('=""','= ""','"', "'", "=''", "= ''", "%", " OR ", " or ", " AND ", " and ", "=", "<", ">", "`", "+", ",", ";", ":", "*", " FROM ", " from ", " WHERE ", " where ", " UNION SELECT ", " union select ", "&", " LIKE ", " like ");
	$texto = trim($valor);
	$num = count($caracteres);
	for($i=0; $i<$num; $i++){
		$texto = str_replace($caracteres[$i], " ", $texto);
	}
	return $texto;
}
function limpiaTexto2($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql REDUCIDO dejando más caracteres
	$caracteres = array("'.'",'"."','=""','= ""', "=''", "= ''", "%", " OR ", " or ", " AND ", " and ", "`", ";", "*", " FROM ", " from ", " WHERE ", " where ", " UNION SELECT ", " union select ", "&", " LIKE ", " like ");
	$texto = trim($valor);
	$num = count($caracteres);
	for($i=0; $i<$num; $i++){
		$texto = str_replace($caracteres[$i], " ", $texto);
	}
	return $texto;
}

function formatoImagen($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql
	$retorno = false;
	$caracteres = array('.jpg', ".gif", ".png", ".bmp", ".jpeg");
	for($i=0; $i<count($caracteres); $i++){
		if(strrpos($valor, $caracteres[$i]) !== false){
			$retorno = true;
		}
	}
	return $retorno;
}

function obten_fechahora() {//Función que devuelve la fecha y hora
	$fecha = date('Y-m-d H:i:s');
	return $fecha;
}

/*function obten_fecha() {//Función que devuelve la fecha
	$fecha = date('Y-m-d');
	return $fecha;
}*/

function obten_hora() {//Función que devuelve la hora
	$hora = date('H:i:s');
	return $hora;
}

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

function obten_ip(){//obtiene ip
	$ip=$_SERVER['REMOTE_ADDR'];
	return $ip;
}

function cuenta_admin(){//cuenta admin
	return base64_decode('cGFnb3NAbWlsaWdhZGVwYWRlbC5jb20=');
}

function obten_ultimaConexion($email){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id FROM conexiones WHERE usuario = '$email' ORDER BY id DESC; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultados['id'];
}

function cerrar_conexion($id){
	$db = new MySQL('session');//LIGA PADEL
	$fecha = obten_fechahora();
	$consulta = $db->consulta("UPDATE conexiones SET  fin =  '$fecha' WHERE  id = '$id'; ");
}

function obten_equipos($tipo_pago){//obtiene el numero de equipos
	if($tipo_pago == 1){
		$max_equipos = 15;
	}
	else if($tipo_pago == 2){
		$max_equipos = 20;
	}
	else if($tipo_pago == 3){
		$max_equipos = 25;
	}
	else{
		$max_equipos = 10;
	}
	return $max_equipos;
}

function obten_precio($tipo_pago){//obtiene el precio
	if($tipo_pago == 1){
		$precio = 30;
	}
	else if($tipo_pago == 2){
		$precio = 40;
	}
	else if($tipo_pago == 3){
		$precio = 50;
	}
	else{
		$precio = 0;
	}
	return $precio;
}

function comprobar_pagina($pagina){//comprueba pagina principal
	if ($pagina != 'inicio' && $pagina != 'gestion_liga' && $pagina != 'gestion_division' && $pagina != 'gestion_pista' && $pagina != 'gestion_arbitro' && $pagina != 'gestion_partido' && $pagina != 'gestion_noticia' && $pagina != 'gestion_cuenta' && $pagina != 'gestion_sancion' && $pagina != 'gestion_regla' && $pagina != 'gestion_inscripcion'){
		header ("Location: http://miligadepadel.manuelbdv.es");
	}
}

function alerta($num_alertas){//muestra alertas
	if($num_alertas != 0){
		if($num_alertas > 9){
			$texto = '<span class="alerta2">'.$num_alertas.'</span>';
		}
		else{
			$texto = '<span class="alerta">'.$num_alertas.'</span>';
		}
	}
	else{$texto = '';}
	return $texto;
}

function codifica($cadena){//encripta
	$nueva = base64_encode($cadena);
	return $nueva;
}

function decodifica($cadena){//desencripta
	$nueva = base64_decode($cadena);
	return $nueva;
}

function genera_id_url($largo,$num,$posicion){//genera la url
	//$caracteres = "0123456789"; //posibles caracteres a usar
	$caracteres = "01Y23w45M67l89O"; //posibles caracteres a usar
	$cadena = ""; //variable para almacenar la cadena generada
	for($i=1; $i<=$largo; $i++){
		if($i == $posicion){
			$cadena .= $num;
		}
		else{
			$pos = rand(0,9);
			$cadena .= substr($caracteres,$pos,1);
			//Extraemos 1 caracter de los caracteres entre el rango 0 a Numero de letras que tiene la cadena 
		}
	}
	return codifica($cadena);
}

function obtenLocalizacion($tipo,$id){//obtiene el nombre
	$db = new MySQL('unicas');//UNICAS
	if($tipo == 1){//OBTENGO EL PAIS
		$consulta = $db->consulta("SELECT Name FROM paises WHERE Code = '$id'; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$retorno = $resultados['Name'];
	}
	else if($tipo == 2){//OBTENGO LA PROVINCIA
		$consulta = $db->consulta("SELECT provincia FROM provincias WHERE id = '$id'; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$retorno = utf8_encode($resultados['provincia']);
	}
	else{//OBTENGO LA CIUDAD
		$consulta = $db->consulta("SELECT municipio FROM municipios WHERE id = '$id'; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$retorno = $resultados['municipio'];
	}
	return $retorno;
}

function obtenPagoDivisionesPagadas($id_liga){//obtiene el id de divisiones pagadas
	$id_pago_web = array();
	$i = 0;
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT id_pago_web FROM pago_web WHERE liga = '$id_liga' AND tipo = 'D' AND pagado = 'S'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$id_pago_web[$i] = $resultados['id_pago_web'];
		$i++;
	}
	return $id_pago_web;
}

function hay_ligaPago($email){//obtiene si el usuario tiene ligas de pago
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_liga FROM liga WHERE usuario = '$email' AND tipo_pago > 0; ");
	$num = $consulta->num_rows;
	return $num;
}

function obten_idDivisionUrl($url){
	//hay que buscar a partir de $inicio la primera F
	$n = decodifica($url);
	$pos = strpos($n, 'F',12);
	return substr($n,12,$pos-12);
}

function obtenPagoWeb($id_liga,$id_division,$tipo){//obtiene el id de pago web
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT id_pago_web FROM pago_web WHERE liga = '$id_liga' AND division = '$id_division' AND tipo = '$tipo'; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	$id_pago_web = $resultados['id_pago_web'];
	return $id_pago_web;
}

function obtenAdminPagoRecibido($id){//obtiene si el administrados ha recibido algún pago a su cuenta paypal
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT receptor FROM pago_web WHERE receptor = '$id' AND pagado = 'S'; ");
	$num = $consulta->num_rows;
	return $num;
}

function modificaDivisiones($id_liga,$bloqueo,$max_equipos){//bloquea/desbloquea divisiones menos la 1 (se utiliza durante los 7 dias por si cambia de plan)
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("UPDATE division SET max_equipos = '$max_equipos', bloqueo = '$bloqueo' WHERE liga = '$id_liga' and not (num_division = 1); ");
}

function obtenNumPartidos($id_division){//obtiene el numero de partidos para una division
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_partido FROM partido WHERE division = '$id_division'; ");
	$cont = $consulta->num_rows;
	return $cont;
}
function obtenNumJornadas($id_division){//obtiene el numero de partidos para una division
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT MAX(jornada) AS max FROM partido WHERE division = '$id_division'; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['max'];
}
/*function jornadaPartidosJugados($id_division,$jornada){//obtiene el numero de partidos para una division
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_partido) AS num FROM partido WHERE division = '$id_division' AND jornada = '$jornada' AND (set1_local > 0 OR set2_local > 0 OR set3_local > 0 OR set4_local > 0 OR set5_local > 0 OR set1_visitante > 0 OR set2_visitante > 0 OR set3_visitante > 0 OR set4_visitante > 0 OR set5_visitante > 0); ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['num'];
}*/
function jornadaPartidosFinalizados($id_division,$jornada){//obtiene el numero de partidos con ESTADO = 1 finalizados para la jornada indicada
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_partido) AS num FROM partido WHERE division = '$id_division' AND jornada = '$jornada' AND estado >= 1 ; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['num'];
}
function hayPartidoDescanso($id_division,$jornada){//obtiene si la jornada tiene partido de descanso
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_partido) AS num FROM partido WHERE division = '$id_division' AND jornada = '$jornada' AND (set1_local = -1  AND set1_visitante = -1 ); ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['num'];
}
function obten_edad($fecha){
	$anyo = substr($fecha,0,4);
	$res = date('Y')-$anyo;
	return $res;
}
function obten_idJugador($nombre,$apellidos,$email){//obtiene el nombre del jugador directamente
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT id_jugador FROM jugador WHERE nombre = '$nombre' AND apellidos = '$apellidos' AND email = '$email' ; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['id_jugador'];
}
function obtenNombreJugador($id_equipo,$campo){//obtiene el nombre del jugador a través del id_equipo
	session_start();
	$db = new MySQL($_SESSION['bd']);//LIGA PADEL
	if($campo == 'jugador1'){
		$consulta = $db->consulta("SELECT jugador1 as jugador FROM equipo WHERE id_equipo = '$id_equipo'; ");
	}
	else{
		$consulta = $db->consulta("SELECT jugador2 as jugador FROM equipo WHERE id_equipo = '$id_equipo'; ");
	}
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);//obtengo el id del jugador
	$id_jugador = $resultado['jugador'];
	return obtenNombreJugador2($id_jugador);
}
function obtenNombreJugador2($id_jugador){//obtiene el nombre del jugador directamente
	$db = new MySQL('unicas');//UNICAS
	$consulta = $db->consulta("SELECT nombre,apellidos FROM jugador WHERE id_jugador = '$id_jugador'; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return ucwords($resultado['nombre'].' '.$resultado['apellidos']);
}
function nombreLigaRepetido($nombre){//comprueba si ya está el nombre de liga repetido
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_liga FROM liga WHERE nombre='$nombre'; ");
	$cont = $consulta->num_rows;
	return $cont;
}

function nombreEmailRepetido($email){//comprueba si ya está el nombre de liga repetido
	$db = new MySQL('unicas_liga');//UNICAS LIGA
	$consulta = $db->consulta("SELECT email FROM usuario WHERE email='$email'; ");
	$cont = $consulta->num_rows;
	return $cont;
}

function modificaEmail($actual,$nuevo){//comprueba si ya está el nombre de liga repetido
	$db = new MySQL('unicas_liga');//UNICAS LIGA
	$consulta = $db->consulta("UPDATE usuario SET email = '$nuevo' WHERE email = '$actual'; ");
}

function obtenIdLiga($email){//obtiene el id liga
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_liga FROM liga WHERE usuario = '$email' ORDER BY id_liga DESC; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	$id_liga = $resultado['id_liga'];
	return $id_liga;
}

function obtenNumInscripciones($id_liga,$id_division){//obtiene el numero de inscripciones pagadas
	$db = new MySQL('session');//LIGA PADEL
	if($id_division != ''){//para liga y division
		$consulta = $db->consulta("SELECT id_inscripcion FROM inscripcion WHERE liga = '$id_liga' AND division = '$id_division' AND pagado = 'S' ; ");
		$num = $consulta->num_rows;
	}
	else{//para toda la liga
		$consulta = $db->consulta("SELECT id_inscripcion FROM inscripcion WHERE liga = '$id_liga' AND pagado = 'S' ; ");
		$num = $consulta->num_rows;
	}
	return $num;
}

function obtenNumEquipos($id_liga,$id_division,$pagado){//obtiene el numero de equipos
	$db = new MySQL('session');//LIGA PADEL
	if($id_division != ''){//para liga y division
		$consulta = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '$id_liga' AND division = '$id_division' AND pagado = '$pagado' ; ");
		$num = $consulta->num_rows;
	}
	else{//totales para la liga
		$consulta = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '$id_liga' ; ");
		$num = $consulta->num_rows;
	}
	return $num;
}
function modificaEstadoEquipo($id_equipo,$estado){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("UPDATE equipo SET estado = '$estado' WHERE id_equipo = '$id_equipo' ;");
}
function obtenNumPremio($id_division){//obtiene si tiene registro de premio insertado para una división 0=no 1=si
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_premio FROM premio WHERE division = '$id_division'; ");
	$num = $consulta->num_rows;
	return $num;
}

/*
COMPROBAR QUE NO SE LLAMA DE NINGUN SITIO
function obtenRestoDiasSuscripcion($id_liga){//devuelve el resto de días de margen de suscripción para que pueda cambiar de plan en la LIGA
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT fec_creacion FROM liga WHERE id_liga='$id_liga'; ");
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
				$i = 4;
			}
		}
	}
	else{
		$resto = 0;
	}
	return $resto;
}*/
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

function obtenNumDivisiones($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_division FROM division WHERE liga = '$id_liga'; ");
	$num_divisiones = $consulta->num_rows;
	return $num_divisiones;
}
function obtenGanador($local,$visitante,$set1_l,$set2_l,$set3_l,$set4_l,$set5_l,$set1_v,$set2_v,$set3_v,$set4_v,$set5_v){
	$error = 0;
	$cont_l = 0;
	$cont_v = 0;
	$array_local = array($set1_l,$set2_l,$set3_l,$set4_l,$set5_l);
	$array_visitante = array($set1_v,$set2_v,$set3_v,$set4_v,$set5_v);
	if($set4_l == -1 && $set4_v == -1){$tam = 3;}
	else{$tam = 5;}
	for($i=0; $i<$tam; $i++){
		if($array_local[$i] > $array_visitante[$i]){//si local gana
			$cont_l++;
			if($array_local[$i] < 6){//SI HAY ERROR DEVUELVO -1 Y NO FINALIZO EL PARTIDO
				$error = -1;
				break;
			}	
		}
		else if($array_local[$i] < $array_visitante[$i]){//si visitante gana
			$cont_v++;
			if($array_visitante[$i] < 6){//SI HAY ERROR DEVUELVO -1 Y NO FINALIZO EL PARTIDO
				$error = -1;
				break;
			}
		}
		else{//entra aqui si son iguales
			$error = -1;
			break;
		}
	}
	if($cont_l > $cont_v){$retorno = $local;}
	else{$retorno = $visitante;}
	if($error != 0){$retorno = $error;}
	return $retorno;
}
function hayTiebreak($set1_l,$set2_l,$set3_l,$set4_l,$set5_l,$set1_v,$set2_v,$set3_v,$set4_v,$set5_v){
	$retorno = 'N';
	$array_local = array($set1_l,$set2_l,$set3_l,$set4_l,$set5_l);
	$array_visitante = array($set1_v,$set2_v,$set3_v,$set4_v,$set5_v);
	if($set4_l == -1 && $set4_v == -1){$tam = 3;}
	else{$tam = 5;}
	for($i=0; $i<$tam; $i++){
		if( ($array_local[$i] == 7 && $array_visitante[$i] == 6) || ($array_local[$i] == 6 && $array_visitante[$i] == 7) ){
			$retorno = 'S';
		}
	}
	return $retorno;
}
function obtenNumSancionesJugador($id_jugador,$tipo){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_sancion) as total FROM sancion_jugador WHERE jugador = '$id_jugador' AND tipo = $tipo; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	$num = $res['total'];
	if($num == ''){$num = 0;}
	return $num;
}
function obtenNumSancionesEquipo($id_equipo,$tipo){
	$db = new MySQL('session');//LIGA PADEL
	if($tipo == 0){//sancion de partido
		$consulta = $db->consulta("SELECT SUM(partido) as total FROM sancion_equipo WHERE equipo = '$id_equipo' AND tipo = $tipo; ");
	}
	else{//expulsion
		$consulta = $db->consulta("SELECT COUNT(id_sancion) as total FROM sancion_equipo WHERE equipo = '$id_equipo' AND tipo = $tipo; ");
	}
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	$num = $res['total'];
	if($num == ''){$num = 0;}
	return $num;
}
function sumaPartidosSancion($id_equipo,$partido,$estado_buscar,$estado_nuevo){//realiza los partidos de sanción a los partidos en estado=0 activos
	$partidos_obt = array();
	$local = array();
	$visitante = array();
	$sets_local = array();
	$sets_visitante = array();
	$ganador = array();//para controlar los partidos expulsados
	$i = 0;
	$db = new MySQL('session');//LIGA PADEL
	//al estar en estado=0 coge los partidos activos, por lo tanto los partidos de descanso se omiten porque están a = 1 finalizado.
	$consulta = $db->consulta("SELECT id_partido,local,visitante,set4_local,ganador FROM partido WHERE estado = '$estado_buscar' AND (local = '$id_equipo' OR visitante = '$id_equipo') ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$partidos_obt[$i] = $resultados['id_partido'];
		$local[$i] = $resultados['local'];
		$visitante[$i] = $resultados['visitante'];
		$set4 = $resultados['set4_local'];
		$ganador[$i] = $resultados['ganador'];
		if($i == $partido-1){break;}
		else{$i++;}
	}
	$num_partidos = count($partidos_obt);
	for($i=0; $i<$num_partidos; $i++){
		if($local[$i] == $id_equipo){//si el local es el equipo a sancionar, ganador visitante 
			$ganador = $visitante[$i]; 
			$v_local = 0; 
			$v_visitante = 6;
			if(obtenNumSancionesEquipo($visitante[$i],1) > 0 ){//si el otro equipo tambien está expulsado
				$v_visitante = 0;
				$ganador = 0;
			}
			if($estado_buscar == 2){//para controlar los partidos sancionados
				if($local[$i] == $ganador[$i]){//si el local tiene el partido sancionado ganado, la sancion del partido es del visitante
					$v_visitante = 0;
					$ganador = 0;
				} 
			}
		}
		else{//si es el visitante el equipo a sancionar, ganador el local
			$ganador = $local[$i]; 
			$v_local = 6; 
			$v_visitante = 0;
			if(obtenNumSancionesEquipo($local[$i],1) > 0 ){//si el otro equipo tambien está expulsado
				$v_local = 0;
				$ganador = 0;
			}
			if($estado_buscar == 2){//para controlar los partidos sancionados
				if($visitante[$i] == $ganador[$i]){//si el local tiene el partido sancionado ganado, la sancion del partido es del visitante
					$v_local = 0;
					$ganador = 0;
				} 
			}
		}
		for($m=0; $m<5; $m++){//crear sets
			if($m >= 3){//si es el set 4 o 5
				if($set4 == -1){//es de 3 sets
					$sets_local[$m] = -1;
					$sets_visitante[$m] = -1;
				}
				else{//es de 5 sets
					$sets_local[$m] = $v_local;
					$sets_visitante[$m] = $v_visitante;
				}
			}
			else{
				$sets_local[$m] = $v_local;
				$sets_visitante[$m] = $v_visitante;
			}
		}
		$consulta = $db->consulta("UPDATE  partido SET  set1_local = '$sets_local[0]',set2_local = '$sets_local[1]',set3_local = '$sets_local[2]',set4_local = '$sets_local[3]',set5_local = '$sets_local[4]',set1_visitante = '$sets_visitante[0]',set2_visitante = '$sets_visitante[1]',set3_visitante = '$sets_visitante[2]',set4_visitante = '$sets_visitante[3]',set5_visitante = '$sets_visitante[4]',ganador = '$ganador',tiebreak = 'N',estado = '$estado_nuevo' WHERE id_partido = $partidos_obt[$i]; ");
	}
	return $num_partidos;
}
function restaPartidosSancion($id_equipo,$partido,$estado_buscar,$estado_nuevo){//realiza los partidos de sanción a los partidos en estado=0 activos
	$partidos_obt = array();
	$i = 0;
	$db = new MySQL('session');//LIGA PADEL
	//al estar en estado=0 coge los partidos activos, por lo tanto los partidos de descanso se omiten porque están a = 1 finalizado.
	$consulta = $db->consulta("SELECT id_partido,set4_local FROM partido WHERE estado = '$estado_buscar' AND (local = '$id_equipo' OR visitante = '$id_equipo') ORDER BY id_partido DESC ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$partidos_obt[$i] = $resultados['id_partido'];
		$set4 = $resultados['set4_local'];
		if($i == $partido-1){break;}
		else{$i++;}
	}
	$num_partidos = count($partidos_obt);
	for($i=0; $i<$num_partidos; $i++){
		if($set4 == -1){//es de 3 sets
			$consulta = $db->consulta("UPDATE  partido SET  set1_local = 0,set2_local = 0,set3_local = 0,set1_visitante = 0,set2_visitante = 0,set3_visitante = 0,ganador = NULL,tiebreak = NULL,estado = '$estado_nuevo' WHERE id_partido = $partidos_obt[$i]; ");
		}
		else{//es de 5 sets
			$consulta = $db->consulta("UPDATE  partido SET  set1_local = 0,set2_local = 0,set3_local = 0,set4_local = 0,set5_local = 0,set1_visitante = 0,set2_visitante = 0,set3_visitante = 0,set4_visitante = 0,set5_visitante = 0,ganador = NULL,tiebreak = NULL,estado = '$estado_nuevo' WHERE id_partido = $partidos_obt[$i]; ");
		}
	}
	return $num_partidos;
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

function obtenEquiposDivision($id_liga,$id_division){//DEVUELVE UN ARRAY CON LOS ID DE LOS EQUIPOS
	$id_equipos = array();
	$i = 0;
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '$id_liga' AND division = '$id_division'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$id_equipos[$i] = $resultados['id_equipo'];
		$i++;
	}
	return $id_equipos;
}

function obten_numDiaSemana($fecha){
	$dia = date('w',strtotime($fecha));
	if($dia == 0){
		$dia = 7;
	}
	return $dia;
}
function obten_idUltimaNoticia($id_liga,$id_division){//obtener el id de la ultima noticia insertada
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_noticia FROM noticia WHERE liga = '$id_liga' AND division = '$id_division' AND resumen = 'Administrador' ORDER BY id_noticia DESC; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['id_noticia'];
}
function obten_idUltimoEquipo($id_liga,$id_division,$id_jugador1,$id_jugador2){//obtener el id de la ultimo equipo insertado
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '$id_liga' AND division = '$id_division' AND jugador1 = '$id_jugador1' AND jugador2 = '$id_jugador2' ; ");
	$resultado = $consulta->fetch_array(MYSQLI_ASSOC);
	return $resultado['id_equipo'];
}
function obten_numPistas($id_liga){//obtiene el numero de pistas
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_pista) as cuenta FROM pista WHERE liga = '$id_liga'; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	$num = $res['cuenta'];
	return $num;
}

function obten_nombrePista($id_pista){//obtiene el numero de pistas
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT nombre FROM pista WHERE id_pista = '$id_pista'; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['nombre'];
}

function obten_numArbitros($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_arbitro) as cuenta FROM arbitro WHERE liga = '$id_liga'; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	$num = $res['cuenta'];
	return $num;
}
function buscar_jugador($nombre,$apellidos,$email,$telefono,$dni){//busca jugadores por las 4 claves
	$coincidencias = 0;
	$id_jugador_encontrado = 0;
	$db = new MySQL('unicas');//UNICAS
	if(!empty($nombre) && !empty($apellidos)){
		$consulta = $db->consulta("SELECT id_jugador FROM jugador WHERE nombre = '".ucwords($nombre)."' && apellidos = '".ucwords($apellidos)."' ; ");
		$res = $consulta->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			$id_jugador_encontrado = $res['id_jugador'];
			$coincidencias++;
		}
	}
	if(!empty($email)){
		$consulta = $db->consulta("SELECT id_jugador FROM jugador WHERE email = '$email'; ");
		$res = $consulta->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			if($id_jugador_encontrado != 0 && $id_jugador_encontrado == $res['id_jugador']){
				$coincidencias++;
			}
		}
	}
	if(!empty($telefono)){
		$consulta = $db->consulta("SELECT id_jugador FROM jugador WHERE telefono = '$telefono'; ");
		$res = $consulta->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			if($id_jugador_encontrado != 0 && $id_jugador_encontrado == $res['id_jugador']){
				$coincidencias++;
			}
		}
	}
	if(!empty($dni)){
		$consulta = $db->consulta("SELECT id_jugador FROM jugador WHERE dni = '$dni'; ");
		$res = $consulta->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			if($id_jugador_encontrado != 0 && $id_jugador_encontrado == $res['id_jugador']){
				$coincidencias++;
			}
		}
	}
	if($coincidencias >= 2){$retorno = $id_jugador_encontrado;}
	else{$retorno = 0;}
	return $retorno;
}
/*function obten_dniMinimo(){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT MIN(dni) as minimo FROM jugador; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['minimo'];
}*/
function utilizando_pista($id_division,$id_pista){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_partido) as cuenta FROM partido WHERE division = '$id_division' AND pista = '$id_pista'; ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	//$num = 
	return $res['cuenta'];
}

function utilizando_arbitro($id_division,$id_arbitro){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT COUNT(id_partido) as cuenta FROM partido WHERE division = '$id_division' AND (arbitro_principal = '$id_arbitro' OR arbitro_auxiliar = '$id_arbitro' OR arbitro_adjunto = '$id_arbitro' OR arbitro_silla = '$id_arbitro' OR arbitro_ayudante = '$id_arbitro'); ");
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['cuenta'];
}

function obten_datosRegla($id_liga,$campo){
	$db = new MySQL('session');//LIGA PADEL
	
	if($campo == 'id_regla'){
		$consulta = $db->consulta("SELECT id_regla as resultado FROM regla WHERE liga = '$id_liga'; ");
	}
	else{
		$consulta = $db->consulta("SELECT texto as resultado FROM regla WHERE liga = '$id_liga'; ");
	}
	$res = $consulta->fetch_array(MYSQLI_ASSOC);
	return $res['resultado'];
}

//calcula el numero de días desde el día de incio hasta el que tiene horario
function dias_comienzo($dia_inicio,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo){
	$dia_inicio_c = $dia_inicio;
	for($i=0; $i<6; $i++){//se ejecuta maximo 7 veces
		if($dia_inicio_c == 1){//ES LUNES
			if($lunes == ''){$dia_inicio_c++;}
			else{break;}
		}
		else if($dia_inicio_c == 2){//ES MARTES
			if($martes == ''){$dia_inicio_c++;}
			else{break;}
		}
		else if($dia_inicio_c == 3){//ES MIERCOLES
			if($miercoles == ''){$dia_inicio_c++;}
			else{break;}
		}
		else if($dia_inicio_c == 4){//ES JUEVES
			if($jueves == ''){$dia_inicio_c++;}
			else{break;}
		}
		else if($dia_inicio_c == 5){//ES VIERNES
			if($viernes == ''){$dia_inicio_c++;}
			else{break;}
		}
		else if($dia_inicio_c == 6){//ES SABADO
			if($sabado == ''){$dia_inicio_c++;}
			else{break;}
		}
		else{//ES DOMINGO
			if($domingo == ''){$dia_inicio_c = 1;}
			else{break;}
		}
	}//fin for
	//EL NUMERO DE ITERACIONES DEL BUCLE SON LOS DIAS A SUMAR
	return $i;
}
//comprueba el horario
function comprueba_entreHorario($cont_horas,$duracion,$desde1,$hasta1,$desde2,$hasta2){
	if( $desde1 != 0 || $desde2 != 0 ){
		if($desde1 != 0 && $desde2 == 0){// solo mañana
			if($desde1+$cont_horas <= $hasta1){$retorno = ($desde1+$cont_horas)-$duracion;}
			else{$retorno = 1;}
		}
		else if($desde1 == 0 && $desde2 != 0){// solo tarde
			if($desde2+$cont_horas <= $hasta2){$retorno = ($desde2+$cont_horas)-$duracion;}
			else{$retorno = 1;}
		}
		else{//mañana y tarde AQUI HAY FALLO
			if($desde1+$cont_horas <= $hasta1){//mañana
				$retorno = ($desde1+$cont_horas)-$duracion;
			}
			else{//tarde
				$disputados = ($hasta1-$desde1)/$duracion;
				$pos = strpos($disputados,'.');
				if($pos > 0){$a_descontar = substr($disputados,0,$pos);}
				else{$a_descontar = $disputados;}
				$num_partidos = $cont_horas/$duracion;
				$a_sumar = ($num_partidos - $a_descontar)*$duracion;//por ejemplo 4.5h=3 partidos - 3h=2 partidos jugados || se resta la duración para que empiece bien
				//para que entre aquí y resetee
				if($desde2+$a_sumar <= $hasta2){$retorno = (($desde2+$a_sumar)-$duracion);}
				else{$retorno = 1;}
			}
		}
	}
	else{
		$retorno = 1;
	}
	//TIENE QUE DEVOLVER LA HORA CALCULADA Y SI DA ERROR RETORNA 1
	return $retorno;
}

function detect()
{
	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
	$os=array("WIN","MAC","LINUX");
	
	# definimos unos valores por defecto para el navegador y el sistema operativo
	$info['browser'] = "OTHER";
	$info['os'] = "OTHER";
	
	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent)
	{
		$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
		$f = $s + strlen($parent);
		$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		if ($s)
		{
			$info['browser'] = $parent;
			$info['version'] = $version;
		}
	}
	
	# obtenemos el sistema operativo
	foreach($os as $val)
	{
		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
			$info['os'] = $val;
	}
	
	# devolvemos el array de valores
	return $info;
}

//-------------------------------------------------------------------------------------------------------------------
//--------------------------FUNCIONES DE DESPLEGABLES---------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
function autocompletado($autocompletado){
	echo '<select name="auto_completar" id="auto_completar" class="input_select_liga">';
	if($autocompletado == 'S'){
		echo '<option selected="selected" value="S">S&iacute;</option>';
		echo '<option value="N">No</option>';
	}
	else if($autocompletado == 'N'){
		echo '<option value="S">S&iacute;</option>';
		echo '<option selected="selected" value="N">No</option>';
	}
	else{
		echo '<option selected="selected" value="S">S&iacute;</option>';
		echo '<option value="N">No</option>';
	}
    echo '</select>';
}

function generos($genero){
	if($genero == 'M'){
		echo '<option selected="selected" value="M">Masculino</option>';
		echo '<option value="F">Femenino</option>';
		echo '<option value="A">Mixtos</option>';
	}
	else if($genero == 'F'){
		echo '<option value="M">Masculino</option>';
		echo '<option selected="selected" value="F">Femenino</option>';
		echo '<option value="A">Mixtos</option>';
	}
	else if($genero == 'A'){
		echo '<option value="M">Masculino</option>';
		echo '<option value="F">Femenino</option>';
		echo '<option selected="selected" value="A">Mixtos</option>';
	}
	else{
		echo '<option selected="selected" value="M">Masculino</option>';
		echo '<option value="F">Femenino</option>';
		echo '<option value="A">Mixtos</option>';
	}
}
function generos2($genero,$id){//para el jugador, por lo que mixto se elimina
	echo '<select name="'.$id.'" id="'.$id.'" class="input_select_liga">';
	if($genero == 'M'){
		echo '<option selected="selected" value="M">Masculino</option>';
	}
	else{
		echo '<option selected="selected" value="F">Femenino</option>';
	}
    echo '</select>';
}

function vista($vista){
	echo '<select name="vista" id="vista" class="input_select_liga">';
	if($vista == 0){//publica
		echo '<option selected="selected" value="0">P&uacute;blica</option>';
		echo '<option value="1">Privada</option>';
	}
	else if($vista == 1){//privada
		echo '<option value="0">P&uacute;blica</option>';
		echo '<option selected="selected" value="1">Privada</option>';
	}
	else{
		echo '<option selected="selected" value="0">P&uacute;blica</option>';
		echo '<option value="1">Privada</option>';
	}
    echo '</select>';
}

function tipo_pago($tipo_liga){
	if($tipo_liga == 0){//gratis
		echo '<option selected="selected" value="0">10 Equipos - (Gratis)</option>';
		echo '<option value="1">15 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option value="2">20 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">25 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}
	else if($tipo_liga == 1){//15 equipos
		echo '<option value="0">10 Equipos - (Gratis)</option>';
		echo '<option selected="selected" value="1">15 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option value="2">20 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">25 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}
	else if($tipo_liga == 2){//20 equipos
		echo '<option value="0">10 Equipos - (Gratis)</option>';
		echo '<option value="1">15 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option selected="selected" value="2">20 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">25 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}
	else if($tipo_liga == 3){//25 equipos
		echo '<option value="0">10 Equipos - (Gratis)</option>';
		echo '<option value="1">15 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option value="2">20 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option selected="selected" value="3">25 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}
	else{
		echo '<option value="0">10 Equipos - (Gratis)</option>';
		echo '<option selected="selected" value="1">15 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option value="2">20 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">25 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}
}

function idayvuelta($idayvuelta){
	if($idayvuelta == 'S'){
		echo '<option selected="selected" value="S">S&iacute; (+10 &euro;)</option>';
		echo '<option value="N">No</option>';
	}
	else if($idayvuelta == 'N'){
		echo '<option value="S">S&iacute; (+10 &euro;)</option>';
		echo '<option selected="selected" value="N">No</option>';
	}
	else{
		echo '<option value="S">S&iacute; (+10 &euro;)</option>';
		echo '<option selected="selected" value="N">No</option>';
	}
}

function movimientos($movimientos){
	echo '<select name="movimientos" id="movimientos" class="input_select_liga">';
	for($i=0; $i<7; $i++){
		if($movimientos == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
    echo '</select>';
}
function duracion_partido($duracion){
	echo '<select name="duracion_partido" id="duracion_partido" class="input_select_liga">';
	for($i=1; $i<4; $i=$i+0.5){
		if($i == 1){$texto = ' hora';}
		else{$texto = ' horas';}
		if($duracion == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.$texto.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.$texto.'</option>';
		}
	}
    echo '</select>';
}

function sets($sets){
	echo '<select name="sets" id="sets" class="input_select_liga">';
	for($i=3; $i<6; $i+=2){
		if($sets == $i){
			echo '<option selected="selected" value="'.$i.'">Al mejor de '.$i.' sets</option>';
		}
		else{
			echo '<option value="'.$i.'">Al mejor de '.$i.' sets</option>';
		}
	}
    echo '</select>';
}
function select_horas($name,$id,$horario){
	echo '<select name="'.$name.'" id="'.$id.'" onchange="color_hora(this)" class="input_select_liga">';
	if($horario == 'M'){$i = 8;$f = 15;}
	else{$i = 15;$f = 24;}
	if(substr($name,0,5) == 'desde'){
		echo '<option value="">--Desde--</option>';
		for(; $i<$f; $i=$i+0.5){
			echo '<option value="'.$i.'">'.crear_hora($i).'</option>';
		}
	}
	else{
		echo '<option value="">--Hasta--</option>';
		$i++;
		for(; $i<=$f; $i=$i+0.5){
			echo '<option value="'.$i.'">'.crear_hora($i).'</option>';
		}
	}
    echo '</select>';
}

function select_horas2($datos,$hora){
	echo '<select name="'.$datos.'" id="'.$datos.'" class="input_select_liga">';
	if($hora == '00:00:00' || $hora == '' || $hora == NULL){
		echo '<option value="" selected="selected">--Sin Hora--</option>';
	}
	else{
		echo '<option value="">--Sin Hora--</option>';
	}
	for($i=8; $i<24; $i=$i+0.5){
		$temp = crear_hora($i);
		$completa = $temp.':00';
		if($hora == $completa){
			echo '<option selected="selected" value="'.$i.'">'.$temp.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$temp.'</option>';
		}
	}
    echo '</select>';
}

function check_pistas($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_pista,nombre FROM pista WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		echo '<span class="cuadroInputs"><input type="checkbox" name="id_pistas" value="'.$resultados['id_pista'].'" />'.substr($resultados['nombre'],0,18).'</span>';
	}
}
function select_pistas($id_liga,$campo,$pista){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	if($pista == 0 || $hora == '' || $hora == NULL){
		echo '<option value="" selected="selected">--Sin Pista--</option>';
	}
	else{
		echo '<option value="">--Sin Pista--</option>';
	}
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_pista,nombre FROM pista WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['id_pista'] == $pista){
			echo '<option selected="selected" value="'.$resultados["id_pista"].'">'.$resultados["nombre"].'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_pista"].'">'.$resultados["nombre"].'</option>';
		}
	}
    echo '</select>';
}

function check_arbitros($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_arbitro,nombre,apellidos FROM arbitro WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$nom_completo = $resultados['nombre'].' '.$resultados['apellidos'];
		echo '<span class="cuadroInputs"><input type="checkbox" name="id_arbitros" value="'.$resultados['id_arbitro'].'" />'.substr($nom_completo,0,15).'</span>';
	}
}
function select_arbitros($id_liga,$campo,$arbitro){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	if($arbitro == NULL || $arbitro == 0){
		echo '<option selected="selected" value="">'.ucwords(str_replace('_',' ',$campo)).'</option>';//sustituir _ por espacio	
	}
	else{
		echo '<option value="">'.ucwords(str_replace('_',' ',$campo)).'</option>';//sustituir _ por espacio
	}
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_arbitro,nombre,apellidos FROM arbitro WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		//PERMITIR 15 CARACTERES ENTRE NOMBRE Y APELLIDOS
		if($resultados['id_arbitro'] == $arbitro){
			echo '<option selected="selected" value="'.$resultados["id_arbitro"].'">'.substr($resultados["nombre"].' '.$resultados["apellidos"],0,15).'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_arbitro"].'">'.$resultados["nombre"].' '.substr($resultados["apellidos"],0,5).'</option>';
		}
	}
    echo '</select>';
}
function tipo_arbitros($tipo){
	$array_tipos = array('Principal','Auxiliar','Adjunto','Silla','Ayudante');
	echo '<select name="tipo" id="tipo" class="input_select_liga">';
	for($i=0; $i<5; $i++){
		if($tipo == $i){
			echo '<option selected="selected" value="'.$i.'">'.$array_tipos[$i].'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$array_tipos[$i].'</option>';
		}
	}
    echo '</select>';
}
function resultados($resultado,$campo){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	for($i=0; $i<8; $i++){
		if($resultado == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
    echo '</select>';
}
function dia($resultado,$campo){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="inputText">';
	for($i=1; $i<=31; $i++){
		if($resultado == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
    echo '</select>';
}
function mes($resultado,$campo){
	$meses = array('Mes','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	echo '<select name="'.$campo.'" id="'.$campo.'" class="inputText">';
	for($i=1; $i<=12; $i++){
		if($resultado == $i){
			echo '<option selected="selected" value="'.$i.'">'.$meses[$i].'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$meses[$i].'</option>';
		}
	}
    echo '</select>';
}
function anyo($resultado,$campo){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="inputText">';
	$inicio = date('Y')-10;
	$fin = date('Y')-90;
	for(; $inicio>=$fin; $inicio--){
		if($resultado == $inicio){
			echo '<option selected="selected" value="'.$inicio.'">'.$inicio.'</option>';
		}
		else if($inicio == 1970){
			echo '<option selected="selected" value="'.$inicio.'">'.$inicio.'</option>';
		}
		else{
			echo '<option value="'.$inicio.'">'.$inicio.'</option>';
		}
	}
    echo '</select>';
}
function desplegable_liga($email,$id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_liga,nombre FROM liga WHERE usuario = '$email' AND bloqueo = 'N'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['id_liga'] == $id_liga){
			echo '<option selected="selected" value="'.$resultados["id_liga"].'">'.$resultados["nombre"].'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_liga"].'">'.$resultados["nombre"].'</option>';
		}
	}
}

function desplegable_division($id_liga,$id_division){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_division,num_division FROM division WHERE liga = '$id_liga' AND bloqueo = 'N'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['id_division'] == $id_division){
			echo '<option selected="selected" value="'.$resultados["id_division"].'">'.$resultados["num_division"].'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_division"].'">'.$resultados["num_division"].'</option>';
		}
	}
}
?>