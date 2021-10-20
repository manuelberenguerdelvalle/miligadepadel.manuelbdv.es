<?php
include_once ("../../funciones/f_general.php");
session_start();
$pagina = $_SESSION['pagina'];
if ($pagina != 'inscribir_equipo'){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
else{
	if ( isset($_POST['recibir_pago']) && !empty($_POST['recibir_pago']) ){//SI EXISTE POST
		$_SESSION['recibir_pago'] = limpiaTexto($_POST['recibir_pago']);
	}
}//fin else


?>


