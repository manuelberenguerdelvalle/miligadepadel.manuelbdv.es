<?php
include("../../class/mysql.php");
include_once ("../funciones/f_general.php");
include_once ("../funciones/f_desplegables.php");
header("Content-Type: text/html;charset=utf-8");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '<option value="">--Ciudad--</option>';
$ciudades = array();
$ciudades = obten_localizacionDistintasBds(numero_de_BDligas(),'ciudad','liga','provincia',$elegido);
$db2 = new MySQL('unicas');//UNICAS
for($i=0; $i<count($ciudades); $i++){
	$consulta2 = $db2->consulta("SELECT municipio FROM municipios WHERE id = '$ciudades[$i]'; ");
	$resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC);
	$rpta.= '<option value="'.$ciudades[$i].'">'.$resultados2['municipio'].'</option>';
}
$db2->cerrar_conexion();
echo $rpta;
?>