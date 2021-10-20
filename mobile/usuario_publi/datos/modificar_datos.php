<?php
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario_publi.php");
session_start();
if(isset($_SESSION['usuario_publi'])){ 
	$usuario_publi = unserialize($_SESSION['usuario_publi']);
	$cif = $usuario_publi->getValor("cif");
	if($cif == 0){$cif = '';}
	$nombre = $usuario_publi->getValor("nombre");
	$empresa = $usuario_publi->getValor("empresa");
	$password = $usuario_publi->getValor("password");
	$direccion = $usuario_publi->getValor("direccion");
	$ciudad = $usuario_publi->getValor("ciudad");
	$provincia = $usuario_publi->getValor("provincia");
	$pais = $usuario_publi->getValor("pais");
	$telefono = $usuario_publi->getValor("telefono");
	if($telefono == 0){$telefono = '';}
	$email = $usuario_publi->getValor("email");
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
$patro_activos = 0;
if(obten_consultaUnCampo('unicas','COUNT(id_publicidad_gratis)','publicidad_gratis','usuario_publi',$usuario_publi->getValor("id_usuario_publi"),'pagado','S','estado','1','','','') > 0){$patro_activos++;}//si hay congelados no puede eliminar
else{//vemos  fechas de finalizados
	$db = new MySQL('unicas');//LIGA PADEL
	$c = $db->consulta("SELECT COUNT(id_publicidad_gratis) AS s FROM publicidad_gratis WHERE usuario_publi='".$usuario_publi->getValor("id_usuario_publi")."' AND fecha_fin <= '".date('Y-m-d H:i:s')."' AND estado = '0'; ");
	$r = $c->fetch_array(MYSQLI_ASSOC);
	if($r['s'] > o){
		$patro_activos++;
	}
}
//echo 'patrocinios activos: '.$patro_activos;
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_datos.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script>
//se utiliza 14 de 15, el 15 = 'null'
formulario = new formularioGeneralQuince('null','null','null','null','null','null','null','null','null','null','null','null','null','null','null');
</script>
<script src="javascript/modificar_datos.js" type="text/javascript"></script>

</head>
<body>
<div class="columna1">
    <div class="cuadroTexto">Nombre:</div>
    <div class="cuadroTexto">Empresa:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio. Introduce tu email habitual, lo necesitar&aacute;s para el acceso y gesti&oacute;n del men&uacute;.');" onMouseOut="hiddenDiv()" style="display:table;">Email:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional. Es importante indicar un m&oacute;vil v&aacute;lido.');" onMouseOut="hiddenDiv()" style='display:table;'>Tel&eacute;fono:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Introduce la contrasea actual.');" onMouseOut="hiddenDiv()" style="display:table;">Pass Actual:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Introduce la nueva contrasea');" onMouseOut="hiddenDiv()" style="display:table;">Pass Nueva:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Repite la nueva contrasea');" onMouseOut="hiddenDiv()" style="display:table;">Repetir:</div>
	<div class="cuadroTexto">Cif:</div>
    <div class="cuadroTexto">Direcci&oacute;n:</div>
    <div class="cuadroTexto">Pa&iacute;s:</div>
    <div class="cuadroTexto">Provincia:</div>
    <div class="cuadroTexto">Ciudad:</div>
</div>
<div id="flotante"></div>
<!-- COMPROBAR CAMPOS DESHABILITADOS, CUENTA PAYPAL, TELEFONO, cif....
BLOQUEAR TODOS MENOS CONTRASEA EN EL CASO DE TENER AL MENOS UNA LIGA DE PAGO
-->
<div class="columna2">
	<span><form id="formulario" action="actualiza_cuenta.php" method="post" name="formulario"></span>
	<span class="cuadroInputs"><input type="text" value="<?php echo ucfirst($nombre); ?>" class="input_text_liga_disabled" disabled></span>
    <span class="cuadroInputs"><input type="text" value="<?php echo ucfirst($empresa); ?>" class="input_text_liga_disabled" disabled></span>
