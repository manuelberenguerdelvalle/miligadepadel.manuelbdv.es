<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if($pagina != 'gestion_noticia' || $opcion != 1){
	header ("Location: ../cerrar_sesion.php");
}
$liga = unserialize($_SESSION['liga']);
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
//SE GUARDA EN SESSION
$_SESSION['id_liga'] = $liga->getValor('id_liga');
$_SESSION['tipo_pago'] = $tipo_pago;
$_SESSION['id_division'] = $division->getValor('id_division');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_noticia.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/insertar_noticia.js" type="text/javascript"></script>

</head>
<body>
<input type="hidden" name="tipo_pago" id="tipo_pago" value="<?php echo $tipo_pago;?>">
<div class="horizontal">&nbsp;</div>
<div class="horizontal"><div><b>Inserta una noticia:</b></div></div>
<div class="horizontal">&nbsp;</div>
<div class="columna1">
    <div class="cuadroTextoDes">Texto:</div>
    <div class="cuadroTexto">Imagen 1:</div>
    <?php if($tipo_pago > 0){ ?>
    <div class="cuadroTexto">Imagen 2:</div>
    <div class="cuadroTexto">Imagen 3:</div>
    <div class="cuadroTexto">Imagen 4:</div>
     <?php } ?>    
</div>
<div id="flotante"></div>
<div class="columna2">
	<span><form id="formulario" enctype="multipart/form-data" action="crear_division.php" method="post" name="formulario"></span>
    <span class="cuadroInputsDes"><textarea name="descripcion" id="descripcion" rows="6" cols="15" class="input_area"  onkeypress="return tecla_direccion(event)" onBlur="limpiaDireccion('descripcion',0,1)"  ></textarea></span>   
    <span class="cuadroInputs"><input class="file" value="hola" type="file" name="imagen1" id="imagen1" onBlur="compruebaImagen('imagen1',1,0)" ></span>
    <?php if($tipo_pago > 0){ ?>
    <span class="cuadroInputs"><input class="file" type="file" name="imagen2" id="imagen2" onBlur="compruebaImagen('imagen2',2,0)" ></span>
    <span class="cuadroInputs"><input class="file" type="file" name="imagen3" id="imagen3" onBlur="compruebaImagen('imagen3',3,0)" ></span>
    <span class="cuadroInputs"><input class="file" type="file" name="imagen4" id="imagen4" onBlur="compruebaImagen('imagen4',4,0)" ></span>
 <?php } ?>    
</div>
<div class="columna3">
    <div class="cuadroComentarioDes"><span id="descripcionCom">*</span></div>
    <div class="cuadroComentario"><span id="imagen1Com">*</span></div>
    <div class="cuadroComentario"><span id="imagen2Com">*</span></div>
    <div class="cuadroComentario"><span id="imagen3Com">*</span></div>
    <div class="cuadroComentario"><span id="imagen4Com">*</span></div>
</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Insertar"  class="boton" /></form></div>
<div id="respuesta" class="horizontal"><!--<div style="margin-left:31%; margin-top:0.5%;"><img src="../../images/28.gif" width="50"></div><div style="margin-left:15%;">Por favor espere, este proceso puede tardar entre 30 segundos o varios minutos, <br>dependiendo de la calidad de la imagen y su conexin a internet, gracias.</div>--></div>
</body>
</html>