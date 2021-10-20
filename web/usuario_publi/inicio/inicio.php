<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario_publi.php");
session_start();
$pagina = $_SESSION['pagina'];
comprobar_pagina_publicidad($pagina);
$_SESSION['pagina'] = 'inicio';
//$_SESSION['tipo'] = limpiaTexto(trim(htmlspecialchars($_POST["tipo"])));
if(isset($_POST["email"])){
	$email = limpiaTexto(trim(htmlspecialchars($_POST["email"])));
	$usuario_publi = new Usuario_publi('',$email,'','','','','','','','','','','','','','');
}
//$usuario_publi = new Usuario_publi('','','',limpiaTexto(trim(htmlspecialchars($_POST["email"]))),'','','','','','','','','','','','');
$_SESSION['id_usuario_publi'] = $usuario_publi->getValor('id_usuario_publi');
$_SESSION['usuario_publi'] = serialize($usuario_publi);
$ip = obten_ip();
crear_conexion_publicidad($usuario_publi->getValor('id_usuario_publi'),$ip);
$_SESSION['conexion_usuario_publi'] = obten_ultimaConexion_publicidad($usuario_publi->getValor('id_usuario_publi'));
cabecera_inicio();
incluir_general(1,1);
?>
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
        	Bienvenido <?php echo ucwords($usuario_publi->getValor('nombre')); ?> <a href="../cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="desplegable_liga">&nbsp;</div>
        <div class="desplegable_division">&nbsp;</div>
        <div class="cuenta">&nbsp;</div>
        <div class="cuenta"><a href="">Contacto</a></div>
        <div class="traductor"><div id="google_translate_element"></div></div>
    </div>
    <div id="menuIzq" class="menuIzq">

    </div>
    <div class="contenido">
		
    </div>
<?php
$db99 = new MySQL('unicas');//UNICAS
$consulta99 = $db99->consulta("INSERT INTO `accesos` (`id`, `pagina`, `tipo`, `lugar`, `usuario`) VALUES (NULL, 'L', 'W', 'P', ".$_SESSION['id_usuario_publi']."); ");
pie();
?>
</div>
<?php
cuerpo_fin();
?>