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
if($opcion != 0){//generar temporada
	header ("Location: ../cerrar_sesion.php");
}
if(obten_numPartidosActivosLiga($id_liga) == 0){//numero de partidos activos para liga
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/generar_temporada.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="javascript/generar_temporada.js" type="text/javascript"></script>
</head>
<body>
<?php
//AQUI COMPROBAR QUE EL ID_LIGA NO ESTA EN LA TABLA NUEVA_TEMPORADA (NUEVA) PARA QUE NO SE MUESTRE AL CREAR NUEVA TEMPORADA
?>
    <div class="horizontal">&nbsp;</div>
    <div class="caja_pago">
        <img src="../../../images/ok.png" />
        <label>
        	Esta Liga ha llegado a su fin, todas las divisiones se encuentran finalizadas en este momento, si lo desea puede generar una nueva temporada en la que se notificar&aacute; a los jugadores si desean continuar con la Nueva Temporada actualizando las siguientes caracter&iacute;sticas:<br>-Ascensos/Descensos<br><br>Precios de Inscripci&oacute;n:
        </label>
        <div class="cuadroResultado">
        	<form id="formulario" action="#" method="post" name="formulario">
			<?php
			//INCLUYO TODAS LAS DIVISIONES YA QUE SI HA LLEGADO AQUI ES PORQUE ESTAN TODOS LOS PARTIDOS DISPUTADOS
            $db = new MySQL('session');//LIGA PADEL
            $consulta = $db->consulta("SELECT id_division,precio,num_division FROM division WHERE liga = '".$id_liga."' AND bloqueo = 'N' ORDER BY num_division ; ");
            while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
                echo '<div class="linea">Divisi&oacute;n '.$resultados["num_division"].':&nbsp;<input type="text" class="input_precio" name="d'.$resultados["id_division"].'" id="'.$resultados["id_division"].'" value="'.$resultados["precio"].'" >&nbsp;&euro;</div>';
            }
            ?>
            </form>
        </div>
         <div class="horizontal">&nbsp;</div>
        <input type="button" class="boton" id="btn_enviar" value="Generar">
        <div class="horizontal"><div id="respuesta2"></div></div>
    </div>
    <div id="respuesta" class="horizontal"></div>
</body>
</html>
<?php
}//fin de if partidos activos
?>