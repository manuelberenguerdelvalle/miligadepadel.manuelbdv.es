<?php
include_once ("../funciones/f_html.php");
include_once ("../funciones/f_funciones.php");
include_once ("../class/mysql.php");
include_once ("../class/usuario.php");
include_once ("../class/liga.php");
include_once ("../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if ($pagina != 'index' && $pagina != 'inicio'){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
else{
	$_SESSION['pagina']  = 'inicio';
}
//Se guarda el usuario en la variable sesion
if(isset($_POST["email"])){
	//$password = limpiaTexto(trim(htmlspecialchars($_POST["password"])));
	$email = limpiaTexto(trim(htmlspecialchars($_POST["email"])));
	$_SESSION['usuario'] = new Usuario('','',$email,'','','','','','','','','','','','','','');
}
$usuario = $_SESSION['usuario'];
$fecha = obten_fechahora();
$ip = obten_ip();
$db = new MySQL();
$consulta = $db->consulta("INSERT INTO  `padel`.`conexiones` (`id` ,`usuario` ,`inicio` ,`fin` ,`ip`) VALUES (NULL ,  '$email',  '$fecha', NULL,  '$ip'); ");//Inserta conexion a la bd
if (!isset($_SESSION['liga'])){//si no existe la sesion de liga la creo SOLO PARA INICIO
	$consulta = $db->consulta("SELECT * FROM `liga` WHERE `usuario` = '$email' AND `bloqueo` = 'N' ORDER BY nombre; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	$_SESSION['liga'] = new Liga($resultados['id_liga'],$resultados['nombre'],$resultados['fec_creacion'],$resultados['ciudad'],$resultados['provincia'],$resultados['pais'],$resultados['usuario'],$resultados['tipo_pago'],$resultados['pagado'],$resultados['vista'],$resultados['pass'], $resultados['auto_completar'],$resultados['movimientos'],$resultados['bloqueo'],$resultados['genero'],$resultados['idayvuelta'],$resultados['estilo']);
}
$liga = $_SESSION['liga'];
$id_liga = $liga->getValor('id_liga');
if(!isset($_SESSION['division'])){//Si no existe la sesion de division la creo SOLO PARA INICIO
	$consulta = $db->consulta("SELECT * FROM `division` WHERE `liga` = '$id_liga' ORDER BY num_division; ");
	$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
	$_SESSION['division'] = new Division($resultados['id_division'],$resultados['fec_creacion'],$resultados['precio'],$resultados['liga'],$resultados['suscripcion'],$resultados['num_division'],$resultados['max_equipos'],$resultados['comienzo'],$resultados['bloqueo']);
}
$division = $_SESSION['division'];
$id_division = $division->getValor('id_division');
$db->cerrar_conexion();
cabecera_inicio();
incluir_general(1,0);
?>
<link rel="stylesheet" type="text/css" href="../css/panel_usuario.css" />
<link rel="stylesheet" type="text/css" href="../css/menu_panel_usuario.css" media="screen">
<script src="../javascript/pace.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	cargar("#menuIzq","menu_izq.php");
	cargar(".contenido","contenidos/cont_inicio.php");
	// Parametros para e combo1
   $("#ligas").change(function () {
   		$("#ligas option:selected").each(function () {
			//alert($(this).val());
				id_liga=$(this).val();
				$.post("../desplegables/divisiones.php", { id_liga: id_liga }, function(data){
				$("#divisiones").html(data);
				cargar("#menuIzq","menu_izq.php");
				cargar(".contenido","contenidos/cont_inicio.php");
				//$(".contenido").html("");
			});			
        });
   })
   // Parametros para cambio de division
	$("#divisiones").change(function () {
   		$("#divisiones option:selected").each(function () {
			//alert($(this).val());
				id_division=$(this).val();
				$.post("../desplegables/divisiones.php", { id_division: id_division }, function(data){
				cargar(".contenido","contenidos/cont_inicio.php");
				//$(".contenido").html('cambio de division');
			});			
        });
   })
});
</script>
<script>
function cargar(div, desde){
     $(div).load(desde);
}
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<div class="superior">
    	<div class="nombre_usuario">
        	Bienvenido <?php echo ucfirst($usuario->getValor('nombre')); ?> <a href="cerrar_sesion.php">(Desconectar)</a>
        </div>
        <div class="desplegable_liga">
            <select name="ligas" id="ligas" class="inputText">	
            	<?php desplegable_liga($id_liga);?>
            </select>
        </div>
        <div class="desplegable_division">
        	<select name="divisiones" id="divisiones" class="inputText">
            	<?php desplegable_division($id_liga,$id_division);?>	
			</select>
        </div>
        <div class="cuenta"><a href="">Mi cuenta</a></div>
        <div class="cuenta"><a href="">Contacto</a></div>
        <div class="traductor"><div id="google_translate_element"></div></div>
    </div>
    <div id="menuIzq" class="menuIzq">

    </div>
    <div class="contenido">
		<?php echo $id_liga ?>
    </div>
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>
