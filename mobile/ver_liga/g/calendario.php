<?php
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_ligas.php");
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_partidos.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
session_start();
$_SESSION['pagina'] = 'ver_liga';
if(!empty($_SESSION['ancho']) && !empty($_SESSION['alto'])){//comprobamos al resolucion para cargar css
	if($_SESSION['alto'] <= 480){$dimensiones = ' 320x480';}// ipad pro 320x480
	else if($_SESSION['alto'] > 480 && $_SESSION['alto'] <= 640){$dimensiones = ' 360x640';}// iphone 5s y android 4.7", 5" y 5.5"
	else if($_SESSION['alto'] > 640 && $_SESSION['alto'] <= 667){$dimensiones = ' 375x667';}// iphone 6s y 7s
	else if($_SESSION['alto'] > 667 && $_SESSION['alto'] <= 736){$dimensiones = ' 414x736';}// iphone 6s+ y 7s+
	else if($_SESSION['alto'] > 736 && $_SESSION['alto'] <= 960){$dimensiones = ' 360x640';}// android 7"
	else if($_SESSION['alto'] > 960 && $_SESSION['alto'] <= 1024){$dimensiones = ' 768x1024';}// android 10", ipad air, air2
	else {$dimensiones = ' 360x640';}
}
else{$dimensiones = ' 360x640';}
if( (!empty($_SESSION['id_liga']) && !empty($_SESSION['id_division']) && !empty($_SESSION['bd']) && $_SESSION['tipo_pago'] == 0) || !empty($_POST['a']) || !empty($_GET['a']) ){//verifico datos session o post
	$continua = false;
	if(!empty($_POST['a']) || !empty($_GET['a'])){//si viene por post viene por acceso compartido, descodifico los datos encriptados
		if(!empty($_POST['a'])){//post
			$cadena = limpiaTexto(decodifica($_POST["a"]));
		}
		else{//get
			$cadena = limpiaTexto(decodifica($_GET["a"]));
		}
		$ini = 12;
		$pos = strpos($cadena,'F');
		$bd = substr($cadena, $ini, 1);
		if($bd == 0){$_SESSION['bd'] = 'admin_liga';}
		else{$_SESSION['bd'] = 'admin_liga'.$bd;}
		$_SESSION['id_division'] = substr($cadena, $ini+1, $pos-($ini+1));
		$_SESSION['id_liga'] = obten_consultaUnCampo('session','liga','division','id_division',$_SESSION["id_division"],'','','','','','','');
		$_SESSION['tipo_pago'] = obten_consultaUnCampo('session','tipo_pago','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
		if($_SESSION['id_liga'] != '' && $_SESSION['id_division'] != '' && $_SESSION['bd'] != ''){$continua = true;}//ninguna variable vacia
	}
	else{//entra desde el index
		$continua = true;
	}
//SI TODO VA BIEN CONTINUAMOS
	if($continua){//accede si todo va bien
		$cont = 0;
		$id_publi = array();
		$url = array();
		$contador = array();
		$liga = new Liga($_SESSION['id_liga'],'','','','','','','','','','','','','','','','');
		//echo 'ahora '.date('Y-m-d H:i:s').'<br>';
		$db = new MySQL('unicas');//LIGA PADEL
		$consulta = $db->consulta("SELECT id_publicidad_gratis,url,contador FROM publicidad_gratis WHERE provincia = '".$liga->getValor('provincia')."' AND ciudad = '".$liga->getValor('ciudad')."' AND pagado = 'S' AND estado = '0' AND fecha_fin >= '".date('Y-m-d H:i:s')."' ORDER BY ultima_rep,contador LIMIT 10; ");
		if($consulta->num_rows > 0){
			while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				//echo $resultados['ultima_rep'].'-'.$resultados['fecha_fin'].'<br>';
				$id_publi[$cont] = $resultados['id_publicidad_gratis'];
				$url[$cont] = $resultados['url'];
				$contador[$cont] = $resultados['contador'];
				$cont++;
			}//fin while
		}//fin num_rows
		$id_publi_izq = array();
		$id_publi_der = array();
		$url_izq = array();
		$url_der = array();
		$cont_izq = 0;
		$cont_der = 0;
		for($j=0; $j<10; $j++){//rellenamos array con publi izquierda y derecha
			if( ($j+1) <= count($id_publi) ){//entro si hay datos
				if($j <= 4){//para la columna izq
					$id_publi_izq[$cont_izq] = $id_publi[$j];
					$url_izq[$cont_izq] = $url[$j];
					$cont_izq++;
				}
				else{//para la columna der
					$id_publi_der[$cont_der] = $id_publi[$j];
					$url_der[$cont_der] = $url[$j];
					$cont_der++;
				}
			}//fin if con datos
			else{
				break;
			}
		}//fin for
		$url_publi_vacia = '../../usuario/registro/registrar_patrocinador.php';//URL PARA LA PUBLI VACIA, EN ESTE CASO ACCESO AL REGISTRO DE PUBLICIDAD
		//DATOS CALENDARIO
		$max_jornadas = obten_consultaUnCampo('session','MAX(jornada)','partido','division',$_SESSION['id_division'],'','','','','','','');
		if( isset($_GET['jornada']) && is_numeric($_GET['jornada']) && $_GET['jornada'] > 0 && $_GET['jornada'] <= $max_jornadas ){//cuando recibimos la jornada por GET
			$_SESSION['jornada'] = $_GET['jornada'];
			$jornada = $_SESSION['jornada'];
		}
		else if ( isset($_SESSION['jornada']) ){//cuando se actualiza la pagina
			$jornada = $_SESSION['jornada'];
		}
		else{//cuando entramos por primera vez
			$jornada = 1;
		}
		//echo $max_jornadas;
		//las jornadas siempre son equipos-1, asi que se suma 1 para obtener el numero de equipos y dividir
		if($idayvuelta == 'S'){// si hay ida y vuelta son el doble de jornadas
			$partidos_x_jornada = (($max_jornadas/2)+1)/2;
		}
		else{
			$partidos_x_jornada = ($max_jornadas+1)/2;// normal
		}				
		$id_premio = obten_consultaUnCampo('session','id_premio','premio','division',$_SESSION["id_division"],'','','','','','','');
		$id_regla = obten_consultaUnCampo('session','id_regla','regla','liga',$_SESSION["id_liga"],'','','','','','','');
		$estilo = obten_consultaUnCampo('session','estilo','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
		$auto_completar = obten_consultaUnCampo('session','auto_completar','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
cabecera_inicio();
?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Alegreya+SC">
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../css/ligas<?php echo $estilo.$dimensiones; ?>.css" />
<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/detect_paginas.js" type="text/javascript"></script>
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="../javascript/modificar_calendario.js" type="text/javascript"></script>
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'es'}
</script>
<?php
cabecera_fin();
?>
<div class="principal">
    <div class="izquierdo">
        <?php mostrar_columna($id_publi_izq,$url_izq,$url_publi_vacia); ?>
    </div>
    <div class="central">
    	<div class="cabecera">
        	<div class="logo">
            <?php 
				$logo = '../../../logos/'.$_SESSION['bd'].$_SESSION['id_liga'].'.jpg';
				if(file_exists($logo)){echo '<img src="'.$logo.'" alt="ligas de padel" />';}
				else{echo '<img src="../../../logos/0'.$estilo.'.jpg" alt="ligas de padel" />';}
			?>
            </div>
    		<div class="superior">
        		<div class="nombre"><?php echo  $_SESSION["nombre"].' - Divisi&oacute;n '.$_SESSION['num_division'];?></div>
            	<div class="inicio"><a href="http://miligadepadel.manuelbdv.es">Inicio</a></div>
            	<div class="idioma"><div id="google_translate_element"></div></div>
        	</div>
        	<div class="menu">
        		<ul class="block-menu">
                    <li><a href="calendario.php" class="three-d">
                        Calendario
                        <span aria-hidden="true" class="three-d-box">
                            <span class="front">Calendario</span>
                            <span class="back">Calendario</span>
                        </span>
                    </a></li>
                    <li><a href="clasificacion.php" class="three-d">
                        Clasificacion
                        <span aria-hidden="true" class="three-d-box">
                            <span class="front">Clasificacion</span>
                            <span class="back">Clasificacion</span>
                        </span>
                    </a></li>
                    <!-- more items here -->
                    <li><a href="noticia.php" class="three-d">
                        Noticias
                        <span aria-hidden="true" class="three-d-box">
                            <span class="front">Noticias</span>
                            <span class="back">Noticias</span>
                        </span>
                    </a></li>
<?php if( !empty($id_premio) || !empty($id_regla) ){ ?>
                    <li><a href="info.php" class="three-d">
                        Info
                        <span aria-hidden="true" class="three-d-box">
                            <span class="front">Info</span>
                            <span class="back">Info</span>
                        </span>
                    </a></li>
<?php } ?>
                    <li><a href="contacto.php" class="three-d">
                        Contacto
                        <span aria-hidden="true" class="three-d-box">
                            <span class="front">Contacto</span>
                            <span class="back">Contacto</span>
                        </span>
                    </a></li>
                </ul>
        	</div>
        </div><!--FIN CABECERA-->
        <div class="contenido">
        	<div class="texto_titulo">CALENDARIO</div>
        	<div class="publi_google"><!--REDES SOCIALES-->
            	<div class="red_social"><a href="http://facebook.com/sharer.php?u=http%3A%2F%2Fmiligadepadel.manuelbdv.es" target="_blank"><img src="../../../images/facebook-logo.png" /></a></div>
               <div class="red_social"><a href="whatsapp://send?text= http://miligadepadel.manuelbdv.es" data-action="share/whatsapp/share"><img src="../../../images/whatsapp-logo.png" /></a></div>
               <div class="red_social"><a href="https://plus.google.com/share?url=http%3A%2F%2Fmiligadepadel.manuelbdv.es" target="_blank"><img src="../../../images/google-logo.png" /></a></div>
               <div class="red_social"><a href="http://twitter.com/home?status=<?php echo urlencode("Ligas de padel http://miligadepadel.manuelbdv.es");?>" target="_blank"><img src="../../../images/twitter-logo.png" /></a></div>
            </div><!-- FIN REDES SOCIALES-->
            <div class="contenedor_datos">
            	<div id="flotante"></div>
            <?php
			//onMouseOver="showdiv(event,'."'Obligatorio'".');" onMouseOut="hiddenDiv()" style="display:table;"
			echo '<div class="contenedor_jornadas">';
			for($i=1; $i<=$max_jornadas; $i++){
				$partidos_finalizados = jornadaPartidosFinalizados($_SESSION["id_division"],$i);
				$hay_partidos_descanso = hayPartidoDescanso($_SESSION["id_division"],$i);
				if($partidos_finalizados == 0 || ($partidos_finalizados == 1 && $hay_partidos_descanso == 1) ){//si hay partidos de descanso minimo va a ver 1 partido finalizado si no 0 = jornada normal
					if($i == $jornada){
						echo '<div class="jornada"><a href="calendario.php?jornada='.$i.'" class="seleccionado">'.$i.'</a></div>';
					}
					else{
						echo '<div class="jornada"><a href="calendario.php?jornada='.$i.'" class="normal">'.$i.'</a></div>';
					}
				}
				else if($partidos_finalizados == $partidos_x_jornada){//si la suma de todos los finalizados es igual a los partidos por jornada = jornada finalizada
					if($i == $jornada){
						echo '<div class="jornada_completa"><a href="calendario.php?jornada='.$i.'" class="seleccionado">'.$i.'</a></div>';
					}
					else{
						echo '<div class="jornada_completa"><a href="calendario.php?jornada='.$i.'" class="normal">'.$i.'</a></div>';
					}
				}
				else{
					if($i == $jornada){//si es cualquier valor intermedio = jornada disputandose
						echo '<div class="jornada_disputandose"><a href="calendario.php?jornada='.$i.'" class="seleccionado">'.$i.'</a></div>';
					}
					else{
						echo '<div class="jornada_disputandose"><a href="calendario.php?jornada='.$i.'" class="normal">'.$i.'</a></div>';
					}
				}
			}
			echo '</div>';
			
			$campos_local = array('set1_local','set2_local','set3_local','set4_local','set5_local');
			$campos_visitante = array('set1_visitante','set2_visitante','set3_visitante','set4_visitante','set5_visitante');
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM partido WHERE division = '".$_SESSION['id_division']."' AND jornada = '$jornada'; ");
			while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				$jugador1_loc = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['local'],'','','','','','','');
				$jugador2_loc = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$resultados['local'],'','','','','','','');
				$jugador1_vis = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
				$jugador2_vis = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
				if($jugador1_loc == 0 && $jugador2_loc == 0 && $jugador1_vis == 0 && $jugador2_vis == 0){$todosTemporales = true;}
				else{$todosTemporales = false;}
			?>
				<div class="caja">
			<?php	
				if($resultados['local'] != 0 && $resultados['visitante'] != 0 && $todosTemporales == false){
					if($resultados['estado'] == 0 && $auto_completar == 'S'){//partido estado <= 1 activo o finalizado y auto_completar a S
			?>
					<div class="boton">
						<a href="#" onClick="return enviar(<?php echo $resultados['id_partido'];?>)">
							<div class="letra">M</div>
							<div class="letra">O</div>
							<div class="letra">D</div>
							<div class="letra">I</div>
							<div class="letra">F</div>
							<div class="letra">I</div>
							<div class="letra">C</div>
							<div class="letra">A</div>
							<div class="letra">R</div>
						</a>
					</div>  
			<?php	
					}//fin if 
					else{//partido estado = 3 expulsado
			?>
					<div class="boton_fin">
						<div class="letra">F</div>
						<div class="letra">I</div>
						<div class="letra">N</div>
						<div class="letra">A</div>
						<div class="letra">L</div>
						<div class="letra">I</div>
						<div class="letra">Z</div>
						<div class="letra">A</div>
						<div class="letra">D</div>
                        <div class="letra">O</div>
					</div>
			<?php
					}//fin else
				}//fin if local y visitante
				else{//partidos descanso
					echo '<div class="boton_fin">&nbsp;</div>';
				}
			?> 
            		<div class="mostrar_equipo">
						<div class="jugador1">
							<div class="alinear_texto">
							<?php
							if($resultados['local'] == 0){echo 'Descansa';}
							else{
								if($resultados['modificado'] == $jugador1_loc && $jugador1_loc > 0){
									echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
									echo '<b><i>'.substr(obtenNombreJugadorMostrar($resultados['local'],'jugador1'),0,35).'</i></b></span>';
								}
								else{
									if($jugador1_loc == 0){//si es temporal
										$inscripcion_equipoLocal = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$resultados['local'],'','','','','','','');
										echo substr(obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','',''),0,35);
									}
									else{echo substr(obtenNombreJugador($resultados['local'],'jugador1'),0,35);}
								}
							}		 
							?>
							</div>
						</div>
						<div class="jugador2">
							<div class="alinear_texto">
							<?php
							if($resultados['local'] == 0){echo 'Descansa';}
							else{
								if($resultados['modificado'] == $jugador1_loc && $jugador1_loc > 0){
									echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
									echo '<b><i>'.substr(obtenNombreJugadorMostrar($resultados['local'],'jugador2'),0,35).'</i></b></span>';
								}
								else{
									if($jugador1_loc == 0){//si es temporal
										echo substr(obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','',''),0,35);
									}
									else{echo substr(obtenNombreJugador($resultados['local'],'jugador2'),0,35);}
								}
							}	 
							?>
							</div>
						</div>
					</div>
					<div class="resultados">
					<form name="<?php echo 'form_res_local'.$resultados['id_partido']; ?>" id="<?php echo 'form_res_local'.$resultados['id_partido']; ?>" action="#" method="post">
						<?php
						if($resultados['local'] != 0 && $resultados['visitante'] != 0){
							if($resultados['set5_local'] == -1){$sets = 3;}
							else{$sets = 5;}
							for($i=0; $i<$sets; $i++){
								$campo = $campos_local[$i];
								if($resultados['estado'] == 0 && $auto_completar == 'S' && $todosTemporales == false){
									resultados($resultados[$campo],$campo);
								}
								else{
									echo '<div class="datos_planos">'.$resultados[$campo].'</div>';
								}
							}
						}
						?>
					</form>
					</div>
					<div class="resultados">
					<form name="<?php echo 'form_res_visit'.$resultados['id_partido']; ?>" id="<?php echo 'form_res_visit'.$resultados['id_partido']; ?>" action="#" method="post">
						<?php
						if($resultados['local'] != 0 && $resultados['visitante'] != 0){
							for($i=0; $i<$sets; $i++){
								$campo = $campos_visitante[$i];
								if($resultados['estado'] == 0 && $auto_completar == 'S' && $todosTemporales == false){
									resultados($resultados[$campo],$campo);
								}
								else{
									echo '<div class="datos_planos">'.$resultados[$campo].'</div>';
								}
							}
						}
						?>
					</form>
					</div>
					<div class="mostrar_equipo">
						<div class="jugador1">
							<div class="alinear_texto">
							<?php
							if($resultados['visitante'] == 0){echo 'Descansa';}
							else{
								if($resultados['modificado'] == $jugador1_vis && $jugador1_vis > 0){
									echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
									echo '<b><i>'.substr(obtenNombreJugadorMostrar($resultados['visitante'],'jugador1'),0,35).'</i></b></span>';
								}
								else{
									if($jugador1_vis == 0){//si es temporal
										$inscripcion_equipoVisit = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
										echo substr(obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','',''),0,35);
									}
									else{echo substr(obtenNombreJugador($resultados['visitante'],'jugador1'),0,35);}
								}
							}		 
							?>
							</div>
						</div>
						<div class="jugador2">
							<div class="alinear_texto">
							<?php
							if($resultados['visitante'] == 0){echo 'Descansa';}
							else{
								if($resultados['modificado'] == $jugador1_vis && $jugador1_vis > 0){
									echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
									echo '<b><i>'.substr(obtenNombreJugadorMostrar($resultados['visitante'],'jugador2'),0,35).'</i></b></span>';
								}
								else{
									if($jugador1_vis == 0){//si es temporal
										echo substr(obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','',''),0,35);
									}
									else{echo substr(obtenNombreJugador($resultados['visitante'],'jugador2'),0,35);}
								}
							}		 
							?>
							</div>
						</div>
					</div> 
					<div class="datos">
					<form name="<?php echo 'form_sup'.$resultados['id_partido']; ?>" id="<?php echo 'form_sup'.$resultados['id_partido']; ?>" action="#" method="post">
					<?php
					if($resultados['local'] != 0 && $resultados['visitante'] != 0 && $resultados["estado"] != 0 && $resultados["fecha"] != '0000-00-00'){//insertado muestra fecha
						$fecha = datepicker_fecha(substr($resultados["fecha"],0,10));
						echo '<br><br><div class="datos_planos">'.$fecha.'</div>';
					}
					else if($resultados['local'] != 0 && $resultados['visitante'] != 0 && $resultados['estado'] == 0 && $auto_completar == 'S' && $todosTemporales ==  false){//inserta
					?>
                        <span class="titulo">Acceso del Jugador</span>
                        <div class="nom_campo">Email:</div>
                        <div class="input_campo"><input type="text" name="email<?php echo $resultados['id_partido']; ?>" id="email<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_email(event)" ></div>
                        <div class="nom_campo">Pass:</div>
                        <div class="input_campo"><input type="password" name="password<?php echo $resultados['id_partido']; ?>" id="password<?php echo $resultados['id_partido']; ?>" class="input_text" onBlur="limpiaPassword('password',1)" maxlength="15" ></div>
                        <input type="hidden" name="local" value="<?php echo $resultados['local']; ?>" />
                        <input type="hidden" name="visitante" value="<?php echo $resultados['visitante']; ?>" />
                     <?php
					}
					else{//si auto_completar = no
					}
					?>	
					</form>
					</div>
					<div class="datos2">
					<form name="<?php echo 'form_inf'.$resultados['id_partido']; ?>" id="<?php echo 'form_inf'.$resultados['id_partido']; ?>" action="#" method="post">
                    <?php
						if($resultados['estado'] != 0 && $resultados['local'] != 0 && $resultados['visitante'] != 0 && $todosTemporales == false){
					?>
                    		<span class="titulo">Acceso del Jugador</span>
                            <div class="nom_campo">Email:</div>
                            <div class="input_campo"><input type="text" name="email<?php echo $resultados['id_partido']; ?>" id="email<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_email(event)" ></div>
                            <div class="nom_campo">Pass:</div>
                            <div class="input_campo"><input type="password" name="password<?php echo $resultados['id_partido']; ?>" id="password<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_password(event)" maxlength="15" ></div>
                            <input type="hidden" name="local" value="<?php echo $resultados['local']; ?>" />
                            <input type="hidden" name="visitante" value="<?php echo $resultados['visitante']; ?>" />
                            <input type="button" class="boton_ticket" onclick="abrir_ticket(<?php echo $resultados['id_partido'];?>)" value="Crear Ticket"  />
                    <?php
						}
					?>
					</form>
					</div><!-- fin datos2 -->   
				</div><!-- fin caja -->
			<?php
				}//fin del while
			?>   
            </div><!--fin contenedor datos -->
    </div>
    </div><!-- FIN DE CENTRAL--> 
    <div class="derecho">
    	<?php mostrar_columna($id_publi_der,$url_der,$url_publi_vacia); ?>
    </div>   
<?php
	//pie();
?>    
</div>
<?php
cuerpo_fin();
		//updatear la publicidad
		for($i=$cont; $i>=0; $i--){
		//for($i=0; $i<$cont; $i++){
			$aux = $contador[$i]+1;
			$update = 'contador='.$aux.',ultima_rep="'.date("Y-m-d H:i:s").'"';
			realiza_updateGeneral('unicas','publicidad_gratis',$update,'id_publicidad_gratis',$id_publi[$i],'','','','','','','','','');	
		}
	}//fin de continua
}//FIN DE IF INICIAL
?>