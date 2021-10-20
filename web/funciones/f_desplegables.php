<?php
session_start();
//----------------------------------------------------------------------------------------------------------------
//Para todas hay que hacer join de liga con division, localización: que comienzo = 'S'
//----------------------------------------------------------------------------------------------------------------
function obten_localizacionDistintasBds($num_bdligas,$c_select,$c_tabla,$c_where,$buscar){//devuelve las distintas provincias o ciudades de ligas para todas las bds
	$datos = array();
	for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
		if($i == 1){
			$_SESSION['bd'] = 'admin_liga';
		}
		else{
			$_SESSION['bd'] = 'admin_liga'.$i;
		}
		$fecha_hoy = date('Y-m-d H:i:s');
		$fecha_vacia = '0000-00-00 00:00:00';
		$cont_temp = 0;
		$temp = array();
		$db = new MySQL('session');//LIGA PADEL
		$consulta = $db->consulta("SELECT distinct(".$c_select.") FROM liga,division WHERE ".$c_where."='".$buscar."' AND liga.bloqueo = 'N' AND id_liga = liga AND division.bloqueo = 'N' AND (comienzo = 'S' OR (comienzo = 'N' AND suscripcion <= '$fecha_hoy')) ; ");
		if($consulta->num_rows>0){
		   while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			   $temp[$cont_temp] = $resultados[$c_select];
			   $cont_temp++;
		   }
		   if($i == 1){//si es la primera bd asigno directamente
				$datos = $temp;
		   }
		   else{//hay que recorrer el anterior para no duplicar
				for($a=0; $a<$cont_temp; $a++){//este recorre las ciudades temporales
					$encontrado = false;
					$num_prov = count($datos);
					for($b=0; $b<$num_prov; $b++){//recorre las almacenadas en datos
						if($datos[$b] == $temp[$a]){
							$encontrado = true;
							break;
						}
					}//fin for b
					if($encontrado == false){
						$datos[$num_prov] = $temp[$a];
					}
				}//fin for a
		   }//fin else
		}//fin num_rows
		unset($temp,$cont_temp);
	}//FIN FOR BD
	return $datos;
}

function obten_localizacionGratisDistintasBds($num_bdligas,$c_select,$c_tabla,$c_where,$buscar){//devuelve las distintas provincias o ciudades de ligas GRATIS para todas las bds
	$datos = array();
	for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
		if($i == 1){
			$_SESSION['bd'] = 'admin_liga';
		}
		else{
			$_SESSION['bd'] = 'admin_liga'.$i;
		}
		$cont_temp = 0;
		$temp = array();
		$db = new MySQL('session');//LIGA PADEL1
		$consulta = $db->consulta("SELECT distinct(".$c_select.") FROM liga,division WHERE ".$c_where."='".$buscar."' AND tipo_pago = 0 AND liga.bloqueo = 'N' AND id_liga = liga AND comienzo = 'S' AND division.bloqueo = 'N' ; ");
		if($consulta->num_rows>0){
		   while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			   $temp[$cont_temp] = $resultados[$c_select];
			   $cont_temp++;
		   }
		   if($i == 1){//si es la primera bd asigno directamente
				$datos = $temp;
		   }
		   else{//hay que recorrer el anterior para no duplicar
				for($a=0; $a<$cont_temp; $a++){//este recorre las ciudades temporales
					$encontrado = false;
					$num_prov = count($datos);
					for($b=0; $b<$num_prov; $b++){//recorre las almacenadas en datos
						if($datos[$b] == $temp[$a]){
							$encontrado = true;
							break;
						}
					}//fin for b
					if($encontrado == false){
						$datos[$num_prov] = $temp[$a];
					}
				}//fin for a
		   }//fin else
		}//fin num_rows
		unset($temp,$cont_temp);
	}//FIN FOR BD
	return $datos;
}

function obten_ligasDistintasBds($num_bdligas,$ciudad){//devuelve un array con los id_liga encontrados y la bd
	$datos = array();
	$cont = 0;
	for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
		if($i == 1){
			$_SESSION['bd'] = 'admin_liga';
		}
		else{
			$_SESSION['bd'] = 'admin_liga'.$i;
		}
		$fecha_hoy = date('Y-m-d H:i:s');
		$fecha_vacia = '0000-00-00 00:00:00';
		$db = new MySQL('session');//LIGA PADEL1
		$consulta = $db->consulta("SELECT DISTINCT id_liga,nombre,pass FROM liga,division WHERE ciudad = '$ciudad' AND liga.bloqueo = 'N' AND id_liga = liga AND division.bloqueo = 'N' AND (comienzo = 'S' OR (comienzo = 'N' AND suscripcion <= '$fecha_hoy')) ; ");
		if($consulta->num_rows>0){ 
		   while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			   $datos[$cont] = $resultados['id_liga'].'-'.$i.'-'.$resultados['nombre'];//id_liga - bd - nombre
			   $cont++;
		   }
		}//fin num_rows
		unset($temp,$cont_temp);
	}//FIN FOR BD
	return $datos;
}

function obten_ligasGratisDistintasBds($num_bdligas,$ciudad){//devuelve un array con los id_liga GRATIS encontrados y la bd
	$datos = array();
	$cont = 0;
	for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
		if($i == 1){
			$_SESSION['bd'] = 'admin_liga';
		}
		else{
			$_SESSION['bd'] = 'admin_liga'.$i;
		}
		$db = new MySQL('session');//LIGA PADEL1
		$consulta = $db->consulta("SELECT id_liga FROM liga,division WHERE ciudad = '$ciudad' AND tipo_pago = 0 AND liga.bloqueo = 'N' AND id_liga = liga AND comienzo = 'S' AND division.bloqueo = 'N' ; ");
		if($consulta->num_rows>0){ 
		   while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			   $datos[$cont] = $resultados['id_liga'].'-'.$i;//le añadimos la bd
			   $cont++;
		   }
		}//fin num_rows
		unset($temp,$cont_temp);
	}//FIN FOR BD
	return $datos;
}
?>