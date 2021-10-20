<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
/*if($pagina != 'gestion_pago' && $opcion != 2){
	header ("Location: ../cerrar_sesion.php");
}*/

$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');

/*$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$nombre = $liga->getValor('nombre');
$tipo_pago = $liga->getValor('tipo_pago');

$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_division = $division->getValor('num_division');*/
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_pago_enviado.css" />

</head>
<body>
<div class="cont_principal">	
<?php
if(isset($_SESSION['mensaje_pago'])){
	echo '<div class="caja_pago"><img src="../../../images/ok.png" /><label>'.utf8_decode($_SESSION['mensaje_pago']).'</label></div>';
	unset($_SESSION['mensaje_pago']);
}
if(isset($_SESSION['mensaje_pago_error'])){
	echo '<div class="caja_pago"><img src="../../../images/error.png" /><label>'.utf8_decode($_SESSION['mensaje_pago_error']).'</label></div>';
	unset($_SESSION['mensaje_pago_error']);
}
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT id_liga,nombre,tipo_pago FROM liga WHERE usuario = '$id_usuario' AND bloqueo = 'S' AND pagado = 'N' AND tipo_pago > 0 ORDER BY nombre; ");
if($consulta->num_rows > 0){
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
	$db2 = new MySQL('unicas');//LIGA PADEL
	$consulta2 = $db2->consulta("SELECT * FROM pago_web WHERE usuario = '$id_usuario' AND liga = '".$resultados['id_liga']."' AND pagado = 'N' AND tipo = 'L' ORDER BY pagado,fecha; ");
	//if($consulta2->num_rows > 0){
		$resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC);
				$fec_anyo = substr($resultados2['fecha'],0,4);
				$fec_mes = substr($resultados2['fecha'],5,2);
				$fec_dia = substr($resultados2['fecha'],8,2);
				$fec_lim_anyo = substr($resultados2['fecha_limite'],0,4);
				$fec_lim_mes = substr($resultados2['fecha_limite'],5,2);
				$fec_lim_dia = substr($resultados2['fecha_limite'],8,2);
				if($fec_lim_anyo == '0000'){
					$fec_lim_anyo = '';
					$fec_lim_mes = 'No hay';
					$fec_lim_dia = '';
				}
		$num_division = 1;
		//$num_division = obten_consultaUnCampo('session','id_division','division','liga',$resultados['id_liga'],'num_divis','S','','','','','');
	?>
	<div class="caja"> 
    	<div class="datos">
        	<label class="datos_linea"><?php echo '<b>Liga:</b> '.substr($resultados['nombre'],0,30); if(strlen($resultados['nombre']) >= 30){echo '..';}?></label>
            <label class="datos_linea"><?php echo '<b>Divisi&oacute;n:</b> '.$num_division;?></label> 
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
            <label class="datos_linea">
				<?php 
				if($resultados2['pagado'] == 'S'){echo '<b>Fecha Pago:</b> ';}
				else{echo '<b>Fecha Limite:</b> ';}
				echo $fec_lim_dia.'-'.$fec_lim_mes.'-'.$fec_lim_anyo;
				?>
            </label>
        </div>
    	<div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Pagado:</b> No';?></label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados2['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos2">
        	<label class="datos_linea">

            </label>
            <label class="datos_linea">
				<?php 
					echo '<b>Pack Liga '.obten_equipos($resultados['tipo_pago']).'</b>';
					$descrip_pago = 'Pack Liga Premium '.obten_equipos($resultados['tipo_pago']).': '.$resultados['nombre'];
                ?>
             </label>
        </div>
        <div class="datos3">
        	<?php //muestra_formulario($descrip_pago,$resultados2['id_pago_web'].rand(),$resultados2['precio'],'http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/recibir_pagos/pago_web.php','manu_oamuf-facilitator@hotmail.com');
			//muestra_formulario($descrip_pago,$resultados2['id_pago_web'],$resultados2['precio'],'http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/recibir_pagos/pago_web.php','manu_oamuf-facilitator@hotmail.com'); 
			muestra_formulario($descrip_pago,$resultados2['id_pago_web'],$resultados2['precio'],'http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/recibir_pagos/pago_web.php',cuenta_admin()); 
			?>
            <label class="datos_linea">&nbsp;</label>
        </div>  
	</div>
