<?php
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
				//echo $disputados.'-';
				$pos = strpos($disputados,'.');
				if($pos > 0){$a_descontar = substr($disputados,0,$pos);}
				else{$a_descontar = $disputados;}
				//echo $a_descontar.'-';
				$num_partidos = $cont_horas/$duracion;
				//echo $num_partidos.'-';
				$a_sumar = ($num_partidos - $a_descontar)*$duracion;//por ejemplo 4.5h=3 partidos - 3h=2 partidos jugados || se resta la duración para que empiece bien
				//para que entre aquí y resetee
				//echo $a_sumar.'-';
				if($desde2+$a_sumar <= $hasta2){$retorno = (($desde2+$a_sumar)-$duracion);}
				else{$retorno = 1;}
				//echo '||||||||||||||';
			}
		}
	}
	else{
		$retorno = 1;
	}
	//TIENE QUE DEVOLVER LA HORA CALCULADA Y SI DA ERROR RETORNA 1
	return $retorno;
}
?>