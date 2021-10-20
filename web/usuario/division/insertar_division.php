<?php
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/premio.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_division'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$idayvuelta = $liga->getValor('idayvuelta');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
if($tipo_pago > 0 && $opcion == 1){//SI ES LIGA DE PAGO SI PUEDO INSERTAR DIVISION
	//SE GUARDA EN SESSION
	$_SESSION['id_liga'] = $id_liga;
	$_SESSION['tipo_pago'] = $tipo_pago;
	$_SESSION['idayvuelta'] = $idayvuelta;
	//CALCULAR LOS 3 DIAS DESPUES DEL PAGO
	//$dias_min_suscripcion = 4 ;// Aqu se calculan los das mnimos para poder comenzar la suscripcin a la divisin, controlando que est fuera de los 3 das de pago.
	//$fecha_min_suscripcion = fecha_suma(date('Y-m-d'),'','',$dias_min_suscripcion,'','','');
	//$fecha_min_suscripcion = substr($fecha_min_suscripcion,0,10);
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_division.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link href="../../../jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/insertar_division.js" type="text/javascript"></script>
<script src="../../../jquery-ui/jquery-ui.js"></script>
<script language="javascript">
  $(function () {
		$.datepicker.setDefaults($.datepicker.regional["es"]);
		$("#datepicker").datepicker({
		firstDay: 1,
		minDate: "4D",
		changeMonth: true,
        changeYear: true
		});
	});
</script>
</head>
<body>
<div class="horizontal">&nbsp;</div>
<div class="horizontal"><div><b>Importante:</b> Crear una nueva divisi&oacute;n tiene un coste de <b>5 euros</b>. Introduzca el precio que recibir&aacute; por inscripci&oacute;n en esta Divisi&oacute;n.</div></div>
<div class="horizontal">&nbsp;</div>
<div class="columna1">
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Este es el precio que cada jugador deber abonar para participar en esta divisi&oacute;n/liga.');" onMouseOut="hiddenDiv()" style="display:table;">Precio de Inscripci&oacute;n (&euro;):</div>
    <!--<div class="cuadroTexto" onMouseOver="showdiv(event,'Esta fecha es la que indica el comienzo de las inscripciones de los jugadores para esta divisin/liga. Si no quieres activarla ahora djala en blanco.');" onMouseOut="hiddenDiv()" style='display:table;'>Fecha de fin de Inscripciones:</div>-->
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el primer premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 1&deg; clasificado:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el segundo premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 2&deg; clasificado:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el tercer premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 3&deg; clasificado:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el cuarto premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 4&deg; clasificado:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el quinto premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 5&deg; clasificado:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el premio para todos los participantes o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio todos los participantes:</div>
 
</div>
<div id="flotante"></div>
<div class="columna2">
	<span><form id="formulario" action="crear_division.php" method="post" name="formulario"></span>
    <span class="cuadroInputs"><input type="number" name="precio" id="precio" value="0" class="input_text" min="0" max="1000"></span>
	<!--<span class="cuadroInputs"><input type="text" name="suscripcion" id="datepicker" class="input_text" onBlur="fecha('datepicker',1)" /></span>-->
    <span class="cuadroInputs"><input type="text" name="primero" id="primero" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('primero',2)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="segundo" id="segundo" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('segundo',3)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="tercero" id="tercero" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('tercero',4)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="cuarto" id="cuarto" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('cuarto',5)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="quinto" id="quinto" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('quinto',6)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="todos" id="todos" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('todos',7)" maxlength="100" ></span>
     
</div>
<div class="columna3">
    <div class="cuadroComentario"><span id="precioCom">* Introduzca el precio de suscripci&oacute;n.</span></div>
    <!--<div class="cuadroComentario"><span id="fechaCom">&nbsp;</span></div>-->
    <div class="cuadroComentario"><span id="primeroCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="segundoCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="terceroCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="cuartoCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="quintoCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="todosCom">&nbsp;</span></div>
</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Insertar" class="boton" /></form></div>
<div id="respuesta" class="horizontal"></div>
</body>
</html>
<style>.cuadroError{color: #030;font-family:Arial;	font-size:80%;width:30%;border:1px solid #34495e;	background-color:#c5fbc6;padding:5px;border-radius:5px;}</style>
<?php
}
else{//SI ES LIGA GRATIS NO PUEDE INSERTAR
	echo '<style>.cuadroError{color:#34495e; font-family:Arial; font-size:80%; width:50%; border:1px solid #34495e;	background-color:#c5fbc6; padding:1%; border-radius:5px; margin:0 auto;margin-top:1%;}</style>';
	echo '<div class="cuadroError">'.utf8_encode('Esta liga tiene el plan gratuito y no es posible a&ntilde;adir m&aacute;s divisiones').'</div>';
}
?>