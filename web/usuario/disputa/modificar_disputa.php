<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_disputa'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$_SESSION['num_division'] = $division->getValor('num_division');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
//$num_disputas = obten_numDisputas($id_division);
$num_disputas = obten_consultaUnCampo('session','COUNT(id_disputa)','disputa','division',$id_division,'','','','','','','');
if($opcion == 0 && $num_disputas > 0){//modificacion
	/*FALTA INCLUIR LO SIGUIENTE CUANDO UN JUGADOR CREA LA DISPUTA/TICKET
	include_once ("../../class/notificacion.php");
	$notificacion = new Notificacion('',$id_usuario,$id_liga,$id_division,'modificar_disputa.php',date('Y-m-d H:i:s'),'N');
	$notificacion->insertar();
	*/
	$alerta_disputa_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','liga',$id_liga,'division',$id_division,'seccion','modificar_disputa.php','leido','N','');
	if($alerta_disputa_rec > 0){
		realiza_updateGeneral('session','notificacion','leido="S"','liga',$id_liga,'division',$id_division,'seccion','modificar_disputa.php','','','','','');
	}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_disputa.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_disputa.js" type="text/javascript"></script>
</head>
<body>
<div class="cont_principal">
	
<?php
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT id_disputa,disputa.fecha,jugador,respuesta,texto,id_partido,jornada,local,visitante,set1_local,set2_local,set3_local,set4_local,set5_local,set1_visitante,set2_visitante,set3_visitante,set4_visitante,set5_visitante,estado FROM partido,disputa WHERE disputa.division = '$id_division' AND id_partido = partido; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
	obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['local'],'','','','','','','');
	$id_l_j1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['local'],'','','','','','','');
	$id_l_j2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$resultados['local'],'','','','','','','');
	$id_v_j1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
	$id_v_j2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
	if($resultados['respuesta'] == 'N'){
		$mensaje = 'Ticket Contestado: NO';
		$imagen = 'no_like';
	}
	else{
		$mensaje = 'Ticket Contestado: SI';
		$imagen = 'like';
	}
	$emisor = substr(obtenNombreJugador2($resultados['jugador']),0,35);
	$l_j1 = substr(obtenNombreJugador($resultados['local'],'jugador1'),0,35);
	$l_j2 = substr(obtenNombreJugador($resultados['local'],'jugador2'),0,35);
	$v_j1 = substr(obtenNombreJugador($resultados['visitante'],'jugador1'),0,35);
	$v_j2 = substr(obtenNombreJugador($resultados['visitante'],'jugador2'),0,35);
	$anyo = substr($resultados['fecha'],2,2);
	$mes = substr($resultados['fecha'],5,2);
	$dia = substr($resultados['fecha'],8,2);
	$c = 0;// si es l_j1=1 , l_j2=2 , v_j1=3 , v_j2=4 
	if($emisor == $l_j1){$c=1;}
	else if($emisor == $l_j2){$c=2;}
	else if($emisor == $v_j1){$c=3;}
	else {$c=4;}
?>
	<div class="caja"> 
    	<div class="equipo">
<?php
if($c == 1){
	$fec = ' ('.$dia.'-'.$mes.'-'.$anyo.')';
     echo  '<div class="jugador_emisor">';
}
else{
	$fec = '';
	 echo  '<div class="jugador1">';
}
?>
           	<div class="alinear_texto">
				<?php
				if($resultados['local'] == 0){echo 'Descansa';}
				else{echo $l_j1.$fec;}		 
				?>
            	</div>
            </div>
<?php
if($c == 2){
	$fec = ' ('.$dia.'-'.$mes.'-'.$anyo.')';
     echo  '<div class="jugador_emisor">';
}
else{
	$fec = '';
	 echo  '<div class="jugador2">';
}
?>
            	<div class="alinear_texto">
				<?php
				if($resultados['local'] == 0){echo 'Descansa';}
				else{echo $l_j2.$fec;}		 
				?>
                </div>
            </div>
        </div>
        <div class="resultados">
        	<?php
			if($resultados['local'] != 0 && $resultados['visitante'] != 0){
				if($resultados['set5_local'] == -1){$sets = 3;}
				else{$sets = 5;}
				for($i=1; $i<=$sets; $i++){
					$campo = "set".$i."_local";
					echo '<input type="text" value="'.$resultados[$campo].'" class="input_resultado" disabled>';
				}
			}
			?>
        </div>
        <div class="resultados">
        	<?php
			if($resultados['local'] != 0 && $resultados['visitante'] != 0){
				for($i=1; $i<=$sets; $i++){
					$campo = "set".$i."_visitante";
					echo '<input type="text" value="'.$resultados[$campo].'" class="input_resultado" disabled>';
				}
			}
			?>
        </div>
        <div class="equipo">
<?php
if($c == 3){
	$fec = ' ('.$dia.'-'.$mes.'-'.$anyo.')';
     echo  '<div class="jugador_emisor">';
}
else{
	$fec = '';
	 echo  '<div class="jugador1">';
}
?>
            	<div class="alinear_texto">
                <?php
				if($resultados['visitante'] == 0){echo 'Descansa';}
				else{echo $v_j1.$fec;}		 
				?>
            	</div>
            </div>
<?php
if($c == 4){
	$fec = ' ('.$dia.'-'.$mes.'-'.$anyo.')';
     echo  '<div class="jugador_emisor">';
}
else{
	$fec = '';
	 echo  '<div class="jugador2">';
}
?>
            	<div class="alinear_texto">
                <?php
				if($resultados['visitante'] == 0){echo 'Descansa';}
				else{echo $v_j2.$fec;}		 
				?>
            	</div>
            </div>
        </div> 
        <div class="datos">
        	<label class="datos_jornada">Jornada <?php echo $resultados['jornada']; ?></label>
            <label class="datos_estado">Estado: <?php echo obten_estadoPartido($resultados['estado']); ?></label>
            <textarea readonly rows="4" cols="27" disabled><?php echo $resultados['texto']; ?></textarea>
        </div>
        <div class="respuesta">
        	<div class="contestacion"><img src="../../../images/<?php echo $imagen.'.png';?>"><label><?php echo $mensaje;?></label></div>
            <div class="destinatarios">
            	<label><input type="checkbox" id="1_<?php echo $resultados['id_disputa']; ?>" value="<?php echo $id_l_j1; ?>"><?php echo $l_j1; ?></label>
                <label><input type="checkbox" id="2_<?php echo $resultados['id_disputa']; ?>" value="<?php echo $id_l_j2; ?>"><?php echo $l_j2; ?></label>
                <label><input type="checkbox" id="3_<?php echo $resultados['id_disputa']; ?>" value="<?php echo $id_v_j1; ?>"><?php echo $v_j1; ?></label>
                <label><input type="checkbox" id="4_<?php echo $resultados['id_disputa']; ?>" value="<?php echo $id_v_j2; ?>"><?php echo $v_j2; ?></label>
                <label><input type="button" value="Enviar E-m@il" class="boton2" onClick="javascript: email(<?php echo $resultados['id_disputa']; ?>);"></label>
                
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