<?php
include("../../class/mysql.php");
include_once ("../funciones/f_desplegables.php");
include_once ("../funciones/f_general.php");
header("Content-Type: text/html;charset=utf-8");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '<option value="">--Liga--</option>';
$ligas = array();
$ligas = obten_ligasDistintasBds(numero_de_BDligas(),$elegido);
$datos = array();
for($i=0; $i<count($ligas); $i++){
	$datos = explode('-',$ligas[$i]);//id_liga - bd - nombre
	$id_ligaybd = $datos[0].'-'.$datos[1];//id_liga - bd
	$rpta.= '<option value="'.$id_ligaybd.'">'.$datos[2].'</option>';
}
echo $rpta;	
?>