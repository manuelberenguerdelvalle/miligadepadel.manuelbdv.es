<?php
include_once ("../../funciones/f_html.php");
session_start();
$_SESSION['pagina_secundaria'] = 'contacto';
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
	<!--<div id="content_popup">
		<div class="poptitulo"><h2>Solicitar informacion sobre ClonPadel</h2></div>
		<div class="popcentro">
			<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
				<label class="caja_texto">Tu email:</label><label class="caja_input"><input name="contacto"  type="text" class="input_text_liga" ></label><label id="errorContacto" class="caja_error">*</label>
				<label class="caja_texto">Telefono:</label><label class="caja_input"><input name="telefono" type="text" class="input_text_liga" maxlength="9" ></label><label id="errorTelefono" class="caja_error">*</label>
				<label class="caja_texto">Mensaje:</label><label class="caja_input_area"><textarea  rows="11" cols="35" name="mensaje" class="input_text_area" ></textarea></label><label id="errorTextarea" class="caja_error">*</label>
                <input type="hidden" name="modo" value="0">
			</form>
		</div>
		<div class="poppie">
			<span class="button b-close"><span><a class="env" href="#"  onclick="enviar();">ENVIAR</a></span></span>
		</div>
	</div>-->
	<!--<div class="izquierdo">&nbsp;</div>-->
    <div class="hvr-glow" id="contenido">
        <div class="central">
        	<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
            	<label class="caja_texto">&nbsp;</label><label class="caja_input">&nbsp;</label><label id="errorTextarea" class="caja_error">&nbsp;</label>
            	<label class="caja_texto">&nbsp;</label><label class="caja_input"><label class="titulo">CONTACTO</label></label><label id="errorTextarea" class="caja_error">&nbsp;</label>
				<label class="caja_texto">Tu Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label class="caja_input"><input name="contacto" id="contacto"  type="text" class="input_text_liga" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('contacto',0)" ></label>
                <label class="caja_error">*<span  id="contactoCom"> Email err&oacute;neo</span></label>
				<label class="caja_texto">Asunto:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label class="caja_input"><input name="asunto" id="asunto" type="text" class="input_text_liga" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('asunto',1,1)" ></label>
                <label class="caja_error">*<span id="asuntoCom"> Asunto err&oacute;neo</span></label>
				<label class="caja_texto">Mensaje:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label class="caja_input_area"><textarea  rows="4" cols="15" name="mensaje" id="mensaje" class="input_text_area" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('mensaje',2,1)"  ></textarea></label>
                <label class="caja_error">*<span id="mensajeCom"> Mensaje err&oacute;neo</span></label>
                <label id="errorTextarea" class="caja_error">&nbsp;</label>
                <label id="errorTextarea" class="caja_error">&nbsp;</label>
                <label id="errorTextarea" class="caja_error">&nbsp;</label>
                <label id="errorTextarea" class="caja_error">&nbsp;</label>
                <label id="errorTextarea" class="caja_error">&nbsp;</label>
                <label class="caja_texto">&nbsp;</label><label class="caja_input"><span><a class="botonAtras" onclick="enviar();" href="#">ENVIAR</a></span></label><label id="errorTextarea" class="caja_error">&nbsp;</label>
                <input type="hidden" name="modo" value="2">
                <label class="caja_texto">&nbsp;</label><label class="caja_input">&nbsp;</label><label id="errorTextarea" class="caja_error">&nbsp;</label>
			</form>
        </div>
    </div>
    <!--<div class="derecho">&nbsp;</div>-->
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>
