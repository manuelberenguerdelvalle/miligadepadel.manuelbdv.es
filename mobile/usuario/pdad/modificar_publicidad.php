<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if($pagina != 'gestion_publicidad' || $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$bd = $usuario->getValor('bd');
$id_usuario = $usuario->getValor('id_usuario');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_division = $division->getValor('num_division');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_publicidad.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_publicidad.js" type="text/javascript"></script>
</head>
<body>
<div class="cont_principal">
<?php
if($tipo_pago > 0){
	$publi_id = array();
	$publi_posicion = array();
	$publi_url = array();
	$i = 0;
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_publicidad,posicion_publi,url FROM publicidad WHERE usuario_publi = '$id_usuario' AND liga = '$id_liga' AND division = '$id_division' AND pagado = 'S'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$publi_id[$i] = $resultados['id_publicidad'];
		$publi_posicion[$i] = $resultados['posicion_publi'];
		$publi_url[$i] = $resultados['url'];
		$i++;
	}//fin del while
?>
	<div class="horizontal">&nbsp;</div>
    <div class="horizontal"><div class="titulo"><b>Importante:</b> Solo es posible modificar la Url de las publicidades completadas correctamente.</div></div>
    <div class="horizontal">&nbsp;</div>
	<div class="caja1">
<?php
for($i=1; $i<=5; $i++){
	$pos = buscar_enArray($publi_posicion,$i.'I');
	if($pos == -1){//no encontrada
		//$valor_izq = 'disabled';//si no se encuentra no se muestra nada la parte izquierda
?>
		<div class="cuadro_publi"><div class="fondo_publi_vacio">&nbsp;</div></div>
        <div class="cuadro_precio">&nbsp;</div>
        <div class="cuadro_seleccion_izq">&nbsp;</div>
<?php
	}
	else{//encontrada
		$img_izq = '../../../fotos_publicidad/'.$bd.$id_liga.$id_division.'/'.$i.'I.jpg';
		$valor_izq = 'value="'.$i.'I"';
?>
		<div class="cuadro_publi">
        	<div class="fondo_publi"><img src="<?php echo $img_izq; ?>" class="imagen_redondeada"></div>
        </div>
        <div class="cuadro_precio"><input type="hidden" id="<?php echo $publi_posicion[$pos]; ?>" value="<?php echo $publi_id[$pos]; ?>"></div>
        <div class="cuadro_seleccion_izq"><input id="posicion" name="posicion" type="radio" class="radio" <?php echo $valor_izq; ?> onClick="insertar_url('<?php echo $publi_url[$pos]; ?>')" ></div>
<?php
	}
	
	$pos = buscar_enArray($publi_posicion,$i.'D');
	if($pos == -1){//no encontrada
		//$valor_der = 'disabled';//si no se encuentra no se muestra nada la parte derecha
?>
        <div class="cuadro_seleccion_der">&nbsp;</div>
        <div class="cuadro_precio">&nbsp;</div>
        <div class="cuadro_publi"><div class="fondo_publi_vacio">&nbsp;</div></div>
<?php
	}
	else{//encontrada
		$img_der = '../../../fotos_publicidad/'.$bd.$id_liga.$id_division.'/'.$i.'D.jpg';
		$valor_der = 'value="'.$i.'D"';
?>
        <div class="cuadro_seleccion_der"><input name="posicion" type="radio" class="input_text" <?php echo $valor_der; ?> onClick="insertar_url('<?php echo $publi_url[$pos]; ?>')" ></div>
        <div class="cuadro_precio"><input type="hidden" id="<?php echo $publi_posicion[$pos]; ?>" value="<?php echo $publi_id[$pos]; ?>"></div>
        <div class="cuadro_publi">
        	<div class="fondo_publi"><img src="<?php echo $img_der; ?>" class="imagen_redondeada"></div>
        </div>
<?php
	}
	
}//fin for
?>
    </div>
    <div class="caja2">
        <div class="horizontal">&nbsp;</div>
        <div class="horizontal"><div class="titulo">Url:&nbsp;<input type="text" id="url" name="url" class="input_text_liga" maxlength="200"></div></div>
        <div class="horizontal">&nbsp;</div>
        <div class="horizontal"><input type="button" id="btn_enviar" value="Modificar" class="boton" /></div>
        <div id="respuesta" class="horizontal"></div>
        <div class="horizontal">&nbsp;</div>
    	<div class="horizontal">&nbsp;</div>
    </div>
<?php
}//FIN IF TIPO PAGO
?>
</div>
</body>
</html>