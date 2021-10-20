<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');
if( $_SESSION['conexion'] != obten_ultimaConexion($id_usuario) ){
	header ("Location: ../cerrar_sesion.php");
}
comprobar_pagina($pagina);
$_SESSION['pagina']  = 'gestion_noticia';
$opcion = substr(decodifica($_GET["id"]), 12, 1);
$_SESSION['opcion'] = $opcion;
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$_SESSION['bd'] = $usuario->getValor('bd');
if($opcion == 0){
	$carga_pagina = 'modificar_noticia.php';
}
else if($opcion == 1){
	$carga_pagina = 'insertar_noticia.php';
}
else{
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
	cargar(".contenido","<?php echo $carga_pagina; ?>");
	ligasydivisiones("<?php echo $carga_pagina; ?>");
});
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<div class="superior">
    	<div class="nombre_usuario">
        	Bienvenido <?php echo ucfirst($usuario->getValor('nombre')); ?> <a href="../cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="desplegable_liga">
             <?php 
			if(!empty($_SESSION['liga'])){
				echo '<select name="ligas" id="ligas" class="inputText">';
				desplegable_liga($id_usuario,$id_liga);
				echo '</select>';
			}
			?>
        </div>
        <div class="desplegable_division">
        	<?php 
			if(!empty($_SESSION['liga'])){
				echo '<select name="divisiones" id="divisiones" class="inputText">';
				desplegable_division($id_liga,$id_division);
				echo '</select>';
			}
			?>
        </div>
        <div class="cuenta"><a href="../cuenta/gestion_cuenta.php">Mi cuenta</a></div>
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
