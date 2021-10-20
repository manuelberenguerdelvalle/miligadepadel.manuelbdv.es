<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_regla'){
	header ("Location: ../cerrar_sesion.php");
}
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$opcion = $_SESSION['opcion'];
if($opcion == 0){//modificacion
	//SE GUARDA EN SESSION
	$_SESSION['id_liga'] = $id_liga;//añado la liga para usarlo en el resto de paginas
	$_SESSION['id_division'] = $id_division;//añado la division para usarlo en el resto de paginas
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_regla.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="javascript/modificar_regla.js" type="text/javascript"></script>
</head>
<body>
<div class="cont_principal">
	<div class="horizontal">&nbsp;</div>
	<div class="horizontal"><div class="titulo"><b>Ver/Modificar las reglas de la liga.</b></div></div>
<?php
$texto_regla = obten_consultaUnCampo('session','texto','regla','liga',$id_liga,'','','','','','','');
if($texto_regla == '' || $texto_regla == 'vacio'){
	$texto_regla = '--Introduce aqui las reglas--';
}
?>
        <div class="columna1">
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Introduce el texto de las reglas.');" onMouseOut="hiddenDiv()" style="display:table;">Reglas:</div>
        </div>
            <div class="columna2">
            <span class="cuadroInputs"><textarea name="reglas" id="reglas" class="input_text_area" rows="22" cols="119"><?php echo $texto_regla; ?></textarea></span>
        </div>
        <div class="horizontal">
        	<input type="button" id="btn_enviar" value="Actualizar" class="boton" />
        	</form>
        </div>
        <div id="respuesta" class="horizontal"></div>
</div>
</body>
</html>
<?php
}//fin de if
else{
	header ("Location: ../cerrar_sesion.php");
}
?>