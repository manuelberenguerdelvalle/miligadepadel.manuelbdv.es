<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/usuario_publi.php");
include_once ("../../../class/datos.php");
require_once ("../../../fpdf/fpdf.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_pago'){
	header ("Location: ../cerrar_sesion.php");
}
$id_pago_web = limpiaTexto($_GET['id_pago_web']);
$estado = limpiaTexto($_GET['estado']);

$pago = new Pago_web($id_pago_web,'','','','','','','','','','','','','','','',''); 
	$usuario_publi = unserialize($_SESSION['usuario_publi']);
	$_SESSION['bd'] = $pago->getValor('bd');
	$provincia = obtenLocalizacion(2,$pago->getValor('liga'));//PROVINCIA ALMACENADA EN CAMPO LIGA
	$ciudad = obtenLocalizacion(3,$pago->getValor('division'));//CIUDAD ALMACENADA EN CAMPO DIVISION
	$datos = new Datos(2,'','','','','','');
	$nom_yo = '   '.$datos->getValor('c1');
	$nom_cli = '   Contacto: '.ucwords($usuario_publi->getValor('nombre'));
	$emp_cli = '   Empresa: '.ucwords($usuario_publi->getValor('empresa'));
	$dir_yo = '   '.$datos->getValor('c2');
	$dir_cli = '   '.ucwords($usuario_publi->getValor('direccion'));
	$loc_yo = '   '.$datos->getValor('c3');
	$loc_cli = '   '.ucwords( obtenLocalizacion(3,$usuario_publi->getValor('ciudad')).' ('.obtenLocalizacion(2,$usuario_publi->getValor('provincia')).')' );
	$doc_yo = '   '.$datos->getValor('c4');
	$doc_cli = '   '.$usuario_publi->getValor('cif');
	if($pago->getValor('modo_pago') == 'P'){$modo_pago = 'Online';}
	else{$modo_pago = 'Presencial';}
	$tipo = $pago->getValor('tipo');
	$iva = $precio_sin = $pago->getValor('precio')*0.21;
	$precio_sin = $pago->getValor('precio')-$iva;
	$euro = utf8_encode(' EUR');
	if($estado == 'devolucion'){
		$descrip_pago = 'Devolución ';
		$signo = '-';
	}
	else{$signo = '';}
	$descrip_pago .= 'Publicidad en las ligas de la ciudad: ';
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="../../css/facturas_mobile.css" />
</head>
<body>
	<a href="#" onClick="window.history.back();">
	<div class="contenedor_principal">
    	<div class="datos"><?php echo substr($nom_yo,0,56).'<br>'.substr($dir_yo,0,56).'<br>'.substr($loc_yo,0,56).'<br>'.$doc_yo;?></div>
        <div class="cliente"><?php echo substr($nom_cli,0,56).'<br>'.substr($dir_cli,0,56).'<br>'.substr($loc_cli,0,56).'<br>'.$doc_cli;?></div>
        <div class="num_factura"><?php echo $id_pago_web;?></div>
        <div class="fecha"><?php echo datepicker_fecha(substr($pago->getValor('fecha_limite'),0,10));?></div>
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