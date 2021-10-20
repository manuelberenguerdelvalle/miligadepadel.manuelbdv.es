<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/pago_web.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:7px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:15%;
	color:#006;
}
.actualizacion img{
	width:2%;
	margin-top:1%;
	margin-right:1%;
}
</style>
<?php
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if ( $pagina != 'gestion_liga' || $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	$id_usuario = $_SESSION['id_usuario'];
	if($_SESSION['cuenta_paypal'] != ''){$email_ins = $_SESSION['cuenta_paypal'];}
	else{$email_ins = $_SESSION['email'];}
	$bd_usuario = $_SESSION['bd'];
	include_once ("../../funciones/f_recoger_post.php");
	$texto = 'La liga se ha creado correctamente.';
	$nombre = ucfirst(mb_strtolower($nombre));
	$nombre_repetido = obten_consultaUnCampo('session','COUNT(id_liga)','liga','nombre',$nombre,'','','','','','','');
	$descripcion_noticia = '';
	if($nombre_repetido != 0){
		$texto.= '. El nombre de la liga ya existe, vaya a ver/modificar para ponerle un nombre correcto.';
		$nombre = 'Sin nombre';
	}
	if( $pass == '' ){//genero aleatorio
		$pass = genera_pass(6);
	}
	if($tipo_pago == 0){//si es gratis compruebo 
		if($vista == 1){$vista = 0;}
		if($idayvuelta == 'S'){$idayvuelta = 'N';}
		if($movimientos > 0){$movimientos = 0;}
	}
	if($estilo > 1 && $tipo_pago == 0){//entro si el estilo es de pago y pago gratis
		$estilo = 0;
	}	
	$fecha = obten_fechahora();
	$nuevaLiga = new Liga(NULL,$nombre,$fecha,$ciudad,$provincia,$pais,$id_usuario,$tipo_pago,'N',$vista,$pass,$auto_completar,$movimientos,'N',$genero,$idayvuelta,$estilo);
	$nuevaLiga->insertar();
	$max_equipos = obten_equipos($tipo_pago);
	$id_liga = obten_consultaUnCampo('session','id_liga','liga','usuario',$id_usuario,'','','','','','','ORDER BY id_liga DESC');
	$division = new Division(NULL,$fecha,0,$id_liga,NULL,1,$max_equipos,'N','N');
	$division->insertar();//INSERTAR DIVISION	
	$division_1 = new Division('','','',$id_liga,'',1,'','','');
	$id_division = $division_1->getValor('id_division');//id_divison num 1, la que entra con la liga
	if($tipo_pago > 0){//si es de pago creo el pago
		$fecha = obten_fechahora();
		$pago = new Pago_web(NULL,$bd_usuario,$id_liga,$id_division,'L',NULL,obten_precio($tipo_pago),'P','N',cuenta_admin(),$email_ins,$id_usuario,$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
		$pago->insertar();
		/*
		if( $idayvuelta == 'S' ){//si tiene ida y vuelta
			unset($pago);
			$pago = new Pago_web(NULL,$bd_usuario,$id_liga,$id_division,'I',NULL,10,'P','N',cuenta_admin(),$email_ins,$id_usuario,$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
			$pago->insertar();
		}//fin pago idayvuelta
		*/
	}//fin de tipo pago
	//PONEMOS LA NUEVA LIGA Y DIVISION CREADAS A LA SESSION
	$nuevaLiga->setValor('id_liga',$id_liga);
	$_SESSION['liga'] = serialize($nuevaLiga);
	$_SESSION['division'] = serialize($division_1);
	$resumen_noticia = utf8_decode('Sección: Liga -> Crear Nueva.');
	$descripcion_noticia .= utf8_decode('La Liga ha sido creada de tipo ');
	if($tipo_pago == 0){$descripcion_noticia .= utf8_decode('gratuito con un máximo de '.$max_equipos.' equipos. ');}
	else{$descripcion_noticia .= utf8_decode('premier con un máximo de '.$max_equipos.' equipos. ');}
	$fecha_noticia = obten_fechahora();
	$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,$fecha_noticia,'');
	$noticia->insertar();
	unset($division,$division_1,$nuevaLiga,$noticia,$pago);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
	//echo $nombre.'-'.$pass.'-'.$auto_completar.'-'.$vista.'-'.$genero.'-'.$pais.'-'.$provincia.'-'.$ciudad.'-'.$tipo_pago.'-'.$idayvuelta.'-'.$movimientos;
}

?>
