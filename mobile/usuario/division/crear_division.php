<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/division.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/premio.php");
include_once ("../../../class/noticia.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:10px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:15%;
	color:#006;
}
.actualizacion img{
	width:10%;
	margin-top:1%;
	margin-right:1%;
}
</style>
<?php
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_usuario = $_SESSION['id_usuario'];
if($_SESSION['cuenta_paypal'] != ''){$email_ins = $_SESSION['cuenta_paypal'];}
else{$email_ins = $_SESSION['email'];}
$id_liga = $_SESSION['id_liga'];
$tipo_pago = $_SESSION['tipo_pago'];
$bd = $_SESSION['bd'];
if ( $pagina != 'gestion_division' && $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {//aqui solo se entra si el tipo pago es > 0
	//DEBEMOS DE CONTROLAR SI SE HAN REALIZADO LOS PAGOS PARA LA IDA Y VUELTA SEGURO, SI NO NO CONTINUAMOS, 
	include_once ("../../funciones/f_recoger_post.php");
	$texto = 'La división se ha insertado correctamente.';
	if($tipo_pago > 0 && $tipo_pago <= 3 ){
		$max_equipos = obten_equipos($tipo_pago);
		$num_division = obten_consultaUnCampo('session','COUNT(id_division)','division','liga',$id_liga,'','','','','','','') + 1;//siguiente num_division
		$division = new Division(NULL,obten_fechahora(),$precio,$id_liga,$suscripcion,$num_division,$max_equipos,'N','N');
		$division->insertar();
		$nueva_division = new Division('','','',$id_liga,'',$num_division,'','','');//SE CREA LA NUEVA DIVISION Y SE ASIGNA A SESSION
		$id_division_nueva = $nueva_division->getValor('id_division');
		$premio = new Premio(NULL,$id_division_nueva,ucfirst($primero),ucfirst($segundo),ucfirst($tercero),ucfirst($cuarto),ucfirst($quinto),ucfirst($todos));
		$premio->insertar();
		$fecha = obten_fechahora();
		//5 es el precio de pago de division
		$pago = new Pago_web(NULL,$bd,$id_liga,$id_division_nueva,'D',NULL,5,'P','N',cuenta_admin(),$email_ins,$id_usuario,$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
		$pago->insertar();
	}
	//si es liga premium de prueba gratis marca pagos 
	$prueba_gratis = obten_consultaUnCampo('unicas_liga','COUNT(usuario)','prueba_gratis','usuario',$id_usuario,'bd',$bd,'liga',$id_liga,'','','');
	if($prueba_gratis > 0){
		realiza_updateGeneral('unicas','pago_web','pagado = "S",modo_pago = "G", fecha_limite = "'.$fecha.'"','liga',$id_liga,'bd',$bd,'usuario',$id_usuario,'','','','','');
	}
	//CARGAR NUEVA DIVISION EN SESSION
	$_SESSION['division'] = serialize($nueva_division);
	$resumen_noticia = utf8_decode('Sección: División -> Crear nueva.');
	$descripcion_noticia .= utf8_decode('Se ha creado la nueva división. ');
	$fecha_noticia = obten_fechahora();
	$noticia = new Noticia(NULL,$id_liga,$id_division_nueva,$resumen_noticia,$descripcion_noticia,$fecha_noticia,'');
	$noticia->insertar();
	unset($division,$premio,$noticia,$pago);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>