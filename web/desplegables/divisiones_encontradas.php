<?php
include("../../class/mysql.php");
session_start();
header("Content-Type: text/html;charset=utf-8");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '<option value="">--Division--</option>';
$datos = array();
$datos = explode('-',$elegido);//id_liga - bd
if($datos[1] == '1'){
	$_SESSION['bd'] = 'admin_liga';
}
else{
	$_SESSION['bd'] = 'admin_liga'.$datos[1];
}
$fecha_hoy = date('Y-m-d H:i:s');
$fecha_vacia = '0000-00-00 00:00:00';
$db3 = new MySQL('session');//SESSION

$consulta3 = $db3->consulta("SELECT id_division,num_division FROM division WHERE liga = '$datos[0]' AND bloqueo = 'N' AND (comienzo = 'S' OR (comienzo = 'N' AND suscripcion <= '$fecha_hoy')) ; ");
if($consulta3->num_rows>0){
  	while($resultados3 = $consulta3->fetch_array(MYSQLI_ASSOC)){
		$rpta.= '<option value="'.$resultados3['id_division'].'">'.$resultados3['num_division'].'</option>';
  	}
}
// Desconectarse de la base de datos
$db3->cerrar_conexion();
echo $rpta;	
?>