<?php
include_once ("../../funciones/f_obten.php");
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
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$id_usuario = $_SESSION['id_usuario'];
$num_inscripciones = obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$id_liga,'division',$id_division,'','','','','');
$division_copiada = obten_consultaUnCampo('session','COUNT(id_nueva_temporada)','nueva_temporada','nueva',$id_liga,'nueva_div',$id_division,'','','','','');//si viene de nueva temporada
if($opcion == 0){//modificacion
	$precio = $division->getValor('precio');
	//if($precio == 0){$precio = 1;}
	$suscripcion = $division->getValor('suscripcion');
	if($suscripcion != '0000-00-00 00:00:00'){$fecha = datepicker_fecha(substr($suscripcion,0,10));}
	else{$fecha = '';}
	//comprobar si hay premio, si no hay todo a 0 y creamos el premio en actualiza_division
	if(obten_consultaUnCampo('session','COUNT(id_premio)','premio','division',$id_division,'','','','','','','') == 0){
		/*$precio = '';
		$fecha = '';*/
		$primero = '';
		$segundo = '';
		$tercero = '';
		$cuarto = '';
		$quinto = '';
		$todos = '';
	}
	else{//ya tiene premio
		$premio = new Premio('',$id_division,'','','','','','');
		$primero = $premio->getValor('primero');
		$segundo = $premio->getValor('segundo');
		$tercero = $premio->getValor('tercero');
		$cuarto = $premio->getValor('cuarto');
		$quinto = $premio->getValor('quinto');
		$todos = $premio->getValor('todos');
	}
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
$pago_pagado = obten_consultaUnCampo('unicas','pagado','pago_web','bd',$_SESSION['bd'],'liga',$id_liga,'division',$id_division,'','','');//obtengo el estado del pago de la division
if($tipo_pago > 0){//Aqu entro si es de pago y no est pagada la liga
	if($division->getValor('num_division') == 1){//si es division 1 busco pago de liga
		if($liga->getValor('pagado') == 'S'){//si la liga est pagada
			$dias_min_suscripcion = 0;
		}
		else{//si la liga no est pagada
			//CALCULAR LOS 3 DIAS DESPUES DEL PAGO
			$dias_min_suscripcion = obtenRestoDiasSuscripcion($id_division);// Aqu se calculan los das mnimos para poder comenzar la suscripcin a la divisin, controlando que est fuera de los 3 das de pago.
		}
	}//fin if division 1
	else{//si es otras divisiones cuento la fecha minima del pago
		if( $pago_pagado == 'S' ){//si la division est pagada
			$dias_min_suscripcion = 0;
		}
		else{
			$dias_min_suscripcion = obtenRestoDiasSuscripcion($id_division);// Aqu se calculan los das mnimos para poder comenzar la suscripcin a la divisin, controlando que est fuera de los 3 das de pago.
		}
	}//fin else otras divsisiones
}//fin if tipo pago
else{
	$dias_min_suscripcion = 0;
}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_division.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<link href="../../../jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_division.js" type="text/javascript"></script>
<script src="../../../jquery-ui/jquery-ui.js"></script>
<script language="javascript">
<?php
if($_SESSION['id_division_reload'] != $id_division){
	$_SESSION['id_division_reload']  = $id_division;
	echo 'window.location.reload();';
}
?>
  $(function () {
		$.datepicker.setDefaults($.datepicker.regional["es"]);
		$("#datepicker<?php echo $id_division;?>").datepicker({
		firstDay: 1,
		minDate: "<?php echo $dias_min_suscripcion;?>D",
		changeMonth: true,
        changeYear: true
		});
	});
</script>
</head>
<body>
<div class="horizontal">&nbsp;</div>
<div class="horizontal"><div><b>Importante:</b>Revisa que toda la configuraci&oacute;n de la Liga y Divisi&oacute;n es correcta antes de establecer una fecha de inicio para inscripciones.</div></div>
<div class="horizontal">&nbsp;</div>
<div class="columna1">
<?php if($tipo_pago > 0){
    echo '<div class="cuadroTexto" onMouseOver="showdiv(event,'."'Este es el precio que cada jugador deber abonar para participar en esta divisi&oacute;n/liga.'".');" onMouseOut="hiddenDiv()" style="display:table;">Inscripci&oacute;n(&euro;):</div>';
	} ?>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Esta fecha es la que indica cuando pueden comenzar las inscripciones de los jugadores para esta divisi&oacute;n/liga. Si no quieres activarla ahora d&eacute;jala en blanco.');" onMouseOut="hiddenDiv()" style='display:table;'>Fecha fin:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el primer premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 1&deg;:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el segundo premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 2&deg;:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el tercer premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 3&deg;:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el cuarto premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 4&deg;:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el quinto premio o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio 5&deg;:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduzca el premio para todos los participantes o d&eacute;jelo en blanco si no tiene premio.');" onMouseOut="hiddenDiv()" style='display:table;'>Premio todos:</div>
 
