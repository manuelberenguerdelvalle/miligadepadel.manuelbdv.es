<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_partidos.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/partido.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_partido'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$idayvuelta = $liga->getValor('idayvuelta');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_partidos = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$id_division,'','','','','','','');
if($opcion == 0 && $num_partidos > 0){//modificacion
	//SE GUARDA EN SESSION
	$_SESSION['id_liga'] = $id_liga;
	$_SESSION['id_division'] = $id_division;
	//SE ENVIA CON JAVASCRIPT PARA NO RECARGAR LA PAGINA, Y PARA QUE NO LO RECOJA GESTION_DATOS
	if( isset($_GET['jornada']) && is_numeric($_GET['jornada']) ){//cuando recibimos la jornada por GET
		$_SESSION['jornada'] = $_GET['jornada'];
		$jornada = $_SESSION['jornada'];
	}
	else if ( isset($_SESSION['jornada']) ){//cuando se actualiza la pagina
		$jornada = $_SESSION['jornada'];
	}
	else{//cuando entramos por primera vez
		$jornada = 1;
	}
	//$dias_min_suscripcion = 0;
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="css/modificar_partido.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<link href="../../../jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<!--<script src="../../javascript/validaciones.js" type="text/javascript"></script>-->
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_partido.js" type="text/javascript"></script>
<script src="../../../jquery-ui/jquery-ui.js"></script>
<script language="javascript">
	$(function(){
		  $('.dateTxt').datepicker({
				firstDay: 1,
				minDate: "0D",
				changeMonth: true,
				changeYear: true
			}); 
	  });
</script>
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
.contenedor_jornadas {
	width:90% !important;
	margin-left:5%;
	/*border:1px #000 solid;*/
	float:left;
}
.jornada {
	margin-left:0.5%;
	margin-bottom:0.5%;
	width: 10%;
	background-color: #EDEDFC;
	text-align:center;
	border-radius:10px;
	border:3px #181C83 solid;
	padding-bottom:0.6%;
	float:left;
}
.jornada_disputandose {
	margin-left:0.5%;
	margin-bottom:0.5%;
	width: 10%;
	/*AABBCC*/
	background-color: #ecc8fa;
	text-align:center;
	border-radius:10px;
	border:3px #181C83 solid;
	padding-bottom:0.6%;
	float:left;
}
.jornada_completa {
	margin-left:0.5%;
	margin-bottom:0.5%;
	width: 10%;
	background-color: #c5fbc6;
	text-align:center;
	border-radius:10px;
	border:3px #181C83 solid;
	padding-bottom:0.6%;
	float:left;
}
.normal {
	color: #181C83;
	font-style:italic;
	font-size:80%;
}
.seleccionado{
	color: #181C83;
	font-style:italic;
	font-weight:bolder;
	font-size:100%;
}
.caja {
	width:99% !important;
	background-color:#f3fcf5;
	border-radius: 20px;
	border:5px #C1C1C1 solid;
	margin-top:6%;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	float:left;
}
.caja:hover {
	width:99% !important;
	background-color:#dbe4dd;
	border-radius: 20px;
	border:5px #C1C1C1 solid;
	margin-top:6%;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	float:left;
}
.boton {
	width:7% !important;
	height:95% !important;
	background-color: #AABBCC;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	margin-top:0.2%;
	margin-left:0.5%;
	margin-right:0.5%;
	border-radius:10px;
	border:2px #34495e solid;
	float:left;
}
.boton_sus {
	width:7% !important;
	height:95% !important;
	background-color: #FF9;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	margin-top:0.2%;
	margin-left:0.5%;
	margin-right:0.5%;
	border-radius:10px;
	border:2px #34495e solid;
	float:left;
}
.boton_exp {
	width:7% !important;
	height:95% !important;
	background-color: #FFC1CC;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	margin-top:0.2%;
	margin-left:0.5%;
	margin-right:0.5%;
	border-radius:10px;
	border:2px #34495e solid;
	float:left;
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
	width:34% !important;
	float:left;
}
.equipo1 {
	width:99.9% !important;
	font-size:80%;
	text-align:center;
	color:#003;
	border-right:3px #C1C1C1 solid;
	border-bottom:3px #C1C1C1 solid;
	border-left:3px #C1C1C1 solid;
	float:left;
}
.equipo2 {
	width:99.9% !important;
	font-size:80%;
	text-align:center;
	color:#003;
	border-left:3px #C1C1C1 solid;
	border-bottom:3px #C1C1C1 solid;
	float:left;
}
.alinear_texto{
	margin-top:6%;
}

.resultados {
	width:10% !important;
	height:99% !important;
	padding-left:0.5%;
	float:left;
	/*border:1px black solid;*/
}
.resultados select {
	font-size:100%;
	margin-top:10%;
	float:left;
}
.datos {
	width:99% !important;
	padding-left:0.5%;
	/*border:1px black solid;*/
	float:left;
}

.datos2 {
	width:99% !important;
	padding-left:0.5%;
	/*border:1px black solid;*/
	float:left;
}
.input_text_liga {
	width:95%;
	font-size:80%;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:10px;
	font-style:italic;
	border:2px #8989FE solid;
}
.input_select_liga {
	color: #181C83;
	font-size:80%;
	background-color: #EDEDFC;
	border-radius:10px;
	font-style:italic;
	margin-left:5%;
	border:2px #8989FE solid;
}
.dateTxt {
	width:35% !important;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:10px;
	font-style:italic;
	margin-left:5%;
	font-size:80%;
	border:2px #8989FE solid;
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


</style>
</head>
<body>
<div class="cont_principal">
<div id="flotante"></div>
	<!--<p>Date 1: <input id="one" class="dateTxt" type="text" ></p>
        <p>Date 2: <input id="two" class="dateTxt" type="text" ></p>
        <p>Date 3: <input id="three" class="dateTxt" type="text" ></p>-->
	<div class="horizontal">&nbsp;</div>
	<div class="horizontal"><div class="titulo"><b>JORNADAS</b></div></div>
<?php
$max_jornadas = obten_consultaUnCampo('session','MAX(jornada)','partido','division',$id_division,'','','','','','','');
//las jornadas siempre son equipos-1, asi que se suma 1 para obtener el numero de equipos y dividir
if($idayvuelta == 'S'){// si hay ida y vuelta son el doble de jornadas
	$partidos_x_jornada = (($max_jornadas/2)+1)/2;
}
else{
	$partidos_x_jornada = ($max_jornadas+1)/2;// normal
}
echo '<div class="contenedor_jornadas">';
for($i=1; $i<=$max_jornadas; $i++){
	$partidos_finalizados = jornadaPartidosFinalizados($id_division,$i);
	$hay_partidos_descanso = hayPartidoDescanso($id_division,$i);
	if($partidos_finalizados == 0 || ($partidos_finalizados == 1 && $hay_partidos_descanso == 1) ){//si hay partidos de descanso minimo va a ver 1 partido finalizado si no 0 = jornada normal
		if($i == $jornada){
			echo '<div class="jornada"><a href="#" class="seleccionado" onClick="cambiar_jornada('."$i".')">'.$i.'</a></div>';
		}
		else{
			echo '<div class="jornada"><a href="#" class="normal" onClick="cambiar_jornada('."$i".')">'.$i.'</a></div>';
		}
	}
	else if($partidos_finalizados == $partidos_x_jornada){//si la suma de todos los finalizados es igual a los partidos por jornada = jornada finalizada
		if($i == $jornada){
			echo '<div class="jornada_completa"><a href="#" class="seleccionado" onClick="cambiar_jornada('."$i".')">'.$i.'</a></div>';
		}
		else{
			echo '<div class="jornada_completa"><a href="#" class="normal" onClick="cambiar_jornada('."$i".')">'.$i.'</a></div>';
		}
	}
	else{
		if($i == $jornada){//si es cualquier valor intermedio = jornada disputandose
			echo '<div class="jornada_disputandose"><a href="#" class="seleccionado" onClick="cambiar_jornada('."$i".')">'.$i.'</a></div>';
		}
		else{
			echo '<div class="jornada_disputandose"><a href="#" class="normal" onClick="cambiar_jornada('."$i".')">'.$i.'</a></div>';
		}
	}
}
echo '</div>';

$campos_local = array('set1_local','set2_local','set3_local','set4_local','set5_local');
$campos_visitante = array('set1_visitante','set2_visitante','set3_visitante','set4_visitante','set5_visitante');
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT * FROM partido WHERE division = '$id_division' AND jornada = '$jornada'; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
	if($resultados["fecha"] != '0000-00-00 00:00:00'){$fecha = datepicker_fecha(substr($resultados["fecha"],0,10));}
	else{$fecha = '';}
?>
	<div class="caja">
<?php	
	if($resultados['local'] != 0 && $resultados['visitante'] != 0){
		if($resultados['estado'] <= 1){//partido estado <= 1 activo o finalizado
?>
		<div class="boton">
        	<a href="#" onClick="return enviar(<?php echo $resultados['id_partido'];?>)">
        		<div class="letra">M</div>
                <div class="letra">O</div>
                <div class="letra">D</div>
                <div class="letra">I</div>
                <div class="letra">F</div>
                <div class="letra">I</div>
                <div class="letra">C</div>
                <div class="letra">A</div>
                <div class="letra">R</div>
            </a>
		</div>  
<?php	
		}//fin if 
		else if($resultados['estado'] == 2){//partido estado = 2 suspendido
?>
		<div class="boton_sus">
        	<div class="letra">S</div>
            <div class="letra">A</div>
            <div class="letra">N</div>
            <div class="letra">C</div>
            <div class="letra">I</div>
            <div class="letra">O</div>
            <div class="letra">N</div>
		</div> 
<?php
		}//fin else if
		else{//partido estado = 3 expulsado
?>
		<div class="boton_exp">
        	<div class="letra">E</div>
            <div class="letra">X</div>
            <div class="letra">P</div>
            <div class="letra">U</div>
            <div class="letra">L</div>
            <div class="letra">S</div>
            <div class="letra">I</div>
            <div class="letra">O</div>
            <div class="letra">N</div>
		</div>
<?php
		}//fin else
	}//fin if local y visitante
	else{//partidos descanso
		echo '<div class="boton">&nbsp;</div>';
	}
?> 
    	<div class="equipo">
        	<div class="equipo1">
            	<div class="alinear_texto">
				<?php
				if($resultados['local'] == 0){echo 'Descansa';}
				else{
					$jugador1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['local'],'','','','','','','');
					if($resultados['modificado'] == $jugador1 && $jugador1 > 0){
						echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
						echo '<b><i>'.substr(obtenNombreJugador($resultados['local'],'jugador1'),0,35).'</i></b></span>';
					}
					else{
						if($jugador1 == 0){//si es temporal
							$inscripcion_equipoLocal = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$resultados['local'],'','','','','','','');
							echo substr(obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','',''),0,35);
						}
						else{echo substr(obtenNombreJugador($resultados['local'],'jugador1'),0,35);}
					}
				}		 
				?>
            	</div>
            </div>
        	<div class="equipo1">
            	<div class="alinear_texto">
				<?php
				if($resultados['local'] == 0){echo 'Descansa';}
				else{
					$jugador2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$resultados['local'],'','','','','','','');
					if($resultados['modificado'] == $jugador2 && $jugador2 > 0){
						echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
						echo '<b><i>'.substr(obtenNombreJugador($resultados['local'],'jugador2'),0,35).'</i></b></span>';
					}
					else{
						if($jugador2 == 0){//si es temporal
							echo substr(obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion_equipoLocal,'','','','','','',''),0,35);
						}
						else{echo substr(obtenNombreJugador($resultados['local'],'jugador2'),0,35);}
					}
				}		 
				?>
                </div>
            </div>
        </div>
        <div class="resultados">
        <form name="<?php echo 'form_res_local'.$resultados['id_partido']; ?>" id="<?php echo 'form_res_local'.$resultados['id_partido']; ?>" action="#" method="post">
        	<?php
			if($resultados['local'] != 0 && $resultados['visitante'] != 0){
				if($resultados['set5_local'] == -1){$sets = 3;}
				else{$sets = 5;}
				for($i=0; $i<$sets; $i++){
					$campo = $campos_local[$i];
					resultados($resultados[$campo],$campo);
				}
			}
			?>
        </form>
        </div>
        <div class="resultados">
        <form name="<?php echo 'form_res_visit'.$resultados['id_partido']; ?>" id="<?php echo 'form_res_visit'.$resultados['id_partido']; ?>" action="#" method="post">
        	<?php
			if($resultados['local'] != 0 && $resultados['visitante'] != 0){
				for($i=0; $i<$sets; $i++){
					$campo = $campos_visitante[$i];
					resultados($resultados[$campo],$campo);
				}
			}
			?>
        </form>
        </div>
        <div class="equipo">
        	<div class="equipo2">
            	<div class="alinear_texto">
                <?php
				if($resultados['visitante'] == 0){echo 'Descansa';}
				else{
					$jugador1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
					if($resultados['modificado'] == $jugador1 && $jugador1 > 0){
						echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
						echo '<b><i>'.substr(obtenNombreJugador($resultados['visitante'],'jugador1'),0,35).'</i></b></span>';
					}
					else{
						if($jugador1 == 0){//si es temporal
							$inscripcion_equipoVisit = obten_consultaUnCampo('session','seguro_jug1','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
							echo substr(obten_consultaUnCampo('session','nombre1','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos1','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','',''),0,35);
						}
						else{echo substr(obtenNombreJugador($resultados['visitante'],'jugador1'),0,35);}
					}
				}		 
				?>
            	</div>
            </div>
        	<div class="equipo2">
            	<div class="alinear_texto">
                <?php
				if($resultados['visitante'] == 0){echo 'Descansa';}
				else{
					$jugador2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$resultados['visitante'],'','','','','','','');
					if($resultados['modificado'] == $jugador2 && $jugador2 > 0){
						echo '<span style="text-align:center" onMouseOver="'."showdiv(event,'Este jugador/a ha insertado el resultado del partido');".'" onMouseOut="hiddenDiv()" style="display:table;">';
						echo '<b><i>'.substr(obtenNombreJugador($resultados['visitante'],'jugador2'),0,35).'</i></b></span>';
					}
					else{
						if($jugador2 == 0){//si es temporal
							echo substr(obten_consultaUnCampo('session','nombre2','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','','').' '.obten_consultaUnCampo('session','apellidos2','inscripcion','id_inscripcion',$inscripcion_equipoVisit,'','','','','','',''),0,35);
						}
						else{echo substr(obtenNombreJugador($resultados['visitante'],'jugador2'),0,35);}
					}
				}		 
				?>
            	</div>
            </div>
        </div> 
        <div class="datos">
        <form name="<?php echo 'form_sup'.$resultados['id_partido']; ?>" id="<?php echo 'form_sup'.$resultados['id_partido']; ?>" action="#" method="post">
        <?php
		
		if($resultados['local'] != 0 && $resultados['visitante'] != 0){//si no es partido DESCANSO muestro hora y pista
			echo '<span><input type="text" name="fecha" value="'.$fecha.'" id="'.$resultados["id_partido"].'" class="dateTxt" /></span>';
			echo '<span>'.select_horas2("hora",$resultados["hora"]).'</span>';
			if(obten_consultaUnCampo('session','COUNT(id_pista)','pista','liga',$id_liga,'','','','','','','') != 0 && $tipo_pago != 0){
				echo '<span>'.select_pistas($id_liga,'pista',$resultados["pista"]).'</span>';
			}
		}	
?>	
		</form>
        </div>
        <div class="datos2">
        <form name="<?php echo 'form_inf'.$resultados['id_partido']; ?>" id="<?php echo 'form_inf'.$resultados['id_partido']; ?>" action="#" method="post">
  	<?php
	if($resultados['local'] != 0 && $resultados['visitante'] != 0){
		
		if(obten_consultaUnCampo('session','COUNT(id_arbitro)','arbitro','liga',$id_liga,'','','','','','','') != 0 && $tipo_pago != 0){
			echo '<span>'.select_arbitros($id_liga,"arbitro_principal",$resultados["arbitro_principal"]).'</span>';
			echo '<span>'.select_arbitros($id_liga,"arbitro_auxiliar",$resultados["arbitro_auxiliar"]).'</span>';
			echo '<span>'.select_arbitros($id_liga,"arbitro_adjunto",$resultados["arbitro_adjunto"]).'</span>';
			echo '<span>'.select_arbitros($id_liga,"arbitro_silla",$resultados["arbitro_silla"]).'</span>';
			echo '<span>'.select_arbitros($id_liga,"arbitro_ayudante",$resultados["arbitro_ayudante"]).'</span>';
		}
	}
	?>
    	</form>
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