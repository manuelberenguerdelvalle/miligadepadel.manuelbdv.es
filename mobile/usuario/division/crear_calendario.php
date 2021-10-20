<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_calendario.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/division.php");
include_once ("../../../class/partido.php");
include_once ("../../../class/noticia.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:10px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:5%;
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
$opcion = $_SESSION['opcion'];
$division = unserialize($_SESSION['division']);
$id_liga = $_SESSION['id_liga'];
$idayvuelta = $_SESSION['idayvuelta'];
if ( $pagina != 'gestion_division' && $opcion != 2 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	//para que existan todos
	if(!isset($lunes)){$lunes = '';}
	if(!isset($martes)){$martes = '';}
	if(!isset($miercoles)){$miercoles = '';}
	if(!isset($jueves)){$jueves = '';}
	if(!isset($viernes)){$viernes = '';}
	if(!isset($sabado)){$sabado = '';}
	if(!isset($domingo)){$domingo = '';}
	include_once ("../../funciones/f_recoger_post.php");
	//$inicio = insercion_fecha($inicio);
	$texto = 'El calendario se ha creado correctamente. Puede verlo en Partidos';
	$id_division = $division->getValor('id_division');
	$id_equipos = array();
	$id_equipos = obtenEquiposDivision($id_liga,$id_division);//obtiene los ids de los equipos
	$num_equipos = count($id_equipos);
	if( $num_equipos%2 != 0){//añadimos el equipo 0 = descansa si los equipos son impares
		$id_equipos[$num_equipos] = 0;
	}
	$num_equipos = count($id_equipos);
	$local = array();
	$visitante = array();
	$num_partidos_jornada = $num_equipos/2;//numero de jornadas que se juega
	$j = 0;
	for($i=0; $i<$num_equipos; $i++){//dividimos en local y visitante
		if($i < $num_partidos_jornada){
			$local[$i] = $id_equipos[$i];
		}
		else{
			$visitante[$j] = $id_equipos[$i];
			$j++;
		}
	}
	$num_jornadas = $num_equipos-1;//numero de jornadas
	if($modo == 0){//AUTOMATICO OK
		for($i=1; $i<=$num_jornadas; $i++){
			$auxl = $local[$num_partidos_jornada-1];//el ultimo equipo es igual al numero de jornadas equipos-1
			$auxv = $visitante[0];//el primero de visitante
			if($i > 1){//a partir de la segunda jornada
				$y = $num_partidos_jornada-1;
				for($z=0; $z<$num_partidos_jornada-1; $z++){//numero de partidos por jornada
					$visitante[$z] = $visitante[$z+1];
					$local[$y] = $local[$y-1];
					$y--;
				}
				$visitante[$num_partidos_jornada-1] = $auxl;//asignaciones
				$local[1] = $auxv;
			}
			for($b=0; $b<$num_partidos_jornada; $b++){
				if($local[$b] != 0 && $visitante[$b] !=0){//PARTIDO NORMAL
					$partido = new Partido(NULL,$i,NULL,NULL,$local[$b],$visitante[$b],0,0,0,-1,-1,0,0,0,-1,-1,NULL,NULL,0,NULL,$id_division,NULL,NULL,NULL,NULL,NULL,0);
				}
				else{
					$partido = new Partido(NULL,$i,NULL,NULL,$local[$b],$visitante[$b],-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,NULL,NULL,1,NULL,$id_division,NULL,NULL,NULL,NULL,NULL,0);
				}
				$partido->insertar();
				unset($partido);
			}
		}
	}//FIN IF AUTOMATICO
	else{//PERSONALIZADO
	//Me aseguro que estan los días aunque sea vacío
	//hay que controlar que aunque esté marcado el día, si no hay horario se ponga a vacío
		if(isset($num_id_pista) && $num_id_pista != 0){//hay pistas
			$id_pistas = array();//ARRAY PISTAS
			for($i=0; $i<$num_id_pista; $i++){
				$pistas = 'id_pista'.$i;
				$id_pistas[$i] = $$pistas;
			}
		}
		if(isset($num_id_arbitro) && $num_id_arbitro != 0){//hay arbitros
			$id_arbitros = array();//ARRAY ARBITROS
			for($i=0; $i<$num_id_arbitro; $i++){
				$arbitros = 'id_arbitro'.$i;
				$id_arbitros[$i] = $$arbitros;
			}
		}
		$array_desde1 = array();
		$array_hasta1 = array();
		$array_desde2 = array();
		$array_hasta2 = array();
		for($i=0; $i<8; $i++){//vaciamos
			$array_desde1[$i] = 0;
			$array_hasta1[$i] = 0;
			$array_desde2[$i] = 0;
			$array_hasta2[$i] = 0;
		}
		$cont = 1;//LUNES
		if($desdelunes1 != ''){$array_desde1[$cont] = $desdelunes1;}
		else{$array_desde1[$cont] = 0;}
		if($hastalunes1 != ''){$array_hasta1[$cont] = $hastalunes1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdelunes2 != ''){$array_desde2[$cont] = $desdelunes2;}
		else{$array_desde2[$cont] = 0;}
		if($hastalunes2 != ''){$array_hasta2[$cont] = $hastalunes2;}
		else{$array_hasta2[$cont] = 0;}
		$cont++;//MARTES
		if($desdemartes1 != ''){$array_desde1[$cont] = $desdemartes1;}
		else{$array_desde1[$cont] = 0;}
		if($hastamartes1 != ''){$array_hasta1[$cont] = $hastamartes1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdemartes2 != ''){$array_desde2[$cont] = $desdemartes2;}
		else{$array_desde2[$cont] = 0;}
		if($hastamartes2 != ''){$array_hasta2[$cont] = $hastamartes2;}
		else{$array_hasta2[$cont] = 0;}
		$cont++;//MIERCOLES
		if($desdemiercoles1 != ''){$array_desde1[$cont] = $desdemiercoles1;}
		else{$array_desde1[$cont] = 0;}
		if($hastamiercoles1 != ''){$array_hasta1[$cont] = $hastamiercoles1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdemiercoles2 != ''){$array_desde2[$cont] = $desdemiercoles2;}
		else{$array_desde2[$cont] = 0;}
		if($hastamiercoles2 != ''){$array_hasta2[$cont] = $hastamiercoles2;}
		else{$array_hasta2[$cont] = 0;}
		$cont++;//JUEVES
		if($desdejueves1 != ''){$array_desde1[$cont] = $desdejueves1;}
		else{$array_desde1[$cont] = 0;}
		if($hastajueves1 != ''){$array_hasta1[$cont] = $hastajueves1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdejueves2 != ''){$array_desde2[$cont] = $desdejueves2;}
		else{$array_desde2[$cont] = 0;}
		if($hastajueves2 != ''){$array_hasta2[$cont] = $hastajueves2;}
		else{$array_hasta2[$cont] = 0;}
		$cont++;//VIERNES
		if($desdeviernes1 != ''){$array_desde1[$cont] = $desdeviernes1;}
		else{$array_desde1[$cont] = 0;}
		if($hastaviernes1 != ''){$array_hasta1[$cont] = $hastaviernes1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdeviernes2 != ''){$array_desde2[$cont] = $desdeviernes2;}
		else{$array_desde2[$cont] = 0;}
		if($hastaviernes2 != ''){$array_hasta2[$cont] = $hastaviernes2;}
		else{$array_hasta2[$cont] = 0;}
		$cont++;//SABADO
		if($desdesabado1 != ''){$array_desde1[$cont] = $desdesabado1;}
		else{$array_desde1[$cont] = 0;}
		if($hastasabado1 != ''){$array_hasta1[$cont] = $hastasabado1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdesabado2 != ''){$array_desde2[$cont] = $desdesabado2;}
		else{$array_desde2[$cont] = 0;}
		if($hastasabado2 != ''){$array_hasta2[$cont] = $hastasabado2;}
		else{$array_hasta2[$cont] = 0;}
		$cont++;//DOMINGO
		if($desdedomingo1 != ''){$array_desde1[$cont] = $desdedomingo1;}
		else{$array_desde1[$cont] = 0;}
		if($hastadomingo1 != ''){$array_hasta1[$cont] = $hastadomingo1;}
		else{$array_hasta1[$cont] = 0;}
		if($desdedomingo2 != ''){$array_desde2[$cont] = $desdedomingo2;}
		else{$array_desde2[$cont] = 0;}
		if($hastadomingo2 != ''){$array_hasta2[$cont] = $hastadomingo2;}
		else{$array_hasta2[$cont] = 0;}
		
		for($i=0; $i<8; $i++){//para evitar que se entre en el horario con todo vacio o parejas incorrectas
			if($i != 0){//el 0 no es ningun dia y entra en el else y pone domingo a ''
				//si es todo a 0 la variable la pongo a vacio
				if($array_desde1[$i] == 0 && $array_hasta1[$i] == 0 && $array_desde2[$i] == 0 && $array_hasta2[$i] == 0){
					if($i == 1){$lunes = '';}
					else if($i == 2){$martes = '';}
					else if($i == 3){$miercoles = '';}
					else if($i == 4){$jueves = '';}
					else if($i == 5){$viernes = '';}
					else if($i == 6){$sabado = '';}
					else{$domingo = '';}
				}
				//si alguna de las parejas es 0 y la otra no, pongo ambas a 0 porque son datos erroneos
				if($array_desde1[$i] == 0 && $array_hasta1[$i] != 0){//
					$array_desde1[$i] = 0;
					$array_hasta1[$i] = 0;
				}
				if($array_desde1[$i] != 0 && $array_hasta1[$i] == 0){
					$array_desde1[$i] = 0;
					$array_hasta1[$i] = 0;
				}
				if($array_desde2[$i] == 0 && $array_hasta2[$i] != 0){
					$array_desde2[$i] = 0;
					$array_hasta2[$i] = 0;
				}
				if($array_desde2[$i] != 0 && $array_hasta2[$i] == 0){
					$array_desde2[$i] = 0;
					$array_hasta2[$i] = 0;
				}
			}
		}
		$inicio = insercion_fecha($inicio);//formato correcto aaaa-mm-dd
		//echo $inicio;
		$cont_horas = $duracion_partido;
		$num_dia_semana = obten_numDiaSemana($inicio);//numero día semana 1=lunes 7=domingo
		$sumar_dias = dias_comienzo($num_dia_semana,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo);
		if($sumar_dias != 0){
			$inicio = fecha_suma($inicio,'','',$sumar_dias,'','','');
		}
		$num_dia_semana = obten_numDiaSemana($inicio);//numero día semana 1=lunes 7=domingo
		if(isset($id_arbitros)){$num_arbitros = count($id_arbitros);}
		else{$arbitro_insertar = NULL;}	
		for($i=1; $i<=$num_jornadas; $i++){//JORNADAS
			$auxl = $local[$num_partidos_jornada-1];//el ultimo equipo es igual al numero de jornadas equipos-1
			$auxv = $visitante[0];//el primero de visitante
			if($i > 1){//ALGORITMO a partir de la segunda jornada
				$y = $num_partidos_jornada-1;
				for($z=0; $z<$num_partidos_jornada-1; $z++){//numero de partidos por jornada
					$visitante[$z] = $visitante[$z+1];
					$local[$y] = $local[$y-1];
					$y--;
				}
				$visitante[$num_partidos_jornada-1] = $auxl;//asignaciones
				$local[1] = $auxv;
			}
			if(isset($id_pistas)){
				if(!isset($num_pistas)){$num_pistas = count($id_pistas);}}
			else{
				$pista_insertar = NULL;
			}
			for($b=0; $b<$num_partidos_jornada; $b++){//CREO CADA JORNADA
				if(isset($id_pistas)){//GESTION PISTAS
					if($num_pistas > 0){//entro decremento y no sumo dias
						$num_pistas--;
					}
					else{//vuelvo a restablecer las pistas
						$num_pistas = count($id_pistas);
						$num_pistas--;
					}
					$pista_insertar = $id_pistas[$num_pistas];
				}
				if(isset($num_arbitros)){//GESTION ARBITROS
					if($num_arbitros == 0){
						$num_arbitros = count($id_arbitros);
					}
					$num_arbitros--;
					$arbitro_insertar = $id_arbitros[$num_arbitros];
				}
				if( $lunes != '' || $martes != '' || $miercoles != '' || $jueves != '' || $viernes != '' || $sabado != '' || $domingo != '' ){//si entra aquí es porque se han puesto horarios
					$res = 0;
					//0=CONTINUAR BUCLE, 1=CAMBIO DE DIA, >1 HORA A INSERTAR
					while( $res == 0 ){//mientras sea 0 error no hay hora
						if($array_desde1[$num_dia_semana] != 0 || $array_hasta1[$num_dia_semana] != 0 || $array_desde2[$num_dia_semana] != 0 || $array_hasta2[$num_dia_semana] != 0){//si no hay datos cambio de dia
							$res = comprueba_entreHorario($cont_horas,$duracion_partido,$array_desde1[$num_dia_semana],$array_hasta1[$num_dia_semana],$array_desde2[$num_dia_semana],$array_hasta2[$num_dia_semana]);
							if($res > 1){//inserto en el mismo día
								$hora = '';
								$hora = crear_hora($res).':00';
								$fecha = substr($inicio,0,10);
								if($local[$b] != 0 && $visitante[$b] !=0){//PARTIDO NORMAL
									if($sets == 3){//partido a 3 sets
										$partido = new Partido(NULL,$i,$fecha,$hora,$local[$b],$visitante[$b],0,0,0,-1,-1,0,0,0,-1,-1,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
									}
									else{//partido a 5 sets
										$partido = new Partido(NULL,$i,$fecha,$hora,$local[$b],$visitante[$b],0,0,0,0,0,0,0,0,0,0,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
									}
									//echo 'f_partido '.$fecha.' '.$hora.'--';
								}
								else{//PARTIDO DE DESCANSO
									$partido = new Partido(NULL,$i,$fecha,NULL,$local[$b],$visitante[$b],-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,NULL,NULL,1,NULL,$id_division,NULL,NULL,NULL,NULL,NULL,0);
									if(isset($num_pistas)){//si hay pistas sumo 1 pista
										$num_pistas++;
									}
									else{//si no hay pistas resto hora
										$cont_horas -= $duracion_partido;
									}
									//echo 'f_descanso '.$fecha.'--';
 								}//fin else partido descanso
								//echo 'fecha '.$fecha.'---';
								$partido->insertar();
								unset($partido);
							}
						}
						else{
							$res = 1;
						}
						if($res == 1){//cambio de dia
							$cont_horas = $duracion_partido;
							$inicio = fecha_suma($inicio,'','',1,'','','');//sumo un dia
							//echo 'suma '.$inicio.'-';
							$num_dia_semana = obten_numDiaSemana($inicio);//numero día semana 1=lunes 7=domingo
							$sumar_dias = dias_comienzo($num_dia_semana,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo);
							if($sumar_dias != 0){
								$inicio = fecha_suma($inicio,'','',$sumar_dias,'','','');
							}
							$num_dia_semana = obten_numDiaSemana($inicio);//numero día semana 1=lunes 7=domingo
							$res = 0;//SE PONE A 0 PARA QUE CONTINUE EL BUCLE HASTA CONSEGUIR UN 1 E INSERTAR LA HORA
						}
					}//fin while
					//AQUI SE COMPRUEBAN LAS PISTAS PARA INCREMENTAR O NO LA HORA
					if(isset($num_pistas)){
						if($num_pistas == 0){//si es cero incremento la hora
							$cont_horas += $duracion_partido;
						}
					}
					else{
						$cont_horas += $duracion_partido;
					}
				}//fin if con horarios
				else{//sin horarios
					if($sets == 3){//3 sets
						$partido = new Partido(NULL,$i,NULL,NULL,$local[$b],$visitante[$b],0,0,0,-1,-1,0,0,0,-1,-1,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
					}
					else{//5 sets
						$partido = new Partido(NULL,$i,NULL,NULL,$local[$b],$visitante[$b],0,0,0,0,0,0,0,0,0,0,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
					}
					
					$partido->insertar();
					unset($partido);
					
				}//fin sin horarios
			}//fin for partidos por jornada
		}//fin for jornada
		if($idayvuelta == 'S'){//SI IDA Y VUELTA ESTA ACTIVADA
			$auxl = '';
			$auxv = '';
			$y = '';
			for($i=$num_jornadas+1; $i<=$num_jornadas*2; $i++){
				$auxl = $local[$num_partidos_jornada-1];//el ultimo equipo es igual al numero de jornadas equipos-1
				$auxv = $visitante[0];//el primero de visitante
				if($i > 1){//ALGORITMO a partir de la segunda jornada
					$y = $num_partidos_jornada-1;
					for($z=0; $z<$num_partidos_jornada-1; $z++){//numero de partidos por jornada
						$visitante[$z] = $visitante[$z+1];
						$local[$y] = $local[$y-1];
						$y--;
					}
					$visitante[$num_partidos_jornada-1] = $auxl;//asignaciones
					$local[1] = $auxv;
				}
				if(isset($id_pistas)){
					if(!isset($num_pistas)){$num_pistas = count($id_pistas);}}
				else{
					$pista_insertar = NULL;
				}
				for($b=0; $b<$num_partidos_jornada; $b++){//CREO CADA JORNADA
					if(isset($id_pistas)){//GESTION PISTAS
						if($num_pistas > 0){//entro decremento y no sumo dias
							$num_pistas--;
						}
						else{//vuelvo a restablecer las pistas
							$num_pistas = count($id_pistas);
							$num_pistas--;
						}
						$pista_insertar = $id_pistas[$num_pistas];
					}
					if(isset($num_arbitros)){//GESTION ARBITROS
						if($num_arbitros == 0){
							$num_arbitros = count($id_arbitros);
						}
						$num_arbitros--;
						$arbitro_insertar = $id_arbitros[$num_arbitros];
					}
					if( $lunes != '' || $martes != '' || $miercoles != '' || $jueves != '' || $viernes != '' || $sabado != '' || $domingo != '' ){//si entra aquí es porque se han puesto horarios
						$res = 0;
						while( $res == 0 ){//mientras sea 0 error no hay hora
							if($array_desde1[$num_dia_semana] != 0 || $array_hasta1[$num_dia_semana] != 0 || $array_desde2[$num_dia_semana] != 0 || $array_hasta2[$num_dia_semana] != 0){//si no hay datos cambio de dia
								$res = comprueba_entreHorario($cont_horas,$duracion_partido,$array_desde1[$num_dia_semana],$array_hasta1[$num_dia_semana],$array_desde2[$num_dia_semana],$array_hasta2[$num_dia_semana]);
								if($res > 1){//inserto en el mismo día
									$hora = '';
									$hora = crear_hora($res).':00';
									$fecha = substr($inicio,0,10);
									if($local[$b] != 0 && $visitante[$b] !=0){//PARTIDO NORMAL
										if($sets == 3){//partido a 3 sets
											$partido = new Partido(NULL,$i,$fecha,$hora,$visitante[$b],$local[$b],0,0,0,-1,-1,0,0,0,-1,-1,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
										}
										else{//partido a 5 sets
											$partido = new Partido(NULL,$i,$fecha,$hora,$visitante[$b],$local[$b],0,0,0,0,0,0,0,0,0,0,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
										}
									}
									else{//PARTIDO DE DESCANSO
										$partido = new Partido(NULL,$i,$fecha,NULL,$visitante[$b],$local[$b],-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,NULL,NULL,1,NULL,$id_division,NULL,NULL,NULL,NULL,NULL,0);
										if(isset($num_pistas)){//si hay pistas sumo 1 pista
											$num_pistas++;
										}
										else{//si no hay pistas resto hora
											$cont_horas -= $duracion_partido;
										}
										//HACER QUE NO VARIE LA HORA, PISTA, ARBITRO PARA QUE NO CUENTE ESTE PARTIDO
									}//fin partido descanso
									//echo $fecha.'--';
									
									$partido->insertar();
									unset($partido);
									
								}
							}
							else{
								$res = 1;
							}
							if($res == 1){//cambio de dia
								$cont_horas = $duracion_partido;
								$inicio = fecha_suma($inicio,'','',1,'','','');//sumo un dia
								$num_dia_semana = obten_numDiaSemana($inicio);//numero día semana 1=lunes 7=domingo
								$sumar_dias = dias_comienzo($num_dia_semana,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo);
								if($sumar_dias != 0){
									$inicio = fecha_suma($inicio,'','',$sumar_dias,'','','');
								}
								$num_dia_semana = obten_numDiaSemana($inicio);//numero día semana 1=lunes 7=domingo
								$res = 0;//SE PONE A 0 PARA QUE CONTINUE EL BUCLE HASTA CONSEGUIR UN 1 E INSERTAR LA HORA
							}
						}//fin while
						if(isset($num_pistas)){
							if($num_pistas == 0){//si es cero incremento la hora
								$cont_horas += $duracion_partido;
							}
						}
						else{
							$cont_horas += $duracion_partido;
						}
					}//fin if con horarios
					else{//sin horarios
						if($sets == 3){//3 sets
							$partido = new Partido(NULL,$i,NULL,NULL,$visitante[$b],$local[$b],0,0,0,-1,-1,0,0,0,-1,-1,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
						}
						else{//5 sets
							$partido = new Partido(NULL,$i,NULL,NULL,$visitante[$b],$local[$b],0,0,0,0,0,0,0,0,0,0,NULL,NULL,0,$pista_insertar,$id_division,$arbitro_insertar,NULL,NULL,NULL,NULL,0);
						}
						
						$partido->insertar();
						unset($partido);
						
					}//fin sin horarios
				}//fin for partidos por jornada
			}//fin for jornada
		}//FIN IF IDA Y VUELTA
	}//FIN ELSE PERSONALIZADO
	 //ENVIAR CORREO AL PAGADOR
	 
	 $nombre = utf8_encode(obten_consultaUnCampo('session','nombre','liga','id_liga',$id_liga,'','','','','','',''));
	 $email_admin = $_SESSION['email'];
	 include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
	$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
	$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
	$mail->AddBCC($email_admin);
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT email1,email2 FROM inscripcion WHERE liga = '$id_liga' AND division = '".$division->getValor('id_division')."' AND pagado = 'S' ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$mail->AddBCC($resultados['email1']);
		$mail->AddBCC($resultados['email2']);
	}
	$asunto = utf8_decode('Ha comenzado la Liga de Padel <'.$nombre.' división '.$division->getValor('num_division').'>.');
	$mail->Subject = $asunto;
	$cuerpo = '<br><br>El calendario de tu Liga de Padel '.$nombre.' división '.$division->getValor('num_division').' ya se ha generado, y por lo tanto ya está disponible toda la información de tu liga en miligadepadel.manuelbdv.es<br><br>';
	$cuerpo .= 'Ahora a Ganar!!<br><br>';
	$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios en miligadepadel.manuelbdv.es!<br>",$cuerpo);
	$mail->msgHTML($body);
	$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
	$mail->send();
	$division->setValor('comienzo','S');//COMIENZA LA DIVISION
	$division->modificar();
	$resumen_noticia = utf8_decode('Sección: División -> Comenzar.');
	$descripcion_noticia = utf8_decode('Se ha generado el calendario con los partidos, puede consultarlos en Partidos -> Ver/Modificar. ');
	$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,obten_fechahora(),'');
	$noticia->insertar();
	unset($liga,$division,$noticia,$partido);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
	
}//fin else
?>