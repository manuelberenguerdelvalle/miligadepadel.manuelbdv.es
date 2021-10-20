<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$usuario = unserialize($_SESSION['usuario']);
$email = $usuario->getValor('email');
$id_usuario = $usuario->getValor('id_usuario');
if( $_SESSION['conexion'] != obten_ultimaConexion($id_usuario) ){
	header ("Location: ../cerrar_sesion.php");
}
comprobar_pagina($pagina);
$_SESSION['pagina']  = 'gestion_division';
$opcion = substr(decodifica($_GET["id"]), 12, 1);
$_SESSION['opcion'] = $opcion;
//$liga = unserialize($_SESSION['liga']);
//compruebo liga
if(empty($_SESSION['liga'])){//si liga es vacio
	$id_liga = obten_consultaUnCampo('session','id_liga','liga','usuario',$id_usuario,'bloqueo','N','','','','','');
	if($id_liga != ''){
        $liga = new Liga($id_liga,'','','','','','','','','','','','','','','','');
        $_SESSION['liga'] = serialize($liga);
    }
}
else{//si ya existe cargo de la bd
	//ES NECESARIO CARGAR  DE NUEVO LA LIGA ACTUAL PARA TENER LOS DATOS MÁS RECIENTES
	$liga = unserialize($_SESSION['liga']);
	$id_liga = $liga->getValor("id_liga");
	unset($liga);
	$liga = new Liga($id_liga,'','','','','','','','','','','','','','','','');
	$_SESSION['liga'] = serialize($liga);
}
//compruebo division
if(empty($_SESSION['division'])){//si division es vacia
    $id_division = obten_consultaUnCampo('session','id_division','division','liga',$id_liga,'num_division','1','','','','','');
	if($id_division != '' && $id_liga != ''){
        $division = new Division($id_division,'','','','','','','','');
        $_SESSION['division'] = serialize($division);
    }	
}
else{//si ya existe cargo de bd
	//ES NECESARIO CARGAR  DE NUEVO LA DIVISION ACTUAL PARA TENER LOS DATOS MÁS RECIENTES
    $division = unserialize($_SESSION['division']);
	$id_division = $division->getValor('id_division');
	unset($division);
	$division = new Division($id_division,'','','','','','','','');
	$_SESSION['division'] = serialize($division);
}
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
if($opcion == 0){
	$carga_pagina = 'modificar_division.php';
}
else if($opcion == 1){
	$carga_pagina = 'insertar_division.php';
}
else if($opcion == 2){
	$carga_pagina = 'comenzar_division.php';
}
/*else if($opcion == 3){
	$carga_pagina = 'calendario_division.php';
}*/
else{
	header ("Location: ../cerrar_sesion.php");
}
//GURADAR EN SESSION
$_SESSION['id_division_reload'] = $id_division;
$_SESSION['bd'] =  $usuario->getValor('bd');
$_SESSION['email'] = $email;
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['cuenta_paypal'] = $usuario->getValor('cuenta_paypal');
$_SESSION['id_liga'] = $id_liga;
$_SESSION['id_division'] = $id_division;
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
    	<div class="nombre_usuario"> <a href="../cerrar_sesion.php" >Salir</a></div>
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
