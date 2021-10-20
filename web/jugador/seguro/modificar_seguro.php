<?php
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
session_start();
if(isset($_SESSION['jugador'])){ 
	$jugador = unserialize($_SESSION['jugador']);
	$id_jugador = $jugador->getValor('id_jugador');
	$_SESSION['id_jugador'] = $id_jugador;
	$db = new MySQL('unicas');//LIGA PADEL
	$consulta = $db->consulta("SELECT * FROM seguro WHERE jugador = '$id_jugador' ; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	$licencia = $resultados['licencia'];
	$categoria = $resultados['categoria'];
	$federacion = $resultados['federacion'];
	$fecha_caducidad = $resultados['fecha_caducidad'];
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_seguro.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/modificar_seguro.js" type="text/javascript"></script>
<script language="javascript">
//reutilizo la de liga que son 5 campos
<?php
if($resultados['id_seguro'] == ''){//INSERCION
	echo "formulario = new formularioLiga('error','error','error','error','null');";
}
else{//MODIFICACION
	echo "formulario = new formularioLiga('null','null','null','null','null');";
}
?>
</script>
<link href="../../../jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../../jquery-ui/jquery-ui.js"></script>
<script language="javascript">
  $(function () {
		$.datepicker.setDefaults($.datepicker.regional["es"]);
		$("#datepicker").datepicker({
		firstDay: 1,
		minDate: "0D",
		changeMonth: true,
        changeYear: true
		});
	});
</script>
</head>
<body>
<div class="horizontal">&nbsp;</div>
<div class="horizontal">&nbsp;</div>
<div class="horizontal">&nbsp;</div>
<div class="horizontal" id="mensaje"></div>
<div class="columna1">
    <div class="cuadroTexto">Nº Licencia:</div>
    <div class="cuadroTexto">Categoría:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduce la comunidad aut&oacute;noma.');" onMouseOut="hiddenDiv()" style="display:table;">Federación:</div>
    <div class="cuadroTexto">Caducidad:</div>
</div>
<div id="flotante"></div>
<!-- COMPROBAR CAMPOS DESHABILITADOS, CUENTA PAYPAL, TELEFONO, DNI....
BLOQUEAR TODOS MENOS CONTRASEÑA EN EL CASO DE TENER AL MENOS UNA LIGA DE PAGO
-->
<div class="columna2">
	<span><form id="form" action="#" method="post" name="formulario"></span>
	<span class="cuadroInputs"><input type="text" name="licencia" id="licencia" class="input_text_liga" value="<?php echo $licencia; ?>" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('licencia',0)" maxlength="30" ></span>
    <span class="cuadroInputs"><input type="text" name="categoria" id="categoria" class="input_text_liga" value="<?php echo $categoria; ?>" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('categoria',1)" maxlength="30" ></span>
    <span class="cuadroInputs"><input type="text" name="federacion" id="federacion" class="input_text_liga" value="<?php echo $federacion; ?>" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('federacion',2)" maxlength="30" ></span>
    <span class="cuadroInputs"><input type="text" id="datepicker" value="<?php echo datepicker_fecha($fecha_caducidad);?>" name="fecha_caducidad" class="input_text_liga" /></span>
    <span><input type="hidden" name="id_seguro" value="<?php echo $resultados['id_seguro'];?>"</span>
</div>
<div class="columna3">
	<div class="cuadroComentario"><span id="licenciaCom">* Introduzca la licencia correctamente.</span></div>
    <div class="cuadroComentario"><span id="categoriaCom">* Introduzca la categoría correctamente.</span></div>
    <div class="cuadroComentario"><span id="federacionCom">* Introduzca la federación correctamente.</span></div>
    <div class="cuadroComentario"><span id="datepickerCom">* Introduce una fecha correctamente.</span></div>
</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form></div>
<div id="respuesta" class="horizontal"></div>
</body>
</html>