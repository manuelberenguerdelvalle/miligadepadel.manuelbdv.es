<?php
session_start();
$pagina = $_SESSION['pagina'];
$id_liga = $_SESSION['id_liga'];
if($pagina != 'gestion_liga'){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
	$file=$_FILES['archivo']['name'];
	$ruta_local = $_FILES['archivo']['tmp_name'];
	$tipo = strstr($file, '.');
	$destino = '../../logos/';
	$devolucion = 1;
	if(!is_dir($destino)){
		mkdir($destino,0777);
	}
	if(file_exists($destino.$id_liga.'.jpg')){
		rename($destino.$id_liga.'.jpg', $destino.$id_liga.'-1.jpg');
		$antiguo_tipo = '.jpg';
	}
	if(file_exists($destino.$id_liga.'.jpeg')){
		rename($destino.$id_liga.'.jpeg', $destino.$id_liga.'-1.jpeg');
		$antiguo_tipo = '.jpeg';
	}
	if(file_exists($destino.$id_liga.'.png')){
		rename($destino.$id_liga.'.png', $destino.$id_liga.'-1.png');
		$antiguo_tipo = '.png';
	}
	if(file_exists($destino.$id_liga.'.bmp')){
		rename($destino.$id_liga.'.bmp', $destino.$id_liga.'-1.bmp');
		$antiguo_tipo = '.bmp';
	}
	if($file && move_uploaded_file($ruta_local,$destino.$id_liga.$tipo) ){// se monta el archivo 
		$devolucion = 0;
	}
	$imagen = getimagesize($destino.$id_liga.$tipo);    //Sacamos la información
  	$ancho = $imagen[0];              //Ancho
  	$alto = $imagen[1];               //Alto
	if($alto != $ancho){// si es diferente tamaño contemplo una diferencia de 10px
		if($alto+10 < $ancho || $alto-10 > $ancho){
			$devolucion = 1;
		}
		else if($ancho+10 < $alto || $ancho-10 > $alto){
			$devolucion = 1;
		}
		else{
			$devolucion = 0;
		}
	}
	else{
		$devolucion = 0;
	}
	if($devolucion == 1){//si la foto hay mucha diferencia de pixels se elimina
		if(file_exists($destino.$id_liga.'.jpg')){
			unlink($destino.$id_liga.'.jpg');
		}
		if(file_exists($destino.$id_liga.'.jpeg')){
			unlink($destino.$id_liga.'.jpeg');
		}
		if(file_exists($destino.$id_liga.'.png')){
			unlink($destino.$id_liga.'.png');
		}
		if(file_exists($destino.$id_liga.'.bmp')){
			unlink($destino.$id_liga.'.bmp');
		}
		rename($destino.$id_liga.'-1'.$antiguo_tipo, $destino.$id_liga.$antiguo_tipo);
	}
	else{// si esta correcto borro la antigua
		if(file_exists($destino.$id_liga.'-1.jpg')){
			unlink($destino.$id_liga.'-1.jpg');
		}
		if(file_exists($destino.$id_liga.'-1.jpeg')){
			unlink($destino.$id_liga.'-1.jpeg');
		}
		if(file_exists($destino.$id_liga.'-1.png')){
			unlink($destino.$id_liga.'-1.png');
		}
		if(file_exists($destino.$id_liga.'-1.bmp')){
			unlink($destino.$id_liga.'-1.bmp');
		}
	}
	echo $devolucion;
}

?>