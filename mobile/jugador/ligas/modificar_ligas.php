<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_partidos.php");
include_once ("../../../class/mysql.php");
session_start();
$pagina = $_SESSION['pagina'];
$id_jugador = $_SESSION['id_jugador'];
if ( $pagina != 'gestion_ligas'){
	header ("Location: ../cerrar_sesion.php");
}
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_ligas.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<!--<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>-->
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_ligas.js" type="text/javascript"></script>
</head>
<body>
<div class="cont_principal">
<?php
//---------------------------------------------------------------------------------------------------------
//IMPLEMENTAR QUE EL JUGADOR PUEDA ELIMINARSE DE UNA LIGA SI NO EST PAGADA O ES GRATIS, Y NO HA COMENZADO
//---------------------------------------------------------------------------------------------------------
//CALCULAR EN QUE BD ESTA EL EQUIPO
$num_bdligas = numero_de_BDligas();
$cont = 0;
$nombre = array();
$ciudad = array();
$provincia = array();
$usuario = array();
$tipo_pago = array();
$num_division = array();
$id_equipo = array();
$bd = array();
for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
	if($i == 1){
		$_SESSION['bd'] = 'admin_liga';
	}
	else{
		$_SESSION['bd'] = 'admin_liga'.$i;
	}
	$equipos = array();
	$equipos = obten_arrayPerteneceAequipos($id_jugador);
	$db = new MySQL('session');//LIGA PADEL
	for($j=0; $j<count($equipos); $j++){//FOR EQUIPOS
		$consulta = $db->consulta("SELECT nombre,ciudad,provincia,usuario,tipo_pago,num_division FROM liga,division,partido WHERE local = '$equipos[$j]' AND division = id_division AND liga = id_liga; ");
		$bd[$cont] = $_SESSION['bd'];
		$id_equipo[$cont] = $equipos[$j];
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$nombre[$cont] = $resultados['nombre'];
		$ciudad[$cont] = $resultados['ciudad'];
		$provincia[$cont] = $resultados['provincia'];
		$usuario[$cont] = $resultados['usuario'];
		$tipo_pago[$cont] = $resultados['tipo_pago'];
		$num_division[$cont] = $resultados['num_division'];
		//echo $nombre[$cont].'-'.$ciudad[$cont].'-'.$provincia[$cont].'-'.$usuario[$cont].'-'.$tipo_pago[$cont].'-'.$num_division[$cont].'<br>';
		$cont++;
	}//FIN FOR DE EQUIPO
}//FIN FOR BD
$muestra_vacio = true;//variable para mostrar texto si todo es vacio
//comienza a mostrar
for($j=0; $j<$cont; $j++){
	//if($nombre[$j] != '' && $num_division[$j] != ''){//comprueba nombre y division vacios para que se muestre cuando ya estn generados los partidos
		$muestra_vacio = false;
		$finalizados = obten_datosPartidos($id_equipo[$j],1);//solo cargo finalizados y ganados para, ya que las derrotas son finalizados-ganados
		//$victorias = obten_partidosGanados($id_equipo[$j]);
		$victorias = obten_consultaUnCampo('session','COUNT(id_partido)','partido','ganador',$id_equipo[$j],'','','','','','','');
		//$victorias = obten_partidosGanados($id_equipo[$j]);
		//aqui esta el fallo entre esto
		if(obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$j],'','','','','','','') == $id_jugador){//est correcto
		//if(obten_idJugador3($id_equipo[$j],'jugador1') == $id_jugador){//est correcto
			$j1 = substr(obtenNombreJugador($id_equipo[$j],'jugador1'),0,35);
			$j2 = substr(obtenNombreJugador($id_equipo[$j],'jugador2'),0,35);
		}
		else{//intercambiamos para que el jugador que accede aparezca como primer jugador
			$j1 = substr(obtenNombreJugador($id_equipo[$j],'jugador2'),0,35);
			$j2 = substr(obtenNombreJugador($id_equipo[$j],'jugador1'),0,35);
		}
		//echo 'hasta aqui bd:'.$_SESSION['bd'];
		//y esto
		$fec_creacion = obten_consultaUnCampo('session','fec_creacion','equipo','id_equipo',$id_equipo[$j],'','','','','','','');
		//$fec_creacion = obten_fecCreacionEquipo($id_equipo[$j]);
		$anyo = substr($fec_creacion,0,4);
		$mes = substr($fec_creacion,5,2);
		$dia = substr($fec_creacion,8,2);
