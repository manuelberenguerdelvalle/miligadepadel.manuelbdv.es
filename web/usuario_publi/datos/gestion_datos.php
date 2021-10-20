<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario_publi.php");
session_start();
$pagina = $_SESSION['pagina'];
comprobar_pagina_publicidad($pagina);
$_SESSION['pagina'] = 'gestion_datos';
$usuario_publi = unserialize($_SESSION['usuario_publi']);
if($_SESSION['conexion_usuario_publi'] != obten_ultimaConexion_publicidad($usuario_publi->getValor('id_usuario_publi'))){//modificacion
	header ("Location: ../cerrar_sesion.php");
}
cabecera_inicio();
incluir_general(1,1);
?>
<link rel="stylesheet" type="text/css" href="../../css/panel_usuario.css" />
<script src="../../javascript/selects_principales.js" type="text/javascript"></script>
<script src="../../javascript/pace.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	cargar("#menuIzq","../menu_izq.php");
	cargar(".contenido","modificar_datos.php");
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
pie();
?>
</div>
<?php
cuerpo_fin();
?>
