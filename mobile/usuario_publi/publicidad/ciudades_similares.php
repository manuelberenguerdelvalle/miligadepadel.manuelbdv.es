<?php
include_once("../../../class/mysql.php");
include_once("../../funciones/f_general.php");
include_once("../../funciones/f_desplegables.php");
include_once("../../funciones/f_obten.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$ciudad_actual = $_SESSION['ciudad_actual'];
if ( $pagina != 'gestion_publicidad' || $opcion != 0){
	header ("Location: ../cerrar_sesion.php");
}

header("Content-Type: text/html;charset=ISO-8859-1");
$elegido=$_POST["elegido"];
$rpta= '<option value="">--Cambiar--</option>';
$num_BDligas = numero_de_BDligas();
$latitud_actual = obten_latitud($ciudad_actual);
$latitud_sup = $latitud_actual+0.3;
$latitud_inf = $latitud_actual-0.3;
$num_ligas = count(obten_ligasGratisDistintasBds($num_BDligas,$ciudad_actual));//obtiene la cantidad de ligas para la ciudad actual en todas las bds
$num_publicidad = obten_consultaUnCampo('unicas','COUNT(id_publicidad_gratis)','publicidad_gratis','ciudad',$ciudad_actual,'pagado','S','','','','','');
//$num_publicidad = obten_numPublicidad($ciudad_actual);//contiene el numero de publicidades para la ciudad actual
$numAnuncios = obten_anunciosPorLiga();
$tope = $num_ligas * $numAnuncios;//la media son 15 publicidades por liga entra 10 y se quedan 5
$precio = obten_plus_publicidad_gratuita(3,$num_ligas);
$precio_max = $precio+15;//precio maximo
$db2 = new MySQL('unicas');//UNICAS
$consulta2 = $db2->consulta("SELECT id,municipio FROM municipios WHERE provincia = '$elegido' AND latitud >= $latitud_actual AND latitud <= $latitud_sup ORDER BY latitud; ");
while($resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC)){
	$num_ligas_while = count(obten_ligasGratisDistintasBds($num_BDligas,$resultados2['id']));
	if($num_ligas_while > 0){//si hay
		//$num_publicidad_while = obten_numPublicidad($resultados2['id']);
		$num_publicidad_while = obten_consultaUnCampo('unicas','COUNT(id_publicidad_gratis)','publicidad_gratis','ciudad',$resultados2['id'],'pagado','S','','','','','');
		$tope_while = $num_ligas_while * $numAnuncios;
		$precio_while = obten_plus_publicidad_gratuita(3,$num_ligas_while);
		if($precio_while <= $precio_max){
			if($resultados2['id'] == $ciudad_actual){
				$rpta.= '<option  value="'.$resultados2['id'].'">'.$resultados2['municipio'].' - (Continuar Aqui)</option>';
			}
			else{
				$rpta.= '<option  value="'.$resultados2['id'].'">'.$resultados2['municipio'].'</option>';
			}
		}
	}//fin if
}//fin while

$consulta2 = $db2->consulta("SELECT id,municipio FROM municipios WHERE provincia = '$elegido' AND latitud < $latitud_actual AND latitud >= $latitud_inf ORDER BY latitud DESC; ");
while($resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC)){
	$num_ligas_while = count(obten_ligasGratisDistintasBds($num_BDligas,$resultados2['id']));
	if($num_ligas_while > 0){//si hay
		//$num_publicidad_while = obten_numPublicidad($resultados2['id']);
		$num_publicidad_while = obten_consultaUnCampo('unicas','COUNT(id_publicidad_gratis)','publicidad_gratis','ciudad',$resultados2['id'],'pagado','S','','','','','');
		$tope_while = $num_ligas_while * $numAnuncios;
		$precio_while = obten_plus_publicidad_gratuita(3,$num_ligas_while);
		if($precio_while <= $precio_max){
			$rpta.= '<option  value="'.$resultados2['id'].'">'.$resultados2['municipio'].'</option>';
		}
	}//fin if
}//fin while

// Desconectarse de la base de datos
$db2->cerrar_conexion();
echo $rpta;
?>