<?php
include("../../class/mysql.php");
header("Content-Type: text/html;charset=utf-8");
$rpta6="";
$elegido=$_POST["elegido"];
$rpta6= '
<option value="">--Ciudad--</option>
';	
$db6 = new MySQL('unicas');//UNICAS
$consulta6 = $db6->consulta("SELECT id, municipio FROM municipios WHERE provincia='$elegido' ORDER BY municipio");
if($consulta6->num_rows>0){
  	while($resultados6 = $consulta6->fetch_array(MYSQLI_ASSOC)){
     	$rpta6.= '<option  value="'.$resultados6['id'].'">'.$resultados6['municipio'].'</option>';
  	}
}

// Desconectarse de la base de datos
$db6->cerrar_conexion();
echo $rpta6;	
?>