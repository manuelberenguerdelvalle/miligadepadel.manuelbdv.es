<?php
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_liga'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$opcion = $_SESSION['opcion'];
if($opcion == 2){//nueva temporada
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/nueva_temporada.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/nueva_temporada.js" type="text/javascript"></script>
</head>
<body>
    <div class="horizontal">&nbsp;</div>
    <div class="horizontal">&nbsp;</div>
    <div id="respuesta" class="horizontal"></div>
    <div class="caja_pago">
        <img src="../../images/ok.png" />
        <label>
        	Esta Liga ha llegado a su f&iacute;n, todas las divisiones se encuentran finalizadas en este momento, si lo desea puede generar una nueva temportada en la que se crear&aacute; una r&eacute;plica exacta de esta liga antes de comenzar actualizando las siguientes caracter&iacute;sticas:<br>-Liga<br>-Divisiones<br>-Ascensos/Descensos<br>-Equipos<br>-Inscripciones<br>-Pagos<br>-<br>-<br>
        </label>
        <form id="formulario" action="#" method="post" name="formulario">
        <?php
		$db = new MySQL('session');//LIGA PADEL
		$consulta = $db->consulta("SELECT id_division,precio FROM division WHERE liga = '".$id_liga."' AND pagado = 'S' AND bloqueo = 'N' ORDER BY num_division ; ");
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			echo '<input type="text" class="" name="'.$resultados["id_division"].'" id="'.$resultados["id_division"].'" value="'.$resultados["precio"].'" > ';
		}
		?>
        </form>
    </div>
</body>
</html>