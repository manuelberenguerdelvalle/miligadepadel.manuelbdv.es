<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if ($pagina != 'index' && $pagina != 'inicio'){
	header ("Location: ../cerrar_sesion.php");
}
else{
	$_SESSION['pagina']  = 'inicio';
}
//Se guarda el usuario en la variable sesion
if(isset($_POST["email"])){
	$email = limpiaTexto(trim(htmlspecialchars($_POST["email"])));
	$usuario = new Usuario('','','',$email,'','','','','','','','','','','','','');
}
//SESSION USUARIO
$_SESSION['usuario'] = serialize($usuario);
$_SESSION['bd'] = $usuario->getValor('bd');
$id_usuario = $usuario->getValor('id_usuario');

//COMPROBAR SI  HAY LIGAS O DIVISIONES NO PAGADAS FUERA DE TIEMPO Y BLOQUEAR
$db = new MySQL('unicas');//LIGA
$consulta = $db->consulta("SELECT liga,division,tipo,fecha_limite FROM `pago_web` WHERE `usuario` = '$id_usuario' AND `pagado` = 'N' AND (tipo = 'L' OR tipo = 'D'); ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//LIGAS DE PAGO bloqueo de ligas y divisiones pasada fecha limite
	if(strtotime(date('Y-m-d H:i:s')) > strtotime($resultados['fecha_limite']) ){
		$tipo = $resultados['tipo'];
		if($tipo == 'L'){//liga
			realiza_updateGeneral('session','liga','bloqueo = "S"','id_liga',$resultados['liga'],'','','','','','','','','');
		}
		else if($tipo == 'D'){//division
			realiza_updateGeneral('session','division','bloqueo="S"','id_division',$resultados['division'],'','','','','','','','','');
		}
		else{// NO DEBE ENTRAR NUNCA
		}
	}//fin if
}//fin while

//COMPROBAR SI HAY LIGAS GRATIS  SIN PAGOS Y PASADOS 3 DIAS Y MARCAR COMO PAGADA Y LA DIVISION 1
//ADEMAS LIMPIAR DATOS EN CASO DE QUE SE HAYA CAMBIADO DE UNA LIGA A OTRA.
$db = new MySQL('session');//LIGA
$consulta = $db->consulta("SELECT id_liga,fec_creacion FROM `liga` WHERE `usuario` = '$id_usuario' AND `pagado` = 'N' AND tipo_pago = 0; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//LIGAS GRATIS
	if(strtotime(date('Y-m-d H:i:s')) > (strtotime($resultados['fec_creacion']) + pasar_segundos(3)) ){//comprobamos que ha pasado los 3 dias de la creacion
		realiza_updateGeneral('session','liga','pagado = "S"','id_liga',$resultados['id_liga'],'','','','','','','','','');//marco liga a pagado
		//ELIMINAR DATOS POR SI HA CAMBIADO DE PAGO A GRATIS
		$num_divs = obten_consultaUnCampo('session','COUNT(id_division)','division','liga',$resultados['id_liga'],'','','','','','','');
		for($i = $num_divs; $i > 1; $i--){//si tiene divisiones QUE NO SEA LA 1 para borrar
			$id_division = obten_consultaUnCampo('session','id_division','division','liga',$resultados['id_liga'],'num_division',$i,'','','','','');
			//COMPROBAR si es posible generar, y despues cambiar de liga y si no es posible BORRAR
			//deletes por division
			realiza_deleteGeneral('session','premio','division',$id_division,'','','','','','','','','');
			realiza_deleteGeneral('session','noticia','liga',$resultados['id_liga'],'division',$id_division,'','','','','','','');
			realiza_deleteGeneral('session','division','id_division',$id_division,'','','','','','','','','');
		}//fin for
		///deletes por liga
		realiza_deleteGeneral('session','arbitro','liga',$resultados['id_liga'],'','','','','','','','','');
		realiza_deleteGeneral('session','pista','liga',$resultados['id_liga'],'','','','','','','','','');
		realiza_deleteGeneral('unicas','pago_web','liga',$resultados['id_liga'],'bd',$usuario->getValor('bd'),'','','','','','','');
	}//fin if
}//fin while

