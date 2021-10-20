<?php
include_once ("../../class/mysql.php");
include_once ("../funciones/f_general.php");
include_once ("../funciones/f_obten.php");
include_once ("../funciones/f_secundarias.php");
include_once ("../../class/usuario.php");
include_once ("../../class/liga.php");
include_once ("../../class/division.php");
session_start();
if(!empty($_SESSION['usuario'])){$usuario = unserialize($_SESSION['usuario']);}
if(!empty($_SESSION['liga'])){$liga = unserialize($_SESSION['liga']);}
if(!empty($_SESSION['division'])){$division = unserialize($_SESSION['division']);}
$id_usuario = $usuario->getValor('id_usuario');
$email = $usuario->getValor('email');
$bd = $usuario->getValor('bd');
//$bloqueo = obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','emisor',$email,'pagado','N','','','','','');//obtenemos las ligas bloqueadas
//si una liga esta pagada y bloqueada es porque ha terminado, las bloqueadas no estn pagadas
$bloqueo = obten_consultaUnCampo('session','COUNT(id_liga)','liga','usuario',$id_usuario,'bloqueo','S','pagado','N','','','');//obtenemos las ligas bloqueadas sin pagar
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT id_division FROM liga,division WHERE usuario = '$id_usuario' AND pagado = 'N' AND id_liga = liga AND division.bloqueo = 'S' AND num_division > 1; ");
if($consulta->num_rows > 0){$bloqueo += $consulta->num_rows;}
if($liga != ''){
	$tipo_pago = $liga->getValor('tipo_pago');
	$id_liga = $liga->getValor('id_liga');
	$id_division = $division->getValor('id_division');
	$num_division = $division->getValor('num_division');
	$suscripcion = $division->getValor('suscripcion');
	//num generales
	$numEquipos = obten_consultaUnCampo('session','COUNT(id_equipo)','equipo','liga',$id_liga,'division',$id_division,'pagado','S','','','');
	$numPartidos = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$id_division,'','','','','','','');
	$numPartidosTotales = obten_numMaxPartidos($id_liga);
	$num_inscripciones = obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$id_liga,'division',$id_division,'','','','','');
	$num_disputas = obten_consultaUnCampo('session','COUNT(id_disputa)','disputa','division',$id_division,'','','','','','','');
	$num_partidos_act =obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$id_division,'estado','0','','','','','');//numero de partidos activos
	$comienzoActualizado = obten_consultaUnCampo('session','comienzo','division','id_division',$id_division,'','','','','','','');//comienzo actualizado
	//alertas generales
	$alerta_prueba_gratis = obten_consultaUnCampo('unicas_liga','COUNT(usuario)','prueba_gratis','usuario',$id_usuario,'bd',$bd,'','','','','');
	$alerta_liga_ver = 0;
	if($liga->getValor('nombre') == 'Sin nombre'){$alerta_liga_ver = 1;}//SI LA LIGA ES SIN NOMBRE
	$a_comenzado = $division->getValor('comienzo');
	$alerta_inscrip_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','liga',$id_liga,'division',$id_division,'seccion','modificar_inscripcion.php','leido','N','');
	$alerta_disputa_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','liga',$id_liga,'division',$id_division,'seccion','modificar_disputa.php','leido','N','');
	if($tipo_pago > 0 ){//PAGO
		//num pago
		$num_pistas = obten_consultaUnCampo('session','COUNT(id_pista)','pista','liga',$id_liga,'','','','','','','');
		$num_arbitros = obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$id_liga,'','','','','','','');
		$num_publicidad = obten_consultaUnCampo('session','COUNT(id_publicidad)','publicidad','usuario_publi',$id_usuario,'liga',$id_liga,'division',$id_division,'','','');//numero de publicidad insertada
		$num_pago_rec_div = obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','division',$id_division,'usuario',$id_usuario,'pagado','S','estado','R','');
		//NUEVA TEMPORADA
		$num_partidos_act_liga = obten_numPartidosActivosLiga($id_liga);
		$hay_registros = obten_consultaUnCampo('session','COUNT(id_nueva_temporada)','nueva_temporada','liga',$id_liga,'','','','','','','');
		$consulta = $db->consulta("SELECT id_division FROM liga,division,nueva_temporada WHERE usuario = '$id_usuario' AND id_liga = division.liga AND division.comienzo = 'S' AND division.bloqueo = 'S' AND id_division = division ; ");//obtiene las divisiones bloqueadas por nueva temporada a restar a bloqueados
		$bloqueo = $bloqueo-($consulta->num_rows);
		$num_divisiones = obten_consultaUnCampo('session','COUNT(id_division)','division','liga',$id_liga,'bloqueo','N','','','','','');
		$num_comienzos = obten_consultaUnCampo('session','COUNT(id_division)','division','liga',$id_liga,'comienzo','S','','','','','');
		$num_ligas_pago = obten_consultaUnCampo('session','COUNT(id_liga)','liga','usuario',$id_usuario,'tipo_pago','1','','','','','');
		///SE PASA A BOTONES EN LIGA Y DIVISION
		//alertas pago
		$alerta_pago_env_liga = obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','liga',$id_liga,'usuario',$id_usuario,'pagado','N','estado','E','');
		$alerta_pago_env_div = obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','division',$id_division,'usuario',$id_usuario,'pagado','N','estado','E','');
		$alerta_pago_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','liga',$id_liga,'division',$id_division,'seccion','modificar_pago_recibido.php','leido','N','');
		//puntos
		$db2 = new MySQL('unicas');//LIGA PADEL
		$consulta2 = $db2->consulta("SELECT DISTINCT(jugador) FROM puntos, jugador WHERE usuario ='$id_usuario' AND bd = '$bd' AND jugador = id_jugador AND genero = 'M'; ");
		$num_puntosMasc = $consulta2->num_rows;
		$db2 = new MySQL('unicas');//LIGA PADEL
		$consulta2 = $db2->consulta("SELECT DISTINCT( jugador) FROM puntos, jugador WHERE usuario ='$id_usuario' AND bd = '$bd' AND jugador = id_jugador AND genero = 'F'; ");
		$num_puntosFem = $consulta2->num_rows;
		unset($db,$db2,$consulta,$consulta2);
	}
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="../../css/menu_panel_usuario.css" media="screen">
</head>
<body>
<?php
//COMPROBAR FECHAS DE CREACION Y DIAS DE SUSCRIPCION, PARA ACTIVAR O DESACTIVAR SECCIONES, SI YA HA PASADO DE LAS FECHAS SOLO MOSTRAR LA SECCIN DE PAGOS. LAS LIGAS Y DIVISIONES ESTAN CAPADAS EN LOS SELECTS YA QUE NO MUESTRAN LAS BLOQUEADAS, PERO DEBEN DE MOSTRAR PARA QUE PUEDAN PAGAR.
$bloqueo_todo = 'n';
if($liga != ''){
	//NUEVA TEMPORADA
	$gratis = '
	<div class="container">
		<ul id="nav">';
	//en cuanto se informa la nueva liga, se ha confirmado y no se debe de entrar
	if($tipo_pago > 0 && $num_partidos_act_liga == 0 && $num_divisiones == $num_comienzos){//nueva temporada
		$gratis .= '
			<li><a href="#">Temporada '.alerta_verde(1).'</a>
				<ul class="subs">';	
		if($hay_registros == 0){//entro en el primer paso generar
			$gratis .= '<li><a href="../nueva_temporada/gestion_temporada.php?id='.genera_id_url(50,0,13).'">Generar</a></li>';
		}
		else{//entro en el segundo paso confirmar
			$gratis .= '<li><a href="../nueva_temporada/gestion_temporada.php?id='.genera_id_url(50,1,13).'">Ver Respuestas</a></li>';
			$bloqueo_todo = 's';
		}		
		$gratis .= '
					<li><a href="../nueva_temporada/gestion_temporada.php?id='.genera_id_url(50,2,13).'">Finalizar</a></li>
				</ul>
			</li>';
	}//fin if
	if($bloqueo_todo == 'n'){
		if($alerta_liga_ver == 0  && ($alerta_prueba_gratis == 0 && $numPartidosTotales == 0)){//si solo tiene alerta de prueba_gratis
			$gratis .= '	
				<li><a href="#">Liga '.alerta_verde(1).'</a>';
		}
		else{
			$gratis .= '	
				<li><a href="#">Liga '.alerta($alerta_liga_ver).'</a>';
		}
		$gratis .= '	
					<ul class="subs">
						<li><a href="../liga/gestion_liga.php?id='.genera_id_url(50,0,13).'">Ver/Modif.&nbsp;'.alerta($alerta_liga_ver).'</a></li>
						<li><a href="../liga/gestion_liga.php?id='.genera_id_url(50,1,13).'">Crear nueva</a></li>
						<li><a href="../liga/gestion_liga.php?id='.genera_id_url(50,2,13).'">Enlace Externo</a></li>';
		if($alerta_prueba_gratis == 0 && $numPartidosTotales == 0){
			$gratis .= '<li><a href="../liga/gestion_liga.php?id='.genera_id_url(50,3,13).'">Activar Premium&nbsp;'.alerta_verde(1).'</a></li>';
		}
		$gratis .= '
					</ul>
				</li>';
		if($numEquipos > 2 && $numPartidos == 0 && $comienzoActualizado == 'N'){
			$gratis .= '<li><a href="#">Division '.alerta_verde(1).'</a>';
		}
		else{
			$gratis .= '<li><a href="#">Division</a>';
		}
		$gratis .= '
					<ul class="subs">
						<li><a href="../division/gestion_division.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;</a></li>';
		if($tipo_pago != 0){
			
			 $gratis.= '<li><a href="../division/gestion_division.php?id='.genera_id_url(50,1,13).'">Crear nueva</a></li>';
		}
		if($numEquipos > 3 && $numPartidos == 0 && $division->getValor('comienzo') == 'N'){
			 $gratis.= '<li><a href="../division/gestion_division.php?id='.genera_id_url(50,2,13).'">Comenzar '.alerta_verde(1).'</a></li>';
		}						 			
		  $gratis.='</ul>
				</li>';
		if($bloqueo > 0 && $tipo_pago == 0){
			$gratis .= '
				<li><a href="#">Pagos&nbsp;'.alerta($bloqueo).'</a>
					<ul class="subs">
						<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,2,13).'">Bloqueados&nbsp;'.alerta($bloqueo).'</a></li>
					</ul>
				</li>';
		}
		if($numPartidos > 0){
		$gratis.= ' 
				<li><a href="#">Partidos</a>
						<ul class="subs">
							<li><a href="../partido/gestion_partido.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;</a></li>
						</ul>
				</li>';
		}
		/*if($numEquipos > 1){
				$gratis.='
					<li><a href="#">Equipos</a>
						<ul class="subs">
							<li><a href="#">Ver/Modificar</a></li>
						</ul>
					</li>';
		}*/
		//If($numEquipos < obten_equipos($tipo_pago) && $a_comenzado == 'N' && strtotime(date('Y-m-d H:i:s')) >= strtotime($division->getValor('suscripcion')) ){//entra si la division no ha comenzado y hay plazas de equipos
				
		$gratis.='
					<li><a href="#">Inscripciones&nbsp;'.alerta_verde($alerta_inscrip_rec).'</a>
						<ul class="subs">';
						if($num_inscripciones > 0){
							$gratis.='
							<li><a href="../inscripcion/gestion_inscripcion.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;'.alerta_verde($alerta_inscrip_rec).'</a></li>';
						}//fin si hay inscripciones
						if($numPartidos == 0 && $num_inscripciones < obten_equipos($tipo_pago)){
							$gratis.='
							<li><a href="../inscripcion/gestion_inscripcion.php?id='.genera_id_url(50,1,13).'">Crear nueva</a></li>';
						}
		$gratis.='		
						</ul>
					</li>';
			
				/*NO DEJO INSERTAR EL ADMIN POR PROBLEMAS DE PRIVACIDAD, PUEDE VER NOMBRES Y CORREOS DE TODA LA WEB
				$gratis.='
							<li><a href="../inscripcion/gestion_inscripcion.php?id='.genera_id_url(50,1,13).'">Crear nueva</a></li>';
				*/
				
		//}//fin comprobacion de inscripciones
		if($numPartidos > 0){//SI NO HAY PARTIDOS NO TIENE SENTIDO CREAR NOTICIAS
				$gratis.='		
					<li><a href="#">Noticias</a>
						<ul class="subs">
							<li><a href="../noticia/gestion_noticia.php?id='.genera_id_url(50,1,13).'">Crear nueva</a></li>
						</ul>
					</li>';
		}// fin if
				$gratis.='	
					<li><a href="#">Reglas</a>
						<ul class="subs">
							<li><a href="../regla/gestion_regla.php?id='.genera_id_url(50,0,13).'">Ver/Modificar</a></li>
						</ul>
					</li>';
		if($num_disputas != 0){
				$gratis.='
					<li><a href="#">Tickets&nbsp;'.alerta($alerta_disputa_rec).'</a>
						<ul class="subs">
							<li><a href="../disputa/gestion_disputa.php?id='.genera_id_url(50,0,13).'">Revisar&nbsp;'.alerta($alerta_disputa_rec).'</a></li>
						</ul>
					</li>';
		}
		$pago = '
				<li><a href="#">Pagos&nbsp;'.alerta($alerta_pago_env_liga+$alerta_pago_rec+$bloqueo).'</a>
					<ul class="subs">
							<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,0,13).'">Enviados&nbsp;'.alerta($alerta_pago_env_div).'</a></li>';
		if($alerta_pago_rec > 0){//si hay ALERTAS DE pagos recibidos
			$pago .= '<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,1,13).'">Recibidos&nbsp;'.alerta_verde($alerta_pago_rec).'</a></li>';
		}
		else{//compruebo si hay pagos recibidos
			if($num_pago_rec_div > 0){//si hay PAGOS RECIBIDOS PARA LA DIVISION
				$pago .= '<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,1,13).'">Recibidos&nbsp;'.alerta_verde(0).'</a></li>';
			}
		}
		if($bloqueo > 0){
			$pago .= '<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,2,13).'">Bloqueados&nbsp;'.alerta($bloqueo).'</a></li>';
		}
		$pago .= '					
					</ul>
				</li>';
		$pago .= '
				<li><a href="#">Patrocinador</a>
					<ul class="subs">';
		if($num_publicidad != 0){
			$pago .= '<li><a href="../pdad/gestion_publicidad.php?id='.genera_id_url(50,0,13).'">Ver/Modficar&nbsp;</a></li>';
		}
		if($num_publicidad <= 10){
			$pago .= '<li><a href="../pdad/gestion_publicidad.php?id='.genera_id_url(50,1,13).'">Crear Nuevo&nbsp;</a></li>';
		}
		$pago .='
					</ul>
				</li>';
		$pago .= '
				<li><a href="#">Pistas</a>
					<ul class="subs">';
		if($num_pistas != 0){		
			$pago .= '	<li><a href="../pista/gestion_pista.php?id='.genera_id_url(50,0,13).'">Ver/Modificar</a></li>';
		}
		$pago .= '		<li><a href="../pista/gestion_pista.php?id='.genera_id_url(50,1,13).'">Crear nueva&nbsp;</a></li>
					</ul>
				</li>';
		$pago .= '
				<li><a href="#">Arbitros</a>
					<ul class="subs">';
		if($num_arbitros != 0){
			$pago .= '	<li><a href="../arbitro/gestion_arbitro.php?id='.genera_id_url(50,0,13).'">Ver/Modificar</a></li>';
		}					
		$pago .= '		<li><a href="../arbitro/gestion_arbitro.php?id='.genera_id_url(50,1,13).'">Crear nuevo&nbsp;</a></li>
					</ul>
				</li>	';
		if($num_ligas_pago > 0 && ($num_puntosMasc > 0 || $num_puntosFem > 0 || $numPartidos > 0)){
			$pago.= ' 
				<li><a href="#">Puntos</a>
					<ul class="subs">';
			if($num_puntosMasc > 0){
					$pago.= '<li><a href="../puntos/gestion_puntos.php?id='.genera_id_url(50,0,13).'">Ranking Mas.&nbsp;</a></li>';
			}
			if($num_puntosFem > 0){
					$pago.= '<li><a href="../puntos/gestion_puntos.php?id='.genera_id_url(50,2,13).'">Ranking Fem.&nbsp;</a></li>';
			}
			if($numPartidos > 0){
				$pago.= '<li><a href="../puntos/gestion_puntos.php?id='.genera_id_url(50,1,13).'">Insertar&nbsp;</a></li>';
			}
			$pago.= '	
					</ul>
				</li>';
		}//fin if puntos
		$pago.= ' 
				<li><a href="#">Puntuaciones</a>
					<ul class="subs">
						<li><a href="../puntuacion/gestion_puntuacion.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;</a></li>
					</ul>
				</li>';
		if($numPartidos > 0 && $num_partidos_act > 0){
			$pago.= ' 
				<li><a href="#">Sanciones</a>
					<ul class="subs">
						<li><a href="../sancion/gestion_sancion.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;</a></li>
					</ul>
				</li>';
		}
	}//FIN DE BLOQUEO TODO
	$final = '
		</ul>
	</div>';
}
else{
	echo '
	<div class="container">
		<ul id="nav">
			<li><a href="#">Liga</a>
				<ul class="subs">
					<li><a href="../liga/gestion_liga.php?id='.genera_id_url(50,1,13).'">Crear nueva</a></li>
				</ul>
			</li>';
	if($bloqueo > 0){
	echo'
			<li><a href="#">Pagos&nbsp;'.alerta($bloqueo).'</a>
				<ul class="subs">
					<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,2,13).'">Bloqueados&nbsp;'.alerta($bloqueo).'</a></li>
				</ul>
			</li>';
	}
	echo '
		</ul>
	</div>';
}
if($liga != ''){
	if($tipo_pago == 0){
		echo $gratis.$final;
	}
	else{
		echo $gratis.$pago.$final;
	}
}

?>
</body>
</html>