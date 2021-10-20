<?php
session_start();
include_once ("../../class/mysql.php");
include_once ("../funciones/f_conexion.php");
$id = $_SESSION['conexion_jugador'];
//echo 'Cerrando sesión.';
//sleep(4);
cerrar_conexion_jugador($id);
session_destroy();
header ("Location: http://miligadepadel.manuelbdv.es");
?>