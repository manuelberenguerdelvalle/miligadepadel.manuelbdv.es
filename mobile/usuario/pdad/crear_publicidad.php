<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/thumbnail.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/publicidad.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$tipo_pago = $_SESSION['tipo_pago'];
$id_division = $_SESSION['id_division'];
$num_division = $_SESSION['num_division'];
$bd = $_SESSION['bd'];
$id_usuario = $_SESSION['id_usuario'];
if($_SESSION['cuenta_paypal'] != ''){$email_ins = $_SESSION['cuenta_paypal'];}
else{$email_ins = $_SESSION['email'];}
if ( $pagina != 'gestion_publicidad' || $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
		$file = limpiaTexto3($_FILES['nueva_publi']['name']);
		$ruta_local = limpiaTexto3($_FILES['nueva_publi']['tmp_name']);
		$posicion = limpiaTexto($_POST['posicion']);
		$url = limpiaTexto3($_POST['url']);
		$fecha = obten_fechahora();
		//PARA LA VERSION CLON OBTENER EL PRECIO Y NO CALCULAR
		$destino = '../../../fotos_publicidad/'.$bd.$id_liga.$id_division.'/';
		$tipo = strtolower(strstr($file, '.'));//obtengo el tipo	$destino = '../../logos/';
		$devolucion = 0;
		if(!is_dir($destino)){
			mkdir($destino,0777);
		}
		$imagen_temp = 'temp/'.$posicion.'_temp'.$tipo;
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
			$thumb->save_jpg($destino,$posicion);//donde lo guardo en JPG
			//$thumb->save_png("../../fotos_noticias/", $id_noticia.$cont);
		}
		unlink($imagen_temp);//borro
		
		//crear pago_web
		$pos = substr($posicion,0,1);
		//$p_base = obten_precio_publicidad(settype($pos,"integer")) - ($num_division*2);
		$p_base = obten_precio_publicidad(intval($pos));
		$p_base = $p_base - ($num_division*2);
		$p_final = obten_plus_publicidad($p_base,$tipo_pago);
		//$pago_web = new Pago_web('',$bd,$id_liga,$id_division,'P',$posicion,$p_final,'P','N',cuenta_admin(),$email,obten_fechahora(),fecha_suma($fecha,'','',3,'','',''),'','','');
		//DE MOMENTO SIN LIMITE
		$pago_web = new Pago_web('',$bd,$id_liga,$id_division,'P',$posicion,$p_final,'P','N',cuenta_admin(),$email_ins,$id_usuario,$fecha,'','','','E');
		$pago_web->insertar();
		$id_pago_web = obten_consultaUnCampo('unicas','id_pago_web','pago_web','bd',$bd,'liga',$id_liga,'division',$id_division,'tipo','P','ORDER BY id_pago_web DESC');
		//$id_pago_web = obtenPagoWeb($bd,$id_liga,$id_division,'P');
		//crear publicidad 
		$publicidad = new Publicidad("",$id_usuario,$id_pago_web,"","",$id_liga,$id_division,$posicion,$url,0,$fecha,"","",'N',0);
		$publicidad->insertar();
		//si es liga premium de prueba gratis marca pagos 
		$prueba_gratis = obten_consultaUnCampo('unicas_liga','COUNT(usuario)','prueba_gratis','usuario',$id_usuario,'bd',$bd,'liga',$id_liga,'','','');
		if($prueba_gratis > 0){
			realiza_updateGeneral('unicas','pago_web','pagado = "S",modo_pago = "G", fecha_limite = "'.$fecha.'"','liga',$id_liga,'bd',$bd,'usuario',$id_usuario,'','','','','');
		}
		unset($pago_web,$publicidad,$thumb);
		echo $devolucion;
	}
}//fin else

?>