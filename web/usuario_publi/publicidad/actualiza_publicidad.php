<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/thumbnail.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/publicidad_gratis.php");
include_once ("../../../class/usuario_publi.php");
include_once ("../../../class/pago_web.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$email = $_SESSION['email'];
if ( $pagina != 'gestion_publicidad' || $opcion != 0){
	header ("Location: ../cerrar_sesion.php");
}
else {
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
		if(isset($_FILES['nueva_publi']['name']) && $_FILES['nueva_publi']['name'] != ''){$file = limpiaTexto3($_FILES['nueva_publi']['name']);}
		else{$file = '';}
		if(isset($_FILES['nueva_publi']['tmp_name']) && $_FILES['nueva_publi']['tmp_name'] != ''){$ruta_local = limpiaTexto3($_FILES['nueva_publi']['tmp_name']);}
		else{$ruta_local = '';}
		if(isset($_POST['provincia']) && $_POST['provincia'] != '' && $_POST['provincia'] != 'null' && $_POST['provincia'] != 'undefined'){$provincia = limpiaTexto($_POST['provincia']);}
		else{$provincia = '';}
		if(isset($_POST['ciudad']) && $_POST['ciudad'] != '' && $_POST['ciudad'] != 'null' && $_POST['ciudad'] != 'undefined'){$ciudad = limpiaTexto($_POST['ciudad']);}
		else{$ciudad = '';}
		if(isset($_POST['id_publicidad_gratis'])){$id_publicidad_gratis = limpiaTexto($_POST['id_publicidad_gratis']);}
		if(isset($_POST['url'])){$url = strtolower(limpiaTexto3($_POST['url']));}
		if($url == 'copiar y pegar el enlace'){$url == '';}
		$publicidad = new Publicidad_gratis($id_publicidad_gratis,'','','','','','','','','','','');
		if( $ciudad != '' ){
			if($ciudad != $publicidad->getValor('ciudad')){
				//SI CAMBIO DE CIUDAD MODIFICO EL PAGO WEB
				//ESTUDIAR LA POSIBILIDAD DE NO CAMBIARLO, PARA SABER LA CIUDAD DE ORIGEN Y CAMBIAR AL ORIGEN CUANDO VUELVAN A HABER AUTOMATICAMENTE
				$pago_web = new Pago_web($publicidad->getValor('pago_web'),'','','','','','','','','','','','','','','','');
				$publicidad->setValor('ciudad',$ciudad);
				$pago_web->setValor('division',$ciudad);
				$pago_web->modificar();//modifico lel pago web también
				unset($pago_web);
			}
			$publicidad->setValor('estado',0);//pasamos de congelado 1 a activo 0
			if($publicidad->getValor('contador') > 0){//modifico la fecha de fin, ultima repeticion
				//$ultima_rep = strtotime($publicidad->getValor('ultima_rep'));
				$nueva_fecha_fin = resto_suscripcion($publicidad->getValor('ultima_rep'),$publicidad->getValor('fecha_fin'));
				/*$ahora = time();
				$sumar_dias = $ahora - $ultima_rep;
				$sumar_dias = $sumar_dias / 86400;
				$sumar_dias = intval(ceil($sumar_dias));
				$nueva_fecha_fin = fecha_suma($publicidad->getValor('fecha_fin'),'','',$sumar_dias,'','','');*/
				$publicidad->setValor('fecha_fin',$nueva_fecha_fin);
			}
		}
		if( $url != '' && $url != $publicidad->getValor('url') ){	
			$publicidad->setValor('url',$url);
		}
		if($file != '' && $ruta_local != ''){//cambio imagen
			$destino = '../../fotos_publicidad/gratis/';
			$tipo = strtolower(strstr($file, '.'));//obtengo el tipo	$destino = '../../logos/';
			$devolucion = 0;
			if(!is_dir($destino)){
				mkdir($destino,0777);
			}
			$imagen_temp = 'temp/'.$id_publicidad_gratis.'_temp'.$tipo;
			move_uploaded_file($ruta_local,$imagen_temp);
			$thumb = new Thumbnail($imagen_temp);//creo thumbnail
			if($thumb->error) {
				$devolucion = 1;
				echo $thumb->error;
			} else {
				$imagen = getimagesize($imagen_temp);
				$ancho = $imagen[0];          
				$alto = $imagen[1];
				if($ancho >= 1000){//ES NECESARIO EL RESIZE SI NO NO VA
					$thumb->resize(1000,1000);
				}
				else{
					$thumb->resize($ancho,$ancho);
				}
				$thumb->save_jpg($destino,$id_publicidad_gratis);//donde lo guardo en JPG
				//$thumb->save_png("../../fotos_noticias/", $id_noticia.$cont);
			}
			unlink($imagen_temp);//borro	
		}
		$publicidad->modificar();
		unset($publicidad,$thumb);
	}
}//fin else

?>