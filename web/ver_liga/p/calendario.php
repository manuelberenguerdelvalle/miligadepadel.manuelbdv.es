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
if( (!empty($_SESSION['id_liga']) && !empty($_SESSION['id_division']) && !empty($_SESSION['bd']) && $_SESSION['tipo_pago'] > 0) || !empty($_POST['a']) || !empty($_GET['a']) ){//verifico datos session o post
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
		if(isset($_POST['id_division'])){
			$_SESSION['id_division'] = limpiaTexto($_POST['id_division']);
		}
		$cont_izq = 0;
		$cont_der = 0;
		$id_publi_izq = array();
		$id_publi_der = array();
		$url_izq = array();
		$url_der = array();
		$posicion_publi_izq = array();
		$posicion_publi_der = array();
		$contador_izq = array();
		$contador_der = array();
		//$liga = new Liga($_SESSION['id_liga'],'','','','','','','','','','','','','','','','');
		//echo 'ahora '.date('Y-m-d H:i:s').'<br>';
		$db = new MySQL('session');//LIGA PADEL
		$consulta = $db->consulta("SELECT id_publicidad,url,posicion_publi,contador FROM publicidad WHERE liga = '".$_SESSION['id_liga']."' AND division = '".$_SESSION['id_division']."' AND pagado = 'S' AND estado = '0' ORDER BY posicion_publi ; ");
		if($consulta->num_rows > 0){
			while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				//echo $resultados['id_publicidad'].'-'.$resultados['posicion_publi'].'<br>';
				if(strpos($resultados['posicion_publi'],'I') != false){//si es columna Izquierda
					$id_publi_izq[$cont_izq] = $resultados['id_publicidad'];
					$url_izq[$cont_izq] = $resultados['url'];
					$posicion_publi_izq[$cont_izq] = $resultados['posicion_publi'];
					$contador_izq[$cont_izq] = $resultados['contador'];
					$cont_izq++;
				}
				else{///si es columna Derecha
					$id_publi_der[$cont_der] = $resultados['id_publicidad'];
					$url_der[$cont_der] = $resultados['url'];
					$posicion_publi_der[$cont_der] = $resultados['posicion_publi'];
					$contador_der[$cont_der] = $resultados['contador'];
					$cont_der++;
				}
			}//fin while
		}//fin num_rows
		$url_publi_vacia = '#';//URL PARA LA PUBLI VACIA, EN ESTE CASO ACCESO AL REGISTRO DE PUBLICIDAD
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
<link rel="stylesheet" type="text/css" href="../../css/bpopup.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../css/ligas<?php echo $estilo; ?>.css" />
<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/detect_paginas.js" type="text/javascript"></script>
<script src="../../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="../javascript/ligas_pago.js" type="text/javascript"></script>
<style>

