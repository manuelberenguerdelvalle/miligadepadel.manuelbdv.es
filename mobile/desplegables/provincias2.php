<?php
include("../../class/mysql.php");
header("Content-Type: text/html;charset=utf-8");
$rpta5="";
$elegido2=$_POST["elegido2"];
$rpta5= '
<option value="">--Provincia--</option>
';	
$db5 = new MySQL('unicas');//UNICAS
$consulta5 = $db5->consulta("SELECT id, provincia FROM provincias WHERE pais='$elegido2' ORDER BY provincia ");
if($consulta5->num_rows>0){
  	while($resultados5 = $consulta5->fetch_array(MYSQLI_ASSOC)){
     	$rpta5.= '<option  value="'.$resultados5['id'].'">'.$resultados5['provincia'].'</option>';
  	}
}
// Desconectarse de la base de datos
$db5->cerrar_conexion();	
echo $rpta5;	
?>