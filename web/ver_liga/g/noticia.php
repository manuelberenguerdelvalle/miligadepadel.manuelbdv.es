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
		//solo recibo bd en formato numero 1,2...seguido del id_division y ya obtengo el id_liga,tipo pago y creo las session
		/*
		EJEMPLO A ENVIAR POR POST O GET
		$a = genera_id_url(100,$cadena,13);
		if($_SESSION['bd'] == 'admin_liga'){$cadena = 0;}
		else{$cadena = numero_de_BDligas();}
		$cadena .= $_SESSION['id_division'].'F';
		9999999999990456F
		038F
		?a=NE0xTVlZM3dNNDIxMDM4RnczMjYwTTJNTTIydzUwMnd3MFlZTTIxMTR3NXdZMTVNdzA1NE0wdzQyTTExNnc0MzRNNXdZTTRNMjMzd3cxMjExMTU2WVk0dzFNNTAyMnc2MVkzMzExMg==
4M1MYY3wM421038Fw3260M2MM22w502ww0YYM2114w5wY15Mw054M0w42M116w434M5wYM4M233ww1211156YY4w1M5022w61Y33112
		

		*/
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
		$_SESSION['nombre'] = obten_consultaUnCampo('session','nombre','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
		//echo $_SESSION['bd'].'-';
		//echo $_SESSION['id_division'].'-';
		//echo $_SESSION['id_liga'].'-';
		//echo $_SESSION['tipo_pago'];
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
		$id_premio = obten_consultaUnCampo('session','id_premio','premio','division',$_SESSION["id_division"],'','','','','','','');
		$id_regla = obten_consultaUnCampo('session','id_regla','regla','liga',$_SESSION["id_liga"],'','','','','','','');
		$estilo = obten_consultaUnCampo('session','estilo','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
/*
$_SESSION["id_liga"];
$_SESSION["nombre"];
$_SESSION["id_division"];
$_SESSION["num_division"];
$_SESSION["usuario"] = $resultados3["usuario"];
$_SESSION["tipo_pago"] = $resultados3["tipo_pago"];
$_SESSION["pass"] = $resultados3["pass"];
$_SESSION["genero"] = $resultados3["genero"];
$_SESSION["vista"] = $resultados3["vista"];
$_SESSION["precio"] = $resultados3["precio"];*/

cabecera_inicio();
?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Alegreya+SC">
<link rel="stylesheet" type="text/css" href="../../css/ligas<?php echo $estilo; ?>.css" />
<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
<link rel="stylesheet" type="text/css" href="../../css/hover.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/detect_paginas.js" type="text/javascript"></script>
<!--
<link rel="stylesheet" type="text/css" href="../../css/bpopup.css" />
<script src="../../javascript/jquery.bpopup.min.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
function abrir_popup(){
	alert('hola');
	$('#content_popup').bPopup();
}
</script>-->
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
        	<div class="texto_titulo">NOTICIAS</div>
        	<div class="publi_google"><!--REDES SOCIALES-->
            	<div class="red_social"><div class="fb-share-button" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/g/noticia.php" data-layout="button"></div></div>
                <div class="red_social"><a href="https://twitter.com/share" class="twitter-share-button" data-text="Ligas de Padel">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div>
                <div class="red_social"><div class="g-plus" data-action="share" data-annotation="none" data-href="http://miligadepadel.manuelbdv.es/web/ver_liga/g/noticia.php"></div></div>
            </div><!-- FIN REDES SOCIALES-->
            <div class="contenedor_datos">
            <?php
			//comprobar el numero de noticias por el administrador, si hay mas de 10, dividir en 3 partes, añadir boton con link y limit
			$num_not = obten_consultaUnCampo('session','COUNT(id_noticia)','noticia','liga',$_SESSION["id_liga"],'division',$_SESSION["id_division"],'','','','','');
			//echo 'tot'.$num_not.'<br>';
			if($num_not  <= 20){$div = 2;}
			else if($num_not  > 20 && $num_not <= 30){$div = 4;}
			else if($num_not  > 30 && $num_not <= 50){$div = 7;}
			else if($num_not > 50){$div = 10;}
			else{}
			$resto = $num_not % $div;
			if($_SESSION['limite'] == ''){
				if(obten_numNoticiasFotos($_SESSION["id_liga"],$_SESSION["id_division"]) > 10 || $num_not > 15){//noticias con fotos
					$_SESSION['limite'] = (ceil($num_not / $div)) + $resto;//así obtengo por lo alto
					$limite = 'LIMIT '.$_SESSION['limite'];
				}//fin noticias con fotos
				else{$limite = '';}
			}//fin de limite vacio
			else{//limite tiene datos
				if(isset($_POST['limite'])){//si existe sumo
					$_SESSION['limite'] += (ceil($num_not / $div)) + $resto;
					$limite = 'LIMIT '.$_SESSION['limite'];
				}
			}//fin else existe limite
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM noticia WHERE liga = '".$_SESSION["id_liga"]."' AND division = '".$_SESSION["id_division"]."' ORDER BY fecha DESC ".$limite."; ");
			while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				//echo $resultados['id_noticia'].'-'.$resultados['fecha'].'<br>';
				$fecha = vuelta_fecha(substr($resultados['fecha'],0,10));
				$fecha .= ' '.substr($resultados['fecha'],11,5);
				if($resultados['resumen'] == 'Administrador'){//ADMINISTRADOR
					echo '<div class="cab_noticia">&nbsp;<br>Administrador '.$fecha.'<span class="en_blanco">padel ligas torneos crear liga jugar participar organizar web deporte</span></div>';
					if($resultados['imagenes'] == ''){echo '<div class="body_noticia_fin">'.$resultados['descripcion'].'.<br>&nbsp;</div>';}
					else{//con imagenes
						$ruta = '../../../fotos_noticias/'.$_SESSION['bd'].$_SESSION['id_liga'].$_SESSION['id_division'].'/';
						$fotos = array();
						$fotos = explode(';',$resultados['imagenes']);
						$tam = count($fotos);//el tamaño real tiene uno mas
						echo '<div class="body_noticia">'.$resultados['descripcion'].'.</div>';
						echo '<div class="pie_noticia"><br>';
						for($z=0; $z<$tam-1; $z++){
							if($z == 0){//si es el primer
								$prev = $fotos[$tam-2];
								$next = $fotos[$z+1];
							}
							else if($z == $tam-2){//si es el ultimo
								$prev = $fotos[$z-1];
								$next = $fotos[0];
							}
							else{
								$prev = $fotos[$z-1];
								$next = $fotos[$z+1];
							}
							echo '<a href="#image-'.$fotos[$z].'"><img class="imagen_mini" src="'.$ruta.$fotos[$z].'" /></a>
										<div class="lb-overlay" id="image-'.$fotos[$z].'">
											<img src="'.$ruta.$fotos[$z].'" alt="image'.$fotos[$z].'" />
											<div>';
							if($tam-1 > 1){
							echo				'<a href="#image-'.$prev.'" class="lb-prev">&nbsp;</a>
												 <a href="#image-'.$next.'" class="lb-next">&nbsp;</a>';
							}
							echo			'</div>
											<a href="#page" class="lb-close">X</a>
										</div>';	
						}
						echo '<div class="horizontal">&nbsp;</div></div>';
					}
				}
				else{//SISTEMA
					echo '<div class="cab_noticia">&nbsp;<br>Sistema '.$fecha.'<span class="en_blanco">padel ligas torneos crear liga jugar participar organizar web deporte</span></div>';
					echo '<div class="body_noticia_fin">'.$resultados['resumen'].'<br>'.$resultados['descripcion'].'<br>&nbsp;</div>';
				}//fin else sistema
			}//fin while
			if($_SESSION['limite'] != '' && $_SESSION['limite'] < $num_not){
					//echo 'entro-'.$_SESSION['limite'].'-'.$div;
					echo '<div class="horizontal">
                				<form name="limite" method="post" action="noticia.php">
                				<input type="hidden" name="limite" value="suma" />
                				<input type="submit" value="--Mostrar Siguientes Noticias -->" class="boton_noticias" />
                				</form>
                			</div>';
			}
			?>
                <!--<div class="cab_noticia">Administrador<span class="en_blanco">padel ligas torneos crear liga jugar participar organizar web deporte</span></div>
                <div class="body_noticia">blablablablablalblab.</div>
                <div class="pie_noticia">
                <a href="#image-1"><img class="imagen_mini" src="<?php //echo $ruta.'1.jpg';?>" /></a>
                <div class="lb-overlay" id="image-1">
                	<img src="<?php //echo $ruta.'1.jpg'; ?>" alt="image01" />
                	<div>
                        <a href="#image-4" class="lb-prev">&nbsp;</a>
                        <a href="#image-2" class="lb-next">&nbsp;</a>
                    </div>
                    <a href="#page" class="lb-close">X</a>
                </div>
                <a href="#image-2"><img class="imagen_mini" src="<?php //echo $ruta.'2.jpg';?>" /></a>
                <div class="lb-overlay" id="image-2">
                	<img src="<?php //echo $ruta.'2.jpg'; ?>" alt="image02" />
                	<div>
                        <a href="#image-1" class="lb-prev">&nbsp;</a>
                        <a href="#image-3" class="lb-next">&nbsp;</a>
                    </div>
                    <a href="#page" class="lb-close">X</a>
                </div>
                <a href="#image-3"><img class="imagen_mini" src="<?php //echo $ruta.'3.jpg';?>" /></a>
                <div class="lb-overlay" id="image-3">
                	<img src="<?php //echo $ruta.'3.jpg'; ?>" alt="image03" />
                	<div>
                        <a href="#image-2" class="lb-prev">&nbsp;</a>
                        <a href="#image-4" class="lb-next">&nbsp;</a>
                    </div>
                    <a href="#page" class="lb-close">X</a>
                </div>
                <a href="#image-4"><img class="imagen_mini" src="<?php //echo $ruta.'4.jpg';?>" /></a>
                <div class="lb-overlay" id="image-4">
                	<img src="<?php //echo $ruta.'4.jpg'; ?>" alt="image04" />
                	<div>
                        <a href="#image-3" class="lb-prev">&nbsp;</a>
                        <a href="#image-1" class="lb-next">&nbsp;</a>
                    </div>
                    <a href="#page" class="lb-close">X</a>
                </div>
                </div>--><!-- fin div pie -->
                
                
                
                
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