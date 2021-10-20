<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/usuario.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_liga'){
	header ("Location: ../cerrar_sesion.php");
}

$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');
$bd = $usuario->getValor('bd');
$opcion = $_SESSION['opcion'];
$numPartidosTotales = obten_numMaxPartidos($id_liga);
$alerta_prueba_gratis = obten_consultaUnCampo('unicas_liga','COUNT(usuario)','prueba_gratis','usuario',$id_usuario,'bd',$bd,'','','','','');
if($opcion != 3){//generar temporada
	header ("Location: ../cerrar_sesion.php");
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/activar_liga.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="javascript/activar_liga.js" type="text/javascript"></script>
</head>
<body>
<?php 
if($alerta_prueba_gratis == 0 && $numPartidosTotales == 0){
?>
    <div class="horizontal">&nbsp;</div>
    <div class="horizontal">&nbsp;</div>
    <div class="caja_pago">
        <img src="../../../images/ok.png" />
        <label>
        	¿Deseas activar esta Liga como Premium? Podrás tener acceso a todos los servicios premium totalmente gratis.<br> &nbsp;
        </label>
        <br>
        <input type="button" class="boton" value="Activar" onClick="activar(<?php echo $id_liga;?>)">
        <div class="horizontal"><div id="respuesta2"></div></div>
    </div>
    <div id="respuesta" class="horizontal"></div>
<?php
}

?>
</body>
</html>
