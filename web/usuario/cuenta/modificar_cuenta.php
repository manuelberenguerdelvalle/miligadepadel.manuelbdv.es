<?php
include_once ("../../funciones/f_cuenta.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_cuenta'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$opcion = $_SESSION['opcion'];
if($opcion == 0){//modificacion
	$id_usuario = $usuario->getValor("id_usuario");
	$email = $usuario->getValor("email");
	$telefono = $usuario->getValor("telefono");
	$password = $usuario->getValor("password");
	$nombre = $usuario->getValor("nombre");
	$apellidos = $usuario->getValor("apellidos");
	$dni = $usuario->getValor("dni");
	if($dni == 0){$dni = '';}
	$cuenta_paypal = $usuario->getValor("cuenta_paypal");
	$recibir_pago = $usuario->getValor("recibir_pago");
	$direccion = $usuario->getValor("direccion");
	$cp = $usuario->getValor("cp");
	$ciudad = $usuario->getValor("ciudad");
	$provincia = $usuario->getValor("provincia");
	$pais = $usuario->getValor("pais");
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
$hay_liga_pago = hay_ligaPago($id_usuario);
$ligas_activas = 0;
$db3 = new MySQL('session');//LIGA
$consulta = $db3->consulta("SELECT id_liga FROM `liga` WHERE `usuario` = '$id_usuario' AND `bloqueo` = 'N' ; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//LIGAS DE PAGO bloqueo de ligas y divisiones pasada fecha limite
    //no puede eliminar si hay partidos pendientes
	if(obten_numMaxPartidos($resultados['id_liga']) > 0){//obtengo num partidos
		if(obten_numPartidosActivosLiga($resultados['id_liga']) > 0){$ligas_activas++;}//hay partidos activos
	}
	else{//no hay partidos busco inscripciones
		if(obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$resultados['id_liga'],'pagado','S','','','','','') > 0){
			$ligas_activas++;
		}
	}
}//fin while
//echo 'ligas activas = '.$ligas_activas;

//YA SE HAN HABILITADO CAMBIOS EN EL USUARIO YA QUE GUARDAMOS HISTORIAL DE CADA MODIFICACION DE USUARIO
//SOLO BLOQUEAMOS NOMBRE, APE, DNI
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="../../css/bpopup.css" />
<link rel="stylesheet" type="text/css" href="css/modificar_cuenta.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/modificar_cuenta.js" type="text/javascript"></script>
<script src="../../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script>
function mostrar_popup(){
	var posicion = document.getElementById('recibir_pago').options.selectedIndex; //posicion contrario
	var valor = document.getElementById('recibir_pago').options[posicion].value; // valor contrario
	if(valor != 'M'){
		$('#content_popup').bPopup();
	}
	//alert(posicion+'-'+valor);
}
</script>
<style>
.titulo_fases{
	width:99% !important;
	/*border:1px black solid;*/
	margin-top:2%;
	margin-bottom:1%;
	color:#006;
	float:left;
}
.imagen_fases{
	width:80% !important;
	margin-left:10%;
	margin-bottom:1%;
	border-radius: 3px;
	border:1px #CCC solid;
	float:left;
}
.imagen_fases:hover {
	border:1px #06C solid;
}
</style>
</head>
<body>
<!-- POPUP -->
	<div id="content_popup">
    	<div class="poptitulo"><h2>Instrucciones para Recibir Pagos PayPal</h2></div>
        <div class="popcentro">
        	<div class="titulo_fases"><b>&bull;&nbsp;&nbsp;&nbsp;1-</b> Crear cuenta Business Premier</div>
            <img class="imagen_fases" src="../../../images/fase1-1.png">
            <div class="titulo_fases">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O cambiar tu cuenta existente a cuenta Business</div>
            <img class="imagen_fases" src="../../../images/fase1-2.png">
            <div class="titulo_fases"><b>&bull;&nbsp;&nbsp;&nbsp;2-</b> Ir a Perfil -> Configuraci&oacute;n de la cuenta -> Opciones de venta -> Preferencias de sitio web (cambiar) -> Activar Retorno autom&aacute;tico y Url de retorno: http://miligadepadel.manuelbdv.es</div>
            <img class="imagen_fases" src="../../../images/fase2-1.png"><img class="imagen_fases" src="../../../images/fase2-2.png">
            <div class="titulo_fases"><b>&bull;&nbsp;&nbsp;&nbsp;3-</b> Validar cuenta bancaria donde recibir&aacute;s el dinero. Comprobar que la cuenta est&aacute; verificada, y en caso contrario buscar en la bandeja de entrada de tu cuenta de em@il el correo de verificaci&oacute;n.</div>
             <div class="titulo_fases"><b>&bull;&nbsp;&nbsp;&nbsp;4-</b> Solicitar el cambio para recibir pagos indicando si deseas modo Online, o Online y Presencial rellenando el siguiente formulario de <b><a href="../../paginas/basicas/contacto.php" target="_blank">Contacto</a></b> (Por su seguridad y la de sus jugadores es necesario activarlo de forma manual para evitar cualquier tipo de estafa o acci&oacute;n fraudulenta).</div>
        </div>
        <div class="poppie">
        	<span class="button b-close"><span>ENTENDIDO</span></span>
        </div>
	</div>
    <!-- FIN POPUP -->
<div class="columna1">
    <div class="cuadroTexto">Nombre:</div>
    <div class="cuadroTexto">Apellidos:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio. Introduce tu email habitual, lo necesitar&aacute;s para el acceso y gesti&oacute;n de tu liga.');" onMouseOut="hiddenDiv()" style="display:table;">Email:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio. Es muy importante indicar un m&oacute;vil v&aacute;lido.');" onMouseOut="hiddenDiv()" style='display:table;'>Tel&eacute;fono:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Introduce la contrasea actual.');" onMouseOut="hiddenDiv()" style="display:table;">Contrase&ntilde;a Actual:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Introduce la nueva contrasea');" onMouseOut="hiddenDiv()" style="display:table;">Contrase&ntilde;a Nueva:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Repite la nueva contrasea');" onMouseOut="hiddenDiv()" style="display:table;">Repita la contrase&ntilde;a Nueva:</div>
	<div class="cuadroTexto">NIF:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Seleccione una de las 2 opciones de como recibir el pago de las inscripciones de jugadores.');" onMouseOut="hiddenDiv()" style="display:table;">Recibir pagos:</div>
	<div class="cuadroTexto" onMouseOver="showdiv(event,'Introduce tu cuenta de paypal si deseas recibir pagos seguros online a travs de paypal.');" onMouseOut="hiddenDiv()" style="display:table;">Cuenta PayPal:</div>
    <div class="cuadroTexto">Direcci&oacute;n:</div>
    <div class="cuadroTexto">C&oacute;digo postal:</div>
    <div class="cuadroTexto">Pa&iacute;s:</div>
    <div class="cuadroTexto">Provincia:</div>
    <div class="cuadroTexto">Ciudad:</div>
