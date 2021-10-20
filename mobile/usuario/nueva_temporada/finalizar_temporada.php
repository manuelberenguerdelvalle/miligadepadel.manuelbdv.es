<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_temporada'){
	header ("Location: ../cerrar_sesion.php");
}
//$usuario = unserialize($_SESSION['usuario']);
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$opcion = $_SESSION['opcion'];
if($opcion != 2){//generar temporada
	header ("Location: ../cerrar_sesion.php");
}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/generar_temporada.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="javascript/finalizar_temporada.js" type="text/javascript"></script>
</head>
<body>
<?php
if($liga->getValor("bloqueo") == 'N' && $_SESSION['liga_finalizada'] == ''){//numero de partidos activos para liga
?>
    <div class="horizontal">&nbsp;</div>
    <div class="caja_pago">
        <img src="../../../images/ok.png" />
        <label>
        	Esta Liga ha llegado a su fin, todas las divisiones se encuentran finalizadas en este momento, si lo desea puede marcarla como finalizada y la liga va a quedar archivada e inaccesible. <br> &nbsp;
        </label>
        <br>
        <input type="button" class="boton" value="Finalizar" onClick="finalizar(<?php echo $id_liga;?>)">
        <div class="horizontal"><div id="respuesta2"></div></div>
    </div>
    <div id="respuesta" class="horizontal"></div>
<?php
}//fin de if partidos activos
?>
</body>
</html>