<?php 
if($hay_liga_pago > 0){//si tiene ligas de pago deshabilito  
?>
    <span class="cuadroInputs"><input type="text" name="email" id="email" class="input_text_liga_disabled" value="<?php echo $email; ?>" disabled ></span>
    <span class="cuadroInputs"><input type="text" name="telefono" id="telefono" class="input_text_liga_disabled" value="<?php echo $telefono; ?>" disabled ></span>
<?php 
}//fin if
else{//normal 
?>
	<span class="cuadroInputs"><input type="text" name="email" id="email" class="input_text_liga" value="<?php echo $email; ?>" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('email',2)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="telefono" id="telefono" class="input_text_liga" value="<?php echo $telefono; ?>" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('telefono',3,1)" maxlength="11" ></span>
<?php 
}//fin else 
?>
    <span class="cuadroInputs"><input type="password" name="antpassword" id="antpassword" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('antpassword',4)" maxlength="15" ></span>
    <span class="cuadroInputs"><input type="password" name="password" id="password" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('password',5)" maxlength="15" ></span>
    <span class="cuadroInputs"><input type="password" name="repassword" id="repassword" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="compara('password','repassword',6)" maxlength="15" ></span>
<?php
if($cif == ''){//si no tiene cif habilitado
	echo '<span class="cuadroInputs"><input type="text" name="cif" id="cif" class="input_text_liga" maxlength="15"></span>';
}
else{//si tiene cif deshabilito
	echo '<span class="cuadroInputs"><input type="text" value="'.$cif.'" class="input_text_liga_disabled" disabled></span>';
} 
?> 
    
	<span class="cuadroInputs"><input type="text" name="direccion" id="direccion" class="input_text_liga" value="<?php echo ucfirst($direccion); ?>" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('direccion',9,0)" maxlength="50" ></span>
    <span class="cuadroInputs">
    	<select name="pais" id="pais" class="inputText" onChange="lista('pais',2)">
        <option value="">--Elige--</option>
      	<option selected value="<?php echo $pais; ?>"><?php echo obtenLocalizacion(1,$pais); ?></option>
     </select>
     </span>
     <span class="cuadroInputs"><select name="provincia" id="provincia" class="inputText" onChange="lista('provincia',3)">
     	<option value="<?php echo $provincia; ?>"><?php echo obtenLocalizacion(2,$provincia); ?></option>
     	</select>
     </span>
     <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="inputText" onChange="lista('ciudad',4)">
     	<option value="<?php echo $ciudad; ?>"><?php echo obtenLocalizacion(3,$ciudad); ?></option>
     </select>
     </span>
</div>
<div class="columna3">
	<div class="cuadroComentario"><span id="nombreCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="empresaCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="emailCom">*</span></div>
    <div class="cuadroComentario"><span id="telefonoCom">*</span></div>
    <div class="cuadroComentario"><span id="antpasswordCom">*</span></div>
    <div class="cuadroComentario"><span id="passwordCom">*</span></div>
    <div class="cuadroComentario"><span id="repasswordCom">*</span></div>
    <div class="cuadroComentario"><span id="cifCom">*</span></div>
    <div class="cuadroComentario"><span id="cuenta_paypalCom">*</span></div>
    <div class="cuadroComentario"><span id="direccionCom">*</span></div>
    <div class="cuadroComentario"><span id="cpCom">*</span></div>
    <div class="cuadroComentario"><span id="paisCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="provinciaCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="ciudadCom">&nbsp;</span></div>
</div>
<div class="horizontal">
	<input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form>
    <br>
    &nbsp;
    <br>
    <?php
	if($patro_activos == 0){
		echo '<input type="button" onClick="eliminar('.$usuario_publi->getValor("id_usuario_publi").')" value="Eliminar" class="botonEli" />';
	}
	?>
</div>
<div id="respuesta" class="horizontal"></div>
</body>
</html>