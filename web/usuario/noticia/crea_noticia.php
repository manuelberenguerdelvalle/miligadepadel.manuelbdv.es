<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/thumbnail.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$id_division = $_SESSION['id_division'];
$bd = $_SESSION['bd'];
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
if($pagina != 'gestion_noticia' && $opcion != 1){
	header ("Location: ../cerrar_sesion.php");
}
else{
	$texto = 'La noticia se ha insertado correctamente.';
	$descripcion = utf8_decode(limpiaTexto(trim($_POST['descripcion'])));
	$resumen_noticia = utf8_decode('Administrador');
	$fecha_noticia = obten_fechahora();
	$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion,$fecha_noticia,'');
	$noticia->insertar();
	$id_noticia = obten_consultaUnCampo('session','id_noticia','noticia','liga',$id_liga,'division',$id_division,'resumen','Administrador','','','ORDER BY id_noticia DESC');
	$imagenes = '';
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest' ){
		$cont = 1;//contador real del nombre
		$destino = '../../../fotos_noticias/'.$bd.$id_liga.$id_division.'/';
		if(!is_dir($destino)){
			mkdir($destino,0777);
		}
		for($i=1; $i<5; $i++){
			$nombre = 'imagen'.$i;
			$imagen=utf8_decode(strtolower(trim($_FILES[$nombre]['name'])));
			if($imagen != ''){
				if(formatoImagen($imagen)){
					$tipo = strtolower(strstr($imagen, '.'));//obtengo el tipo
					$temp = $_FILES[$nombre]["tmp_name"];
					$imagen_temp = 'temp/'.$id_noticia.$cont.$tipo;//destino temporal
					move_uploaded_file($temp,$imagen_temp);//guardo destino temporal
					$thumb = new Thumbnail($imagen_temp);//creo thumbnail
					if($thumb->error) {
						echo $thumb->error;
					} else {
						$imagen = getimagesize($imagen_temp);
						$ancho = $imagen[0];          
  						$alto = $imagen[1];
						
						if($ancho == $alto){
							$thumb->resize(800,800);
						}
						else if($ancho != $alto && $ancho > $alto){
							$thumb->resize(800,600);
						}
						else{
							$thumb->resize(600,800);
						}
						$thumb->save_jpg($destino, $id_noticia.$cont);//donde lo guardo en JPG
						//$thumb->save_jpg("../../fotos_noticias/", $id_noticia.$cont);//donde lo guardo en JPG
					}
					$imagenes .= $id_noticia.$cont.'.jpg;';//para la noticia
					unlink($imagen_temp);//borro
					$cont++;
				}
			}//fin if
		}//fin for
		$noticia->setValor('id_noticia',$id_noticia);
		$noticia->setValor('imagenes',$imagenes);
		$noticia->modificar();
	}//comprobar imagenes
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>