<?php
session_start();
//---------------------PAGO WEB-----------------------------------------------------------------------------------------------
function obtenPagoDivisionesPagadas($bd,$id_liga){//obtiene el id de divisiones pagadas
	$id_pago_web = array();
	$i = 0;
	$db = new MySQL('unicas');//UNICAS
	//va a faltar la bd
	$c = $db->consulta("SELECT id_pago_web FROM pago_web WHERE bd = '$bd' AND liga = '$id_liga' AND tipo = 'D' AND pagado = 'S'; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$id_pago_web[$i] = $r['id_pago_web'];
		$i++;
	}
	return $id_pago_web;
}

function obten_idJugadores($id_liga){//obtiene el id de jugadores de una liga
	$id_jugador = array('');
	$i = 0;
	$db = new MySQL('session');//SESSION
	//va a faltar la bd
	$c = $db->consulta("SELECT id_jugador1,id_jugador2 FROM inscripcion WHERE liga = '$id_liga' AND pagado = 'S'; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$id_jugador[$i] = $r['id_jugador1'];
		$i++;
		$id_jugador[$i] = $r['id_jugador2'];
		$i++;
	}
	return $id_jugador;
}//SE QUEDA
function obten_sumaSets($id_equipo,$lugar){//obtiene partidos no activos
	$sets = array();
	$l = 0;
	$v = 0;
	//echo $id_equipo.'<br>';
	$db = new MySQL('session');//SESSION
	$c = $db->consulta("SELECT SUM(set1_local+set2_local+set3_local) AS l,SUM(set1_visitante+set2_visitante+set3_visitante) AS v FROM partido WHERE ".$lugar." = '".$id_equipo."' AND set1_local != '-1' ; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
	//para los partidos de descanso que es todo -1
		//$r = $c->fetch_array(MYSQLI_ASSOC);
		$l += $r['l'];
		$v += $r['v'];
		//echo 'primero local '.$l.'primero visit '.$v.'<br>';
	}
	//obten_consultaUnCampo('session','COUNT(set4_local)','partido','local',$id_equipo,'','','','','','','');
	//Y AQUI TAMBIEN WHILE
	$c = $db->consulta("SELECT SUM(set4_local+set5_local) AS l,SUM(set4_visitante+set5_visitante) AS v FROM partido WHERE ".$lugar." = '".$id_equipo."' AND set4_local != '-1' ; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		//$r = $c->fetch_array(MYSQLI_ASSOC);
		$l += $r['l'];
		$v += $r['v'];
		//echo 'segundo local '.$l.'segundo visit '.$v.'<br>';
	}
	if($lugar == 'local'){//solicita a favor local, visitante en contra
		$sets[0] = $l;
		$sets[1] = $v;
	}
	else{//solicita a favor visitante, local en contra
		$sets[0] = $v;
		$sets[1] = $l;
	}
	return $sets;
}//SE QUEDA

function generar_input_divs($liga,$actual,$pagina){
	?><select name="id_division" id="id_division" onchange="recargar('<?php echo $pagina;?>')"><?php
	$db = new MySQL('session');//LIGA PADEL
		$consulta = $db->consulta("SELECT id_division,num_division FROM division WHERE liga = '$liga' AND comienzo = 'S' AND bloqueo = 'N' ORDER BY num_division; ");
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($actual == $resultados['id_division']){//seleccionada
				echo '<option value="'.$resultados["id_division"].'" selected="selected">'.$resultados["num_division"].'</option>';
			}
			else{
				echo '<option value="'.$resultados["id_division"].'">'.$resultados["num_division"].'</option>';
			}
		}
	echo '</select>';	
}
function obten_numPartidosActivosLiga($id_liga){//obtiene el numero total de partidos activos para una liga
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT id_division FROM division WHERE liga = '".$id_liga."' AND bloqueo = 'N'; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$t += obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$r['id_division'],'estado','0','','','','','');//numero de partidos activos en toda la liga
	}
	return $t;
}
/*
function obten_numPartidosTotales($id_liga){//obtiene el numero total de partidos para una liga
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT id_division FROM division WHERE liga = '".$id_liga."' AND bloqueo = 'N'; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$t += obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$r['id_division'],'estado','0','','','','','');//numero de partidos activos en toda la liga
	}
	return $t;
}
*/

