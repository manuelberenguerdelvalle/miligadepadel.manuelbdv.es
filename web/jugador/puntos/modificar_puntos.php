<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_partidos.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/mysql.php");
session_start();
$pagina = $_SESSION['pagina'];
$id_jugador = $_SESSION['id_jugador'];
if ( $pagina != 'gestion_puntos'){
	header ("Location: ../cerrar_sesion.php");
}
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_puntos.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<!--<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>-->
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_puntos.js" type="text/javascript"></script>

</head>
<body>
	<br>
	<div class="horizontal"><div class="titulo"><b>&nbsp;Puntos en Torneos</b></div></div>
    	<div class="caja1">
<?php
	$cont = 0;
	$ptos_totales = array();
	$usuario = array();
	$nom_usuario = array();
	$bd = array();
	$liga = array();
	$nom_liga = array();
	$division = array();
	$db = new MySQL('unicas');//LIGA PADEL
	$consulta = $db->consulta("SELECT SUM(puntos) as ptos_totales, usuario, bd, liga, division FROM puntos WHERE jugador = '$id_jugador' AND bd LIKE 'admin_liga%' GROUP BY usuario, bd, liga, division; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		//echo $id_jugador.'-'.$resultados['ptos_totales'].'-'.$resultados['usuario'].'-'.$resultados['bd'].'-'.$resultados['liga'].'-'.$resultados['division'].'<br>';
		$ptos_totales[$cont] = $resultados['ptos_totales'];
		$usuario[$cont] = $resultados['usuario'];
		$_SESSION['bd'] = $resultados['bd'];
		$bd[$cont] = $resultados['bd'];
		$liga[$cont] = $resultados['liga'];
		$division[$cont] = $resultados['division'];
		$c_usuario = new Usuario($resultados['usuario'],'','','','','','','','','','','','','','','','');
		$tipoRanking = obten_consultaUnCampo('unicas','genero','jugador','id_jugador',$id_jugador,'','','','','','','');
		$nom_usuario[$cont] = ucwords($c_usuario->getValor('nombre')).' '.ucwords($c_usuario->getValor('apellidos'));
		$nom_liga[$cont] = obten_consultaUnCampo('session','nombre','liga','id_liga',$liga[$cont],'','','','','','','');
?>
		<div class="linea<?php echo $tipoRanking;?>" onMouseOver="comprueba('cuadro<?php echo $cont;?>')">
                <div class="columna1">
                    <div class="cuadroTexto"><?php echo $nom_usuario[$cont].' - '.$nom_liga[$cont].' (<b>'.$resultados['ptos_totales'].' ptos</b>)';?></div>
                </div>
                <div id="flotante"></div>
         </div><!-- fin linea -->
 <?php
 		$cont++;
	}//fin while
?>	
		</div><!-- fin caja1 -->
        <input type="hidden" id="cantidad" value="<?php echo $cont;?>">
        <div class="caja2">
<?php
		
		for($i=0; $i<count($liga); $i++){
			echo '<div class="cuadro_jugador" id="cuadro'.$i.'">';
				echo '<div class="nombre_jugador'.$tipoRanking.'"><div>'.$nom_usuario[$i].'</div></div>';
				echo '<div class="linea2">';
					echo '<div class="columna5"><div class="cuadroTexto2">Fecha</div></div>';
					echo '<div class="columna4"><div class="cuadroTexto2">Puntos</div></div>';
					echo '<div class="columna6"><div class="cuadroTexto2">Tipo</div></div>';
				echo '</div>';
			$db = new MySQL('unicas');//LIGA PADEL
			$consulta = $db->consulta("SELECT fecha,puntos,tipo FROM puntos WHERE usuario ='".$usuario[$i]."' AND bd = '".$bd[$i]."' AND liga = '".$liga[$i]."' AND division = '".$division[$i]."' AND jugador = '$id_jugador' ORDER BY fecha DESC; ");
			while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				echo '<div class="linea2">';
					echo '<div class="columna5"><div class="cuadroTexto2">'.datepicker_fecha($resultados['fecha']).' '.substr($resultados['fecha'],11,5).'h</div></div>';
					echo '<div class="columna4"><div class="cuadroTexto2">'.$resultados['puntos'].'</div></div>';
					echo '<div class="columna6"><div class="cuadroTexto2">'.obten_tipoPuntos($resultados['tipo']).'</div></div>';	
				echo '</div>';
			}//fin while
			echo '</div>';//fin div cuadro jugador
			unset($db,$consulta,$resultados);
		}
		
?>
        </div><!-- fin caja2-->
</body>
</html>