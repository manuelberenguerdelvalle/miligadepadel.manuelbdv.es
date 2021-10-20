<?php
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_desplegables.php");
include_once ("../../../class/usuario_publi.php");
include_once ("../../../class/mysql.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$usuario_publi = unserialize($_SESSION['usuario_publi']);
$id_usuario_publi = $usuario_publi->getValor('id_usuario_publi');
if ( $pagina != 'gestion_publicidad' || $opcion != 0){
	header ("Location: ../cerrar_sesion.php");
}
if(isset($_GET['id_publicidad'])){$id_publicidad_gratis = $_GET['id_publicidad'];}
if(isset($_GET['provincia'])){$provincia = $_GET['provincia'];}
if(isset($_GET['ciudad'])){
	$ciudad = $_GET['ciudad'];
	$_SESSION['ciudad_actual'] = $ciudad;//lo necesito para ciudades_similares.php
}
if(isset($_GET['estado'])){$estado = $_GET['estado'];}
if(isset($_GET['url'])){$url = $_GET['url'];}
header("Content-Type: text/html;charset=ISO-8859-1");
$provincias = array();
$provincias = obten_localizacionGratisDistintasBds(numero_de_BDligas(),'provincia','liga','pais','ESP');

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_publicidad.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_publicidad.js" type="text/javascript"></script>
</head>
<body>
<div class="horizontal">&nbsp;</div>
<div class="caja1">
<?php
$db = new MySQL('unicas');//LIGA PADEL
$consulta = $db->consulta("SELECT id_publicidad_gratis,provincia,ciudad,fecha,fecha_fin,contador,estado,url FROM publicidad_gratis WHERE usuario_publi = '$id_usuario_publi' AND pagado = 'S' ORDER BY fecha_fin,provincia,ciudad; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
	if($id_publicidad_gratis == $resultados['id_publicidad_gratis']){$seleccionado = 'checked';}
	else{$seleccionado = '';}
?>
	<div class="linea">
    	<div class="radio"><input name="id_publicidad" onClick="cambiar_publicidad(<?php echo $resultados['id_publicidad_gratis'];?>,<?php echo $resultados['provincia'];?>,<?php echo $resultados['ciudad'];?>,<?php echo $resultados['estado'];?>,'<?php echo $resultados['url']; ?>')" type="radio" <?php echo $seleccionado;?> ></div> 
    	<div class="datos">
        	<label class="datos_linea"><?php echo substr(obtenLocalizacion(2,$resultados['provincia']),0,25);?></label>
        	<label class="datos_linea"><?php echo substr(obtenLocalizacion(3,$resultados['ciudad']),0,25);?></label>
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo 'Inicio: '.vuelta_fecha($resultados['fecha']);?></label>
        	<label class="datos_linea"><?php echo 'Fin: '.vuelta_fecha($resultados['fecha_fin']);?></label>
        </div>
    	<div class="datos3">
        	<label class="datos_linea"><?php echo 'Publicados: '.$resultados['contador'];?></label>
        	<label class="datos_linea"><?php echo 'Estado: '.obten_estadoPubliGratis($resultados['estado']);?></label>	
        </div>    
	</div>
<?php
}//fin while
?>
</div><!-- fin caja1 -->
<?php
if($id_publicidad_gratis != ''){
	//echo $id_publicidad_gratis.'-'.$provincia.'-'.$ciudad.'-'.$estado.'-'.$url;
	$imagen = '../../../fotos_publicidad/gratis/'.$id_publicidad_gratis.'.jpg';
?>
<input type="hidden" id="id_publicidad_gratis" value="<?php echo $id_publicidad_gratis;?>">
<div class="caja2">
	<?php if($estado == 1){ ?>
	<div class="horizontal"><label class="titulo">Actualmente no hay ligas disponibles en su ciudad elegida. No se preocupe, se le abonarán todos los días que ha estado sin publicitarse en cuanto hayan de nuevo ligas en su ciudad elegida o escoja una nueva ciudad (cambio no reversible).</label></div>
    <?php } ?>
    <div class="horizontal">&nbsp;</div>
    <div class="columna1">
		<?php if($estado == 1){ ?>
        <div class="cuadroTexto">Provincia:</div>
        <div class="cuadroTexto">Ciudad:</div>
		<?php } ?>
        <div class="cuadroTexto">Url:</div>
    </div>
    <div id="flotante"></div>
    <div class="columna2">
		<?php if($estado == 1){ ?>
        <span class="cuadroInputs">
            <select name="provincia" id="provincia" class="input_select_liga" onChange="lista('provincia',0)" >
                 <option value="">--Elige--</option>
                 <?php
                 echo '<option  value="'.$provincia.'">'.obtenLocalizacion(2,$provincia).'</option>';
                 ?>
              </select>
         </span>
         <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="input_select_liga" onChange="lista('ciudad',1)" ></select></span>
		<?php } ?>
         <span class="cuadroInputs"><input type="text" name="url" id="url" class="input_url" value="<?php echo $url;?>" maxlength="200"  onchange="limpiaDireccionWeb('url',2,'')" ></span>
    </div> 
    <div class="columna3">
		<?php if($estado == 1){ ?>
        <div class="cuadroComentario"><span id="provinciaCom">* Intruduzca una provincia</span></div>
        <div class="cuadroComentario"><span id="ciudadCom">* Introduzca una ciudad</span></div>
		<?php } ?>
        <div class="cuadroComentario"><span id="urlCom">Introduzca una url válida o déjela vacía</span></div>
    </div>
    <div class="columna4">
        <div class="fondo_vista_previa"><img id="vista_previa" src="<?php echo $imagen;?>" class="imagen_redondeada"></div>
        <div class="horizontal">&nbsp;</div>
        <div class="horizontal"><div class="titulo"><form enctype="multipart/form-data" id="formulario" action="#" method="post" name="formulario"><input type="file" name="nueva_publi" id="nueva_publi" class="nueva_publi" ></form></div></div>
        <div class="cuadroComentario"><span id="nueva_publiCom">* El formato es diferente a |.jpg| |.jpeg| |.png| |.bmp| o el tama&ntilde;o de la imagen es superior a 1 Mb.</span></div>
    </div>
</div><!-- fin caja2 -->
<div class="horizontal"><input type="button" id="btn_enviar" value="Modificar" class="boton" /></form></div>
<?php
}
?>
<div id="respuesta" class="horizontal"></div>
</body>
</html>