</style>
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'es'}
</script>
<?php
cabecera_fin();
?>
<div id="fb-root"></div>
<script>
(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.5";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="principal">
	<div id="oculto"></div>
	<div id="content_popup">
		<div class="poptitulo"><h2>Contactar con el Administrador</h2></div>
		<div class="popcentro">
        <h3>Para publicitarte en esta posici&oacute;n debes contactar con el administrador de esta liga.</h3>
		<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
            <label class="caja_texto">Tu email:</label><label class="caja_input"><input name="contacto"  type="text" class="input_text_liga" ></label><label id="errorContacto" class="caja_error">*</label>
            <label class="caja_texto">Asunto:</label><label class="caja_input"><input name="asunto" type="text" class="input_text_liga" ></label><label id="errorAsunto" class="caja_error">*</label>
            <label class="caja_texto">Mensaje:</label><label class="caja_input_area"><textarea  rows="11" cols="30" name="mensaje" class="input_text_area" ></textarea></label><label id="errorTextarea" class="caja_error">*</label>
            <input type="hidden" name="id_liga" value="<?php echo $_SESSION["id_liga"]; ?>">
            <input type="hidden" name="id_division" value="<?php echo $_SESSION["id_division"]; ?>">
            <input type="hidden" name="bd" value="<?php echo $_SESSION["bd"]; ?>">
            <input type="hidden" name="modo" value="1">
		</form>
		</div>
        <div class="poppie2">
            <span class="button b-close"><span><a class="env" href="#"  onclick="enviar();">ENVIAR</a></span></span>
        </div>
	</div><!-- FIN POPUP -->
    <div class="izquierdo">
        <?php mostrar_columna_pago($_SESSION['bd'],$_SESSION['id_liga'],$_SESSION['id_division'],$url_izq,$posicion_publi_izq,'I',$url_vacia);?>
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
        		<div class="nombre"><?php echo  '<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$_SESSION["nombre"].' - Divisi&oacute;n&nbsp;</div>'.generar_input_divs($_SESSION['id_liga'],$_SESSION['id_division'],'calendario.php');?></div>
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
            	<div class="red_social"><div class="fb-share-button" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/p/noticia.php" data-layout="button"></div></div>
                <div class="red_social"><a href="https://twitter.com/share" class="twitter-share-button" data-text="Ligas de Padel">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div>
                <div class="red_social"><div class="g-plus" data-action="share" data-annotation="none" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/p/noticia.php"></div></div>
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
					if($resultados['estado'] == 0 && $auto_completar == 'S'){//partido activo
			?>
					<div class="boton">
						<a href="#" onClick="return enviar_datos(<?php echo $resultados['id_partido'];?>)">
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
					else{//partido finalizado
						if($resultados['estado'] == 2){
			?>
					<div class="boton_fin">
						<div class="letra">S</div>
						<div class="letra">A</div>
						<div class="letra">N</div>
						<div class="letra">C</div>
						<div class="letra">I</div>
						<div class="letra">O</div>
						<div class="letra">N</div>
                        <div class="letra">A</div>
						<div class="letra">D</div>
                        <div class="letra">O</div>
					</div>
			<?php
						}//fin if sancionado
						else if($resultados['estado'] == 3){
			?>
					<div class="boton_fin">
						<div class="letra">E</div>
						<div class="letra">X</div>
						<div class="letra">P</div>
						<div class="letra">U</div>
						<div class="letra">L</div>
						<div class="letra">S</div>
						<div class="letra">A</div>
                        <div class="letra">D</div>
						<div class="letra">O</div>
					</div>
			<?php
						}//fin if sancionado
						else{
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
						}//fin finalizado
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
								if($resultados['estado'] == 0  && $auto_completar == 'S' && $todosTemporales == false){
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
                    	<input type="hidden" name="nada" value="nada" /><!--este campo es para evitar && al enviar formulario-->
					<?php
					if($resultados['local'] != 0 && $resultados['visitante'] != 0 && ($resultados["estado"] != 0 || $auto_completar == 'N') ){//aqui entro si ya esta insertardo
						$fecha = datepicker_fecha($resultados["fecha"]);
						echo '<div class="datos_planos2">'.$fecha.'</div><div class="datos_planos2">'.substr($resultados["hora"],0,5).' h</div>';
						if($resultados['pista'] != 0){
							echo '<div class="datos_planos2">'.obten_consultaUnCampo('session','nombre','pista','id_pista',$resultados['pista'],'','','','','','','').'</div>';
						}
						if($resultados['arbitro_principal'] != 0){
							echo '<div class="datos_planos2">'.obten_consultaUnCampo('session','nombre','arbitro','id_arbitro',$resultados['arbitro_principal'],'','','','','','','').'</div>';
						}
						if($resultados['arbitro_auxiliar'] != 0){
							echo '<div class="datos_planos2">'.obten_consultaUnCampo('session','nombre','arbitro','id_arbitro',$resultados['arbitro_auxiliar'],'','','','','','','').'</div>';
						}
						if($resultados['arbitro_adjunto'] != 0){
							echo '<div class="datos_planos2">'.obten_consultaUnCampo('session','nombre','arbitro','id_arbitro',$resultados['arbitro_adjunto'],'','','','','','','').'</div>';
						}
						if($resultados['arbitro_silla'] != 0){
							echo '<div class="datos_planos2">'.obten_consultaUnCampo('session','nombre','arbitro','id_arbitro',$resultados['arbitro_silla'],'','','','','','','').'</div>';
						}
						if($resultados['arbitro_ayudante'] != 0){
							echo '<div class="datos_planos2">'.obten_consultaUnCampo('session','nombre','arbitro','id_arbitro',$resultados['arbitro_ayudante'],'','','','','','','').'</div>';
						}
						
					}
					else if($resultados['local'] != 0 && $resultados['visitante'] != 0 && $resultados['estado'] == 0 && $auto_completar == 'S' && $todosTemporales ==  false){
						$fecha = datepicker_fecha($resultados["fecha"]);
						echo '<div class="datos_planos2">'.$fecha.'</div><div class="datos_planos2">'.substr($resultados["hora"],0,5).' h</div>';
                        if(obten_consultaUnCampo('session','COUNT(id_pista)','pista','liga',$_SESSION["id_liga"],'','','','','','','') != 0 && $_SESSION["tipo_pago"] != 0){
						//f(obten_numPistas($id_liga) != 0 && $tipo_pago != 0){
							echo '<span>'.select_pistas($_SESSION["id_liga"],'pista',$resultados["pista"]).'</span>';
						}
						if(obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$_SESSION["id_liga"],'tipo','0','','','','','') != 0 && $_SESSION["tipo_pago"] != 0){
							echo '<span>'.select_arbitros2($_SESSION["id_liga"],"arbitro_principal",$resultados["arbitro_principal"]).'</span>';
						}
						if(obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$_SESSION["id_liga"],'tipo','1','','','','','') != 0 && $_SESSION["tipo_pago"] != 0){
							echo '<span>'.select_arbitros2($_SESSION["id_liga"],"arbitro_auxiliar",$resultados["arbitro_auxiliar"]).'</span>';
						}
						if(obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$_SESSION["id_liga"],'tipo','2','','','','','') != 0 && $_SESSION["tipo_pago"] != 0){
							echo '<span>'.select_arbitros2($_SESSION["id_liga"],"arbitro_adjunto",$resultados["arbitro_adjunto"]).'</span>';
						}
						if(obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$_SESSION["id_liga"],'tipo','3','','','','','') != 0 && $_SESSION["tipo_pago"] != 0){
							echo '<span>'.select_arbitros2($_SESSION["id_liga"],"arbitro_silla",$resultados["arbitro_silla"]).'</span>';
						}
						if(obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$_SESSION["id_liga"],'tipo','4','','','','','') != 0 && $_SESSION["tipo_pago"] != 0){
							echo '<span>'.select_arbitros2($_SESSION["id_liga"],"arbitro_ayudante",$resultados["arbitro_ayudante"]).'</span>';
						}
					}//fin else if
					else{
					}
					?>	
					</form>
					</div>
					<div class="datos2">
					<form name="<?php echo 'form_inf'.$resultados['id_partido']; ?>" id="<?php echo 'form_inf'.$resultados['id_partido']; ?>" action="#" method="post">
				<?php
				if($resultados['local'] != 0 && $resultados['visitante'] != 0 && $resultados['estado'] == 0 && $auto_completar == 'S' && $todosTemporales ==  false){
				?>
						<span class="titulo">Acceso del Jugador</span>
                        <div class="nom_campo">Email:</div>
                        <div class="input_campo"><input type="text" name="email<?php echo $resultados['id_partido']; ?>" id="email<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_email(event)" ></div>
                        <div class="nom_campo">Pass:</div>
                        <div class="input_campo"><input type="password" name="password<?php echo $resultados['id_partido']; ?>" id="password<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_password(event)" maxlength="15" ></div>
                <?php
				}
				else if($resultados['local'] != 0 && $resultados['visitante'] != 0 && $todosTemporales == false){//aqui el ticket
				?>
					<span class="titulo">Acceso del Jugador</span>
                    <div class="nom_campo">Email:</div>
                    <div class="input_campo"><input type="text" name="email<?php echo $resultados['id_partido']; ?>" id="email<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_email(event)" ></div>
                    <div class="nom_campo">Pass:</div>
                    <div class="input_campo"><input type="password" name="password<?php echo $resultados['id_partido']; ?>" id="password<?php echo $resultados['id_partido']; ?>" class="input_text" onKeyPress="return tecla_password(event)" maxlength="15" ></div>
                            <input type="hidden" name="local" value="<?php echo $resultados['local']; ?>" />
                            <input type="hidden" name="visitante" value="<?php echo $resultados['visitante']; ?>" />
                            <input type="button" class="boton_ticket" onclick="abrir_ticket(<?php echo $resultados['id_partido'];?>)" value="Crear Ticket"  />
                <?php } else{}?>
					</form>
					</div>      
				</div>
			<?php
				}//fin del while
			?>   
            </div><!--fin contenedor datos -->
    </div>
    </div><!-- FIN DE CENTRAL--> 
    <div class="derecho">
    	<?php mostrar_columna_pago($_SESSION['bd'],$_SESSION['id_liga'],$_SESSION['id_division'],$url_der,$posicion_publi_der,'D',$url_vacia);?>
    </div>   
<?php
	pie();
?>    
</div>
<?php
cuerpo_fin();
		//updatear la publicidad izq
		for($i=0; $i<count($id_publi_izq); $i++){
		//for($i=0; $i<$cont; $i++){
			$aux = $contador_izq[$i]+1;
			$update = 'contador='.$aux.',ultima_rep="'.date("Y-m-d H:i:s").'"';
			realiza_updateGeneral('session','publicidad',$update,'id_publicidad',$id_publi_izq[$i],'','','','','','','','','');	
		}//fin for
		//updatear la publicidad der
		for($i=0; $i<count($id_publi_der); $i++){
		//for($i=0; $i<$cont; $i++){
			$aux = $contador_der[$i]+1;
			$update = 'contador='.$aux.',ultima_rep="'.date("Y-m-d H:i:s").'"';
			realiza_updateGeneral('session','publicidad',$update,'id_publicidad',$id_publi_der[$i],'','','','','','','','','');	
		}//fin for
	}//fin de continua
}//FIN DE IF INICIAL
?>