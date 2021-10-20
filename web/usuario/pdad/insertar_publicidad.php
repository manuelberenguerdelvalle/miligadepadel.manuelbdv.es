<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if($pagina != 'gestion_publicidad' || $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$bd = $usuario->getValor('bd');
$email = $usuario->getValor('email');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_division = $division->getValor('num_division');
//GUARDO EN SESSION
$_SESSION['id_liga'] = $id_liga;
$_SESSION['tipo_pago'] = $tipo_pago;
$_SESSION['id_division'] = $id_division;
$_SESSION['num_division'] = $division->getValor('num_division');
$num_publicidad = obten_consultaUnCampo('session','COUNT(id_publicidad)','publicidad','usuario_publi',$email,'liga',$id_liga,'division',$id_division,'','','');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_publicidad.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/insertar_publicidad.js" type="text/javascript"></script>
</head>
<body>
<div class="cont_principal">
<?php
if($tipo_pago > 0 && $num_publicidad < 10){
?>
	<div class="horizontal">&nbsp;</div>
    <div class="horizontal">&nbsp;</div>
	<div class="caja1">
<?php
for($i=1; $i<=5; $i++){//FOR QUE GENERA EL CUADRO DE PUBLICIDADES
	$p_base = obten_precio_publicidad($i) - ($num_division*2);
	$p_final = obten_plus_publicidad($p_base,$tipo_pago);
	$img_izq = '../../../fotos_publicidad/'.$bd.$id_liga.$id_division.'/'.$i.'I.jpg';
	$img_der = '../../../fotos_publicidad/'.$bd.$id_liga.$id_division.'/'.$i.'D.jpg';
	//echo $img_izq;
	$valor_izq = 'disabled';
	$valor_der = 'disabled';
	if(!file_exists($img_izq)){
		$img_izq = '../../../images/publicidad_libre.jpg';
		$valor_izq = 'value="'.$i.'I"';
	}
	if(!file_exists($img_der)){
		$img_der = '../../../images/publicidad_libre.jpg';
		$valor_der = 'value="'.$i.'D"';
	}
?>
    	<div class="cuadro_publi">
        	<div class="fondo_publi"><img src="<?php echo $img_izq; ?>" class="imagen_redondeada"></div>
        </div>
        <div class="cuadro_precio"><input name="<?php echo 'precio'.$i.'I'; ?>" type="text" class="input_text" value="<?php echo $p_final.' &euro;'; ?>" disabled></div>
        <div class="cuadro_seleccion_izq"><input name="posicion" type="radio" class="input_text" <?php echo $valor_izq; ?> ></div>
        <div class="cuadro_seleccion_der"><input name="posicion" type="radio" class="input_text" <?php echo $valor_der; ?> ></div>
        <div class="cuadro_precio"><input name="<?php echo 'precio'.$i.'D'; ?>" type="text" class="input_text" value="<?php echo $p_final.' &euro;'; ?>" disabled></div>
        <div class="cuadro_publi">
        	<div class="fondo_publi"><img src="<?php echo $img_der; ?>" class="imagen_redondeada"></div>
        </div>
<?php
}//FIN FOR
?>
    </div>
    <div class="caja2">
    	<div class="fondo_vista_previa"><img id="vista_previa" src="../../../images/publicidad_libre.jpg" class="imagen_redondeada"></div>
        <div class="horizontal">&nbsp;</div>
        <div class="horizontal"><div class="titulo">Url:&nbsp;<input type="text" id="url" name="url" value="Copiar y pegar el enlace" onFocus="if(this.value=='Copiar y pegar el enlace')this.value=''" class="input_text_liga" maxlength="200"></div></div>
        <div class="horizontal">&nbsp;</div>
        <div class="horizontal"><div class="titulo"><form enctype="multipart/form-data" id="formulario" action="#" method="post" name="formulario"><input type="file" name="nueva_publi" id="nueva_publi" ></form></div></div>
        <div class="cuadroComentario"><span id="nueva_publiCom">* El formato es diferente a |.jpg| |.jpeg| |.png| |.bmp| o el tama&ntilde;o de la imagen es superior a 10 Mb.</span></div>
        <div class="horizontal">
        	<?php
			if(obten_consultaUnCampo('unicas','pagado','pago_web','liga',$id_liga,'tipo','L','','','','','') == 'S'){
				if($num_division == 1){//division 1
					echo '<input type="button" id="btn_enviar" value="Insertar" class="boton" />';
				}
				else{//resto divisiones
					if(obten_consultaUnCampo('unicas','pagado','pago_web','liga',$id_liga,'division',$id_division,'tipo','D','','','') == 'S'){//division pagada
						echo '<input type="button" id="btn_enviar" value="Insertar" class="boton" />';
					}
					else{
						echo '<span  class="error">&nbsp;&nbsp;&nbsp;&nbsp;Para Insertar Publicidad es necesario que la Divisi&oacute;n est&eacute; Confirmada.</span>';
					}
				}
			}//fin if liga pagada
			else{
				echo '<span  class="error">&nbsp;&nbsp;&nbsp;&nbsp;Para Insertar Publicidad es necesario que la Liga est&eacute; Confirmada.</span>';
			}
			?>
        </div>
        <div id="respuesta" class="horizontal"></div>
    </div>	
<?php
}//FIN IF TIPO PAGO y NUM PUBLICIDAD
?>
</div>
</body>
</html>