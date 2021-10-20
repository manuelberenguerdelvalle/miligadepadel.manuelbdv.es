<?php
include_once ("../../../class/thumbnail.php");
session_start();
$pagina = $_SESSION['pagina'];
$id_liga = $_SESSION['id_liga'];
$bd = $_SESSION['bd'];
if($pagina != 'gestion_liga'){
	header ("Location: ../cerrar_sesion.php");
}
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
	$file=$_FILES['archivo']['name'];
	$ruta_local = $_FILES['archivo']['tmp_name'];
	$destino = '../../../logos/';
	$tipo = strtolower(strstr($file, '.'));//obtengo el tipo	$destino = '../../logos/';
	$devolucion = 0;
	if(!is_dir($destino)){
		mkdir($destino,0777);
	}
	$imagen_temp = $destino.$id_liga.'_temp'.$tipo;
	move_uploaded_file($ruta_local,$imagen_temp);
	$thumb = new Thumbnail($imagen_temp);//creo thumbnail
	if($thumb->error) {
		$devolucion = 1;
		echo $thumb->error;
	} else {
		$imagen = getimagesize($imagen_temp);
		$ancho = $imagen[0];          
  		$alto = $imagen[1];
		if($ancho >= 500){
			$thumb->resize(500,500);
		}
		else{
			$thumb->resize($ancho,$ancho);
		}
		$thumb->save_jpg($destino,$bd.$id_liga);//donde lo guardo en JPG
		//$thumb->save_png("../../fotos_noticias/", $id_noticia.$cont);
	}
	unlink($imagen_temp);//borro
	echo $devolucion;
}

?>