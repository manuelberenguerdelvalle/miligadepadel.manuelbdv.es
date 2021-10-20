<?php
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/puntos.php");
include_once ("../../../class/jugador.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_puntos'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$dni_usuario = $usuario->getValor('dni');
$telefono = $usuario->getValor('telefono');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$id_usuario = $usuario->getValor('id_usuario');
$bd = $usuario->getValor('bd');
//GUARDAR EN SESSION
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['email'] = $usuario->getValor('email');
$_SESSION['id_liga'] = $id_liga;
//$_SESSION['bd_usuario'] = $usuario->getValor('bd');
$opcion = $_SESSION['opcion'];
if($opcion == 0 || $opcion == 2){//modificacion
	$nombre = $liga->getValor("nombre");
	$tipo_pago = $liga->getValor("tipo_pago");
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>www.mitorneodepadel.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_puntos.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/modificar_puntos.js" type="text/javascript"></script>
</head>
<body>
<?php
if($tipo_pago > 0){
	$tipoRanking = $_SESSION['tipoRanking'];
	if($tipoRanking == '' || ($tipoRanking != 'F' && $tipoRanking != 'M')){$tipoRanking = 'M';}
	$mostrarGenero = ($tipoRanking == 'M') ? "Masculino" : "Femenino";
?>
	<br>
	<div class="horizontal"><div class="titulo"><b>&nbsp;Ranking General <?php echo $mostrarGenero; ?></b></div></div>
    <div class="caja1">
    	<div class="sub_caja1">
<?php
	//obtener el sumatorio de puntos para los distintos jugadores que han jugado para el usuario 
	$id_jugadores = array();
	$pos = 0;
	$db = new MySQL('unicas');//LIGA PADEL
	$consulta = $db->consulta("SELECT jugador, SUM(puntos) AS suma FROM puntos, jugador WHERE usuario ='$id_usuario' AND bd = '$bd' AND jugador = id_jugador AND genero = '$tipoRanking' GROUP BY jugador ORDER BY suma DESC; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['jugador'] > 0){
			$id_jugadores[$pos] = $resultados['jugador'];
			$id_jugador = $resultados['jugador'];
			$jugador = new Jugador($resultados['jugador'],'','','','','','','','','','','','','','','');
?>
			<div class="linea<?php echo $tipoRanking;?>" onMouseOver="comprueba('cuadro<?php echo $pos;?>')">
                <div class="columna1">
                    <div class="cuadroTexto"><?php echo $pos+1;?></div>
                </div>
                <div id="flotante"></div>
                <div class="columna2">
                   <div class="cuadroTexto"><?php echo ucwords($jugador->getValor('nombre')).' '.ucwords($jugador->getValor('apellidos')).' (<b>'.$resultados['suma'].' ptos</b>)';?>&nbsp;&nbsp;</div>  
                </div>
                <div class="columna3">
                    <!--<div class="cuadroComentario"><input type="button" id="btn_enviar" value="+" class="boton" onClick="comprueba('cuadro<?php //echo $pos;?>')" /></div>-->
                    <div class="cuadroComentario">&nbsp;</div>
                </div>
            </div><!-- fin linea -->
        <!--<div class="horizontal">&nbsp;</div>-->
<?php		
			$pos++;
		}//fin if jugador1
	}//fin while*/
?> 
		<input type="hidden" id="cantidad" value="<?php echo $pos;?>">
		</div><!-- fin sub_caja1 -->
        <div class="sub_caja2">
<?php
		for($i=0; $i<count($id_jugadores); $i++){
			echo '<div class="cuadro_jugador" id="cuadro'.$i.'">';
				echo '<div class="nombre_jugador'.$tipoRanking.'"><div>'.obtenNombreJugador2($id_jugadores[$i]).'</div></div>';
				echo '<div class="linea2">';
					echo '<div class="columna5"><div class="cuadroTexto2">Fecha</div></div>';
					echo '<div class="columna4"><div class="cuadroTexto2">Puntos</div></div>';
					echo '<div class="columna6"><div class="cuadroTexto2">Tipo</div></div>';
				echo '</div>';
			$db = new MySQL('unicas');//LIGA PADEL
			$consulta = $db->consulta("SELECT fecha,puntos,tipo FROM puntos WHERE usuario ='$id_usuario' AND bd = '$bd' AND jugador = '$id_jugadores[$i]' ORDER BY fecha DESC; ");
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
        </div>
    </div><!-- fin caja1-->
<?php
}//fin tipo pago
?>
</body>
</html>