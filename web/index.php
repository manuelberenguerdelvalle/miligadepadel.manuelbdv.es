<?php
include_once ("funciones/f_html.php");
include_once ("funciones/f_desplegables.php");
include_once ("funciones/f_general.php");
include_once ("../class/mysql.php");
session_destroy();
session_start();
$_SESSION['pagina'] = 'index';
cabecera_inicio();
$provincias = array();
$provincias = obten_localizacionDistintasBds(numero_de_BDligas(),'provincia','liga','pais','ESP');
?>
<link rel="stylesheet" type="text/css" href="css/index.css" />
<link rel="stylesheet" type="text/css" href="css/pie.css" />
<link rel="stylesheet" href="../cssslider_files/csss_engine1/style.css">
<link rel="stylesheet" type="text/css" href="../sweetalert-master/lib/sweet-alert.css" />

<!--<script src="javascript/detect_tipo.js" type="text/javascript"></script>-->
<script src="javascript/detect_index.js" type="text/javascript"></script>
<script src="javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="javascript/localizacion_ligas.js" type="text/javascript"></script>
<script src="javascript/index.js" type="text/javascript"></script>
<script src="../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<?php
cabecera_fin();
?>
<div class="principal"> 
    <div class="cabecera">
        <div class="logo">
            <img alt="padel" class="imagenLogoIzq" src="../../images/raqueta padel2.png" />
            <span class="textoLogo"><b>miligadepadel</b></span>
            <img class="imagenLogoDer" src="../../images/raqueta padel.png" alt="padel" />
            <div class="comentarioLogo">Administraci&oacute;n de Ligas de Padel para Clubs Profesionales y Particulares</div>
        </div>
        <div class="opciones">
             <form method="post" name="formulario" id="formulario" action="#" > 
            <div class="tipo">
                <input type="radio" id="radio1" name="tipo" value="0" checked>
                <label for="radio1">Administrador</label>
                <input type="radio" id="radio2" name="tipo" value="1">
                <label for="radio2">Jugador</label>
                <input type="radio" id="radio3" name="tipo" value="2">
                <label for="radio3">Patrocinador</label> 
            </div>
            <div class="acceso">
                <input class="inputAcceso" name="email" id="email" type="text" value="Email" onfocus="if(this.value=='Email')this.value=''" onkeypress="return tecla_email(event)" onblur="limpiaEmail('email',0)" />
                <input class="inputAcceso" name="password" id="password" type="password" value="" onkeypress="return tecla_password(event)" onblur="limpiaPassword('password',1)" />
                <input type="button" id="btn_enviar" value="Entrar" class="boton2" />
                </form>
            </div>
            <div class="errores">&nbsp;
                	<span id="emailCom">Email incorrecto.</span>
            </div>
            <div class="errores">&nbsp;
                    <span id="passwordCom">Contrase&ntilde;a incorrecta.</span>
                    <span id="respuesta" class="respuesta"></span>
            </div>
            <div class="acceso2">
                <div><a class="link" onclick="recuperar_pass();" href="#"><b>&iquest;Has olvidado tu contrase&ntilde;a?</b></a></div>
                <!--<div id="recuperarpass">&nbsp;</div>-->
                <div><a class="link" href="usuario/registro/elegir_plan.php"><b>Crear Nueva Liga</b></a></div>
                <div><a class="link" href="usuario/registro/registrar_patrocinador.php"><b>Crear Patrocinador</b></a></div>
            </div>       
        </div><!-- fin opciones-->
    </div><!-- fin cabecera-->
    <div class="central">
    	<div class="busqueda_logo"><img src="../images/bola_busqueda.png" class="bola_busqueda" />
        </div>
    	<div class="busqueda">
            <select name="provincia" id="provincia" class="input_select_liga" >
                 <option value="">--Provincia--</option>
                 <?php
				 $db2 = new MySQL('unicas');//UNICAS
				for($i=0; $i<count($provincias); $i++){
					$consulta2 = $db2->consulta("SELECT provincia FROM provincias WHERE id = '$provincias[$i]'; ");
					$resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC);
					echo '<option  value="'.$provincias[$i].'">'.$resultados2['provincia'].'</option>';
				 }
				 $db2->cerrar_conexion();// Desconectarse de la base de datos 
				 ?>
            </select>
        </div>	
        <div class="busqueda">
           <select name="ciudad" id="ciudad" class="input_select_liga" >
           </select>
        </div>	
        <div class="busqueda">
           <select name="liga" id="liga" class="input_select_liga" >
           </select>
        </div>	
        <div class="busqueda">
           <select name="division" id="division" class="input_select_liga" >
           </select>
        </div>
        <div id="mostrar_resultados" class="mostrar_resultados"></div>
    </div><!-- fin central -->
    <div class="central_grande">
    	<div class="videotutoriales">
        	<div class="v_titulo">
            	<img src="../images/logo_youtube.png" />
                <span>Tutoriales</span>
            </div>
            <div id="canales" class="v_contenido">
            </div>
        </div><!-- fin videotutoriales -->
        
        <div id="reproductor" class="reproductor">
        	
             <div class='csslider1 autoplay '>
            <input name="cs_anchor1" id='cs_slide1_0' type="radio" class='cs_anchor slide' >
            <input name="cs_anchor1" id='cs_slide1_1' type="radio" class='cs_anchor slide' >
            <input name="cs_anchor1" id='cs_slide1_2' type="radio" class='cs_anchor slide' >
            <input name="cs_anchor1" id='cs_slide1_3' type="radio" class='cs_anchor slide' >
            <input name="cs_anchor1" id='cs_play1' type="radio" class='cs_anchor' checked>
            <input name="cs_anchor1" id='cs_pause1_0' type="radio" class='cs_anchor pause'>
            <input name="cs_anchor1" id='cs_pause1_1' type="radio" class='cs_anchor pause'>
            <input name="cs_anchor1" id='cs_pause1_2' type="radio" class='cs_anchor pause'>
            <input name="cs_anchor1" id='cs_pause1_3' type="radio" class='cs_anchor pause'>
            <ul>
                <li class="cs_skeleton"><img src="../cssslider_files/csss_images1/fotolia_58638107_s.jpg" style="width: 100%;"></li>
                <li class='num0 img slide'>  <a href="http://miligadepadel.manuelbdv.es/web/usuario/registro/elegir_plan.php" target="_blank"><img src='../cssslider_files/csss_images1/fotolia_58638107_s.jpg' alt='CLONPADEL LIGA' title='CLONPADEL LIGA' /> </a> </li>
                <li class='num1 img slide'>  <a href="http://miligadepadel.manuelbdv.es/web/usuario/registro/elegir_plan.php" target="_blank"><img src='../cssslider_files/csss_images1/fotolia_94925196_s.jpg' alt='LIGAS PREMIUM' title='LIGAS PREMIUM' /> </a> </li>
                <li class='num2 img slide'>  <a href="http://miligadepadel.manuelbdv.es/web/usuario/registro/elegir_plan.php" target="_blank"><img src='../cssslider_files/csss_images1/fotolia_70678968_s.jpg' alt='LIGAS GRATIS' title='LIGAS GRATIS' /> </a> </li>
                <li class='num3 img slide'>  <a href="http://miligadepadel.manuelbdv.es/web/usuario/registro/elegir_plan.php" target="_blank"><img src='../cssslider_files/csss_images1/fotolia_72495241_s.jpg' alt='miligadepadel.manuelbdv.es' title='miligadepadel.manuelbdv.es' /> </a> </li>
            </ul><div class="cs_engine"><a href="http://cssslider.com">http://cssslider.com</a> by cssSlider.com v2.1</div>
            <div class='cs_description'>
                <label class='num0'><span class="cs_title"><span class="cs_wrapper">CLONPADEL LIGA</span></span><br/><span class="cs_descr"><span class="cs_wrapper">Imagina la soluci&oacute;n ideal para la gesti&oacute;n perfecta de tus ligas</span></span></label>
                <label class='num1'><span class="cs_title"><span class="cs_wrapper">LIGAS PREMIUM</span></span><br/><span class="cs_descr"><span class="cs_wrapper">Con los servicios de gesti&oacute;n m&aacute;s profesionales de internet</span></span></label>
                <label class='num2'><span class="cs_title"><span class="cs_wrapper">LIGAS GRATIS</span></span><br/><span class="cs_descr"><span class="cs_wrapper">Con los servicios de gesti&oacute;n m&aacute;s completos para particulares</span></span></label>
                <label class='num3'><span class="cs_title"><span class="cs_wrapper">miligadepadel.manuelbdv.es</span></span><br/><span class="cs_descr"><span class="cs_wrapper">RETA, JUEGA, GANA, DISFRUTA</span></span></label>
            </div>
            <div class='cs_play_pause'>
                <label class='cs_play' for='cs_play1'><span><i></i><b></b></span></label>
                <label class='cs_pause num0' for='cs_pause1_0'><span><i></i><b></b></span></label>
                <label class='cs_pause num1' for='cs_pause1_1'><span><i></i><b></b></span></label>
                <label class='cs_pause num2' for='cs_pause1_2'><span><i></i><b></b></span></label>
                <label class='cs_pause num3' for='cs_pause1_3'><span><i></i><b></b></span></label>
                </div>
            <div class='cs_arrowprev'>
                <label class='num0' for='cs_slide1_0'><span><i></i><b></b></span></label>
                <label class='num1' for='cs_slide1_1'><span><i></i><b></b></span></label>
                <label class='num2' for='cs_slide1_2'><span><i></i><b></b></span></label>
                <label class='num3' for='cs_slide1_3'><span><i></i><b></b></span></label>
            </div>
            <div class='cs_arrownext'>
                <label class='num0' for='cs_slide1_0'><span><i></i><b></b></span></label>
                <label class='num1' for='cs_slide1_1'><span><i></i><b></b></span></label>
                <label class='num2' for='cs_slide1_2'><span><i></i><b></b></span></label>
                <label class='num3' for='cs_slide1_3'><span><i></i><b></b></span></label>
            </div>
            <div class='cs_bullets'>
                <label class='num0' for='cs_slide1_0'> <span class='cs_point'></span>
                    <span class='cs_thumb'><img src='../cssslider_files/csss_tooltips1/fotolia_58638107_s.jpg' alt='CLONPADEL LIGA' title='CLONPADEL LIGA' /></span></label>
                <label class='num1' for='cs_slide1_1'> <span class='cs_point'></span>
                    <span class='cs_thumb'><img src='../cssslider_files/csss_tooltips1/fotolia_70678968_s.jpg' alt='LIGAS PREMIUM' title='LIGAS PREMIUM' /></span></label>
                <label class='num2' for='cs_slide1_2'> <span class='cs_point'></span>
                    <span class='cs_thumb'><img src='../cssslider_files/csss_tooltips1/fotolia_94925196_s.jpg' alt='LIGAS GRATIS' title='LIGAS GRATIS' /></span></label>
                <label class='num3' for='cs_slide1_3'> <span class='cs_point'></span>
                    <span class='cs_thumb'><img src='../cssslider_files/csss_tooltips1/fotolia_72495241_s.jpg' alt='miligadepadel.manuelbdv.es' title='miligadepadel.manuelbdv.es' /></span></label>
            </div>
            </div>
 
            <!-- End cssSlider.com -->	
        </div><!-- fin slides fotos -->
        <div class="noticias">
        	<div class="n_titulo">
            	<img src="../images/logo_noticias.png" />
                <span>Noticias</span>
            </div>
            <div class="n_contenido">
                <div class="mostrar_noticia">&bull;Ya puedes disfrutar de los servicios Miligadepadel!</div>
                <div class="mostrar_noticia">&bull;Puedes descargar la app oficial para <a href="https://play.google.com/store/apps/details?id=com.paquete.miligadepadel" target="_new" style="color:#006; text-decoration:underline;">Android</a> e <a href="https://itunes.apple.com/gb/app/miligadepadel/id1218032065?mt=8" target="_new" style="color:#006; text-decoration:underline;">IOS</a>!</div>
                <div class="mostrar_noticia">&bull;Adem&aacute;s Torneos de Padel en <a href="http://www.mitorneodepadel.es" target="_new" style="color:#006; text-decoration:underline;">www.mitorneodepadel.es</a></div>
            </div>
        </div><!-- fin noticias -->
    </div><!-- fin central grande -->
<?php
	$db99 = new MySQL('unicas');//UNICAS
	$consulta99 = $db99->consulta("INSERT INTO `accesos` (`id`, `pagina`, `tipo`, `lugar`, `usuario`) VALUES (NULL, 'L', 'W', 'I', NULL); ");
	pie();
?>   
</div>
<?php
cuerpo_fin();
?>