</div>
<div id="flotante"></div>
<!-- COMPROBAR CAMPOS DESHABILITADOS, CUENTA PAYPAL, TELEFONO, DNI....
BLOQUEAR TODOS MENOS CONTRASEA EN EL CASO DE TENER AL MENOS UNA LIGA DE PAGO -->
<div class="columna2">
	<span><form id="formulario" action="actualiza_cuenta.php" method="post" name="formulario"></span>
	<span class="cuadroInputs"><input type="text" value="<?php echo ucfirst($nombre); ?>" class="input_text_liga_disabled" maxlength="20"  disabled></span>
    <span class="cuadroInputs"><input type="text" value="<?php echo ucfirst($apellidos); ?>" class="input_text_liga_disabled" maxlength="30"  disabled></span>
	<span class="cuadroInputs"><input type="text" name="email" id="email" class="input_text_liga" value="<?php echo $email; ?>" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('email',2)" ></span>
    <span class="cuadroInputs"><input type="text" name="telefono" id="telefono" class="input_text_liga" value="<?php echo $telefono; ?>" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('telefono',3,1)" ></span>
    <span class="cuadroInputs"><input type="password" name="antpassword" id="antpassword" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('antpassword',4)" maxlength="15" ></span>
    <span class="cuadroInputs"><input type="password" name="password" id="password" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('password',5)" maxlength="15" ></span>
    <span class="cuadroInputs"><input type="password" name="repassword" id="repassword" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="compara('password','repassword',6)" maxlength="15" ></span>
