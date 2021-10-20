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

</head>
<body>
<div class="cont_principal">
	<div class="horizontal">&nbsp;</div>
	<div class="horizontal"><div class="titulo"><b>VER / MODIFICAR  SANCIONES</b></div></div>
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
        <div class="texto_faltas"><div>Faltas</div></div>
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
        <div class="texto_suspensiones"><div>Suspensiones</div></div>
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