<?php
include("../../class/mysql.php");
header("Content-Type: text/html;charset=utf-8");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '
<option value="">--Provincia--</option>
';	
$db2 = new MySQL('unicas');//UNICAS
$consulta2 = $db2->consulta("SELECT id, provincia FROM provincias WHERE pais='$elegido' ORDER BY provincia ");
if($consulta2->num_rows>0){
  	while($resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC)){
     	$rpta.= '<option  value="'.$resultados2['id'].'">'.$resultados2['provincia'].'</option>';
  	}
}
// Desconectarse de la base de datos
$db2->cerrar_conexion();	
echo $rpta;	
?>