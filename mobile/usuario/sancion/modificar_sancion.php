<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/sancion_jugador.php");
include_once ("../../../class/sancion_equipo.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if($pagina != 'gestion_sancion' || $opcion != 0){
	header ("Location: ../cerrar_sesion.php");
}
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$idayvuelta = $liga->getValor('idayvuelta');//guardo este dato para calcular en actualiza las jornadas para expulsiones
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_partidos = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$id_division,'','','','','','','');
if($num_partidos != 0){//entro si hay partidos
//SE GUARDA EN SESSION
	$_SESSION['id_liga'] = $id_liga;
	$_SESSION['id_division'] = $id_division;
	$_SESSION['idayvuelta'] = $liga->getValor('idayvuelta');//guardo este dato para calcular en actualiza las jornadas para expulsiones
	$_SESSION['tipo_pago'] = $liga->getValor('tipo_pago');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_sancion.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_sancion.js" type="text/javascript"></script>
<style>
.cont_principal {
	width:99% !important;
	float:left;
	/*border:1px black solid;*/
}
.titulo {
	font-size:90%;
	color: #006;
	margin-left:15%;
}

.caja_cab {
	width:93% !important;
	background-color:#f3fcf5;
	border-radius: 20px;
	border:5px #C1C1C1 solid;
	margin-top:3%;
	margin-left:2%;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	float:left;
}
.caja_cab:hover {
	background-color:#dbe4dd;
}
.caja {
	width:93% !important;
	height:500px;
	background-color:#f3fcf5;
	border-radius: 20px;
	border:5px #C1C1C1 solid;
	margin-top:3%;
	margin-left:2%;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	float:left;
}
.caja:hover {
	background-color:#dbe4dd;
}
.letra {
	color:#34495e;
	font-size:45%;
	text-align: center;
	width:99% !important;
	float:left;
	font-weight: bolder;
}
.equipo {
	width:30% !important;
	height:99.9% !important;
	float:left;
}
.jugador1 {
	width:99.9% !important;
	height:49% !important;
	font-size:80%;
	text-align:center;
	color:#003;
	border-right:1px #C1C1C1 solid;
	border-bottom:1px #C1C1C1 solid;
	border-left:1px #C1C1C1 solid;
	float:left;
}
.jugador2 {
	width:99.9% !important;
	height:50% !important;
	font-size:80%;
	text-align:center;
	color:#003;
	border-right:1px #C1C1C1 solid;
	border-left:1px #C1C1C1 solid;
	float:left;
}
.alinear_texto{
	margin-top:5%;
}

.texto_faltas {
	width:7% !important;
	height:99.9% !important;
	font-size:80%;
	text-align:center;
	color:#003;
	float:left;
	border-right:1px #C1C1C1 solid;
	/*border:1px black solid;*/
}
.texto_faltas div{
	margin-top:50%;
}
.texto_suspensiones {
	width:11% !important;
	height:99.9% !important;
	font-size:80%;
	text-align:center;
	color:#003;
	float:left;
	border-left:1px #C1C1C1 solid;
	border-right:1px #C1C1C1 solid;
	/*border:1px black solid;*/
}
.texto_suspensiones div{
	margin-top:33%;
}
.faltas {
	width:30% !important;
	height:99% !important;
	/*border:1px black solid;*/
	float:left;
}
.suspensiones {
	width:35% !important;
	height:99% !important;
	/*border:1px black solid;*/
	float:left;
}

.sub_faltas {
	width:99.9% !important;
	height:25% !important;
	border-right:1px #C1C1C1 solid;
	/*border:1px black solid;*/
	float:left;
}
.sub2_faltas {
	width:99.9% !important;
	height:24.5% !important;
	border-right:1px #C1C1C1 solid;
	border-bottom:1px #C1C1C1 solid;
	/*border:1px black solid;*/
	float:left;
}

.sub_suspensiones {
	width:99% !important;
	height:43% !important;
	margin-top:7%;
	/*border:1px black solid;*/
	float:left;
}

.texto1 {
	width:99% !important;
	padding-right:3%;
	font-size:80%;
	text-align: center;
	color:#003;
	/*border:1px #34495e solid;*/
	float:left;
}
.texto2 {
	width:99% !important;
	padding-right:3%;
	font-size:80%;
	text-align: center;
	color:#003;
	/*border:1px #34495e solid;*/
	float:left;
}
.color_link {
	color:#003;	
}
.menos {
	width:20% !important;
	font-size:100%;
	font-weight:bold;
	text-align:center;
	margin-left:20%;
	background-color: #AABBCC;
	border-radius:10px;
	border:1px #34495e solid;
	float:left;
}
.mas {
	width:20% !important;
	padding-left:0.5%;
	font-size:100%;
	text-align:center;
	background-color: #AABBCC;
	border-radius:10px;
	border:1px #34495e solid;
	float:left;
}
.menos2 {
	width:17% !important;
	font-size:100%;
	font-weight:bold;
	text-align:center;
	margin-left:5%;
	background-color: #AABBCC;
	border-radius:10px;
	border:1px #34495e solid;
	float:left;
}
.mas2 {
	width:17% !important;
	padding-left:0.5%;
	font-size:100%;
	text-align:center;
	margin-left:5%;
	background-color: #AABBCC;
	border-radius:10px;
	border:1px #34495e solid;
	float:left;
}
.resultado {
	width:27% !important;
	height:99% !important;
	/*border:1px #34495e solid;*/
	float:left;
}
.resultado2 {
	width:20% !important;
	height:99% !important;
	margin-left:5%;
	/*border:1px #34495e solid;*/
	float:left;
}
.partidos {
	height:99% !important;
	margin-left:5%;
	/*border:1px #34495e solid;*/
	float:left;
}


.input_text_liga {
	width:90%;
	font-size:80%;
	text-align:center;
	margin-left:4%;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:10px;
	font-weight:bold;
	font-style:italic;
	border:2px #8989FE solid;
	float:left;
}
.input_select_liga {
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:10px;
	font-style:italic;
	font-size:80%;
	font-weight:bold;
	border:2px #8989FE solid;
	float:left;
}
.horizontal {
	width:99% !important;
	/*border:1px #000 solid;*/
	float:left;
}
.input_act {
	margin-left:35%;
	border-radius:10px;
}
.input_eli {
	margin-left:20%;
	border-radius:10px;
}
.error{
	margin-left:10%;
	font-size:80%;
	color:#F00;
}
#nombreCom,#apellidosCom,#dniCom,#telefonoCom,#direccionCom,#cpCom,#tipoCom {
	display:none;
}
.equipo_cab {
	width:33%;
	font-size:80%;
	float:left;
}
.faltas_cab { 
	width:28%;
	font-size:80%;
	float:left;
}
.suspensiones_cab {
	width:38%;
	font-size:80%;
	float:left;
}
</style>
</head>
<body>
<div class="cont_principal">
	<div class="horizontal">&nbsp;</div>
	<div class="horizontal"><div class="titulo"><b>VER / MODIFICAR  SANCIONES</b></div></div>
    <div class="caja_cab">
    	<div class="equipo_cab">Equipo</div>
        <div class="faltas_cab">Faltas</div>
        <div class="suspensiones_cab">Suspensiones</div>
	</div>
    
