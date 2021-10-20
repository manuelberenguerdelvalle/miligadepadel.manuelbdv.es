<?php
session_start();
include_once ("../../class/mysql.php");
//include_once ("../funciones/f_funciones.php");
include_once ("../funciones/f_conexion.php");
include_once ("../funciones/f_fechasHoras.php");
$id = $_SESSION['conexion'];
//echo 'Cerrando sesión.';
//sleep(4);
cerrar_conexion($id);
session_destroy();
header ("Location: http://miligadepadel.manuelbdv.es");
?>