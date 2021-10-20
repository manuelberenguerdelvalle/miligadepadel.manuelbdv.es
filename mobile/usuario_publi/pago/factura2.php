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
$id_pago_web = limpiaTexto($_POST['id_pago_web']);
$estado = limpiaTexto($_POST['estado']);
if($id_pago_web != ''){
	class PDF extends FPDF
	{
		// Cabecera de página
		function Header()
		{
			// Logo
			$this->Image('../../../images/diseno_factura.jpg',0,0,210);
			// Arial bold 15
			//$this->SetFont('Arial','B',15);
			// Movernos a la derecha
			//$this->Cell(80);
			// Título
			//$this->Cell(30,10,'Title',1,0,'C');
			// Salto de línea
			$this->Ln(20);
		}
		
		// Pie de página
		function Footer()
		{
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Número de página
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
		// Tabla simple con 2 columnas
		function BasicTable2($header)
		{
			// Cabecera
			foreach($header as $col)
				$this->Cell(97,7,$col,0);
			$this->Ln();
		}
		// Tabla simple con 3 columnas
		function BasicTable3($header)
		{
			// Cabecera
			foreach($header as $col)
				$this->Cell(64,7,$col,0);
			$this->Ln();
		}
		// Tabla simple con 4 columnas
		function BasicTable4($header)
		{
			// Cabecera
			$cont = 0;
			$tam = 20;
			$view = 0;
			foreach($header as $col){
				if($cont == 0){$this->Cell(20,$tam,$col,$view);$cont++;}
				else if($cont == 1){$this->Cell(110,$tam,$col,$view);$cont++;}
				else if($cont == 2){$this->Cell(35,$tam,$col,$view);$cont++;}
				else{$this->Cell(25,$tam,$col,$view);$cont++;}
				//$this->Cell(40,7,$col,1);
			}
			$this->Ln();
		}
	}
	//POSIBLEMENTE QUITAR UTF8_DECODE
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
	$nombres = array(substr($nom_yo,0,56), substr($nom_cli,0,56));
	$direcciones = array(substr($dir_yo,0,56), substr($emp_cli,0,56));
	$localizaciones = array(substr($loc_yo,0,56),substr($dir_cli,0,56));
	$documentos = array($doc_yo,  substr($loc_cli,0,56));
	$documentos2 = array('', $doc_cli);
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
	// Creación del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	//$pdf->SetTextColor(24,28,131);
	$pdf->Cell(50,30,'',0,1);
	$pdf->BasicTable2($nombres);
	$pdf->BasicTable2($direcciones);
	$pdf->BasicTable2($localizaciones);
	$pdf->BasicTable2($documentos);
	$pdf->BasicTable2($documentos2);
	//$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->Cell(0,16,' ',0,1);
	$pdf->BasicTable3(array('                              '.$id_pago_web, '                               '.datepicker_fecha(substr($pago->getValor('fecha_limite'),0,10)), '                             '.$modo_pago));
	$pdf->Cell(0,30,' ',0,1);
	$pdf->BasicTable4(array('    '.obten_tipoArticulo($tipo), utf8_decode($descrip_pago), '                ', '  '));
	$pdf->BasicTable4(array('    ', $ciudad.' ('.$provincia.')', '               1', '  '.$signo.$precio_sin.$euro));
	$pdf->Cell(0,21,' ',0,1);
	$pdf->BasicTable3(array(' ', ' ', '                                        '.$signo.$iva.$euro));
	$pdf->Cell(0,7,' ',0,1);
	$pdf->BasicTable3(array(' ', ' ', '                                        '.$signo.$pago->getValor('precio').$euro));
	$pdf->Output();
	
}//fin if pago_web


?>