<?php
function cabecera_inicio(){
	echo '<!doctype html>
		  <html>
          <head>
		  <meta name="google-translate-customization" content="6636be330e3c98d6-689183e1a42d9e92-g77ead15123a9af0a-14"></meta>
		  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
          <title>Administracion de Ligas de Padel miligadepadel.manuelbdv.es</title>
		  <link rel="icon" type="image/png" href="../../images/favicon.ico" />';//cambiar href diferente index del resto
}
//se ha quitado la etiqueta
//<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=8" /><meta http-equiv=”X-UA-Compatible” content=”IE=9” /><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
/*function incluir_general2($jquery,$validaciones){
	echo '<script src="../javascript/detect_index.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="../css/pie.css" />
	';//INCLUYE LA DETECCION DE EL TAMAO DE LA PANTALLA Y EL ESTILO DEL PIE
	
	/*echo '<script src="../javascript/traductor.js" type="text/javascript"></script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	';*/ //DESCOMENTAR PARA HABILITAR EL TRADUCTOR
/*	if($jquery == 1){//INCLUYE LA LIBRERIA JQUERY
		echo '<script src="../javascript/jquery-1.11.1.js" type="text/javascript"></script>';
	}
	if($validaciones == 1){//INCLUYE LA LIBRERIA DE VALIDACIONES
		echo '<script src="../javascript/validaciones.js" type="text/javascript"></script>';
	}
}*/
function incluir_general($jquery,$validaciones){//PARA LOS QUE ESTN DENTRO DE CARPETAS
	/*echo '<script src="../../javascript/detect_index.js" type="text/javascript"></script>*/
	echo '<link rel="stylesheet" type="text/css" href="../../css/pie.css" />
	';//INCLUYE LA DETECCION DE EL TAMAO DE LA PANTALLA Y EL ESTILO DEL PIE
	
	/*echo '<script src="../../javascript/traductor.js" type="text/javascript"></script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	';*/ //DESCOMENTAR PARA HABILITAR EL TRADUCTOR
	if($jquery == 1){//INCLUYE LA LIBRERIA JQUERY
		echo '<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>';
	}
	if($validaciones == 1){//INCLUYE LA LIBRERIA DE VALIDACIONES
		echo '<script src="../../javascript/validaciones.js" type="text/javascript"></script>';
	}
}
function cabecera_fin(){
	echo '</head><body>';
}
function cuerpo_fin(){
	echo '</body></html>';
}
function pie(){
	if($_SESSION['pagina'] == 'index'){
		if($_SESSION['pagina_secundaria'] != ''){//esta navegando desde el index por las paginas basicas
			echo '<div class="pie">
				<div><a class="enlace" href="contacto.php" target="_blank">Contacto</a></div>
				<div><a class="enlace" href="quienessomos.php" target="_blank">Quienes somos</a></div>
				<div><a class="enlace" href="politicayprivacidad.php" target="_blank">Pol&iacute;tica y Privacidad</a></div>
				<div><span class="enlace">miligadepadel &copy; 2016</span></div>
				<!--<div><span class="enlace">Otra</span></div>-->
			</div>';
		}
		else{
			echo '<div class="pie">
				<div><a class="enlace" href="paginas/basicas/contacto.php" target="_blank">Contacto</a></div>
				<div><a class="enlace" href="paginas/basicas/quienessomos.php" target="_blank">Quienes somos</a></div>
				<div><a class="enlace" href="paginas/basicas/politicayprivacidad.php" target="_blank">Pol&iacute;tica y Privacidad</a></div>
				<div><span class="enlace">miligadepadel &copy; 2016</span></div>
				<!--<div><span class="enlace">Otra</span></div>-->
			</div>';
		}//fin if secundaria
	}//fin if index
	else{
		echo '<div class="pie">
				<div><a class="enlace" href="../../paginas/basicas/contacto.php" target="_blank">Contacto</a></div>
				<div><a class="enlace" href="../../paginas/basicas/quienessomos.php" target="_blank">Quienes somos</a></div>
				<div><a class="enlace" href="../../paginas/basicas/politicayprivacidad.php" target="_blank">Pol&iacute;tica y Privacidad</a></div>
				<div><span class="enlace">miligadepadel &copy; 2016</div>
				<!--<div><span class="enlace">Otra</span></div>-->
			</div>';
	}
}


?>