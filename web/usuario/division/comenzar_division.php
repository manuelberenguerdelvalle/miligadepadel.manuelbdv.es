<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
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
//fechas para los calendarios. Si se ha accedido es porque hay al menos  2 equipos suscritos y si hay equipos es porque ha superado el periodo de suscripcion
$numEquipos = obten_consultaUnCampo('session','COUNT(id_equipo)','equipo','liga',$id_liga,'division',$id_division,'pagado','S','','','');
$numPartidos = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$id_division,'','','','','','','');
if($numEquipos > 2 && $numPartidos == 0 && $opcion == 2 && $division->getValor('comienzo') == 'N'){
	//SE GUARDA EN SESSION
	$_SESSION['id_liga'] = $id_liga;
	$_SESSION['tipo_pago'] = $tipo_pago;
	$_SESSION['idayvuelta'] = $idayvuelta;
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/comenzar_division.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link href="../../../jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/comenzar_division.js" type="text/javascript"></script>
<script src="../../../jquery-ui/jquery-ui.js"></script>
<script language="javascript">
  $(function () {
		$.datepicker.setDefaults($.datepicker.regional["es"]);
		$("#datepicker").datepicker({
		firstDay: 1,
		minDate: "1D",
		changeMonth: true,
        changeYear: true
		});
	});
</script>
</head>
<body>
<?php
//comprobamos que el pago de ida y vuelta est hecho y si no mostramos advertencia para que pague o lo elimine
	//if($idayvuelta == 'N' || ($idayvuelta == 'S' &&  obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','liga',$id_liga,'tipo','I','pagado','S','','','') == 1) ){//filtra idayvuelta
		if(obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$id_liga,'division',$id_division,'pagado','N','','','') == 0){//filtra inscripciones sin pagar
?>
<div class="horizontal">&nbsp;</div>
<div class="horizontal"><div><b>CREAR CALENDARIO PARA LA DIVISION <?php echo $division->getValor('num_division');?></b></div></div>
<div class="horizontal"><div><b>Importante:</b> Una vez creado el calendario no podr&aacute;s inscribir ning&uacute;n equipo m&aacute;s. Podr&aacute;s modificar los partidos en el men&uacute; Partidos -> Ver/Modificar.</div></div>
<div class="horizontal">&nbsp;</div>
<div class="horizontal"><div><input type="radio" name="calendario" value="automatico" checked onClick="comprueba(this)"><b>Autom&aacute;tico</b> (Crea tu calendario de manera r&aacute;pida)</div></div>
<div id="flotante"></div>
<?php if($tipo_pago > 0){
		echo '<div class="horizontal"><div><input type="radio" name="calendario" value="personalizado" onClick="comprueba(this)"><b>Personalizado</b> (Podr&aacute;s configurar d&iacute;as y horarios para generar el calendario)</div></div>';
}	?>
<div class="columna1">
	<div class="cuadroTexto" onMouseOver="showdiv(event,'Indica la fecha que quieres que comiencen los partidos.');" onMouseOut="hiddenDiv()" style='display:table;'>Fecha de inicio:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Indica la duraci&oacute;n m&aacute;xima o estimada por partido para el c&aacute;lculo del horario.');" onMouseOut="hiddenDiv()" style='display:table;'>Duraci&oacute;n estimada por partido:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Indica los sets por partido. Siempre se jugar al mejor de 3 o 5 sets.');" onMouseOut="hiddenDiv()" style='display:table;'>Sets por partido:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Personaliza el horario para tu liga, si no deseas insertar horas d&eacute;jalas en blanco.');" onMouseOut="hiddenDiv()" style='display:table;'>Horario:</div>
</div>
<div class="columna2">
	<span><form id="formulario" action="#" method="post" name="formulario"></span>
    <span class="cuadroInputs"><input type="text" name="inicio" id="datepicker" class="input_select_liga" /></span>
	<input type="hidden" id="modo" name="modo" value="0">
    <span class="cuadroInputs"><?php duracion_partido('');?></span>
    <span class="cuadroInputs"><?php sets('');?></span>
    <span class="cuadroInputs"><input type="checkbox" name="lunes" id="lunes" value="lunes" onClick="comprueba_linea(this)">Lunes</span>
    <span class="cuadroInputs"><input type="checkbox" name="martes" id="martes" value="martes" onClick="comprueba_linea(this)">Martes</span>
    <span class="cuadroInputs"><input type="checkbox" name="miercoles" id="miercoles" value="miercoles" onClick="comprueba_linea(this)">Mi&eacute;rcoles</span>
    <span class="cuadroInputs"><input type="checkbox" name="jueves" id="jueves" value="jueves" onClick="comprueba_linea(this)">Jueves</span>
    <span class="cuadroInputs"><input type="checkbox" name="viernes" id="viernes" value="viernes" onClick="comprueba_linea(this)">Viernes</span>
    <span class="cuadroInputs"><input type="checkbox" name="sabado" id="sabado" value="sabado" onClick="comprueba_linea(this)">S&aacute;bado</span>
    <span class="cuadroInputs"><input type="checkbox" name="domingo" id="domingo" value="domingo" onClick="comprueba_linea(this)">Domingo</form></span>
