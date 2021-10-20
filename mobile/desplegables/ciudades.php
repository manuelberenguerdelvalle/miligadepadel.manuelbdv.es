<?php
include("../../class/mysql.php");
header("Content-Type: text/html;charset=utf-8");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '
<option value="">--Ciudad--</option>
';	
$db3 = new MySQL('unicas');//UNICAS
$consulta3 = $db3->consulta("SELECT id, municipio FROM municipios WHERE provincia='$elegido' ORDER BY municipio");
if($consulta3->num_rows>0){
  	while($resultados3 = $consulta3->fetch_array(MYSQLI_ASSOC)){
     	$rpta.= '<option  value="'.$resultados3['id'].'">'.$resultados3['municipio'].'</option>';
  	}
}

// Desconectarse de la base de datos
$db3->cerrar_conexion();
echo $rpta;	
?>