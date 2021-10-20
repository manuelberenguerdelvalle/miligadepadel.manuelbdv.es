<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/datos.php");
require_once ("../../../fpdf/fpdf.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_pago'){
	header ("Location: ../cerrar_sesion.php");
}
$id_pago_web = limpiaTexto($_POST['id_pago_web']);
$estado = limpiaTexto($_POST['estado']);
if(!empty($id_pago_web)){
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
	$pago = new Pago_web($id_pago_web,'','','','','','','','','','','','','','','',''); 
	$usuario = unserialize($_SESSION['usuario']);
	$liga = unserialize($_SESSION['liga']);
	$nom_liga = utf8_encode($liga->getValor('nombre'));
	$division = unserialize($_SESSION['division']);
	$datos = new Datos(2,'','','','','','');
	
	$nom_yo = '   '.$datos->getValor('c1');
	$nom_cli = '   '.utf8_encode(ucwords($usuario->getValor('nombre').' '.$usuario->getValor('apellidos')));
	$dir_yo = '   '.$datos->getValor('c2');
	$dir_cli = '   '.utf8_encode(ucwords($usuario->getValor('direccion')));
	$loc_yo = '   '.$datos->getValor('c3');
	$loc_cli = '   '.ucwords( obtenLocalizacion(3,$usuario->getValor('ciudad')).' ('.obtenLocalizacion(2,$usuario->getValor('provincia')).')' );
	$doc_yo = '   '.$datos->getValor('c4');
	$doc_cli = '   '.$usuario->getValor('dni').'-'.letraNIF($usuario->getValor('dni'));
	$nombres = array(substr($nom_yo,0,56), substr($nom_cli,0,56));
	$direcciones = array(substr($dir_yo,0,56), substr($dir_cli,0,56));
	$localizaciones = array(substr($loc_yo,0,56), substr($loc_cli,0,56));
	$documentos = array($doc_yo, $doc_cli);
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
	if($tipo == 'L'){
			$descrip_pago .= 'Pack Liga '.obten_equipos($liga->getValor('tipo_pago')).': '.$nom_liga;
	}//pago de liga
	else if($tipo == 'D'){
			$descrip_pago .= 'Pack División extra: nº'.$division->getValor('num_division').' en la liga '.$nom_liga;
	}//division extra
	else if($tipo == 'I'){
			$descrip_pago .= 'Pack Ida y vuelta: en la liga '.$nom_liga;
	}//division extra
	else{
			$descrip_pago .= 'Publicidad: Posición '.$pago->getValor('posicion_publi').' en la liga '.$nom_liga;
	}
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
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->Cell(0,16,' ',0,1);
	$pdf->BasicTable3(array('                              '.$id_pago_web, '                               '.datepicker_fecha(substr($pago->getValor('fecha_limite'),0,10)), '                             '.$modo_pago));
	$pdf->Cell(0,30,' ',0,1);
	$pdf->BasicTable4(array('    '.obten_tipoArticulo($tipo), utf8_decode($descrip_pago), '               1', '  '.$signo.$precio_sin.$euro));
	$pdf->Cell(0,41,' ',0,1);
	$pdf->BasicTable3(array(' ', ' ', '                                        '.$signo.$iva.$euro));
	$pdf->Cell(0,7,' ',0,1);
	$pdf->BasicTable3(array(' ', ' ', '                                        '.$signo.$pago->getValor('precio').$euro));
	$pdf->Output();
}//fin if pago_web


?>