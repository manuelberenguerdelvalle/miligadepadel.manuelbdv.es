<?php
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/puntuacion.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_puntuacion'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$dni_usuario = $usuario->getValor('dni');
$telefono = $usuario->getValor('telefono');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$id_usuario = $usuario->getValor('id_usuario');
//GUARDAR EN SESSION
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['email'] = $usuario->getValor('email');
$_SESSION['id_liga'] = $id_liga;
//$_SESSION['bd_usuario'] = $usuario->getValor('bd');
$opcion = $_SESSION['opcion'];
if($opcion == 0){//modificacion
	$nombre = $liga->getValor("nombre");
	$tipo_pago = $liga->getValor("tipo_pago");
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
$id_puntuacion = obten_consultaUnCampo('session','id_puntuacion','puntuacion','liga',$id_liga,'usuario',$id_usuario,'division',$id_division,'','','');
//echo '--'.$hay_puntuacion;
if($id_puntuacion == ''){//si no hay puntuacion
	$inscripcion = 0;
	$victoria_amistoso = 0;
	$victoria = 0;
	$dieciseisavos = 0;
	$octavos = 0;
	$cuartos = 0; 
	$semifynal = 0;
	$fynal = 0;
	$primero = 0;
	$segundo = 0;
	$tercero = 0;
	$cuarto = 0;
}
else{
	$puntuacion = new Puntuacion($id_puntuacion,'','','','','','','','','','','','','','','','','');
	$inscripcion = $puntuacion->getValor('inscripcion');
	$victoria_amistoso = $puntuacion->getValor('victoria_amistoso');
	$victoria = $puntuacion->getValor('victoria');
	$dieciseisavos = $puntuacion->getValor('dieciseisavos');
	$octavos = $puntuacion->getValor('octavos');
	$cuartos = $puntuacion->getValor('cuartos');
	$semifynal = $puntuacion->getValor('semifynal');
	$fynal = $puntuacion->getValor('fynal');
	$primero = $puntuacion->getValor('primero');
	$segundo = $puntuacion->getValor('segundo');
	$tercero = $puntuacion->getValor('tercero');
	$cuarto = $puntuacion->getValor('cuarto');
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>www.mitorneodepadel.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_puntuacion.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/modificar_puntuacion.js" type="text/javascript"></script>
</head>
<body>
<?php

if($tipo_pago > 0){
?>
	<br>
	<div class="horizontal"><div class="titulo"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Puntuaciones</b></div></div>
    <div class="caja1">
        <div class="columna1">
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Puntos por inscripci&oacute;n.');" onMouseOut="hiddenDiv()" style="display:table;">Inscripci&oacute;n:</div>
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Puntos por victoria en partido de liga.');" onMouseOut="hiddenDiv()" style='display:table;'>Victoria en partido liga:</div>
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Puntos por ser subcampe&oacute;n del torneo.');" onMouseOut="hiddenDiv()" style='display:table;'>Cuarto clasificado liga:</div>
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Puntos por ser tercero en liga.');" onMouseOut="hiddenDiv()" style='display:table;'>Tercero clasificado liga:</div>
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Puntos por ser subcampe&oacute;n de la liga.');" onMouseOut="hiddenDiv()" style='display:table;'>Subcampe&oacute;n liga:</div>
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Puntos por ser campe&oacute;n de la liga.');" onMouseOut="hiddenDiv()" style='display:table;'>Campe&oacute;n liga:</div>
        </div>
        <div id="flotante"></div>
        <div class="columna2">
            <span><form id="formulario" action="#" method="post" name="formulario"></span>
            <span class="cuadroInputs"><input type="text" name="inscripcion" id="inscripcion" value="<?php echo $inscripcion; ?>" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('inscripcion',0,1)" maxlength="5" onFocus="if(this.value==0)this.value=''"></span>
            <span class="cuadroInputs"><input type="text" name="victoria" id="victoria" value="<?php echo $victoria; ?>" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('victoria',1,1)" maxlength="5" onFocus="if(this.value==0)this.value=''" ></span>
            <span class="cuadroInputs"><input type="text" name="cuarto" id="cuarto" value="<?php echo $cuarto; ?>" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('cuarto',2,1)" maxlength="5" onFocus="if(this.value==0)this.value=''" ></span>
            <span class="cuadroInputs"><input type="text" name="tercero" id="tercero" value="<?php echo $tercero; ?>" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('tercero'34,1)" maxlength="5" onFocus="if(this.value==0)this.value=''" ></span>
            <span class="cuadroInputs"><input type="text" name="segundo" id="segundo" value="<?php echo $segundo; ?>" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('segundo',4,1)" maxlength="5" onFocus="if(this.value==0)this.value=''" ></span>
            <span class="cuadroInputs"><input type="text" name="primero" id="primero" value="<?php echo $primero; ?>" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('primero',5,1)" maxlength="5" onFocus="if(this.value==0)this.value=''" ></span>
            
             
        </div>
        <div class="columna3">
            <div class="cuadroComentario"><span id="inscripcionCom">* Introduzca un n&uacute;mero.</span></div>
            <div class="cuadroComentario"><span id="victoriaCom">* Introduzca un n&uacute;mero.</span></div>
            <div class="cuadroComentario"><span id="cuartoCom">* Introduzca un n&uacute;mero.</span></div>
            <div class="cuadroComentario"><span id="terceroCom">* Introduzca un n&uacute;mero.</span></div>
            <div class="cuadroComentario"><span id="segundoCom">* Introduzca un n&uacute;mero.</span></div>
            <div class="cuadroComentario"><span id="primeroCom">* Introduzca un n&uacute;mero.</span></div>
        </div>
        <div class="horizontal"><input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form>
        </div>
        <div class="horizontal">&nbsp;</div>
        <div id="respuesta" class="horizontal">&nbsp;</div>
    </div><!-- fin caja1-->
<?php
}
?>

</body>
</html>