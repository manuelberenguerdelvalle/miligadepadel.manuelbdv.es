<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_liga'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$liga = unserialize($_SESSION['liga']);
$tipo_pago = $liga->getValor("tipo_pago");
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');

$opcion = $_SESSION['opcion'];
if($opcion == 2){//mostrar enlace

}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_liga.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<style>
.caja {
	margin: 0 auto;
	width:95%;
	max-width:95%;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:20px;
	/*font-weight:bold;*/
	font-style:italic;
	box-shadow:5px 5px 7px rgba(0,0,0,0.5);
	border:5px #8989FE solid;
	float:left;
}
</style>
</head>
<body>
    <div class="horizontal">&nbsp;</div>
    <div class="horizontal"><span style="color:#006;">Si desea a&nbsp;adir en su sitio web o blog su Liga de Padel puede hacerlo a&ntilde;adiendo el siguiente enlace en c&oacute;digo html.<br>(Recomendable contactar con el responsable t&eacute;cnico de su web o blog).</span></div>
     <div class="horizontal">&nbsp;</div>
    <div class="caja">
	<?php
		if($_SESSION['bd'] == 'admin_liga'){$bd = 0;}
		else{$bd = substr($_SESSION['bd'],-1,1);}
		if($tipo_pago = 0){
			$cadena = 'http://miligadepadel.manuelbdv.es/web/ver_liga/g/noticia.php?a=';
		}
		else{
			$cadena = 'http://miligadepadel.manuelbdv.es/web/ver_liga/p/noticia.php?a=';
		}
		$cadena .= genera_id_url(100,$bd.$id_division.'F',13);
		echo '<br>';
		echo substr($cadena,0,28).'<br>';
		echo substr($cadena,28,26).'<br>';
		echo substr($cadena,54,18).'<br>';
		echo substr($cadena,72,18).'<br>';
		echo substr($cadena,90,18).'<br>';
		echo substr($cadena,108,18).'<br>';
		echo substr($cadena,126,18).'<br>';
		echo substr($cadena,144,18).'<br>';
		echo substr($cadena,162,18).'<br>';
		echo substr($cadena,180).'<br>&nbsp;';
		//echo decodifica(genera_id_url(100,$bd.$id_division.'F',13));
		//echo htmlentities('<iframe align="middle" frameborder="0" src="'.substr($cadena,0,70).'<br>'.substr($cadena,70,60).'<br>'.substr($cadena,130).'<br>'.'" ></iframe>');
	?>
	</div>
</body>
</html>