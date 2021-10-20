<?php
include_once ("../../funciones/f_html.php");
session_start();
$pagina = $_SESSION['pagina']; 
if ($pagina != 'index' && $pagina != 'elegir_plan' && $pagina != 'registrar_usuario'){
	header ("Location: ../cerrar_sesion.php");
}
else{
	$_SESSION['pagina']  = 'elegir_plan';
}
$mensaje_prof = '-Pagos Online a través de PayPal.\n-Generar Calendario automático con rango de fechas y horas.\n-Elegir Patrocinadores en tu Liga y Divisiones.\n-Gestión automática de Ascensos/Descensos para la siguiente temporada.\n-Insertar Pistas y asignación automática en partidos.\n-Insertar Árbitros y asignación automática en partidos.\n-Gestión de Sanciones a jugadores y equipos.\n-Opción Ida y Vuelta.\n-Prueba gratis durante 3 días.(algunos servicios requieren que el pago esté efectuado). Contacte con nosotros si tiene alguna duda sobre cualquier producto o servicio y la resolveremos en breve.';
$mensaje_clon = '-Todos los servicios del Pack Premium.\n-Servidor virtual exclusivo para la gestión de tus ligas.\n-2 Dominios a elegir.\n-Beneficios íntegros por patrocinadores en ligas y divisiones.\n-Exclusividad para tus ligas y jugadores.\n-Mayor velocidad del sistema.\n-Mejora del rendimiento y proceso de datos.\n-Contacte con nosotros si tiene alguna duda sobre cualquier producto o servicio y la resolveremos en breve.';
cabecera_inicio();
incluir_general(1,0);//jquery,validaciones
?>
<link rel="stylesheet" type="text/css" href="css/elegir_plan.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<link rel="stylesheet" type="text/css" href="../../css/bpopup.css" />
<script src="../../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/elegir_plan.js" type="text/javascript"></script>
<?php
cabecera_fin();
?>
<div class="principal">
	<div id="content_popup">
		<div class="poptitulo"><h2>Solicitar informacion sobre ClonPadel</h2></div>
		<div class="popcentro">
			<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
				<label class="caja_texto">Tu email:</label><label class="caja_input"><input name="contacto"  type="text" class="input_text_liga" ></label><label id="errorContacto" class="caja_error">*</label>
				<label class="caja_texto">Telefono:</label><label class="caja_input"><input name="telefono" type="text" class="input_text_liga" maxlength="9" ></label><label id="errorTelefono" class="caja_error">*</label>
				<label class="caja_texto">Mensaje:</label><label class="caja_input_area"><textarea  rows="11" cols="30" name="mensaje" class="input_text_area" ></textarea></label><label id="errorTextarea" class="caja_error">*</label>
                <input type="hidden" name="modo" value="0">
			</form>
		</div>
		<div class="poppie">
			<span class="button b-close"><span><a class="env" href="#"  onclick="enviar();">ENVIAR</a></span></span>
		</div>
	</div>
	<div class="izquierdo">&nbsp;</div>
    <div class="contenido">
    	<div class="paso">
        	<div class="atras"><a href="http://miligadepadel.manuelbdv.es"><span class="botonAtras">ATRAS</span></a></div>
        	<div class="num_pasos"><img class="pasoImg" src="../../../images/paso1-habilitado.png" alt="ligas de padel" /></div>
            <div class="num_pasos"><img class="pasoImg" src="../../../images/paso2-deshabilitado.png" alt="ligas de padel" /></div>
            <div class="traductor"><div id="google_translate_element"></div></div>
        </div>
        <div class="div1">
            <div class="cuadro">&nbsp;</div>
            <div class="divs">
                <div class="texto1"><br>Pack<BR>Gratuito<br><span>Gesti&oacute;n completa<br>para tu liga<span></div>
                <div class="precio">Gratis</div>
                <form name="gratis" action="registrar_usuario.php" method="post">
                <input name="tipo_pago" type="hidden" value="0" />
                <input class="boton" type="submit" value="Continuar" />
                </form>	
            </div>
            <div class="divs">
                <div class="texto1"><br>Pack<BR>Premium<br><span>Pack gratuito <br />y Servicios++ <a href="#" onclick="swal('Gestiones Premium','<?php echo htmlentities($mensaje_prof);?>');">mas info</a></span></div>
                <div class="precio">
                	<form name="pago" action="registrar_usuario.php" method="post">
                    <input name="tipo_pago" type="hidden" value="1" />
                    <!--<select name="tipo_pago" id="tipo_pago" onchange="setPartidos();" class="select">
                    	<option value="1">30</option>
                        <option value="2">40</option>
                        <option value="3">50</option>
                    </select>-->
                    30&nbsp;&euro; / liga
                </div>               
                <input class="boton" type="submit" value="Continuar" />
                </form>
            </div>
            <div class="divs">
                <div class="texto1"><br>Clon<BR>Padel<br><span>Gesti&oacute;n ilimitada<br>para tu liga <a href="#" onclick="swal('Gesti&oacute;n Premium Exclusiva','<?php echo htmlentities($mensaje_clon);?>');">mas info</a></span></div>
                <div class="precio">Informaci&oacute;n&nbsp;&nbsp;</div>
                <form name="gratis" action="#" method="post">
                <input name="tipo_pago" type="hidden" value="0" />
                <input class="boton"  type="button" value="Contactar" onclick="$('#content_popup').bPopup();" />
                <!--<a class="boton" href="#" onclick="$('#content_popup').bPopup();">Contactar</a>  onclick="alert('hola');"     -->
                </form>	
            </div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Gesti&oacute;n de liga con divisi&oacute;n/es&nbsp;&nbsp;</span></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Auto-completar partidos por los jugadores&nbsp;&nbsp;</span></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Inscripciones</span></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Generar el calendario personalizado&nbsp;&nbsp;</span></div>
             <div class="disponible">&nbsp;</div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Elecci&oacute;n de la privacidad de la liga&nbsp;&nbsp;</span></div>
            <div class="disponible">&nbsp;</div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Gesti&oacute;n de pagos Online con PayPal&nbsp;&nbsp;</span></div>
            <div class="disponible">&nbsp;</div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Elecci&oacute;n de los patrocinadores&nbsp;&nbsp;</span></div>
            <div class="disponible">&nbsp;</div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
            <div class="disponible"><img src="../../../images/tick.png" /></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Equipos&nbsp;&nbsp;</span></div>
            <div class="disponible"><span><b>8 M&aacute;ximo</b></span></div>
            <div class="disponible"><span><b><!--<input type="text" class="num_partidos" id="partidos" readonly="readonly" value="10" />-->20 M&aacute;ximo/divisi&oacute;n</b></span></div>
            <div class="disponible"><span><b>20 M&aacute;ximo/divisi&oacute;n</b></span></div>
        </div>
        <div class="div2">
            <div class="propiedad"><span class="alinear">Divisiones&nbsp;&nbsp;</span></div>
            <div class="disponible"><span><b>1 M&aacute;xima</span></b></div>
            <div class="disponible"><span><b>1 gratis + 5 &euro;/divisi&oacute;n extra</b></span></div>
            <div class="disponible"><span><b>Ligas y Divisiones ILIMITADAS</b></span></div>
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
