<?php
function mostrar_columna($id_publi,$url,$url_vacia){//muestra la columna izq o der
	for($i=0; $i<5; $i++){
		//le sumo 1, por la diferencia de empezar en 0 y el count que comienza con 1
		if( ($i+1) <= count($id_publi) ){//p a mostrar
			$ruta = '../../../fotos_publicidad/gratis/'.$id_publi[$i].'.jpg';
			if(strpos($url[$j],'http://') === false){
				echo '<div class="fondo_publi"><a href="http://'.$url[$i].'" target="_blank"><img class="imagen_redondeada" src="'.$ruta.'" /></a></div>';
			}
			else{
				echo '<div class="fondo_publi"><a href="'.$url[$i].'" target="_blank"><img class="imagen_redondeada" src="'.$ruta.'" /></a></div>';
			}
		}
		else{//p vacia
			//echo '<div class="fondo_publi_vacio"><a href="'.$url_vacia.'" target="_blank"><img class="imagenPubliVacia" src="../../../images/p_vacia.gif" /></a></div>';
			echo '<div class="fondo_publi_vacio"><a href="../../usuario/registro/registrar_patrocinador.php" target="_blank"><img class="imagenPubliVacia" src="../../../images/p_vacia.gif" /></a></div>';
		}
	}//fin de for
}
function mostrar_columna_pago($bd,$liga,$division,$url,$posicion_publi,$letra,$url_vacia){//muestra la columna izq o der
	for($i=1; $i<=5; $i++){//para cada posicion de p
		$encontrado = false;
		$pos_aux = $i.$letra;
		for($j=0; $j<count($posicion_publi); $j++){//recorro array de p
			if($posicion_publi[$j] == $pos_aux){//p a mostrar
				$encontrado = true;
				$ruta = '../../../fotos_publicidad/'.$bd.$liga.$division.'/'.$pos_aux.'.jpg';
				if(strpos($url[$j],'http://') === false){
					echo '<div class="fondo_publi"><a href="http://'.$url[$j].'" target="_blank"><img class="imagen_redondeada" src="'.$ruta.'" /></a></div>';
				}
				else{
					echo '<div class="fondo_publi"><a href="'.$url[$j].'" target="_blank"><img class="imagen_redondeada" src="'.$ruta.'" /></a></div>';
				}
				break;
			}
		}//fin for j
		if(!$encontrado){//si no lo he encontrado muestro vacio
			echo '<div class="fondo_publi_vacio">';
			?><a href="#" target="_self" onclick="crear_publi('<?php echo $pos_aux;?>');$('#content_popup').bPopup();"><?php
            //echo 'hola</a></div>';
			//echo '<img class="imagenPubliVacia" src="../../../images/copa.png" /></a></div>';
			echo '<img class="imagenPubliVacia" src="../../../images/p_vacia.gif" /></a></div>';
		}
	}//fin de for i
}
?>