<?php
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT id_equipo,jugador1,jugador2 FROM equipo WHERE liga = '$id_liga' AND division = '$id_division'; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
	$id_equipo = $resultados['id_equipo'];
	$jugador1 = $resultados['jugador1'];
	$jugador2 = $resultados['jugador2'];
?>
	<div class="caja">
    	<div class="equipo">
        	<div class="jugador1">
            	<div class="alinear_texto"><?php	echo substr(obtenNombreJugador($id_equipo,'jugador1'),0,35);	 ?></div>
            </div>
        	<div class="jugador2">
            	<div class="alinear_texto"><?php	echo substr(obtenNombreJugador($id_equipo,'jugador2'),0,35);	 ?></div>
            </div>
        </div>
        <div class="faltas">
        	<div class="sub_faltas">
            	<div class="texto1">Leves:</div>
                <div class="menos"><a href=" javascript: falta('resta','leve','<?php echo $jugador1; ?>') " class="color_link">-</a></div>
                <div class="resultado"><input id="<?php echo 'leve_'.$jugador1; ?>" type="text" class="input_text_liga" value="<?php echo obten_consultaUnCampo('session','COUNT(id_sancion)','sancion_jugador','jugador',$jugador1,'tipo','0','','','','',''); ?>" disabled></div>
                <div class="mas"><a href=" javascript: falta('suma','leve','<?php echo $jugador1; ?>') " class="color_link">+</a></div>
            </div>
            <div class="sub2_faltas">
            	<div class="texto1">Graves:</div>
                <div class="menos"><a href=" javascript: falta('resta','grave','<?php echo $jugador1; ?>') " class="color_link">-</a></div>
                <div class="resultado"><input id="<?php echo 'grave_'.$jugador1; ?>" type="text" class="input_text_liga" value="<?php echo obten_consultaUnCampo('session','COUNT(id_sancion)','sancion_jugador','jugador',$jugador1,'tipo','1','','','','',''); ?>" disabled></div>
                <div class="mas"><a href=" javascript: falta('suma','grave','<?php echo $jugador1; ?>') " class="color_link">+</a></div>
            </div>
            <div class="sub_faltas">
            	<div class="texto1">Leves:</div>
                <div class="menos"><a href=" javascript: falta('resta','leve','<?php echo $jugador2; ?>') " class="color_link">-</a></div>
                <div class="resultado"><input id="<?php echo 'leve_'.$jugador2; ?>" type="text" class="input_text_liga" value="<?php echo obten_consultaUnCampo('session','COUNT(id_sancion)','sancion_jugador','jugador',$jugador2,'tipo','0','','','','',''); ?>" disabled></div>
                <div class="mas"><a href=" javascript: falta('suma','leve','<?php echo $jugador2; ?>') " class="color_link">+</a></div>
            </div>
            <div class="sub_faltas">
            	<div class="texto1">Graves:</div>
                <div class="menos"><a href=" javascript: falta('resta','grave','<?php echo $jugador2; ?>') " class="color_link">-</a></div>
                <div class="resultado"><input id="<?php echo 'grave_'.$jugador2; ?>" type="text" class="input_text_liga" value="<?php echo obten_consultaUnCampo('session','COUNT(id_sancion)','sancion_jugador','jugador',$jugador2,'tipo','1','','','','',''); ?>" disabled></div>
                <div class="mas"><a href=" javascript: falta('suma','grave','<?php echo $jugador2; ?>') " class="color_link">+</a></div>
            </div>
        </div>
        <div class="suspensiones">
        	<div class="sub_suspensiones">
            	<div class="texto2">Partidos:</div>
                <div class="menos2"><a href=" javascript: sancion('resta','partido','<?php echo $id_equipo; ?>') " class="color_link">-</a></div>
                <div class="partidos"><?php sanciones(obten_datosPartidos($id_equipo,0),'partidos_sancion'.$id_equipo);?></div>
                <div class="mas2"><a href=" javascript: sancion('suma','partido','<?php echo $id_equipo; ?>') " class="color_link">+</a></div>
                 <div class="resultado2"><input id="<?php echo 'partido_'.$id_equipo; ?>" type="text" class="input_text_liga" value="<?php echo obtenNumSancionesEquipo($id_equipo,0); ?>" disabled></div>
            </div>
            <div class="sub_suspensiones">
            	<div class="texto2">Expulsiones:</div>
                <div class="menos2"><a href=" javascript: sancion('resta','expulsion','<?php echo $id_equipo; ?>') " class="color_link">-</a></div>
                <div class="resultado2"><input id="<?php echo 'expulsion_'.$id_equipo; ?>" type="text" class="input_text_liga" value="<?php echo obtenNumSancionesEquipo($id_equipo,1); ?>" disabled></div>
                <div class="mas2"><a href=" javascript: sancion('suma','expulsion','<?php echo $id_equipo; ?>') " class="color_link">+</a></div>
            </div>
        </div>    
	</div>
<?php
	}//fin del while
?>
</div>
</body>
</html>
<?php
}//fin de if opcion tipo numPartidos

?>