</div>
<div id="flotante"></div>
<div class="columna2">
	<span><form id="formulario" action="actualiza_division.php" method="post" name="formulario"></span>
<?php 
if($num_inscripciones > 0 || $pago_pagado == 'N'){//inscritos
	if($division_copiada > 0){//viene de divisiones de nueva temporada
    	echo '<span class="cuadroInputs"><input type="number" name="precio" id="precio" value="'.$precio.'" class="input_text_disabled" min="0" max="1000" disabled></span>';
		echo '<span class="cuadroInputs"><input type="text" id="datepicker'.$id_division.'" name="suscripcion" value="'.$fecha.'" class="input_text" onblur="fecha('.'"datepicker"'.',1)" /></span>';
	}
	else{//divisiones normales
		if($tipo_pago > 0){
			echo '<span class="cuadroInputs"><input type="number" name="precio" id="precio" value="'.$precio.'" class="input_text_disabled" min="0" max="1000" disabled></span>';
		}
		echo '<span class="cuadroInputs"><input type="text" id="datepicker" name="suscripcion" value="'.$fecha.'" class="input_text_disabled" disabled /></span>';
	}
}//fin if
else{//no inscritos
	if($tipo_pago > 0){
    	echo '<span class="cuadroInputs"><input type="number" name="precio" id="precio" value="'.$precio.'" class="input_text" min="0" max="1000"></span>';
	}
	if($fecha == '//'){$fecha = '';}
	echo '<span class="cuadroInputs"><input type="text" id="datepicker'.$id_division.'" name="suscripcion" value="'.$fecha.'" class="input_text" onblur="fecha('.'"datepicker"'.',1)" /></span>';
}
	?>    
    <span class="cuadroInputs"><input type="text" name="primero" id="primero" value="<?php echo $primero; ?>" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('primero',2)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="segundo" id="segundo" value="<?php echo $segundo; ?>" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('segundo',3)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="tercero" id="tercero" value="<?php echo $tercero; ?>" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('tercero',4)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="cuarto" id="cuarto" value="<?php echo $cuarto; ?>" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('cuarto',5)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="quinto" id="quinto" value="<?php echo $quinto; ?>" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('quinto',6)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="todos" id="todos" value="<?php echo $todos; ?>" class="input_text_liga" onKeyPress="return letrasYnum(event)" onBlur="limpiaLetrasYnum('todos',7)" maxlength="100" ></span>
     
</div>
<div class="columna3">
<?php if($tipo_pago > 0){
	echo '<div class="cuadroComentario"><span id="precioCom">* Introduzca el precio de suscripcion.</span></div>';
	} ?>
    <div class="cuadroComentario"><span id="fechaCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="primeroCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="segundoCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="terceroCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="cuartoCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="quintoCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="todosCom">&nbsp;</span></div>
</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form>
<?php
if($tipo_pago > 0 && $division->getValor('num_division') != 1){//ligas de pago y numero de division diferente a 1
	if(obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','liga',$id_liga,'division',$id_division,'usuario',$id_usuario,'pagado','S','') == 0){
		echo '<input type="button" value="Eliminar" onClick="eliminar('.$id_liga.','.$id_division.');" class="botonEli" />';
	}
}
?>
</div>
<div id="respuesta" class="horizontal"></div>
<div class="horizontal">&nbsp;</div>
</body>
</html>