//-----------------------------------------------------------------------------------------------------------------------------------------
//---------------------CLASIFICACION------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
function obten_clasificacion($liga,$division){
	//DATOS CLASIFICACION
	$id_equipo = array();
	$ganados = array();
	$sets_aux = array();
	$sets_favor = array();
	$sets_contra = array();
	$cont = 0;
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '$liga' AND division = '$division' ; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$id_equipo[$cont] = $r['id_equipo'];
		$ganados[$cont] = obten_consultaUnCampo('session','COUNT(id_partido)','partido','ganador',$r['id_equipo'],'','','','','','','');//ganados
		$sets_aux = obten_sumaSets($r['id_equipo'],'local');//solicita a favor local, visitantes en contra
		$sets_favor[$cont] = $sets_aux[0];//local a favor
		$sets_contra[$cont] = $sets_aux[1];//visitante en contra
		$sets_aux = obten_sumaSets($r['id_equipo'],'visitante');//solicita a favor local, visitantes en contra
		$sets_favor[$cont] += $sets_aux[0];//local a favor
		$sets_contra[$cont] += $sets_aux[1];//visitante en contra
		$cont++;
	}
	$aux_id = 0;
	$aux_ga = 0;
	$aux_sf = 0;
	$aux_sc = 0;
	for($i=0; $i<count($id_equipo); $i++){
		for($j=$i+1; $j<count($id_equipo); $j++){
			if($ganados[$j] > $ganados[$i]){//si el siguiente es mayor que el base, hago cambio
				//copio el base
				$aux_id = $id_equipo[$i];
				$aux_ga = $ganados[$i];
				$aux_sf = $sets_favor[$i];
				$aux_sc = $sets_contra[$i];
				//asigno el mayor a base
				$id_equipo[$i] = $id_equipo[$j];
				$ganados[$i] = $ganados[$j];
				$sets_favor[$i] = $sets_favor[$j];
				$sets_contra[$i] = $sets_contra[$j];
				//asigno el menor
				$id_equipo[$j] = $aux_id;
				$ganados[$j] = $aux_ga;
				$sets_favor[$j] = $aux_sf;
				$sets_contra[$j] = $aux_sc;
			}
			else if($ganados[$j] == $ganados[$i]){//si es igual miro sets
				if( ($sets_favor[$j] - $sets_contra[$j]) > ($sets_favor[$i] - $sets_contra[$i]) ){
					//copio el base
					$aux_id = $id_equipo[$i];
					$aux_ga = $ganados[$i];
					$aux_sf = $sets_favor[$i];
					$aux_sc = $sets_contra[$i];
					//asigno el mayor a base
					$id_equipo[$i] = $id_equipo[$j];
					$ganados[$i] = $ganados[$j];
					$sets_favor[$i] = $sets_favor[$j];
					$sets_contra[$i] = $sets_contra[$j];
					//asigno el menor
					$id_equipo[$j] = $aux_id;
					$ganados[$j] = $aux_ga;
					$sets_favor[$j] = $aux_sf;
					$sets_contra[$j] = $aux_sc;
				}
			}
			else{}
		}//fin for i
	}//fin for j
	return $id_equipo;
}
//-----------------------------------------------------------------------------------------------------------------------------------------
//---------------------PUNTOS---------------------------------------------------------------------------------------------------------
function obten_numRegPuntos($id_jugador){//obtiene el numero de publicidades en una ciudad
	$db = new MySQL('unicas');//UNICAS
	$c = $db->consulta("SELECT COUNT(id_puntos) as n FROM puntos WHERE jugador = '$id_jugador' AND bd LIKE 'admin_liga%'; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['n'];
}
//-----------------------------------------------------------------------------------------------------------------------------------------
//---------------------LOCALIZACION----------------------------------------------------------------------------------------------------------
function obten_latitud($ciudad){//obtiene el numero de publicidades en una ciudad
	$db = new MySQL('unicas');//UNICAS
	$c = $db->consulta("SELECT latitud FROM municipios WHERE id = '$ciudad' ; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['latitud'];
}

function modificaDivisiones($id_liga,$bloqueo,$max_equipos){//bloquea/desbloquea divisiones menos la 1 (se utiliza durante los 7 dias por si cambia de plan)
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("UPDATE division SET max_equipos = '$max_equipos', bloqueo = '$bloqueo' WHERE liga = '$id_liga' and not (num_division = 1); ");
}

function obten_estadoPartido($estado){
	if($estado == 0){$retorno = 'Activo';}//ACTIVO
	else if($estado == 1){$retorno = 'Finalizado';}//FINALIZADO
	else if($estado == 2){$retorno = 'Sancionado';}//SANCIONADO
	else {$retorno = 'Expulsado';}//EQUIPO EXPULSADO
	return $retorno;
}
//--------------------PARTIDOS----------------------------------------------------------
function obten_numMaxPartidos($liga){//obitiene el numero maximos de partidos para una liga
	$num = 0;
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT id_division FROM division WHERE liga = '$liga'; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$div = $r['id_division'];
		$num += obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$div,'','','','','','','');
	}
	return $num;
}

