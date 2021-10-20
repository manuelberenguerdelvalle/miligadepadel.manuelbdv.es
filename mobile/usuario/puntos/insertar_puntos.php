<?php
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/puntos.php");
include_once ("../../../class/jugador.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_puntos'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$dni_usuario = $usuario->getValor('dni');
$telefono = $usuario->getValor('telefono');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$id_usuario = $usuario->getValor('id_usuario');
$bd = $usuario->getValor('bd');
//GUARDAR EN SESSION
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['email'] = $usuario->getValor('email');
$_SESSION['id_liga'] = $id_liga;
//$_SESSION['bd_usuario'] = $usuario->getValor('bd');
$opcion = $_SESSION['opcion'];
if($opcion == 1){//modificacion
	$nombre = $liga->getValor("nombre");
	$tipo_pago = $liga->getValor("tipo_pago");
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>www.mitorneodepadel.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_puntos.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/insertar_puntos.js" type="text/javascript"></script>
</head>
<body>
<?php
if($tipo_pago > 0){
?>
<div class="cont_principal">
	<br>
	<div class="horizontal"><div class="titulo"><b>Insertar Puntos Manual</b></div></div>
    <div class="caja1">
<?php
	//obtener el sumatorio de puntos para los distintos jugadores que han jugado para el usuario 
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT jugador1,jugador2 FROM equipo WHERE liga = '$id_liga' AND division = '$id_division' ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['jugador1'] > 0){
			$id_jugador1 = $resultados['jugador1'];
			$jugador1 = new Jugador($resultados['jugador1'],'','','','','','','','','','','','','','','');
			$sumPuntos = obten_consultaUnCampo('unicas','SUM(puntos)','puntos','jugador',$id_jugador1,'usuario',$id_usuario,'bd',$bd,'division',$id_division,'');
			if($sumPuntos == ''){$sumPuntos = 0;}
?>	
		 <div class="columna1">
            <div class="cuadroTexto"><?php echo substr($jugador1->getValor('nombre').' '.$jugador1->getValor('apellidos'),0,13).' (<b>'.$sumPuntos.' pt</b>)';?>&nbsp;</div>
        </div>
        <div id="flotante"></div>
        <div class="columna2">
            <span class="cuadroInputs"><input type="text" name="jugador<?php echo $id_jugador1;?>" id="jugador<?php echo $id_jugador1;?>" value="0" class="input_text_liga" onKeyPress="return numeros(event)"  maxlength="5" ></span>   
        </div>
        <div class="columna3">
            <div class="cuadroComentario"><input type="button" value="+" class="boton" onClick="enviar(<?php echo $id_jugador1;?>,'sumar')" /><input type="button" value="-" class="boton2" onClick="enviar(<?php echo $id_jugador1;?>,'restar')" /></div>
        </div>
        <!--<div class="horizontal">&nbsp;</div>-->
<?php		
		}//fin if jugador1
		if($resultados['jugador2'] > 0){
			$id_jugador2 = $resultados['jugador2'];
			$jugador2 = new Jugador($resultados['jugador2'],'','','','','','','','','','','','','','','');
			$sumPuntos = obten_consultaUnCampo('unicas','SUM(puntos)','puntos','jugador',$id_jugador2,'usuario',$id_usuario,'bd',$bd,'division',$id_division,'');
			if($sumPuntos == ''){$sumPuntos = 0;}
?>	
		 <div class="columna1">
            <div class="cuadroTexto"><?php echo substr($jugador2->getValor('nombre').' '.$jugador2->getValor('apellidos'),0,13).' (<b>'.$sumPuntos.' pt</b>)';?>&nbsp;</div>
        </div>
        <div id="flotante"></div>
        <div class="columna2">
            <span class="cuadroInputs"><input type="text" name="jugador<?php echo $id_jugador2;?>" id="jugador<?php echo $id_jugador2;?>" value="0" class="input_text_liga" onKeyPress="return numeros(event)"  maxlength="5" ></span>   
        </div>
        <div class="columna3">
            <div class="cuadroComentario"><input type="button" value="+" class="boton" onClick="enviar(<?php echo $id_jugador2;?>,'sumar')" /><input type="button" value="-" class="boton2" onClick="enviar(<?php echo $id_jugador2;?>,'restar')" /></div>
        </div>
<?php	
		}//fin if jugador2
	}//fin while
?>
    </div><!-- fin caja1-->
</div>
<?php
}//fin tipo pago
?>

</body>
</html>