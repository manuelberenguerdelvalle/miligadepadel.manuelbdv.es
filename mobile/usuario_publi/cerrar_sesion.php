<?php
session_start();
include_once ("../../class/mysql.php");
include_once ("../funciones/f_conexion.php");
$id = $_SESSION['conexion_usuario_publi'];
cerrar_conexion_publicidad($id);
session_destroy();
header ("Location: http://miligadepadel.manuelbdv.es");
?>