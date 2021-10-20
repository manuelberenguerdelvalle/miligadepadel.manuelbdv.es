<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/jugador.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
require_once ("../../../fpdf/fpdf.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_pago'){
	header ("Location: ../cerrar_sesion.php");
}
$id_pago_admin = limpiaTexto($_GET['id_pago_admin']);
$estado = limpiaTexto($_GET['estado']);

$pago = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','',''); 
	$jugador1 = new Jugador($pago->getValor('jugador1'),'','','','','','','','','','','','','','','');
	$jugador2 = new Jugador($pago->getValor('jugador2'),'','','','','','','','','','','','','','','');
	$usuario = unserialize($_SESSION['usuario']);
	$liga = unserialize($_SESSION['liga']);
	$division = unserialize($_SESSION['division']);
	
	$nom_cli = '   '.ucwords($usuario->getValor('nombre').' '.$usuario->getValor('apellidos'));
	$dir_cli = '   '.ucwords($usuario->getValor('direccion'));
	$loc_cli = '   '.ucwords( obtenLocalizacion(3,$usuario->getValor('ciudad')).' ('.obtenLocalizacion(2,$usuario->getValor('provincia')).')' );
	$doc_cli = '   '.$usuario->getValor('dni').'-'.letraNIF($usuario->getValor('dni'));
	$nom_jug1 = '   '.utf8_encode(ucwords($jugador1->getValor('nombre').' '.$jugador1->getValor('apellidos')));
	$dir_jug1 = '   '.utf8_encode(ucwords($jugador1->getValor('direccion')));
	$loc_jug1 = '   '.utf8_encode(ucwords( obtenLocalizacion(3,$jugador1->getValor('ciudad')).' ('.obtenLocalizacion(2,$jugador1->getValor('provincia')).')' ));
	if($jugador1->getValor('dni') > 0){$doc_jug1 = '   '.$jugador1->getValor('dni').'-'.letraNIF($jugador1->getValor('dni'));}
	else{$doc_jug1 = '';}
	$nom_jug2 = '   '.utf8_encode(ucwords($jugador2->getValor('nombre').' '.$jugador2->getValor('apellidos')));
	$dir_jug2 = '   '.utf8_encode(ucwords($jugador2->getValor('direccion')));
	$loc_jug2 = '   '.utf8_encode(ucwords( obtenLocalizacion(3,$jugador2->getValor('ciudad')).' ('.obtenLocalizacion(2,$jugador2->getValor('provincia')).')' ));
	if($jugador2->getValor('dni') > 0){$doc_jug2 = '   '.$jugador2->getValor('dni').'-'.letraNIF($jugador2->getValor('dni'));}
	else{$doc_jug2 = '';}
	if($pago->getValor('modo_pago') == 'P'){$modo_pago = 'Online';}
	else{$modo_pago = 'Presencial';}
	$iva = $precio_sin = $pago->getValor('precio')*0.21;
	$precio_sin = $pago->getValor('precio')-$iva;
	$euro = utf8_encode(' EUR');
	if($estado == 'devolucion'){
		$descrip_pago = 'Devolución Inscripción: En la liga '.utf8_encode($liga->getValor('nombre')).' división '.$division->getValor('num_division');
		$signo = '-';
	}
	else{
		$descrip_pago = 'Inscripción: En la liga '.utf8_encode($liga->getValor('nombre')).' división '.$division->getValor('num_division');
		$signo = '';
	}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="../../css/justificantes_mobile.css" />
</head>
<body>
	<a href="#" onClick="window.history.back();">
	<div class="contenedor_principal">
    	<div class="datos"><?php echo utf8_decode(substr($nom_cli,0,56)).'<br>'.utf8_decode(substr($dir_cli,0,56)).'<br>'.utf8_decode(substr($loc_cli,0,56)).'<br>'.$doc_cli;?></div>
        <div class="cliente"><?php echo utf8_decode(substr($nom_jug1,0,56)).'<br>'.utf8_decode(substr($dir_jug1,0,56)).'<br>'.utf8_decode(substr($loc_jug1,0,56)).'<br>'.$doc_jug1.'<br>'.utf8_decode(substr($nom_jug2,0,56)).'<br>'.utf8_decode(substr($dir_jug2,0,56)).'<br>'.utf8_decode(substr($loc_jug2,0,56)).'<br>'.$doc_jug2;?></div>
        <div class="num_factura"><?php echo $id_pago_admin;?></div>
        <div class="fecha"><?php echo datepicker_fecha(substr($pago->getValor('fecha'),0,10));?></div>
        <div class="modo"><?php echo $modo_pago;?></div>
        <div class="articulo"><?php echo obten_tipoArticulo($tipo);?></div>
        <div class="descripcion"><?php echo $descrip_pago;?></div>
        <div class="cantidad"><?php echo '1';?></div>
        <div class="precio_sin"><?php echo $signo.$precio_sin.$euro;?></div>
        <div class="iva"><?php echo $signo.$iva.$euro;?></div>
        <div class="total"><?php echo $signo.$pago->getValor('precio').$euro;?></div>
    </div>
    </a>
</body>
</html>