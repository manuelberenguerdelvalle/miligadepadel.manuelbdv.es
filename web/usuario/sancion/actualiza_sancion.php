<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_sanciones.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/sancion_jugador.php");
include_once ("../../../class/sancion_equipo.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$id_division = $_SESSION['id_division'];
$idayvuelta = $_SESSION['idayvuelta'];
if ( $pagina != 'gestion_sancion' && $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");
	$descripcion_noticia = '';
	if(isset($id_jugador)){//FALTA JUGADOR 
		if($tipo == 0){$tipo_falta = 'leve';}
		else{$tipo_falta = 'grave';}
		if($operacion == 'resta'){//eliminar
			$resumen_noticia = 'Sección: Sanciones -> Falta eliminada.';
			$ultima_sancion = new Sancion_jugador('',$id_jugador,'',$tipo,'');//recojo la ultima
			$ultima_sancion->borrar();
			$descripcion_noticia .= utf8_decode('El administrador ha eliminado una falta '.$tipo_falta.' al jugador/a '.utf8_encode(obtenNombreJugador4($id_jugador)).' por la siguiente razón: ');
			$descripcion_noticia .= $descripcion.'.';
			unset($ultima_sancion);
			echo 0;//para recojer y saber que ha ido bien
		}
		else if($operacion == 'suma'){//insertar
			$resumen_noticia = 'Sección: Sanciones -> Falta insertada.';
			$sancion = new Sancion_jugador(NULL,$id_jugador,obten_fechahora(),$tipo,$descripcion);
			$sancion->insertar();
			$descripcion_noticia .= utf8_decode('El administrador ha añadido una falta '.$tipo_falta.' al jugador/a '.utf8_encode(obtenNombreJugador4($id_jugador)).' por la siguiente razón: ');
			$descripcion_noticia .= $descripcion.'.';
			unset($sancion);
			echo 0;//para recojer y saber que ha ido bien
		}
		else{
			echo -1;//si es otra cosa error
		}
	}//FIN IF SANCION JUGADOR
	else if(isset($id_equipo)){//SANCION EQUIPO
	//LA VARIABLE $partido ES LA VARIABLE QUE RECIBIMOS, QUE SON LOS PARTIDOS
		if($tipo == 0){//si es sanción de partidos
			$tipo_sancion = 'partido';
			if($operacion == 'suma'){//suma
				$partidos_sancionados = sumaPartidosSancion($id_equipo,$partido,0,2);
				if($partidos_sancionados > 0){
					$copia_real = $partidos_sancionados;//copia real de los partidos ejecutados
					$sancion_equipo = new Sancion_equipo(NULL,$id_equipo,$partido,$tipo,obten_fechahora(),$descripcion);
					$sancion_equipo->insertar();
					$resumen_noticia = 'Sección: Sanciones -> Sanción de equipo.';
					$descripcion_noticia .= utf8_decode('El administrador ha insertado una sanción de '.$copia_real.' partidos al equipo compuesto por los/las jugadores/as '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador1")).' y '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador2")).' por la siguiente razón: ');
					$descripcion_noticia .= $descripcion.'.';
					unset($sancion_equipo);
				}
				else{
					$copia_real = -1;
				}
				echo $copia_real;//imprimo la copia real de los partidos ejecutados
			}
			else if($operacion == 'resta'){//resta
				$num_sanciones_equipo = obtenNumSancionesEquipo($id_equipo,$tipo);//totales
				if($num_sanciones_equipo >= $partido){
					$partidos_desancionados = restaPartidosSancion($id_equipo,$partido,2,0);//regularizo los partidos
					if($partidos_desancionados > 0){
						$copia_real = $partidos_desancionados;//copia real de los partidos ejecutados
						while($partidos_desancionados > 0){//cuando llega a 0 o menos termina
							$ultima_sancion = new Sancion_equipo('',$id_equipo,'',$tipo,'','');
							$partidos_reg = $ultima_sancion->getValor('partido');
							//si el registro tiene más partidos de los que se van a borrar modifico
							if($partidos_reg > $partidos_desancionados){
								$ultima_sancion->setValor('partido',$partidos_reg-$partidos_desancionados);
								$ultima_sancion->modificar();
							}
							else{//borro
								$ultima_sancion->borrar();
							}
							$partidos_desancionados = $partidos_desancionados - $partidos_reg;
							unset($ultima_sancion);
						}//fin while
						$resumen_noticia = 'Sección: Sanciones -> Sanción de equipo.';
						$descripcion_noticia .= utf8_decode('El administrador ha eliminado '.$copia_real.' partidos de sanción al equipo compuesto por los/las jugadores/as '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador1")).' y '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador2")).' por la siguiente razón: ');
						$descripcion_noticia .= $descripcion.'.';
					}
					else{
						$copia_real = -1;
					}
					echo $copia_real;//imprimo la copia real de los partidos ejecutados
				}//fin if num_sanciones_equipo
				else{
					echo -1;//si es otra cosa error
				}
			}//fin resta
			else{
				echo -1;//si es otra cosa error
			}
		}//fin tipo
		else{//si es expulsion del equipo
			$tipo_sancion = 'expulsion';
			$num_partidos = obten_consultaUnCampo('session','COUNT(id_equipo)','equipo','liga',$id_liga,'division',$id_division,'pagado','S','','','');
			//$num_partidos--;//decremento porque es el numero de equipos menos 1
			//las jornadas siempre son equipos-1, asi que se resta 1 para obtener el numero de partidos reales
			if($idayvuelta == 'S'){// si hay ida y vuelta son el doble de jornadas
				$num_partidos = $num_partidos*2;
			}
			if($operacion == 'suma'){//suma
				$num_sanciones_equipo = obtenNumSancionesEquipo($id_equipo,$tipo);//totales expulsiones 0 o 1
				if($num_sanciones_equipo == 0){//si no es 0 no sumo
					$partidos_sancionados = sumaPartidosSancion($id_equipo,$num_partidos,0,3);//sin jugar
					$partidos_sancionados_finalizados = sumaPartidosSancion($id_equipo,$num_partidos,1,3);//finalizados
					$copia_real = $partidos_sancionados+$partidos_sancionados_finalizados;//copia real de los partidos ejecutados
					$sancion_equipo = new Sancion_equipo(NULL,$id_equipo,1,$tipo,obten_fechahora(),$descripcion);
					$sancion_equipo->insertar();
					modificaEstadoEquipo($id_equipo,3);
					$resumen_noticia = 'Sección: Sanciones -> Sanción de equipo.';
					$descripcion_noticia .= utf8_decode('El administrador ha realizado la expulsión del equipo compuesto por los/las jugadores/as '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador1")).' y '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador2")).' por la siguiente razón: ');
					$descripcion_noticia .= $descripcion.'.';
					unset($sancion_equipo);
					echo $copia_real;//imprimo la copia real de los partidos ejecutados
				}
				else{
					echo -1;//si es otra cosa error
				}
			}
			else if($operacion == 'resta'){//resta
				$num_sanciones_equipo = obtenNumSancionesEquipo($id_equipo,$tipo);//totales expulsiones 0 o 1
				if($num_sanciones_equipo == 1){//si no es 1 no resto
					$partidos_desancionados = restaPartidosSancion($id_equipo,$num_partidos,3,0);//regularizo los partidos
					$copia_real = $partidos_desancionados;//copia real de los partidos ejecutados
					$ultima_sancion = new Sancion_equipo('',$id_equipo,'',$tipo,'','');
					$ultima_sancion->borrar();
					modificaEstadoEquipo($id_equipo,0);
					$resumen_noticia = 'Sección: Sanciones -> Sanción de equipo.';
					$descripcion_noticia .= utf8_decode('El administrador ha eliminado la sanción de '.$tipo_sancion.' al equipo compuesto por los/las jugadores/as '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador1")).' y '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador2")).' por la siguiente razón: ');
					//$descripcion_noticia .= 'El administrador ha eliminado la sanción de '.$tipo_sancion.' al equipo compuesto por los/las jugadores/as '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador1")).' y '.utf8_encode(obtenNombreJugadorMostrar($id_equipo,"jugador2")).' por la siguiente razón: ';
					$descripcion_noticia .= $descripcion.'.';
					unset($ultima_sancion);
					echo $copia_real;//imprimo la copia real de los partidos ejecutados
				}
				else{
					echo -1;//si es otra cosa error
				}
			}
			else{
				echo -1;//si es otra cosa error
			}
		}
	}//FIN ELSE IF SANCION EQUIPO
	else{
		echo -1;//RECOJO E INDICO QUE HAY UN ERROR
	}
	if($descripcion_noticia != ''){
		$fecha_noticia = obten_fechahora();
		$noticia = new Noticia(NULL,$id_liga,$id_division,utf8_decode($resumen_noticia),$descripcion_noticia,$fecha_noticia,'');
		$noticia->insertar();
		unset($noticia);
	}
	unset($noticia);
}//fin else

?>