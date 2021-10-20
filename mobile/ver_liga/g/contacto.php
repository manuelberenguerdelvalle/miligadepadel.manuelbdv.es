<?php
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_ligas.php");
include_once ("../../funciones/f_html.php");
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
		$url_publi_vacia = '#';//URL PARA LA PUBLI VACIA, EN ESTE CASO ACCESO AL REGISTRO DE PUBLICIDAD
		//DATOS CONTACTO
		
		$id_premio = obten_consultaUnCampo('session','id_premio','premio','division',$_SESSION["id_division"],'','','','','','','');
		$id_regla = obten_consultaUnCampo('session','id_regla','regla','liga',$_SESSION["id_liga"],'','','','','','','');
		$estilo = obten_consultaUnCampo('session','estilo','liga','id_liga',$_SESSION["id_liga"],'','','','','','','');
cabecera_inicio();
?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Alegreya+SC">
<link rel="stylesheet" type="text/css" href="../../css/ligas<?php echo $estilo.$dimensiones; ?>.css" />
<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/detect_paginas.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="../../paginas/basicas/javascript/contacto.js" type="text/javascript"></script><!-- comparto el de basicas -->
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
        	<div class="texto_titulo">CONTACTO</div>
        	<div class="publi_google"><!--REDES SOCIALES-->
            	&nbsp;
            </div><!-- FIN REDES SOCIALES-->
            <div class="contenedor_datos">
           		<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
				<label class="caja_texto2">Tu email:</label>
                <label class="caja_input2"><input name="contacto" id="contacto"  type="text" class="input_text_liga" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('contacto',0)" ></label>
                <label class="caja_error2">*<span  id="contactoCom"> Email err&oacute;neo</span></label>
				<label class="caja_texto2">Asunto:</label>
                <label class="caja_input2"><input name="asunto" id="asunto" type="text" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('asunto',1)" ></label>
                <label class="caja_error2">*<span id="asuntoCom"> Asunto err&oacute;neo</span></label>
				<label class="caja_texto2">Mensaje:</label>
                <label class="caja_input_area2"><textarea  rows="5" cols="14" name="mensaje" id="mensaje" class="input_text_area" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('mensaje',2,1)"  ></textarea></label>
                <label class="caja_error2">*<span id="mensajeCom"> Mensaje err&oacute;neo</span></label>
                <label id="errorTextarea" class="caja_error2">&nbsp;</label>
                <label id="errorTextarea" class="caja_error2">&nbsp;</label>
                <label id="errorTextarea" class="caja_error2">&nbsp;</label>
                <label id="errorTextarea" class="caja_error2">&nbsp;</label>
                <label id="errorTextarea" class="caja_error2">&nbsp;</label>
                <label class="caja_texto2">&nbsp;</label><label class="caja_input2"><span><a class="botonAtras2" onclick="enviar();" href="#">ENVIAR</a></span></label><label id="errorTextarea" class="caja_error2">&nbsp;</label>
                <input type="hidden" name="id_liga" value="<?php echo $_SESSION['id_liga'];?>">
                <input type="hidden" name="id_division" value="<?php echo $_SESSION['id_division'];?>">
                <input type="hidden" name="modo" value="3">
                <label class="caja_texto2">&nbsp;</label>
                <label class="caja_input2">&nbsp;</label>
                <label class="caja_error2">&nbsp;</label>
			</form>
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