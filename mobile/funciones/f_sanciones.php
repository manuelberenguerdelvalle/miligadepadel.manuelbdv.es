<?php
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
	$consulta = $db->consulta("SELECT id_partido,local,visitante,set4_local,ganador FROM partido WHERE estado = '$estado_buscar' AND ( (local = '$id_equipo'  AND visitante != 0) OR (local != 0 AND visitante = '$id_equipo') ) ; ");
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
	$consulta = $db->consulta("SELECT id_partido,set4_local FROM partido WHERE estado = '$estado_buscar' AND ( (local = '$id_equipo'  AND visitante != 0) OR (local != 0 AND visitante = '$id_equipo') ) ORDER BY id_partido DESC ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$partidos_obt[$i] = $resultados['id_partido'];
		$set4 = $resultados['set4_local'];
		if($i == $partido-1){break;}
		else{$i++;}
	}
	$num_partidos = count($partidos_obt);
	for($i=0; $i<$num_partidos; $i++){
		/*$consulta = $db->consulta("SELECT count(id_partido) FROM partido WHERE id_partido = $partidos_obt[$i] AND (local = '0' OR visitante = '0');  ");
		$es_descanso = $consulta->fetch_array(MYSQLI_ASSOC);
		if($es_descanso == 1){//partido de descanso
			$consulta = $db->consulta("UPDATE  partido SET  set1_local = -1,set2_local = -1,set3_local = -1,set4_local = -1,set5_local = -1,set1_visitante = -1,set2_visitante = -1,set3_visitante = -1,set4_visitante = -1,set5_visitante = -1,ganador = 0,tiebreak = 'N',estado = 1 WHERE id_partido = $partidos_obt[$i]; ");
		}
		else{
		}//fin else	
		*/
		if($set4 == -1){//es de 3 sets
			$consulta = $db->consulta("UPDATE  partido SET  set1_local = 0,set2_local = 0,set3_local = 0,set1_visitante = 0,set2_visitante = 0,set3_visitante = 0,ganador = 0,tiebreak = NULL,estado = '$estado_nuevo' WHERE id_partido = $partidos_obt[$i]; ");
		}
		else{//es de 5 sets
			$consulta = $db->consulta("UPDATE  partido SET  set1_local = 0,set2_local = 0,set3_local = 0,set4_local = 0,set5_local = 0,set1_visitante = 0,set2_visitante = 0,set3_visitante = 0,set4_visitante = 0,set5_visitante = 0,ganador = 0,tiebreak = NULL,estado = '$estado_nuevo' WHERE id_partido = $partidos_obt[$i]; ");
		}
		
	}
	return $num_partidos;
}


?>