</div>
<div class="columna3">
	<span><form id="formulario_horas1" action="#" method="post" name="formulario_horas1"></span>
	<span class="cuadroInputs"><?php select_horas('desdelunes1','desdelunes1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdemartes1','desdemartes1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdemiercoles1','desdemiercoles1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdejueves1','desdejueves1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdeviernes1','desdeviernes1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdesabado1','desdesabado1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdedomingo1','desdedomingo1','M'); ?></span>
    <span></form></span>
</div>
<div class="columna4">
	<span><form id="formulario_horas2" action="#" method="post" name="formulario_horas2"></span>
	<span class="cuadroInputs"><?php select_horas('hastalunes1','hastalunes1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastamartes1','hastamartes1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastamiercoles1','hastamiercoles1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastajueves1','hastajueves1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastaviernes1','hastaviernes1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastasabado1','hastasabado1','M'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastadomingo1','hastadomingo1','M'); ?></span>
    <span></form></span>
</div>
<div class="columna5">
	<span><form id="formulario_horas3" action="#" method="post" name="formulario_horas3"></span>
	<span class="cuadroInputs"><?php select_horas('desdelunes2','desdelunes2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdemartes2','desdemartes2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdemiercoles2','desdemiercoles2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdejueves2','desdejueves2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdeviernes2','desdeviernes2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdesabado2','desdesabado2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('desdedomingo2','desdedomingo2','T'); ?></span>
    <span></form></span>
</div>
<div class="columna6">
	<span><form id="formulario_horas4" action="#" method="post" name="formulario_horas4"></span>
	<span class="cuadroInputs"><?php select_horas('hastalunes2','hastalunes2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastamartes2','hastamartes2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastamiercoles2','hastamiercoles2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastajueves2','hastajueves2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastaviernes2','hastaviernes2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastasabado2','hastasabado2','T'); ?></span>
    <span class="cuadroInputs"><?php select_horas('hastadomingo2','hastadomingo2','T'); ?></span>
    <span></form></span>
</div>
<div class="columna7">
	<span class="cuadroInputs"><input type="checkbox" name="arbitros" id="arbitros" value="arbitros" onClick="comprueba_linea2(this)"><b>Asignar &Aacute;rbitros</b></span>
    <div class="cont_arbitros">
    <span><form id="formulario_arbitros" action="#" method="post" name="formulario_arbitros"></span>
	<?php check_arbitros($id_liga); ?>
    <span></form></span>
	</div>
</div>
<div class="columna8">
	<span class="cuadroInputs"><input type="checkbox" name="pistas" id="pistas" value="pistas" onClick="comprueba_linea2(this)"><b>Asignar Pistas</b></span>
    <div class="cont_pistas">
    <span><form id="formulario_pistas" action="#" method="post" name="formulario_pistas"></span>
    <?php check_pistas($id_liga); ?>
    <span></form></span>
	</div>
</div>


<div class="horizontal">&nbsp;</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Crear Calendario" class="boton"  /></div>
<div id="respuesta" class="horizontal"></div>
<?php
		}//fin comprobacion inscripciones sin pagar
		else{
			echo '<div class="mensaje">
						<div class="caja_pago"><img src="../../../images/error.png" /><label>Se ha detectado que dispone de inscripciones para esta liga y divisi&oacute;n que no han realizado el pago, por favor envie un e-mail desde el men&uacute; Inscripciones -> Ver/Modificar a los integrantes del equipo, y si no desean participar elim&iacute;nelas para continuar.</label></div>
					</div>';
		}
/*	}//fin comprobacion pago ida y vuelta
	else{
		echo '<div class="mensaje">
						<div class="caja_pago"><img src="../../../images/error.png" /><label>Se ha detectado que el pago del servicio de ida y vuelta  no se ha realizado, si no desea este servicio puede eliminarlo desde el men&uacute; Liga -> Ver/Modificar.</label></div>
					</div>';
		//mostrar mensaje de que elimine el pago o que espere un dia por la mensajeria IPN si ha realizado el pago que contacte con la web
	}*/
?>
</body>
</html>
<?php 
}//final de comprobar si hay equipos y no hay partidos 
?>