<?php
include("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_desplegables.php");
include_once ("../../funciones/f_obten.php");
header("Content-Type: text/html;charset=ISO-8859-1");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '<option value="">--Ciudad--</option>';
$num_BDligas = numero_de_BDligas();
$ciudades = array();
$ciudades = obten_localizacionGratisDistintasBds($num_BDligas,'ciudad','liga','provincia',$elegido);
$db2 = new MySQL('unicas');//UNICAS
for($i=0; $i<count($ciudades); $i++){
	$ligas = array();
	$num_ligas = count(obten_ligasGratisDistintasBds($num_BDligas,$ciudades[$i]));//obtiene la cantidad de ligas para una ciudad en todas las bds
	$num_publicidad = obten_consultaUnCampo('unicas','COUNT(id_publicidad_gratis)','publicidad_gratis','ciudad',$ciudad,'pagado','S','','','','','');
	//$num_publicidad = obten_numPublicidad($ciudad);//contiene el numero de publicidades para una ciudad
	$tope = $num_ligas * obten_anunciosPorLiga();//la media son 15 publicidades por liga entra 10 y se quedan 5
	if($num_publicidad < $tope){// Para evitar el colapso de publicidades
		$consulta2 = $db2->consulta("SELECT municipio FROM municipios WHERE id = '$ciudades[$i]'; ");
		$resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC);
		$rpta.= '<option  value="'.$ciudades[$i].'">'.$resultados2['municipio'].'</option>';
	}
}

// Desconectarse de la base de datos
$db2->cerrar_conexion();
echo $rpta;
?>