//AÑADIR AUTOMATIZADO DE LIMPIEZA DE LIGAS GRATIS DESPUES DE 1 MESES GRATIS Y PAGO POR REVISAR
$db = new MySQL('session');//LIGA
$consulta = $db->consulta("SELECT id_liga,id_division FROM liga,division WHERE usuario = '$id_usuario' AND tipo_pago = 0 AND id_liga = liga AND comienzo = 'S'; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//LIGAS GRATIS
	//comprobar que los partidos el campo ganador se insertan todos a 0
	$num_act = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$resultados['id_division'],'estado','0','','','','','');
	if($num_act == 0){//si todos los estados de partidos es diferente a  0, division finalizada
		$ultimo_fecha = obten_consultaUnCampo('session','fecha','partido','division',$resultados['id_division'],'','','','','','','ORDER BY fecha DESC LIMIT 1');
		if( strtotime(date('Y-m-d H:i:s')) > (strtotime($ultimo_fecha.' 00:00:00') + pasar_segundos(30)) ){//si fecha de hoy es mayor a la última fecha de partido jugado + 30 dias BORRO LIGA
			//deletes
			realiza_deleteGeneral('session','noticia','liga',$resultados['id_liga'],'','','','','','','','','');
			realiza_deleteGeneral('session','regla','liga',$resultados['id_liga'],'','','','','','','','','');
			realiza_deleteGeneral('session','disputa','division',$resultados['id_division'],'','','','','','','','','');
			//realiza_deleteGeneral('session','sancion_equipo','division',$resultados['id_division'],'','','','','','','','','');
			//Aqui hay que ir por equipo
			realiza_deleteGeneral('session','premio','division',$resultados['id_division'],'','','','','','','','','');
			realiza_deleteGeneral('session','partido','division',$resultados['id_division'],'','','','','','','','','');
			realiza_deleteGeneral('session','inscripcion','liga',$resultados['id_liga'],'','','','','','','','','');
			realiza_deleteGeneral('session','equipo','liga',$resultados['id_liga'],'','','','','','','','','');
			realiza_deleteGeneral('session','division','id_division',$resultados['id_division'],'','','','','','','','','');
			realiza_deleteGeneral('session','liga','id_liga',$resultados['id_liga'],'','','','','','','','','');
		} //fin if pasados 30 dias
	}// fin if estado != 0
}//fin WHILE LIGAS
//CREAR CONEXION
if(!isset($_SESSION['conexion']) || empty($_SESSION['conexion'])){
	$ip = obten_ip();
	crear_conexion($id_usuario,$ip);
	$_SESSION['conexion'] = obten_ultimaConexion($id_usuario);
}

$db = new MySQL('session');//LIGA
//CARGO LIGA
if (isset($_SESSION['liga']) && !empty($_SESSION['liga'])){//si ya existe
	$liga = unserialize($_SESSION['liga']);
	$id_liga = $liga->getValor('id_liga');
}
else{//no existe
	$consulta = $db->consulta("SELECT * FROM `liga` WHERE `usuario` = '$id_usuario' AND `bloqueo` = 'N' ORDER BY nombre; ");
	if($consulta->num_rows > 0){//si hay al menos 1
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$liga = new Liga($resultados['id_liga'],$resultados['nombre'],$resultados['fec_creacion'],$resultados['ciudad'],$resultados['provincia'],$resultados['pais'],$resultados['usuario'],$resultados['tipo_pago'],$resultados['pagado'],$resultados['vista'],$resultados['pass'], $resultados['auto_completar'],$resultados['movimientos'],$resultados['bloqueo'],$resultados['genero'],$resultados['idayvuelta'],$resultados['estilo']);
		 $_SESSION['liga'] = serialize($liga);
		 $id_liga = $liga->getValor('id_liga');
	}
	else{
		$_SESSION['liga'] = '';
	}
}

if(!empty($_SESSION['liga']) && !empty($_SESSION['division'])){
	$division = unserialize($_SESSION['division']);
	$id_division = $division->getValor('id_division');
}
else if(!empty($_SESSION['liga'])){//si ya existe
	//CARGO DIVISION
		$consulta = $db->consulta("SELECT * FROM `division` WHERE `liga` = '$id_liga' ORDER BY num_division; ");
		if($consulta->num_rows > 0){//si hay al menos una division
			$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
			$division = new Division($resultados['id_division'],$resultados['fec_creacion'],$resultados['precio'],$resultados['liga'],$resultados['suscripcion'],$resultados['num_division'],$resultados['max_equipos'],$resultados['comienzo'],$resultados['bloqueo']);
			$id_division = $division->getValor('id_division');
			$_SESSION['division'] = serialize($division);
		}
}
else{
	$_SESSION['division'] = '';
}

cabecera_inicio();
incluir_general(1,0);
?>
<link rel="stylesheet" type="text/css" href="../../css/panel_usuario.css" />
<script src="../../javascript/selects_principales.js" type="text/javascript"></script>
<script src="../../javascript/pace.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	cargar("#menuIzq","../menu_izq.php");
	cargar(".contenido","cont_inicio.php");
	ligasydivisiones("cont_inicio.php");
});
</script>
<style>

</style>
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
		<?php echo $id_liga ?>
    </div>
<?php
$db99 = new MySQL('unicas');//UNICAS
$consulta99 = $db99->consulta("INSERT INTO `accesos` (`id`, `pagina`, `tipo`, `lugar`, `usuario`) VALUES (NULL, 'L', 'M', 'U', '$id_usuario'); ");
pie();
?>
</div>
<?php
cuerpo_fin();
?>
