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
$id_pago_admin = limpiaTexto($_POST['id_pago_admin']);
$estado = limpiaTexto($_POST['estado']);
if(!empty($id_pago_admin) && ($estado == 'normal' || $estado == 'devolucion') ){
	class PDF extends FPDF
	{
		// Cabecera de página
		function Header()
		{
			// Logo
			$this->Image('../../../images/diseno_justificante.jpg',0,0,210);
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
	$pago = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','',''); 
	$jugador1 = new Jugador($pago->getValor('jugador1'),'','','','','','','','','','','','','','','');
	$jugador2 = new Jugador($pago->getValor('jugador2'),'','','','','','','','','','','','','','','');
	$usuario = new Usuario($pago->getValor('usuario'),'','','','','','','','','','','','','','','','');
	$_SESSION['bd'] = $pago->getValor('bd');
	$liga = new Liga($pago->getValor('liga'),'','','','','','','','','','','','','','','','');
	$division = new Division($pago->getValor('division'),'','','','','','','','');
	$nom_cli = '   '.utf8_encode(ucwords($usuario->getValor('nombre').' '.$usuario->getValor('apellidos')));
	$dir_cli = '   '.utf8_encode(ucwords($usuario->getValor('direccion')));
	$loc_cli = '   '.utf8_encode(ucwords( obtenLocalizacion(3,$usuario->getValor('ciudad')).' ('.obtenLocalizacion(2,$usuario->getValor('provincia')).')' ));
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
	$nombres = array(utf8_decode(substr($nom_cli,0,56)), utf8_decode(substr($nom_jug1,0,56)));
	$direcciones = array(utf8_decode(substr($dir_cli,0,56)), utf8_decode(substr($dir_jug1,0,56)));
	$localizaciones = array(utf8_decode(substr($loc_cli,0,56)), utf8_decode(substr($loc_jug1,0,56)));
	$documentos = array($doc_cli, $doc_jug1);
	$nombres2 = array('', utf8_decode(substr($nom_jug2,0,56)));
	$direcciones2 = array('', utf8_decode(substr($dir_jug2,0,56)));
	$localizaciones2= array('', utf8_decode(substr($loc_jug2,0,56)));
	$documentos2 = array('', $doc_jug2);
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
	// Creación del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(50,30,'',0,1);
	$pdf->BasicTable2($nombres);
	$pdf->BasicTable2($direcciones);
	$pdf->BasicTable2($localizaciones);
	$pdf->BasicTable2($documentos);
	$pdf->BasicTable2($nombres2);
	$pdf->BasicTable2($direcciones2);
	$pdf->BasicTable2($localizaciones2);
	$pdf->BasicTable2($documentos2);
	$pdf->BasicTable2(array(' ', ' '));
	$pdf->Cell(0,16,' ',0,1);
	$pdf->BasicTable3(array('                                      '.$id_pago_admin, '                               '.datepicker_fecha(substr($pago->getValor('fecha'),0,10)), '                             '.$modo_pago));
	$pdf->Cell(0,30,' ',0,1);
	$pdf->BasicTable4(array('    5', utf8_decode($descrip_pago), '               1', '  '.$signo.$precio_sin.$euro));
	$pdf->Cell(0,41,' ',0,1);
	$pdf->BasicTable3(array(' ', ' ', '                                        '.$signo.$iva.$euro));
	$pdf->Cell(0,7,' ',0,1);
	$pdf->BasicTable3(array(' ', ' ', '                                        '.$signo.$pago->getValor('precio').$euro));
	$pdf->Output();
}//fin if pago_admin


?>