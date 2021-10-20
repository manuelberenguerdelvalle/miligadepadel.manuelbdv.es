<?php
include_once("../../class/mysql.php");
include_once("../../class/liga.php");
include_once("../../class/division.php");
session_start();
if(isset($_POST["id_liga"])){
	$rpta= '';	
	$db = new MySQL('session');//LIGA PADEL
	//limpiar el post con la funcion
	$id_liga = $_POST["id_liga"];
	$liga = new Liga($id_liga,'','','','','','','','','','','','','','','','');
	if($liga->getValor('bloqueo') == 'N'){//se comprueba que no esté bloqueada
		$_SESSION['liga'] = serialize($liga);
	}
	$division = new Division('','','',$id_liga,'',1,'','','');
	if($division->getValor('bloqueo') == 'N'){
		$_SESSION['division'] = serialize($division);
	}
	$consulta = $db->consulta("SELECT id_division, num_division FROM division WHERE liga='$id_liga' AND bloqueo = 'N'; ");
	if($consulta->num_rows>0){//para mostrar la lista de divisiones
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($resultados['num_division'] == 1){//si es la division 1
				$rpta.= '<option selected="selected" value="'.$resultados['id_division'].'">'.$resultados['num_division'].'</option>';
			}
			else{
				$rpta.= '<option value="'.$resultados['id_division'].'">'.$resultados['num_division'].'</option>';
			}
		}
	}
	// Desconectarse de la base de datos
	$db->cerrar_conexion();	
	echo $rpta;
}
else{//si es cambio de division
	//limpiar post
	$_SESSION['division'] = serialize(new Division($_POST["id_division"],'','','','','','','',''));
}

?>