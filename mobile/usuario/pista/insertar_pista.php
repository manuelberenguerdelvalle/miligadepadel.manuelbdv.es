<?php
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_pista'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
if($opcion != 1){//modificacion
	header ("Location: ../cerrar_sesion.php");
}
if($opcion == 1 && $tipo_pago > 0){//si es de pago
//SE GUARDA EN SESSION
$_SESSION['id_liga'] = $id_liga;
$_SESSION['id_division'] = $id_division;
$_SESSION['tipo_pago'] = $tipo_pago;
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_pista.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/insertar_pista.js" type="text/javascript"></script>
</head>
<body>
<div class="horizontal">&nbsp;</div>
<div class="horizontal"><div class="titulo">Nueva Pista:</div></div>
<div class="horizontal">&nbsp;</div>
<div class="columna1">
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Introduce el nombre de la pista.');" onMouseOut="hiddenDiv()" style="display:table;">Nombre:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Introduce la dirección.');" onMouseOut="hiddenDiv()" style='display:table;'>Dirección:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Introduce el código postal.');" onMouseOut="hiddenDiv()" style='display:table;'>C.P:</div>
</div>
<div id="flotante"></div>
<div class="columna2">
	<span><form id="formulario" action="#" method="post" name="formulario"></span>
    <span class="cuadroInputs"><input type="text" name="nombre" id="nombre" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('nombre',0)" maxlength="28" ></span>
    <span class="cuadroInputs"><input type="text" name="direccion" id="direccion" class="input_text_liga" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('direccion',1)" ></span>
    <span class="cuadroInputs"><input type="text" name="cp" id="cp" class="input_text_liga"  onkeypress="return numeros(event)" onBlur="limpiaNumeros('cp',5,0)" maxlength="5"></span>
</div>
<div class="columna3">
    <div class="cuadroComentario"><span id="nombreCom">*</span></div>
    <div class="cuadroComentario"><span id="direccionCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="cpCom">&nbsp;</span></div>
</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Insertar" class="boton" /></form></div>
<div id="respuesta" class="horizontal"></div>
</body>
</html>
<?php
}
?>