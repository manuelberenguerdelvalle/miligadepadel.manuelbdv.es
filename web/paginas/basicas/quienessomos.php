<?php
include_once ("../../funciones/f_html.php");
session_start();
$_SESSION['pagina_secundaria'] = 'quienessomos';
cabecera_inicio();
incluir_general(1,1);//jquery,validaciones
?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin">
<link rel="stylesheet" type="text/css" href="css/paginas_standar.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/contacto.js" type="text/javascript"></script>
<link href="../../css/hover.css" rel="stylesheet" media="all">
<!--
-->
<?php
cabecera_fin();
?>
<div class="principal">
	<div class="izquierdo">&nbsp;</div>
    <div class="hvr-glow" id="contenido">
        <div class="central">
        	<label class="caja_texto">&nbsp;</label><label class="caja_input">&nbsp;</label><label id="errorTextarea" class="caja_error">&nbsp;</label>
            <label class="caja_texto">&nbsp;</label><label class="caja_input"><label class="titulo">&iquest;QUIENES SOMOS?</label></label><label id="errorTextarea" class="caja_error">&nbsp;</label>
            <div class="texto_mostrar">
            	<label id="caja_texto_largo" class="hvr-underline-from-left">&bull;Somos un conjunto de ilusiones combinadas con un gran esfuerzo que ha obtenido como resultado el mejor gestor de ligas de padel de la era de las nuevas tecnologias haciendo que se pueda disfrutar de este gran deporte de manera perfecta con amigos, familia, o participando en campeonatos en clubs oficiales.</label>
            	<!--<label id="caja_texto_largo" class="hvr-underline-from-left">&bull;Somos una empresa que ha nacido para fusionar un gran deporte como es el p&aacute;del con las nuevas tecnolog&iacute;as, resultando as&iacute; una combinaci&oacute;n perfecta para disfrutar de nuestras ligas con amigos y familia, o participando en campeonatos oficiales</label>-->      
            </div>
        </div>
    </div>
    <div class="derecho">&nbsp;</div>
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>