function obten_datosPartidos($id_equipo,$estado){//obitiene el numero de partidos por estados
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT COUNT(id_partido) AS s FROM partido WHERE estado='$estado' AND set1_local != '-1' AND (local='$id_equipo' OR visitante='$id_equipo'); ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['s'];
}//SE QUEDA

function obtenNombreJugador($id_equipo,$campo){//obtiene el nombre del jugador a traves del id del equipo
	//$id_jugador = obten_idJugador3($id_equipo,$campo);
	$id_jugador = obten_consultaUnCampo('session',$campo,'equipo','id_equipo',$id_equipo,'','','','','','','');
	return obtenNombreJugador2($id_jugador);
}//SE QUEDA

function obtenNombreJugadorMostrar($id_equipo,$campo){//obtiene el nombre del jugador a traves del id del equipo
	//$id_jugador = obten_idJugador3($id_equipo,$campo);
	$id_jugador = obten_consultaUnCampo('session',$campo,'equipo','id_equipo',$id_equipo,'','','','','','','');
	return obtenNombreJugador4($id_jugador);
}//SE QUEDA

function obtenNombreJugador2($id_jugador){//obtiene el nombre del jugador directamente a través del id jugador
	$db = new MySQL('unicas');//UNICAS
	$c = $db->consulta("SELECT nombre,apellidos FROM jugador WHERE id_jugador = '$id_jugador'; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return ucwords($r['nombre'].' '.$r['apellidos']);
}//SE QUEDA

function obtenNombreJugador3($email){//obtiene el nombre del jugador directamente a través del email
	$db = new MySQL('unicas');//UNICAS
	$c = $db->consulta("SELECT nombre,apellidos FROM jugador WHERE email = '$email'; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return ucwords($r['nombre'].' '.$r['apellidos']);
}//SE QUEDA

function obtenNombreJugador4($id_jugador){//obtiene el nombre del jugador directamente a través del id jugador
	$a = array();
	$db = new MySQL('unicas');//UNICAS
	$c = $db->consulta("SELECT nombre,apellidos FROM jugador WHERE id_jugador = '$id_jugador'; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	$a = explode(' ',$r['apellidos']);
	return ucwords($r['nombre'].' '.$a[0]);
	//return ucwords($r['nombre'].' '.substr($r['apellidos'],0,1).'.');
}//SE QUEDA

function busca_jugadorEnOtroEquipo($id_liga,$id_jugador){//busca jugador en equipos de la liga para todas las divisiones
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT id_equipo AS s FROM equipo WHERE liga = '$id_liga' AND (jugador1 = '$id_jugador' OR jugador2 = '$id_jugador') ; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['s'];
}//SE QUEDA

function modificaEstadoEquipo($id_equipo,$estado){
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("UPDATE equipo SET estado = '$estado' WHERE id_equipo = '$id_equipo' ;");
}//SE QUEDA

function obten_numDivisionesSuscripcion($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT COUNT(id_division) AS s FROM division WHERE liga = '$id_liga' AND suscripcion != '0000-00-00 00:00:00'; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['s'];
}//SE QUEDA

function obtenNumSancionesEquipo($id_equipo,$tipo){
	$db = new MySQL('session');//LIGA PADEL
	if($tipo == 0){//sancion de partido
		$c = $db->consulta("SELECT SUM(partido) as total FROM sancion_equipo WHERE equipo = '$id_equipo' AND tipo = $tipo; ");
	}
	else{//expulsion
		$c = $db->consulta("SELECT COUNT(id_sancion) as total FROM sancion_equipo WHERE equipo = '$id_equipo' AND tipo = $tipo; ");
	}
	$res = $c->fetch_array(MYSQLI_ASSOC);
	$num = $res['total'];
	if($num == ''){$num = 0;}
	return $num;
}//SE QUEDA

function obtenEquiposDivision($id_liga,$id_division){//DEVUELVE UN ARRAY CON LOS ID DE LOS EQUIPOS
	$id_equipos = array();
	$i = 0;
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '$id_liga' AND division = '$id_division'; ");
	while($r = $c->fetch_array(MYSQLI_ASSOC)){
		$id_equipos[$i] = $r['id_equipo'];
		$i++;
	}
	return $id_equipos;
}//SE QUEDA
function obten_numNoticiasFotos($id_liga,$id_division){//obtener el id de la ultima noticia insertada
	$db = new MySQL('session');//LIGA PADEL
	$c = $db->consulta("SELECT COUNT(id_noticia) AS c FROM noticia WHERE liga = '$id_liga' AND division = '$id_division' AND imagenes != '' ; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['c'];
}//SE QUEDA

function obten_consultaUnCampo($conexion,$select,$tabla,$where1,$buscar1,$where2,$buscar2,$where3,$buscar3,$where4,$buscar4,$order){
	$db = new MySQL($conexion);
	if($where2 == '' && $buscar2 == ''){//1
		$c = $db->consulta("SELECT ".$select." as r FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' ".$order." ; ");
	}
	else if($where3 == '' && $buscar3 == ''){//2
		$c = $db->consulta("SELECT ".$select." as r FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' ".$order." ; ");
	}
	else if($where4 == '' && $buscar4 == ''){//3
		$c = $db->consulta("SELECT ".$select." as r FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' ".$order." ; ");
	}
	else{//4
		$c = $db->consulta("SELECT ".$select." as r FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' AND ".$where4." = '".$buscar4."' ".$order." ; ");
	}
	$r = $c->fetch_array(MYSQLI_ASSOC);
	return $r['r'];
}//SE QUEDA

function realiza_updateGeneral($conexion,$tabla,$update,$where1,$buscar1,$where2,$buscar2,$where3,$buscar3,$where4,$buscar4,$where5,$buscar5,$order){
	$db = new MySQL($conexion);
	if($where2 == '' && $buscar2 == ''){//1
		$c = $db->consulta("UPDATE ".$tabla." SET ".$update." WHERE ".$where1." = '".$buscar1."' ".$order." ; ");
	}
	else if($where3 == '' && $buscar3 == ''){//2
		$c = $db->consulta("UPDATE ".$tabla." SET ".$update." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' ".$order." ; ");
	}
	else if($where4 == '' && $buscar4 == ''){//3
		$c = $db->consulta("UPDATE ".$tabla." SET ".$update." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' ".$order." ; ");
	}
	else if($where5 == '' && $buscar5 == ''){//4
		$c = $db->consulta("UPDATE ".$tabla." SET ".$update." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' AND ".$where4." = '".$buscar4."' ".$order." ; ");
	}
	else{//5
		$c = $db->consulta("UPDATE ".$tabla." SET ".$update." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' AND ".$where4." = '".$buscar4."' AND ".$where5." = '".$buscar5."' ".$order." ; ");
	}
}//SE QUEDA

function realiza_deleteGeneral($conexion,$tabla,$where1,$buscar1,$where2,$buscar2,$where3,$buscar3,$where4,$buscar4,$where5,$buscar5,$order){
	$db = new MySQL($conexion);
	if($where2 == '' && $buscar2 == ''){//1
		$c = $db->consulta("DELETE FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' ".$order." ; ");
	}
	else if($where3 == '' && $buscar3 == ''){//2
		$c = $db->consulta("DELETE FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' ".$order." ; ");
	}
	else if($where4 == '' && $buscar4 == ''){//3
		$c = $db->consulta("DELETE FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' ".$order." ; ");
	}
	else if($where5 == '' && $buscar5 == ''){//4
		$c = $db->consulta("DELETE FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' AND ".$where4." = '".$buscar4."' ".$order." ; ");
	}
	else{//5
		$c = $db->consulta("DELETE FROM ".$tabla." WHERE ".$where1." = '".$buscar1."' AND ".$where2." = '".$buscar2."' AND ".$where3." = '".$buscar3."' AND ".$where4." = '".$buscar4."' AND ".$where5." = '".$buscar5."' ".$order." ; ");
	}
}//SE QUEDA

function buscar_jugador($nombre,$apellidos,$email,$telefono,$dni){//busca jugadores por las 4 claves
	$coincidencias = 0;
	$id_jugador_encontrado = 0;
	$db = new MySQL('unicas');//UNICAS
	if(!empty($email)){
		$c = $db->consulta("SELECT id_jugador FROM jugador WHERE email = '$email'; ");
		$res = $c->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			$id_jugador_encontrado = $res['id_jugador'];
			$coincidencias++;//si coincide el email 
		}
	}
	if(!empty($dni)){
		$c = $db->consulta("SELECT id_jugador FROM jugador WHERE dni = '$dni'; ");
		$res = $c->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			if($id_jugador_encontrado != 0 && $id_jugador_encontrado == $res['id_jugador']){
				$coincidencias++;
			}
			else{
				$id_jugador_encontrado = $res['id_jugador'];
				$coincidencias++;//si coincide el email
			}
		}
	}
	/*
	ESTOS CAMPOS DE MOMENTO NO SON PRINCIPALES QUE COINCIDAN
	EL NOMBRE Y APELLIDOS PUEDE COINCIDIR Y EL TELEFONO TMB
	POR PONERLO ALEATORIO, COMO NO SE ENVIAN MENSAJES POR MOVIL...
	if(!empty($telefono)){
		$c = $db->consulta("SELECT id_jugador FROM jugador WHERE telefono = '$telefono'; ");
		$res = $c->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			if($id_jugador_encontrado != 0 && $id_jugador_encontrado == $res['id_jugador']){
				$coincidencias++;
			}
			else{
				$id_jugador_encontrado = $res['id_jugador'];
				$coincidencias++;//si coincide el email
			}
		}
	}
	if(!empty($nombre) && !empty($apellidos)){
		$c = $db->consulta("SELECT id_jugador FROM jugador WHERE nombre = '".ucwords($nombre)."' && apellidos = '".ucwords($apellidos)."' ; ");
		$res = $c->fetch_array(MYSQLI_ASSOC);
		if(!empty($res['id_jugador'])){
			$id_jugador_encontrado = $res['id_jugador'];
			$coincidencias++;
		}
	}*/
	if($coincidencias >= 1){$retorno = $id_jugador_encontrado;}
	else{$retorno = 0;}
	return $retorno;
}//SE QUEDA

?>
