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
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$movimientos = $liga->getValor("movimientos");
$opcion = $_SESSION['opcion'];
if($opcion != 1){//modificacion
	header ("Location: ../cerrar_sesion.php");
}
if(obten_numPartidosActivosLiga($id_liga) == 0){//numero de partidos activos para liga
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/resultados_temporada.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/resultados_temporada.js" type="text/javascript"></script>
<style>
.cuadro_general{
	width:99% !important;
	margin-top:1%;
	overflow-y: auto;
	/*border:1px black solid;*/
	float:left;
}
.posicion_cab{
	width:13% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:center;
	color:#003;
	background-color:#CCC;
	border-top:2px #CCC solid;
	border-bottom:2px #CCC solid;
	border-left:2px #CCC solid;
	float:left;
}
.movimiento_cab{
	width:4% !important;
	padding-top:0.5%;
	background-color:#CCC;
	border-top:2px #CCC solid;
	border-bottom:2px #CCC solid;
	float:left;
}
.jugador_cab{
	width:33% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:left;
	color:#003;
	background-color:#CCC;
	border-top:2px #CCC solid;
	border-bottom:2px #CCC solid;
	float:left;
}
.respuesta_cab{
	width:12% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:center;
	color:#003;
	background-color:#CCC;
	border-top:2px #CCC solid;
	border-bottom:2px #CCC solid;
	float:left;
}
.opcion_cab{
	width:8% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:left;
	color:#003;
	background-color:#CCC;
	border-top:2px #CCC solid;
	border-bottom:2px #CCC solid;
	border-right:2px #CCC solid;
	float:left;
}
.division_cab{
	width:97.5% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:left;
	padding-left:1.5%;
	color:#003;
	background-color: #d2dbf9;
	border-bottom:2px #CCC solid;
	border-right:2px #CCC solid;
	border-left:2px #CCC solid;
	float:left;
}
.caja{
	width:99% !important;
	border-bottom:2px #CCC solid;
	border-right:2px #CCC solid;
	border-left:2px #CCC solid;
	float:left;
}
.caja:hover{
	background-color: #f5effb;
	font-weight:bold;
}
.caja_asc{
	width:99% !important;
	background-color:#e0fcd2;
	border-bottom:2px #CCC solid;
	border-right:2px #CCC solid;
	border-left:2px #CCC solid;
	float:left;
}
.caja_asc:hover{
	font-weight:bold;
}
.caja_desc{
	width:99% !important;
	background-color:#fcdbd2;
	border-bottom:2px #CCC solid;
	border-right:2px #CCC solid;
	border-left:2px #CCC solid;
	float:left;
}
.caja_desc:hover{
	font-weight:bold;
}
.posicion{
	width:9% !important;
	max-width:9% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:center;
	color:#003;
	float:left;
}
.movimiento{
	width:6% !important;
	max-width:9% !important;
	padding-top:0.5%;
	text-align:center;
	float:left;
}
.foto_mov{
	width:100%;
}
.jugador{
	width:33% !important;
	max-width:35% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:left;
	color:#003;
	float:left;
}
.respuesta{
	width:10% !important;
	max-width:12% !important;
	padding-top:0.5%;
	font-size:80%;
	text-align:center;
	color:#003;
	float:left;
}
.opcion{
	width:8% !important;
	max-width:9% !important;
	padding-top:0.2%;
	text-align:center;
	float:left;
}
.foto_eli{
	width:100%;
}
.horizontal {
	width:99%;
	font-size:80%;
	color:#003;
	float:left;
}
.boton {
	background-color: #34495e;
	border-radius:10px;
	border:3px #34495e solid;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	color:#FFF;
	font-size:100%;
	font-weight:bold;
	margin-left:5%;
	float:left;
}
.cargando {
	width:15%;
	margin-left:30%;
	float:left;
}
.texto {
	float:left;
	margin-left:1%;
	margin-top:1%;
}
</style>
</head>
<body>
    <div class="horizontal">A continuaci&oacute;n se muestra como quedar&aacute; la Nueva Liga, con ascensos / descensos generados y la respuesta de los equipos si desean o no continuar.<br>*Si elimina un equipo, los equipos que est&aacute;n por debajo subir&aacute;n una posici&oacute;n.</div>
    <div class="cuadro_general">
    	<div class="posicion_cab">POS</div>
        <!--<div class="movimiento_cab">&nbsp;</div>-->
        <div class="jugador_cab">JUGADOR 1</div>
        <div class="jugador_cab">JUGADOR 2</div>
        <div class="respuesta_cab">RES</div>
        <div class="opcion_cab">OP</div>
        <?php
			$cont_pos = 0;
			$division_ant = 0;
			$num_division = 0;
			$num_div_real = obten_consultaUnCampo('session','COUNT(DISTINCT(division))','nueva_temporada','liga',$id_liga,'','','','','','','');
			$num_resp_no = 0;
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM nueva_temporada WHERE liga = '".$id_liga."' ORDER BY posicion ; ");
			 while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				if($resultados["division"] != $division_ant){
					$num_equipos = obten_consultaUnCampo('session','COUNT(id_nueva_temporada)','nueva_temporada','liga',$id_liga,'division',$resultados["division"],'','','','','');
					$cont_pos = 1;
					$num_division++;
					echo '<div class="division_cab">Divisi&oacute;n '.$num_division.'</div>';
				}
				
				if(($cont_pos + $movimientos) > $num_equipos && $num_division != $num_div_real){//para ascensos
					echo '<div class="caja_asc">';
				}
				else if($cont_pos <= $movimientos && $num_division > 1){//para descensos
					echo '<div class="caja_desc">';
				}
				else{
					echo '<div class="caja">';
				}
				//echo '<div class="caja">';
					echo '<div class="posicion">'.$cont_pos.'</div>';
					if(($cont_pos + $movimientos) > $num_equipos && $num_division != $num_div_real){//para ascensos
						echo '<div class="movimiento"><img class="foto_mov" src="../../../images/asc.png" ></div>';
					}
					else if($cont_pos <= $movimientos && $num_division > 1){//para descensos
						echo '<div class="movimiento"><img class="foto_mov" src="../../../images/desc.png" ></div>';
					}
					else{
						echo '<div class="movimiento">&nbsp;</div>';
					}
					echo '<div class="jugador">'.obtenNombreJugador($resultados["equipo"],"jugador1").'</div>';
					echo '<div class="jugador">'.obtenNombreJugador($resultados["equipo"],"jugador2").'</div>';
					echo '<div class="respuesta">';
					if($resultados["respuesta"] == 'S'){echo 'Si';}
					else if($resultados["respuesta"] == 'N'){echo 'No';}
					else{echo '-';}	
					echo '</div>';
					echo '<div class="opcion"><a href="#" onClick="eliminar('.$resultados["id_nueva_temporada"].','.$resultados["posicion"].','.$resultados["equipo"].')" ><img class="foto_eli" src="../../../images/error.png" ></a></div>';
				echo '</div>';
				$cont_pos++;
				$division_ant = $resultados["division"];
				if($resultados["respuesta"] == 'N'){$num_resp_no++;}//si hay respuestas en no, no dejo crear nueva temporada
			 }
		?>   
    </div> <!--fin div cuadro general-->
    <div class="horizontal">&nbsp;</div>
   <?php
	if($num_resp_no > 0){//no dejo crear nueva tempordad
	?>
    <div class="horizontal"><input type="button" class="boton" value="Crear Nueva Temporada" onClick="swal('Aviso','Debe eliminar manualmente todos los equipos que han respondido que No desean continuar la proxima temporada');"></div>
    <?php
	}
	else if($cont_pos == 0){//no habilito boton si no hat datos
		echo '<div class="horizontal">&nbsp;</div>';
	}
	else{//si dejo crear nueva temporada
	?>
    <div class="horizontal"><input type="button" id="btn_enviar" class="boton" value="Crear Nueva Temporada" onClick="generar(<?php echo $id_liga;?>)"></div>
    <?php }//fin else?>
    <div class="horizontal">&nbsp;</div>
</body>
</html>
<?php
}//fin de if partidos activos
?>