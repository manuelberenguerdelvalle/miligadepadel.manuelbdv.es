<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/thumbnail.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/publicidad_gratis.php");
include_once ("../../../class/usuario_publi.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$usuario_publi = unserialize($_SESSION['usuario_publi']);
if ( $pagina != 'gestion_publicidad' || $opcion != 1){
	header ("Location: ../cerrar_sesion.php");
}
else {
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
		$file = limpiaTexto3($_FILES['nueva_publi']['name']);
		$ruta_local = limpiaTexto3($_FILES['nueva_publi']['tmp_name']);
		$provincia = limpiaTexto($_POST['provincia']);
		$ciudad = limpiaTexto($_POST['ciudad']);
		$suscripcion = limpiaTexto($_POST['suscripcion']);
		$precio = limpiaTexto($_POST['precio']);
		$url = strtolower(limpiaTexto3($_POST['url']));
		if($url == 'copiar y pegar el enlace'){$url == '';}
		$fecha = obten_fechahora();
		if($suscripcion == 1){$fecha_fin = fecha_suma($fecha,'','',90,'','','');}
		else if($suscripcion == 2){$fecha_fin = fecha_suma($fecha,'','',182,'','','');}
		else{$fecha_fin = fecha_suma($fecha,'','',365,'','','');}
		//DE MOMENTO SIN LIMITE
		//UTILIZO LOS CAMPOS LIGA Y DIVISION PARA PROVINCIA Y CIUDAD
		$pago_web = new Pago_web(NULL,'ligas',$provincia,$ciudad,'P','',$precio,'P','N',cuenta_admin(),$usuario_publi->getValor('email'),$usuario_publi->getValor('id_usuario_publi'),obten_fechahora(),'','','','E');
		$pago_web->insertar();
		$id_pago_web = obten_consultaUnCampo('unicas','id_pago_web','pago_web','usuario',$usuario_publi->getValor('id_usuario_publi'),'bd','ligas','','','','','ORDER BY id_pago_web DESC');
		//$id_pago_web = obtenPagoWebGratis($email);
		//crear publicidad 
		$publicidad = new Publicidad_gratis(NULL,$usuario_publi->getValor('id_usuario_publi'),$id_pago_web,$provincia,$ciudad,$url,0,obten_fechahora(),$fecha_fin,"",'N',0);
		$publicidad->insertar();
		//$id_publicidad_gratis = obten_idPublicidad($email);
		$id_publicidad_gratis = obten_consultaUnCampo('unicas','id_publicidad_gratis','publicidad_gratis','usuario_publi',$usuario_publi->getValor('id_usuario_publi'),'','','','','','','ORDER BY id_publicidad_gratis DESC');
		$destino = '../../../fotos_publicidad/gratis/';
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
			if($ancho >= 250){//ES NECESARIO EL RESIZE SI NO NO VA
				$thumb->resize(250,250);
			}
			else{
				$thumb->resize($ancho,$ancho);
			}
			$thumb->save_jpg($destino,$id_publicidad_gratis);//donde lo guardo en JPG
			//$thumb->save_png("../../fotos_noticias/", $id_noticia.$cont);
		}
		unlink($imagen_temp);//borro
		
		unset($pago_web,$publicidad,$thumb);
		echo $devolucion;
	}
}//fin else

?>