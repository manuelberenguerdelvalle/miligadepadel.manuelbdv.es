<?php
include_once ("../../../class/thumbnail.php");
include_once ("../../funciones/f_general.php");
session_start();
if ( $_SESSION['pagina'] == 'gestion_liga'){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
			$destino = 'temp/';
			if(!is_dir($destino)){
				mkdir($destino,0777);
			}
			$nombre = 'imagen1';
			$imagen=utf8_decode(strtolower(trim($_FILES[$nombre]['name'])));
			if($imagen != ''){
				if(formatoImagen($imagen)){
					//$tipo = strtolower(strstr($imagen, '.'));//obtengo el tipo
					$temp = $_FILES[$nombre]["tmp_name"];
					$imagen_orig = $destino.$_SESSION['bd'].$_SESSION['id_liga'].'.jpg';//destino temporal
					move_uploaded_file($temp,$imagen_orig);//guardo destino temporal
					$thumb = new Thumbnail($imagen_orig);//creo thumbnail
					
						if($thumb->error) {
							echo $thumb->error;
						}
						else {
							$imagen = getimagesize($imagen_orig);
							$ancho = $imagen[0];          
							$alto = $imagen[1];
							
							if($ancho == $alto){
								$thumb->resize(500,500);
							}
							else if($ancho != $alto && $ancho > $alto){
								$thumb->resize(500,400);
							}
							else{
								$thumb->resize(400,500);
							}
							$thumb->save_jpg($destino, 'temp_'.$_SESSION['bd'].$_SESSION['id_liga']);//donde lo guardo en JPG
							//$thumb->save_jpg("../../fotos_noticias/", $id_noticia.$cont);//donde lo guardo en JPG
						}
					unlink($imagen_orig);//borro
					echo '1';
				}//fin if formato
			}//comprobar imagenes
			
	}
}//fin gestion pagina
// If not a POST request, display page below:

?>