<?php
include("../../class/mysql.php");
header("Content-Type: text/html;charset=utf-8");
$rpta="";
$elegido=$_POST["elegido"];
$rpta= '
<option value="">--Provincia--</option>
';
$i = 0;
$provincias = array();
$provincias = obten_localizacionDistintasBds(numero_de_BDligas(),'provincia','provincia','pais','ESP');
//SI HAY MAS BASES DE DATOS HAY QUE CONSULTAR LAS OTRAS BASES DE DATOS Y UNIFICAR RESULTADOS
$db2 = new MySQL('unicas');//UNICAS
for($i=0; $i<count($provincias); $i++){
	$consulta2 = $db2->consulta("SELECT provincia FROM provincias WHERE id = '$provincias[$i]'; ");
	$resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC);
	$rpta.= '<option  value="'.$provincias[$i].'">'.$resultados2['provincia'].'</option>';
}

// Desconectarse de la base de datos
$db2->cerrar_conexion();
echo $rpta;
?>