<?php
if($dni == ''){//si no tiene dni habilitado
	echo '<span class="cuadroInputs"><input type="text" name="dni" id="dni" class="input_text_liga" onkeypress="return dni(event)" onblur="limpiadni('."'dni'".',7)"  maxlength="9"></span>';
}
else{//si tiene dni deshabilito
	echo '<span class="cuadroInputs"><input type="text" value="'.$dni.'" class="input_text_liga_disabled" disabled></span>';
}
?>
	<span class="cuadroInputs">
    	<select name="recibir_pago" id="recibir_pago" class="inputText" onChange="mostrar_popup();">
        	<?php
			if($recibir_pago == 'M'){echo '<option selected value="M">Presencial</option>';}
			else{echo '<option value="M">Presencial</option>';}
			/*if($recibir_pago == 'O'){echo '<option selected value="O">Online (PayPal)</option>';}
			else{echo '<option value="O">Online (PayPal)</option>';}*/
			if($recibir_pago == 'A'){echo '<option selected value="A">Online y Presencial</option>';}
			else{echo '<option value="A">Online y Presencial</option>';}
			?>
        </select>
    </span>
	<span class="cuadroInputs"><input type="text" name="cuenta_paypal" id="cuenta_paypal" class="input_text_liga" value="<?php echo $cuenta_paypal;?>" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('cuenta_paypal',8)" maxlength="50" ></span>
    <!-- return direccion(event) -->
	<span class="cuadroInputs"><input type="text" name="direccion" id="direccion" class="input_text_liga" value="<?php echo ucfirst($direccion); ?>" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('direccion',9,0)" maxlength="50" ></span>
    
    
    <span class="cuadroInputs"><input type="text" name="cp" id="cp" class="input_text_liga" value="<?php echo $cp; ?>" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('cp',10,0)" maxlength="8" ></span>
    <span class="cuadroInputs"><select name="pais" id="pais" class="inputText" onChange="lista('pais',11)">
        <option value="">--Elige--</option>
      	<option selected value="<?php echo $pais; ?>"><?php if($opcion == 0){ echo obtenLocalizacion(1,$pais); } ?></option>
     </select></span>
     <span class="cuadroInputs"><select name="provincia" id="provincia" class="inputText" onChange="lista('provincia',12)">
     	<option value="<?php echo $provincia; ?>"><?php if($opcion == 0){ echo obtenLocalizacion(2,$provincia); } ?></option>
     	</select>
     </span>
     <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="inputText" onChange="lista('ciudad',13)">
     	<option value="<?php echo $ciudad; ?>"><?php if($opcion == 0){ echo obtenLocalizacion(3,$ciudad); } ?></option>
     </select>

     </span>
</div>
<div class="columna3">
	<div class="cuadroComentario"><span id="nombreCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="apellidosCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="emailCom">* Introduzca el email correctamente.</span></div>
    <div class="cuadroComentario"><span id="telefonoCom">* Introduzca el telefono correctamente.</span></div>
    <div class="cuadroComentario"><span id="antpasswordCom">* Introduzca una contrase&ntilde;a correcta.</span></div>
    <div class="cuadroComentario"><span id="passwordCom">* Introduzca una contrase&ntilde;a correcta.</span></div>
    <div class="cuadroComentario"><span id="repasswordCom">* Las nuevas contrase&ntilde;as son diferentes.</span></div>
    <div class="cuadroComentario"><span id="dniCom">* Introduzca su Nif correctamente.</span></div>
    <div class="cuadroComentario"><span id="cuenta_paypalCom">* Introduzca su cuenta paypal correctamente.</span></div>
    <div class="cuadroComentario"><span id="direccionCom">* Introduzca su direccin correctamente.</span></div>
    <div class="cuadroComentario"><span id="cpCom">* Introduzca su cdigo postal correctamente.</span></div>
    <div class="cuadroComentario"><span id="paisCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="provinciaCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="ciudadCom">&nbsp;</span></div>
</div>
<div class="horizontal">
	<input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form>
    <?php
	if($ligas_activas == 0){
     	echo '<input type="button" value="Eliminar" onClick="eliminar('.$id_usuario.')" class="botonEli" />';
	}
	?>
</div>
<div id="respuesta" class="horizontal"></div>
</body>
</html>