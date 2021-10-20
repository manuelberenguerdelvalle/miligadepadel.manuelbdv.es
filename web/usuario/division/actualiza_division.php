<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/premio.php");
include_once ("../../../class/noticia.php");
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
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$num_inscripciones = obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$id_liga,'division',$id_division,'','','','','');
//$num_inscripciones = obtenNumInscripciones($id_liga,$id_division);
if ( $pagina != 'gestion_division' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
		//DEBEMOS DE CONTROLAR SI SE HAN REALIZADO LOS PAGOS PARA LA IDA Y VUELTA SEGURO, SI NO NO CONTINUAMOS, 
	include_once ("../../funciones/f_recoger_post.php");
		$texto = 'La actualización se ha realizado correctamente.';
		$modifica = 'no';
		$tercero = trim($tercero);//NO SE SABE POR QUE INSERTAR UN ESPACIO AL PRINCIPIO
		$descripcion_noticia = '';
		if($suscripcion != ''){$suscripcion = insercion_fecha($suscripcion);}
		if(isset($precio)){
			if( !empty($precio) && $division->getValor('precio') != $precio ){
				$division->setValor('precio',$precio);
				$modifica = 'ok';
				$descripcion_noticia .= 'El precio de inscripción ha cambiado a '.$precio.' euros. ';
			}	
		}
		if($num_inscripciones == 0){
			if( ($suscripcion != '') && (substr($division->getValor('suscripcion'),0,10) != $suscripcion) ){//entro si la suscripcion que llega es diferente de vacio y diferente de lo que hay en bd
				if($tipo_pago > 0){//entra si la liga es de pago
					$fecha_min_suscripcion = fecha_suma($division->getValor('fec_creacion'),'','',3,'','','');
				}
				else{//gratis
					$fecha_min_suscripcion = $division->getValor('fec_creacion');
					$liga->setValor('pagado','S');
					$liga->modificar();
					$_SESSION['liga'] = serialize($liga);
				}
				$suscripcion_completa = $suscripcion.' '.obten_hora();
				//horas con minutos y segundos
				if( obten_consultaUnCampo('unicas','pagado','pago_web','bd',$_SESSION['bd'],'liga',$id_liga,'division',$id_division,'','','') == 'S' ){//si la division está pagada
					$division->setValor('suscripcion',$suscripcion_completa);//inserto fecha introducida
				}
				else{//si no esta pagada
					if( strtotime($suscripcion_completa) > strtotime($fecha_min_suscripcion)  ){//aqui entra si la suscripción es menor a la fecha minima (3 dias pago, 0 dias gratis)
						$division->setValor('suscripcion',$suscripcion_completa);//inserto fecha introducida
					}
					else{//si no la insertada por el usuario
						$division->setValor('suscripcion',$fecha_min_suscripcion);//inserto fecha minima
					}
				}
				$modifica = 'ok';
				$descripcion_noticia .= 'La fecha de inicio de suscripción ha cambiado a '.substr($suscripcion,0,10).'. ';
			}
		}
		if($modifica == 'ok'){
			$division->modificar();
			$_SESSION['division'] = serialize($division);
		}
		//if(obtenNumPremio($id_division) == 0){
		if(obten_consultaUnCampo('session','COUNT(id_premio)','premio','division',$id_division,'','','','','','','') == 0){// no tiene premio
			$premio = new Premio(NULL,$id_division,ucfirst($primero),ucfirst($segundo),ucfirst($tercero),ucfirst($cuarto),ucfirst($quinto),ucfirst($todos));
			$premio->insertar();
			if(!empty($primero) || !empty($segundo) || !empty($tercero) || !empty($cuarto) || !empty($quinto) || !empty($todos)){
				$descripcion_noticia .= 'Se han creado los premios: ';
				if(!empty($primero)){
					$descripcion_noticia .= '-1er premio = '.utf8_encode($primero).'. ';
				}
				if(!empty($segundo)){
					$descripcion_noticia .= '-2o premio = '.utf8_encode($segundo).'. ';
				}
				if(!empty($tercero)){
					$descripcion_noticia .= '-3o premio = '.utf8_encode($tercero).'. ';
				}
				if(!empty($cuarto)){
					$descripcion_noticia .= '-4o premio = '.utf8_encode($cuarto).'. ';
				}
				if(!empty($quinto)){
					$descripcion_noticia .= '-5o premio = '.utf8_encode($quinto).'. ';
				}
				if(!empty($todos)){
					$descripcion_noticia .= '-Todos participantes = '.utf8_encode($todos).'. ';
				}
			}
			else{
				$descripcion_noticia .= 'Los premios están vacíos. ';
			}
		}
		else{//ya tiene premio
			$premio = new Premio('',$id_division,'','','','','','');
			if($premio->getValor('primero') != $primero){
				$premio->setValor('primero',ucfirst($primero));
				if(empty($primero)){
					$descripcion_noticia .= 'El primer premio ha sido modificado a vacío. ';
				}
				else{
					$descripcion_noticia .= 'El primer premio ha sido modificado a '.utf8_encode($primero).'. ';
				}
			}
			if($premio->getValor('segundo') != $segundo){
				$premio->setValor('segundo',ucfirst($segundo));
				if(empty($segundo)){
					$descripcion_noticia .= 'El segundo premio ha sido modificado a vacío. ';
				}
				else{
					$descripcion_noticia .= 'El segundo premio ha sido modificado a '.utf8_encode($segundo).'. ';
				}
			}
			if($premio->getValor('tercero') != $tercero){
				$premio->setValor('tercero',ucfirst($tercero));
				if(empty($tercero)){
					$descripcion_noticia .= 'El tercer premio ha sido modificado a vacío. ';
				}
				else{
					$descripcion_noticia .= 'El tercer premio ha sido modificado a '.utf8_encode($tercero).'. ';
				}
			}
			if($premio->getValor('cuarto') != $cuarto){
				$premio->setValor('cuarto',ucfirst($cuarto));
				if(empty($cuarto)){
					$descripcion_noticia .= 'El cuarto premio ha sido modificado a vacío. ';
				}
				else{
					$descripcion_noticia .= 'El cuarto premio ha sido modificado a '.utf8_encode($cuarto).'. ';
				}
			}
			if($premio->getValor('quinto') != $quinto){
				$premio->setValor('quinto',ucfirst($quinto));
				if(empty($quinto)){
					$descripcion_noticia .= 'El quinto premio ha sido modificado a vacío. ';
				}
				else{
					$descripcion_noticia .= 'El quinto premio ha sido modificado a '.utf8_encode($quinto).'. ';
				}
			}
			if($premio->getValor('todos') != $todos){
				$premio->setValor('todos',ucfirst($todos));
				if(empty($todos)){
					$descripcion_noticia .= 'El premio para todos los participantes ha sido modificado a vacío. ';
				}
				else{
					$descripcion_noticia .= 'El premio para todos los participantes ha sido modificado a '.utf8_encode($todos).'. ';
				}
			}
			$premio->modificar();
		}
		if($descripcion_noticia != ''){
			$resumen_noticia = utf8_decode('Sección: División -> Ver/Modificar.');
			$fecha_noticia = obten_fechahora();
			$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,utf8_decode($descripcion_noticia),$fecha_noticia,'');
			$noticia->insertar();
			unset($noticia);
		}
		echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
		unset($liga,$division,$premio);
}

?>