<?php
/***VARIABLES POR POST ***/

$numero = count($_POST);
$nomVariables = array_keys($_POST); // obtiene los nombres de las varibles
$valores = array_values($_POST);// obtiene los valores de las varibles

// crea las variables y les asigna el valor
for($i=0;$i<$numero;$i++){ 
	$$nomVariables[$i]=utf8_decode(limpiaTexto(trim($valores[$i])));
}
?>