?>
	<div class="caja"> 
    	<div class="datos">
        <?php
		if($nombre[$j] != '' && $num_division[$j] != ''){//si ya hay partidos
			echo '<label class="datos_linea">Liga: '.substr($nombre[$j],0,30); 
			if(strlen($nombre[$j]) >= 27){echo '..';}
			echo '</label>';
            echo '<label class="datos_linea">Division: '.$num_division[$j].'</label>';
            echo '<label class="datos_linea">Fecha Inscripcion: '.$dia.'-'.$mes.'-'.$anyo.'</label>';
            echo '<label class="datos_linea">Ciudad: '.substr(obtenLocalizacion(3,$ciudad[$j]),0,25).'</label>';
            echo '<label class="datos_linea">Provincia: '.substr(obtenLocalizacion(2,$provincia[$j]),0,25).'</label>';
		}
		else{
			$liga = obten_consultaUnCampo('unicas_liga','liga','pago_admin','bd',$bd[$j],'equipo',$id_equipo[$j],'','','','','');
			$division = obten_consultaUnCampo('unicas_liga','division','pago_admin','bd',$bd[$j],'equipo',$id_equipo[$j],'','','','','');
			$_SESSION['bd'] = $bd[$j];
			if($liga == '' && $division == ''){
				$liga = obten_consultaUnCampo('session','liga','equipo','id_equipo',$id_equipo[$j],'','','','','','','');
				$division = obten_consultaUnCampo('session','division','equipo','id_equipo',$id_equipo[$j],'','','','','','','');
			}
			$liga = obten_consultaUnCampo('session','nombre','liga','id_liga',$liga,'','','','','','','');
			$division = obten_consultaUnCampo('session','num_division','division','id_division',$division,'','','','','','','');
			echo '<label class="datos_linea">&nbsp;</label>';
			echo '<label class="datos_linea">Liga: '.substr($liga,0,30); 
			if(strlen($liga) >= 27){echo '..';}
			echo '</label>';
            echo '<label class="datos_linea">Division: '.$division.'</label>';
            echo '<label class="datos_linea">Liga en fase de Inscripciones</label>';
            echo '<label class="datos_linea">Fecha Inscripcion: '.$dia.'-'.$mes.'-'.$anyo.'</label>';
		}
		?>
        </div>
    	<div class="equipo">
        	<div class="jugador1">
           		<div class="alinear_texto"><?php echo $j1; ?></div>
            </div>
			<div class="jugador2">
            	<div class="alinear_texto"><?php echo $j2; ?></div>
            </div>
        </div>
        <div class="partidos">
            <?php
			if($nombre[$j] != '' && $num_division[$j] != ''){//si ya hay partidos
			?>
                <label class="partidos_titulo">
                    <div class="alinear_titulo">Partidos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <img name="<?php echo 'partidos'.$j; ?>" onClick="javascript: enviar_email_admin('<?php echo $usuario[$j]; ?>','Partidos','<?php echo $nombre[$j]; ?>')" class="imagen" src="../../../images/email.png">
                </label>
                <label class="partidos_datos">Victorias:<?php echo ' '.$victorias;?></label>
                <label class="partidos_datos">Por Jugar:<?php echo ' '.obten_datosPartidos($id_equipo[$j],0);?></label>
                <label class="partidos_datos">Finalizados:<?php echo ' '.$finalizados;?></label>
                <label class="partidos_datos">Derrotas:<?php echo ' '.abs($finalizados-$victorias);?></label>
                <label class="partidos_datos">Expulsados:<?php echo ' '.obten_datosPartidos($id_equipo[$j],3);?></label>
                <label class="partidos_datos">Sancionados:<?php echo ' '.obten_datosPartidos($id_equipo[$j],2);?></label>
            <?php
			}
			else{
				//		echo $id_equipo[$j].'-'.$bd[$j];
			?>
                <label class="partidos_titulo">&nbsp;</label>
                <label class="partidos_titulo">
                    <div class="alinear_titulo">Contacto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <img name="<?php echo 'partidos'.$j; ?>" onClick="javascript: enviar_email_admin2('<?php echo $id_equipo[$j]; ?>','Consulta general','<?php echo $bd[$j]; ?>','<?php echo $liga; ?>','<?php echo $division; ?>')" class="imagen" src="../../../images/email.png">
                </label>
            <?php
			}//fin else
			?>
        </div> 
<?php
		if($tipo_pago[$j] > 0){
?>
        <div class="faltas">
        	<label class="faltas_titulo">
            	<div class="alinear_titulo">Faltas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            	<img name="<?php echo 'faltas'.$j; ?>" onClick="javascript: enviar_email_admin('<?php echo $usuario[$j]; ?>','Faltas','<?php echo $nombre[$j].' division '.$num_division[$j]; ?>')" class="imagen" src="../../../images/email.png">
            </label>
            <label class="faltas_datos">Leves:<?php echo ' '.obten_consultaUnCampo('session','COUNT(id_sancion)','sancion_jugador','jugador',$id_jugador,'tipo','0','','','','','');//obtenNumSancionesJugador($id_jugador,0);?></label>
            <label class="faltas_datos">Graves:<?php echo ' '.obten_consultaUnCampo('session','COUNT(id_sancion)','sancion_jugador','jugador',$id_jugador,'tipo','0','','','','','');//obtenNumSancionesJugador($id_jugador,1);?></label>
            
        </div> 
<?php
		}//FIN IF TIPO PAGO
echo '</div>';//FIN DIV CAJA
	//}//fin comprueba nombre y division vacios
}//FIN FOR
if($muestra_vacio){
	echo '<div class="horizontal">&nbsp;</div><div class="horizontal">En estos momentos no ha comenzado ninguna liga en la que ests inscrito/a</div>';
}
?>     
	
</div>
</body>
</html>