<?php
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_ligas.php");
include_once ("../../funciones/f_html.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/premio.php");
include_once ("../../../class/regla.php");
session_start();
$_SESSION['pagina'] = 'ver_liga';
if( (!empty($_SESSION['id_liga']) && !empty($_SESSION['id_division']) && !empty($_SESSION['bd']) && $_SESSION['tipo_pago'] > 0) || !empty($_POST['a']) || !empty($_GET['a']) ){//verifico datos session o post
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
		//DATOS INFO
		$id_premio = obten_consultaUnCampo('session','id_premio','premio','division',$_SESSION["id_division"],'','','','','','','');
		$id_regla = obten_consultaUnCampo('session','id_regla','regla','liga',$_SESSION["id_liga"],'','','','','','','');
		if($id_premio != ''){
			$premio = new Premio('',$_SESSION["id_division"],'','','','','','');
		}
		if($id_regla != ''){
			$regla = new Regla('',$_SESSION["id_liga"],'','');
		}
		$estilo = obten_consultaUnCampo('session','estilo','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
cabecera_inicio();
?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Alegreya+SC">
<link rel="stylesheet" type="text/css" href="../../css/bpopup.css" />
<link rel="stylesheet" type="text/css" href="../../css/ligas<?php echo $estilo; ?>.css" />
<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/detect_paginas.js" type="text/javascript"></script>
<script src="../../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script src="../javascript/ligas_pago.js" type="text/javascript"></script>
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
        		<div class="nombre"><?php echo  '<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$_SESSION["nombre"].' - Divisi&oacute;n&nbsp;</div>'.generar_input_divs($_SESSION['id_liga'],$_SESSION['id_division'],'info.php');?></div>
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
        	<div class="texto_titulo">INFO DE LA LIGA</div>
        	<div class="publi_google"><!--REDES SOCIALES-->
            	<div class="red_social"><div class="fb-share-button" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/p/noticia.php" data-layout="button"></div></div>
                <div class="red_social"><a href="https://twitter.com/share" class="twitter-share-button" data-text="Ligas de Padel">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div>
                <div class="red_social"><div class="g-plus" data-action="share" data-annotation="none" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/p/noticia.php"></div></div>
            </div><!-- FIN REDES SOCIALES-->
            <div class="contenedor_datos">
            <?php
				if($id_premio != ''){
					echo '<div class="medio_contenedor">
								<div class="titulo_info">PREMIOS<br>&nbsp;</div>';
					if($premio->getValor('primero') != ''){
						echo '<div class="color1">1&deg;</div><div class="texto_info">&nbsp;'.$premio->getValor("primero").'</div>';
					}
					if($premio->getValor('segundo') != ''){
						echo '<div class="color2">2&deg;</div><div class="texto_info">&nbsp;'.$premio->getValor("segundo").'</div>';
					}
					if($premio->getValor('tercero') != ''){
						echo '<div class="color3">3&deg;</div><div class="texto_info">&nbsp;'.$premio->getValor("tercero").'</div>';
					}
					if($premio->getValor('cuarto') != ''){
						echo '<div class="color4">4&deg;</div><div class="texto_info">&nbsp;'.$premio->getValor("cuarto").'</div>';
					}
					if($premio->getValor('quinto') != ''){
						echo '<div class="color5">5&deg;</div><div class="texto_info">&nbsp;'.$premio->getValor("quinto").'</div>';
					}
					if($premio->getValor('todos') != ''){
						echo '<div class="todos">TODOS</div><div class="texto_info">&nbsp;'.$premio->getValor("todos").'</div>';
					}
					echo '<div class="texto_info">&nbsp;</div>';
					echo	 '</div>';
				}
				if($id_regla != ''){
					echo '<div class="medio_contenedor">
								<div class="titulo_info">REGLAS<br>&nbsp;</div>
								<div class="texto_regla">'.nl2br($regla->getValor("texto")).'</div>
								<div class="texto_regla">&nbsp;</div>
							</div>';
				}
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
		}
		//updatear la publicidad der
		for($i=0; $i<count($id_publi_der); $i++){
		//for($i=0; $i<$cont; $i++){
			$aux = $contador_der[$i]+1;
			$update = 'contador='.$aux.',ultima_rep="'.date("Y-m-d H:i:s").'"';
			realiza_updateGeneral('session','publicidad',$update,'id_publicidad',$id_publi_der[$i],'','','','','','','','','');	
		}
	}//fin de continua
}//FIN DE IF INICIAL
?>