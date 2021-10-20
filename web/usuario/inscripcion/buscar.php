<?php
include_once ("../../../class/mysql.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$genero = $_SESSION['genero'];

if ( $pagina != 'gestion_inscripcion' || $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	$buscar = $_POST['b'];
	$jugador = $_POST['jugador'];
	if(!empty($buscar) && !empty($jugador)) {
		if($jugador == 1 && $genero == 'A'){$genero = 'M';}
		if($jugador == 2 && $genero == 'A'){$genero = 'F';}
		//echo $buscar.'-'.$jugador.'-'.$genero;
		buscar_jugador($buscar,$jugador,$genero);
	}	
}//fin else
	function buscar_jugador($b,$jugador,$genero) {
			$texto = '';
			$troceada = explode(" ",$b);
			$num_cadenas = count($troceada);
			$db = new MySQL('unicas');//UNICAS
			if($num_cadenas == 0){
				echo 'hola';
			}
			else if($num_cadenas == 1){
				$consulta = $db->consulta("SELECT id_jugador,nombre,apellidos,email FROM jugador WHERE genero = '".$genero."' AND estado = 0 AND (nombre LIKE '%".$troceada[0]."%' OR apellidos LIKE '%".$troceada[0]."%') ORDER BY nombre,apellidos,email; ");
			}
			else if($num_cadenas == 2){
				echo 'hola'.$troceada[0];
				$consulta = $db->consulta("SELECT id_jugador,nombre,apellidos,email FROM jugador WHERE  genero = '".$genero."' AND estado = 0 AND (nombre LIKE '%".$troceada[0]."%' OR nombre LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[0]."%' OR apellidos LIKE '%".$troceada[1]."%') ORDER BY nombre,apellidos,email; ");
			}
			else if($num_cadenas == 3){
				$consulta = $db->consulta("SELECT id_jugador,nombre,apellidos,email FROM jugador WHERE genero = '".$genero."' AND estado = 0 AND (nombre LIKE '%".$troceada[0]."%' OR nombre LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[0]."%' OR apellidos LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[2]."%') ORDER BY nombre,apellidos,email; ");
			}
			else if($num_cadenas == 4){
				$consulta = $db->consulta("SELECT id_jugador,nombre,apellidos,email FROM jugador WHERE genero = '".$genero."' AND estado = 0 AND (nombre LIKE '%".$troceada[0]."%' OR nombre LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[2]."%' OR apellidos LIKE '%".$troceada[3]."%') ORDER BY nombre,apellidos,email; ");
			}
			else{//mas de 5 cadenas
				$consulta = $db->consulta("SELECT id_jugador,nombre,apellidos,email FROM jugador WHERE genero = '".$genero."' AND estado = 0 AND (nombre LIKE '%".$troceada[0]."%' OR nombre LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[1]."%' OR apellidos LIKE '%".$troceada[2]."%' OR apellidos LIKE '%".$troceada[3]."%' OR apellidos LIKE '%".$troceada[4]."%') ORDER BY nombre,apellidos,email; ");
			}
			//$consulta = $db->consulta("SELECT * FROM jugador WHERE nombre LIKE '%".$b."%' OR apellidos LIKE '%".$b."%' ; ");
			$contar = $consulta->num_rows;
			if($contar == 0){
				$texto = "<div class='resultado'>No se han encontrado resultados para '<b>".$b."</b>'.</div>";
			}else{
				while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
					$datos = ucfirst($resultados['nombre']." ".$resultados['apellidos']);
					if($resultados['email'] != ''){
						$datos .= " - ".$resultados['email'];
					}
					$texto .= "<div class='resultado' onclick='javascript: seleccionar(".$resultados['id_jugador'].",".'"'.$datos.'"'.",".$jugador.")'>".substr($datos,0,45)."</div>";
				 }//fin while
			}//fin else
			echo $texto;
		}//fin buscar
?>