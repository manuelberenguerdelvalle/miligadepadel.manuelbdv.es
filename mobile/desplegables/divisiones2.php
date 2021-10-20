<?php
include_once("../../class/mysql.php");
include_once("../../class/liga.php");
include_once("../../class/division.php");
session_start();
if(isset($_POST["id_liga"])){
	$rpta= '';	
	$db = new MySQL('session');//LIGA PADEL
	$id_liga = $_POST["id_liga"];
	$_SESSION['liga'] = serialize(new Liga($_POST["id_liga"],'','','','','','','','','','','','','','','',''));
	$consulta = $db->consulta("SELECT id_division, num_division FROM division WHERE liga='$id_liga' AND bloqueo = 'N'; ");
	if($consulta->num_rows>0){
		$cont = 0;
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($cont == 0){
				$_SESSION['division'] = serialize(new Division($resultados['id_division'],'','','','','','','',''));
			}
			$rpta.= '<option value="'.$resultados['id_division'].'">'.$resultados['num_division'].'</option>';
			$cont++;
		}
	}
	// Desconectarse de la base de datos
	$db->cerrar_conexion();	
	echo $rpta;
}
else{
	$_SESSION['division'] = serialize(new Division($_POST["id_division"],'','','','','','','',''));
}

?>