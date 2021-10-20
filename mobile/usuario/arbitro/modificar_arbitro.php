<?php
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/arbitro.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_arbitro'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
if($opcion == 0 && $tipo_pago > 0){//si es de pago
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
<link rel="stylesheet" type="text/css" href="css/modificar_arbitro.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_arbitro.js" type="text/javascript"></script>

</head>
<body>
<div class="cont_principal">
	<div class="horizontal">&nbsp;</div>
	<div class="horizontal"><div class="titulo"><b>Ver/Modificar Arbitros.</b></div></div>
<?php
$num_arbitros = obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$id_liga,'','','','','','','');
if($num_arbitros != 0){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT * FROM arbitro WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
?>
	<div class="caja">
        <div class="columna1">
            <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduce el nombre del arbitro.');" onMouseOut="hiddenDiv()" style="display:table;">Nombre:</div>
            <div class="cuadroTexto">Apellidos:</div>
            <div class="cuadroTexto">Dni:</div>
            <div class="cuadroTexto">Teléfono:</div>
            <div class="cuadroTexto">Dirección:</div>
            <div class="cuadroTexto">C.P:</div>
            <div class="cuadroTexto">Arbitraje:</div>
        </div>
            <div class="columna2">
            <span><form id="<?php echo $resultados['id_arbitro']; ?>" action="#" method="post" name="<?php echo $resultados['id_arbitro']; ?>"></span>
            <span class="cuadroInputs"><input type="text" name="nombre" id="nombre" value="<?php echo $resultados['nombre']; ?>" class="input_text_liga" onKeyPress="return soloLetras(event)" ></span>
            <span class="cuadroInputs"><input type="text" name="apellidos" id="apellidos" value="<?php echo $resultados['apellidos']; ?>" class="input_text_liga" onKeyPress="return soloLetras(event)" ></span>
            <?php if($resultados['dni'] == 0){$dni = '';}  ?>
            <span class="cuadroInputs"><input type="text" name="dni" id="dni" value="<?php echo $dni; ?>" class="input_text_liga" onKeyPress="return tecla_dni(event)" maxlength="9" ></span>
            <?php if($resultados['telefono'] == 0){$resultados['telefono'] = '';}  ?>
            <span class="cuadroInputs"><input type="text" name="telefono" id="telefono" value="<?php echo $resultados['telefono']; ?>" class="input_text_liga" onKeyPress="return numeros(event)" maxlength="9" ></span>
            <span class="cuadroInputs"><input type="text" name="direccion" id="direccion" value="<?php echo $resultados['direccion']; ?>" class="input_text_liga" onKeyPress="return tecla_direccion(event)" ></span>
            <span class="cuadroInputs"><input type="text" name="cp" id="cp" value="<?php echo $resultados['cp']; ?>" class="input_text_liga" onKeyPress="return numeros(event)" maxlength="5"></span>
            <span class="cuadroInputs"><?php tipo_arbitros($resultados['nombre']);?></span>
        </div>
        <div class="horizontal"><input type="button" value="Actualizar" class="boton" onClick="enviar('<?php echo $resultados['id_arbitro']; ?>')" />
<?php
if(utilizando_arbitro($id_division,$resultados['id_arbitro']) == 0){
	echo '<input type="button" value="Eliminar" class="botonEli" onClick="eliminar('.$resultados["id_arbitro"].')" />';
}?>        
        </form></div>
        <div id="<?php echo 'respuesta'.$resultados['id_arbitro']; ?>" class="horizontal"></div>
        <div class="horizontal">&nbsp;</div>
	</div>
<?php
	}//fin del while
}//fin del if
?>
	<div class="horizontal">&nbsp;</div>
    <div class="horizontal">&nbsp;</div>
</div>
</body>
</html>
<?php
}
else{
	header ("Location: ../cerrar_sesion.php");
}
?>