<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_partidos.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/partido.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/puntos.php");
include_once ("../../../class/puntuacion.php");
include_once ("../../../class/usuario.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$id_division = $_SESSION['id_division'];
$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');
$bd_usuario = $_SESSION['bd'];
if ( $pagina != 'gestion_partido' && $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$descripcion_noticia = '';
	$partido = new Partido($id_partido,'','','','','','','','','','','','','','','','','','','','','','','','','');
	$local = $partido->getValor('local');
	$visitante = $partido->getValor('visitante');
	$descripcion_noticia .= 'El Administrador ha modificado el partido de la jornada '.$partido->getValor("jornada").':<br>';
	$id_puntuacion = obten_consultaUnCampo('session','id_puntuacion','puntuacion','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'aplicacion','L','');
	if($id_puntuacion > 0){//si hay puntuacion cargamos
		$puntuacion = new Puntuacion($id_puntuacion,'','','','','','','','','','','','','','','','','');
	}
	$operacion = '';//esta variable determina si hay que insertar 'I' o eliminar 'D' puntos
	$jugador1_loc = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$local,'','','','','','','');
	if($jugador1_loc == 0){//temporal
		$inscripcion_equipoLocal = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$local,'','','','','','','');
		$descripcion_noticia .= utf8_encode(obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','','').' '.substr(obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','',''),0,1).'. / ');
	}
	else{$descripcion_noticia .= utf8_encode(obtenNombreJugadorMostrar($local,'jugador1').' / ');}//registrado
	$jugador2_loc = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$local,'','','','','','','');
	if($jugador2_loc == 0){//temporal
		$inscripcion_equipoLocal = obten_consultaUnCampo('session','seguro_jug2','equipo','id_equipo',$local,'','','','','','','');
		$descripcion_noticia .= utf8_encode(obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','','').' '.substr(obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','',''),0,1).'.');
	}
	else{$descripcion_noticia .= utf8_encode(obtenNombreJugadorMostrar($local,'jugador2'));}//registrado
	$partido->setValor('set1_local',$set1_local);
	$partido->setValor('set2_local',$set2_local);
	$partido->setValor('set3_local',$set3_local);
	$descripcion_noticia .= ' = '.$partido->getValor("set1_local").' - '.$partido->getValor("set2_local").' - '.$partido->getValor("set3_local");
	if(isset($set4_local)){
		$partido->setValor('set4_local',$set4_local);
		$descripcion_noticia .= ' - '.$partido->getValor("set4_local");
	}
	if(isset($set5_local)){
		$partido->setValor('set5_local',$set5_local);
		$descripcion_noticia .= ' - '.$partido->getValor("set5_local");
	}
	$descripcion_noticia .= '<br>';
	$jugador1_vis = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$visitante,'','','','','','','');
	if($jugador1_vis == 0){//temporal
		$inscripcion_equipoVis = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$visitante,'','','','','','','');
		$descripcion_noticia .= utf8_encode(obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion_equipoVis,'','','','','','','').' '.substr(obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion_equipoVis,'','','','','','',''),0,1).'. / ');
	}
	else{$descripcion_noticia .= utf8_encode(obtenNombreJugadorMostrar($visitante,'jugador1').' / ');}//registrado
	$jugador2_vis = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$visitante,'','','','','','','');
	if($jugador2_vis == 0){//temporal
		$inscripcion_equipoVis = obten_consultaUnCampo('session','seguro_jug2','equipo','id_equipo',$visitante,'','','','','','','');
		$descripcion_noticia .= utf8_encode(obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion_equipoVis,'','','','','','','').' '.substr(obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion_equipoVis,'','','','','','',''),0,1).'.');
	}
	else{$descripcion_noticia .= utf8_encode(obtenNombreJugadorMostrar($visitante,'jugador2'));}//registrado
	$partido->setValor('set1_visitante',$set1_visitante);
	$partido->setValor('set2_visitante',$set2_visitante);
	$partido->setValor('set3_visitante',$set3_visitante);
	$descripcion_noticia .= ' = '.$partido->getValor("set1_visitante").' - '.$partido->getValor("set2_visitante").' - '.$partido->getValor("set3_visitante");
	if(isset($set4_visitante)){
		$partido->setValor('set4_visitante',$set4_visitante);
		$descripcion_noticia .= ' - '.$partido->getValor("set4_visitante");
	}
	if(isset($set5_visitante)){
		$partido->setValor('set5_visitante',$set5_visitante);
		$descripcion_noticia .= ' - '.$partido->getValor("set5_visitante");
	}
	$descripcion_noticia .= '<br>';
	if($hora != ''){$hora .= ':00:00';}
	$partido->setValor('hora',$hora);
	if($fecha != datepicker_fecha($partido->getValor("fecha")) ){
		$partido->setValor('fecha',insertar_fecha($fecha));
		$descripcion_noticia .= ' Fecha: '.datepicker_fecha($partido->getValor("fecha")).' '.substr($partido->getValor("hora"),0,5);
	}
	if(isset($pista) && $pista != ''){$partido->setValor('pista',$pista);}
	if($partido->getValor("pista") != 0){
		$descripcion_noticia .= ' - Pista: '.utf8_encode(obten_consultaUnCampo('session','nombre','pista','id_pista',$partido->getValor("pista"),'','','','','','',''));//FUNCION OBTEN NOMBRE PISTA
	}
	if(isset($arbitro_principal)){$partido->setValor('arbitro_principal',$arbitro_principal);}
	if(isset($arbitro_auxiliar)){$partido->setValor('arbitro_auxiliar',$arbitro_auxiliar);}
	if(isset($arbitro_adjunto)){$partido->setValor('arbitro_adjunto',$arbitro_adjunto);}
	if(isset($arbitro_silla)){$partido->setValor('arbitro_silla',$arbitro_silla);}
	if(isset($arbitro_ayudante)){$partido->setValor('arbitro_ayudante',$arbitro_ayudante);}
	if( $partido->getValor('set1_local') == 0 &&  $partido->getValor('set2_local') == 0 && $partido->getValor('set3_local') == 0 && $partido->getValor('set1_visitante') == 0 && $partido->getValor('set2_visitante') == 0 && $partido->getValor('set3_visitante') == 0 ){// si los 3 sets local y visitante = 0
		if( $partido->getValor('set4_local') <= 0 && $partido->getValor('set5_local') <= 0 && $partido->getValor('set4_visitante') <= 0 && $partido->getValor('set5_visitante') <= 0 ){//si los sets 4,5 local y visitante estan a 0 o -1 es porque el partido está ACTIVO = 0
			$partido->setValor('estado',0);
			$partido->setValor('ganador',0);
			$operacion = 'D';
		}
		//SI POR CASUALIDAD HUBIERA ALGUN PARTIDO CON LOS 3 PRIMEROS SETS LOCAL Y VISITANTE A 0, Y ALGUNO DE LOS 4,5 SETS CON DATOS REALES EL PROGRAMA DE MANTENIMIENTO LO REALIZARIA
	}
	else{//si hay datos en los 3 primeros sets calcular el ganador
		$ganador = obtenGanador($partido->getValor('local'),$partido->getValor('visitante'),$partido->getValor('set1_local'),$partido->getValor('set2_local'),$partido->getValor('set3_local'),$partido->getValor('set4_local'),$partido->getValor('set5_local'),$partido->getValor('set1_visitante'),$partido->getValor('set2_visitante'),$partido->getValor('set3_visitante'),$partido->getValor('set4_visitante'),$partido->getValor('set5_visitante'));
		$tiebreak = hayTiebreak($partido->getValor('set1_local'),$partido->getValor('set2_local'),$partido->getValor('set3_local'),$partido->getValor('set4_local'),$partido->getValor('set5_local'),$partido->getValor('set1_visitante'),$partido->getValor('set2_visitante'),$partido->getValor('set3_visitante'),$partido->getValor('set4_visitante'),$partido->getValor('set5_visitante'));
		if($ganador != -1){//ENTRO SI NO HAY ERROR, SI ES -1 ES PORQUE HA HABIDO ALGUN ERROR DE INSERCION EN LOS SETS
			$partido->setValor('ganador',$ganador);
			$partido->setValor('tiebreak',$tiebreak);
			$partido->setValor('estado',1);
			$operacion = 'I';
		}
	}
	//$partido->setValor('modificado',0);//0 es modificado por el administrador DE MOMENTO NO
	$partido->modificar();
	if($id_puntuacion > 0){//si a insertado en algun momento en puntuaciones
		//solo es posible insertar/eliminar puntuaciones al actualizar partido si es partido de liga, y si es ultimo partido inserto 1o, 2o, 3o, 4o
		$inicio = 2;
		$fin = 12;
		$num_partidos_act = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$id_division,'estado','0','','','','','');//numero de partidos activos en la division
		$id_equipos = array();
		$id_equipos = obten_clasificacion($id_liga,$id_division);
		for(;$inicio < $fin; $inicio++){//inicio for 
			$actuacion = 'n';
			if($inicio == 2){//entramos para actualizar este partido
				$tipo = 2;
				$tipo_puntuacion = 'victoria';
				$actuacion = 's';
			}
			else{//entramos para actualizar puntos de la liga si procede
				if($num_partidos_act == 0){//si los partidos de la division están finalizados
					if($inicio == 8){
						$tipo = 8;
						$tipo_puntuacion = 'primero';
						$equipo = $id_equipos[0];
						$actuacion = 's';
					}
					else if($inicio == 9){
						$tipo = 9;
						$tipo_puntuacion = 'segundo';
						$equipo = $id_equipos[1];
						$actuacion = 's';
					}
					else if($inicio == 10){
						$tipo = 10;
						$tipo_puntuacion = 'tercero';
						$equipo = $id_equipos[2];
						$actuacion = 's';
					}
					else if($inicio == 11){
						$tipo = 11;
						$tipo_puntuacion = 'cuarto';
						$equipo = $id_equipos[3];
						$actuacion = 's';
					}
					else{$actuacion = 'n';}
				}//fin partidos division finalizados
				else{break;}
			}
			if($actuacion == 's'){//entramos a realizar acciones
				if($tipo == 2){//actualizar este partido
					$hay_puntos = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'partido',$id_partido,'tipo',$tipo,'');
					if($ganador == $local){//si el equipo ganador es local
						$j1 = $jugador1_loc;
						$j2 = $jugador2_loc;
					}
					else{//si el equipo ganador es el visitante
						$j1 = $jugador1_vis;
						$j2 = $jugador2_vis;
					}
				}
				else{//insertar puntuaciones fin partidos division EL ID_PARTIDO = 0
					$id_partido = 0;
					$hay_puntos = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'partido',$id_partido,'tipo',$tipo,'');
					$j1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$equipo,'liga',$id_liga,'division',$id_division,'','','');
					$j2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$equipo,'liga',$id_liga,'division',$id_division,'','','');
				}
				if($j1 > 0){//si no es temporal
					if($operacion == 'I' && $hay_puntos == 0 && $puntuacion->getValor($tipo_puntuacion) > 0){//insertamos si no hay puntos
						$puntosj1 = new Puntos('',$id_usuario,$j1,$bd_usuario,$id_liga,$id_division,$id_partido,obten_fechahora(),$puntuacion->getValor($tipo_puntuacion),$tipo);
						$puntosj1->insertar();
					}
				}//fin j1
				if($j2 > 0){//si no es temporal
					if($operacion == 'I' && $hay_puntos == 0 && $puntuacion->getValor($tipo_puntuacion) > 0){//insertamos si no hay puntos
						$puntosj2 = new Puntos('',$id_usuario,$j2,$bd_usuario,$id_liga,$id_division,$id_partido,obten_fechahora(),$puntuacion->getValor($tipo_puntuacion),$tipo);
						$puntosj2->insertar();
					}
				}//fin j2
				if($operacion == 'D' && $hay_puntos > 0){//eliminamos si hay puntos
						realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'partido',$id_partido,'tipo',$tipo,'');
				}
				unset($puntosj1,$puntosj2);
			}//fin actuacion
		}//fin for
		if($num_partidos_act != 0){//si hay partidos activos eliminamos puntuaciones primero,segundo,tercero,cuarto
			realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'partido',0,'tipo',8,'');//primero
			realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'partido',0,'tipo',9,'');//segundo
			realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'partido',0,'tipo',10,'');//tercero
			realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'partido',0,'tipo',11,'');//cuarto
		}
	}//fin de puntuacion
	if($descripcion_noticia != ''){
		$resumen_noticia = utf8_decode('Sección: Partido -> Ver/Modificar.');
		$fecha_noticia = obten_fechahora();
		$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,utf8_decode($descripcion_noticia),$fecha_noticia,'');
		$noticia->insertar();
		unset($noticia);
	}
	unset($liga,$division,$partido);
}//fin else

?>