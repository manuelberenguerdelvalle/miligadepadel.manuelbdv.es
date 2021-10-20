<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
session_start();
$pagina = $_SESSION['pagina'];
comprobar_pagina_jugador($pagina);
$_SESSION['pagina'] = 'inicio';
if(isset($_POST["email"])){
	//$_SESSION['tipo'] = limpiaTexto(trim(htmlspecialchars($_POST["tipo"])));
	$_SESSION['email'] = limpiaTexto(trim(htmlspecialchars($_POST["email"])));
	$jugador = new Jugador(obten_consultaUnCampo('unicas','id_jugador','jugador','email',$_SESSION['email'],'','','','','','',''),'','','','','','','','','','','','','','','');
}
//$jugador = new Jugador(obten_idJugador2($_SESSION['email']),'','','','','','','','','','','','','','','');
$_SESSION['id_jugador'] = $jugador->getValor('id_jugador');
$_SESSION['genero'] = $jugador->getValor('genero');
$genero = $jugador->getValor('genero');
$_SESSION['jugador'] = serialize($jugador);
$ip = obten_ip();
crear_conexion_jugador($jugador->getValor('id_jugador'),$ip);
$_SESSION['conexion_jugador'] = obten_ultimaConexion_jugador($jugador->getValor('id_jugador'));
if($jugador->getValor('creacion') == 'A'){//updateamos si entra como jugador
	$jugador->setValor('creacion','J');
	$jugador->modificar();
}
cabecera_inicio();
incluir_general(1,1);
?>
<link rel="stylesheet" type="text/css" href="../../css/menu_panel_usuario.css">
<link rel="stylesheet" type="text/css" href="../../css/panel_usuario.css" />
<script src="../../javascript/selects_principales.js" type="text/javascript"></script>
<script src="../../javascript/pace.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	cargar("#menuIzq","../menu_izq.php");
	cargar(".contenido","cont_inicio.php");
});
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<div class="superior">
    	<div class="nombre_usuario">
        	<?php echo $jugador->getValor('nombre'); ?> <a href="../cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="desplegable_liga">&nbsp;</div>
        <div class="desplegable_division">&nbsp;</div>
        <div class="cuenta">&nbsp;</div>
        <div class="cuenta"><a href="">&nbsp;</a></div>
        <div class="traductor"><div id="google_translate_element"></div></div>
    </div>
    <div id="menuIzq" class="menuIzq">

    </div>
    <div class="contenido">
		
    </div>
<?php
$db99 = new MySQL('unicas');//UNICAS
$consulta99 = $db99->consulta("INSERT INTO `accesos` (`id`, `pagina`, `tipo`, `lugar`, `usuario`) VALUES (NULL, 'L', 'M', 'J', ".$_SESSION['id_jugador']."); ");
pie();
?>
</div>
<?php
cuerpo_fin();
?>