<?php
	}//fin del while
}//FIN NUM_ROWS
//BUSCO LAS DIVISIONES BLOQUEADAS
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT nombre,id_division,liga,num_division FROM liga,division WHERE usuario = '$id_usuario' AND liga.bloqueo = 'N' AND id_liga = liga AND division.bloqueo = 'S' ; ");
if($consulta->num_rows > 0){
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$db2 = new MySQL('unicas');//LIGA PADEL
		$consulta2 = $db2->consulta("SELECT * FROM pago_web WHERE usuario = '$id_usuario' AND liga = '".$resultados['liga']."' AND division = '".$resultados['id_division']."' AND pagado = 'N' AND tipo = 'D' ORDER BY pagado,fecha; ");
		//if($consulta2->num_rows > 0){
		$resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC);
				$fec_anyo = substr($resultados2['fecha'],0,4);
				$fec_mes = substr($resultados2['fecha'],5,2);
				$fec_dia = substr($resultados2['fecha'],8,2);
				$fec_lim_anyo = substr($resultados2['fecha_limite'],0,4);
				$fec_lim_mes = substr($resultados2['fecha_limite'],5,2);
				$fec_lim_dia = substr($resultados2['fecha_limite'],8,2);
				if($fec_lim_anyo == '0000'){
					$fec_lim_anyo = '';
					$fec_lim_mes = 'No hay';
					$fec_lim_dia = '';
				}
		//$num_division = obten_consultaUnCampo('session','id_division','division','liga',$resultados['id_liga'],'num_divis','S','','','','','');
	?>
	<div class="caja"> 
    	<div class="datos">
        	<label class="datos_linea"><?php echo '<b>Liga:</b> '.substr($resultados['nombre'],0,30); if(strlen($resultados['nombre']) >= 30){echo '..';}?></label>
            <label class="datos_linea"><?php echo '<b>Divisi&oacute;n:</b> '.$resultados['num_division'];?></label> 
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
            <label class="datos_linea">
				<?php 
				if($resultados2['pagado'] == 'S'){echo '<b>Fecha Pago:</b> ';}
				else{echo '<b>Fecha L&iacute;mite:</b> ';}
				echo $fec_lim_dia.'-'.$fec_lim_mes.'-'.$fec_lim_anyo;
				?>
            </label>
        </div>
    	<div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Pagado:</b> No';?></label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados2['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos2">
        	<label class="datos_linea">

            </label>
            <label class="datos_linea">
				<?php 
					echo '<b>Divisi&oacute;n Extra</b>';
						$descrip_pago = 'Pack Division extra: n'.$resultados['num_division'].' en la liga '.$resultados['nombre'];
                ?>
             </label>
        </div>
        <div class="datos3">
        	<?php //muestra_formulario($descrip_pago,$resultados2['id_pago_web'].rand(),$resultados2['precio'],'http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/recibir_pagos/pago_web.php','manu_oamuf-facilitator@hotmail.com');
			muestra_formulario($descrip_pago,$resultados2['id_pago_web'],$resultados2['precio'],'http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/recibir_pagos/pago_web.php',cuenta_admin()); 
			//muestra_formulario($descrip_pago,$resultados2['id_pago_web'],$resultados2['precio'],'http://miligadepadel.manuelbdv.es/mobile/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/recibir_pagos/pago_web.php','manu_oamuf-facilitator@hotmail.com'); 
			?>
        </div>
	</div>
<?php
	}//fin del while
	//}//fin bloqueo liga
}//FIN NUM_ROWS
?>
</div>
</body>
</html>