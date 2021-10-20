<?php
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_ligas.php");
include_once ("../../funciones/f_html.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
session_start();
$_SESSION['pagina'] = 'ver_liga';
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
		$url_publi_vacia = '#';//URL PARA LA PUBLI VACIA, EN ESTE CASO ACCESO AL REGISTRO DE PUBLICIDAD
		//DATOS CLASIFICACION
		$id_equipo = array();
		$ganados = array();
		$sets_aux = array();
		$sets_favor = array();
		$sets_contra = array();
		$cont = 0;
		$db = new MySQL('session');//LIGA PADEL
		$consulta = $db->consulta("SELECT id_equipo FROM equipo WHERE liga = '".$_SESSION["id_liga"]."' AND division = '".$_SESSION["id_division"]."' ; ");
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			$id_equipo[$cont] = $resultados['id_equipo'];
			$ganados[$cont] = obten_consultaUnCampo('session','COUNT(id_partido)','partido','ganador',$resultados['id_equipo'],'','','','','','','');//ganados
			$sets_aux = obten_sumaSets($resultados['id_equipo'],'local');//solicita a favor local, visitantes en contra
			$sets_favor[$cont] = $sets_aux[0];//local a favor
			$sets_contra[$cont] = $sets_aux[1];//visitante en contra
			$sets_aux = obten_sumaSets($resultados['id_equipo'],'visitante');//solicita a favor local, visitantes en contra
			$sets_favor[$cont] += $sets_aux[0];//local a favor
			$sets_contra[$cont] += $sets_aux[1];//visitante en contra
			/*echo 'equipo :'.$id_equipo[$cont];
			echo 'ganados '.$ganados[$cont];
			echo 'suma: '.$sets[$cont].'<br>';	*/
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
		$id_premio = obten_consultaUnCampo('session','id_premio','premio','division',$_SESSION["id_division"],'','','','','','','');
		$id_regla = obten_consultaUnCampo('session','id_regla','regla','liga',$_SESSION["id_liga"],'','','','','','','');
		$estilo = obten_consultaUnCampo('session','estilo','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
cabecera_inicio();
?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Alegreya+SC">
<link rel="stylesheet" type="text/css" href="../../css/ligas<?php echo $estilo; ?>.css" />
<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/detect_paginas.js" type="text/javascript"></script>
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
        		<div class="nombre">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  'Liga: '.$_SESSION["nombre"].' - Divisi&oacute;n '.$_SESSION['num_division'];?></div>
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
        	<div class="texto_titulo">CLASIFICACION</div>
        	<div class="publi_google"><!--REDES SOCIALES-->
            	<div class="red_social"><div class="fb-share-button" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/g/clasificacion.php" data-layout="button"></div></div>
                <div class="red_social"><a href="https://twitter.com/share" class="twitter-share-button" data-text="Ligas de Padel">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div>
                <div class="red_social"><div class="g-plus" data-action="share" data-annotation="none" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/g/clasificacion.php"></div></div>
            </div><!-- FIN REDES SOCIALES-->
            <div class="linea_datos">
					<div class="posicion_datos">POS</div>
					<div class="movimiento">&nbsp;</div>
					<div class="foto">&nbsp;</div>
					<div class="equipo_datos">EQUIPO</div>
					<div class="p_jugados">PJ</div>
					<div class="p_ganados">PG</div>
					<div class="p_perdidos">PP</div>
					<div class="sets_favor">SF</div>
					<div class="sets_contra">SC</div>
					<div class="sets_diferencia">DIF</div>
					<div class="puntos">PTOS</div>
			</div>
            <div class="contenedor_datos">
            <?php
			//onMouseOver="showdiv(event,'."'Obligatorio'".');" onMouseOut="hiddenDiv()" style="display:table;"
			/*
			echo '<div class="linea_datos">';
					echo '<div class="posicion_datos">POS</div>';
					echo '<div class="movimiento">&nbsp;</div>';
					echo '<div class="foto">&nbsp;</div>';
					echo '<div class="equipo_datos">EQUIPO</div>';
					echo '<div class="p_jugados">PJ</div>';
					echo '<div class="p_ganados">PG</div>';
					echo '<div class="p_perdidos">PP</div>';
					echo '<div class="sets_favor">SF</div>';
					echo '<div class="sets_contra">SC</div>';
					echo '<div class="sets_diferencia">DIF</div>';
					echo '<div class="puntos">PTOS</div>';
			echo '</div>';
			*/
			for($i=0; $i<count($id_equipo); $i++){
				//REVISAR OTROS ESTADOS DE PARTIDOS, SANCIONADOS Y EXPULSADOS Y AÑADIRLOS A FINALIZADOS
				$finalizados = obten_datosPartidos($id_equipo[$i],1);
				//class="hvr-glow"
				echo '<div class="linea_clasificacion" >';
					if($i == 0){
						echo '<div class="posicion"><img class="imagen_pos" src="../../../images/copa.png"></div>';
					}
					else{
						echo '<div class="posicion"><div class="numero_pos">'.($i+1).'</div></div>';
					}
					//si no esta la foto se muestra blanco
					echo '<div class="movimiento">&nbsp;</div>';
					$id_jugador1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
					$origen = '../../../../../fotos_jugador/'.$id_jugador1.'.jpg';
					if(file_exists($origen)){
						$foto_temp = '../../../fotos_jugador/'.$id_jugador1.'.jpg';
						copy($origen, $foto_temp);
						echo '<div class="foto">';
							echo '<a href="#image-'.$id_jugador1.'"><img class="imagen_foto" src="'.$foto_temp.'"></a>
										<div class="lb-overlay" id="image-'.$id_jugador1.'">
											<img style="width:20%;" src="'.$foto_temp.'" alt="jugador1" />
											<a href="#page" class="lb-close">X</a>
										</div>';	
							//echo '<img class="imagen_foto" src="'.$foto.'">';
						echo '</div>';
					}
					else{
						echo '<div class="foto">&nbsp;</div>';
					}
					if($id_jugador1 == 0){//jugador temporal
						$inscripcion = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
						echo '<div class="equipo">&nbsp;'.obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion,'','','','','','','').'</div>';
					}
					else{echo '<div class="equipo">&nbsp;'.obtenNombreJugadorMostrar($id_equipo[$i],'jugador1').'</div>';}//jugador registrado
					$id_jugador2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
					$origen = '../../../../../fotos_jugador/'.$id_jugador2.'.jpg';
					if(file_exists($origen)){
						$foto_temp = '../../../fotos_jugador/'.$id_jugador2.'.jpg';
						copy($origen, $foto_temp);
						echo '<div class="foto">';
							echo '<a href="#image-'.$id_jugador2.'"><img class="imagen_foto" src="'.$foto_temp.'"></a>
										<div class="lb-overlay" id="image-'.$id_jugador2.'">
											<img style="width:20%;" src="'.$foto_temp.'" alt="jugador2" />
											<a href="#page" class="lb-close">X</a>
										</div>';	
							//echo '<img class="imagen_foto" src="'.$foto.'">';
						echo '</div>';
					}
					else{
						echo '<div class="foto">&nbsp;</div>';
					}
					if($id_jugador2 == 0){//jugador temporal
						$inscripcion = obten_consultaUnCampo('session','seguro_jug2','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
						echo '<div class="equipo">&nbsp;'.obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion,'','','','','','','').'</div>';
					}
					else{echo '<div class="equipo">&nbsp;'.obtenNombreJugadorMostrar($id_equipo[$i],'jugador2').'</div>';}//jugador registrado
					echo '<div class="p_jugados">'.$finalizados.'</div>';
					echo '<div class="p_ganados">'.$ganados[$i].'</div>';
					echo '<div class="p_perdidos">'.abs($finalizados-$ganados[$i]).'</div>';
					echo '<div class="sets_favor">'.$sets_favor[$i].'</div>';
					echo '<div class="sets_contra">'.$sets_contra[$i].'</div>';
					echo '<div class="sets_diferencia">'.($sets_favor[$i]-$sets_contra[$i]).'</div>';
					echo '<div class="puntos"><b>'.($ganados[$i]*3).'</b></div>';
				/*echo 'equipo :'.$id_equipo[$i];
				echo ;
				echo ;
				echo 'ganados '.$ganados[$i];
				echo 'suma: '.$sets[$i].'<br>';	*/
				echo '</div>';
			}//fin for
			?>     
            </div><!--fin contenedor datos -->
    </div>
    </div><!-- FIN DE CENTRAL--> 
    <div class="derecho">
    	<?php mostrar_columna($id_publi_der,$url_der,$url_publi_vacia); ?>
    </div>   
<?php
	pie();
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