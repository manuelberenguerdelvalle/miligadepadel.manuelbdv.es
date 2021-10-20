<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
session_start();
$pagina = $_SESSION['pagina'];
$id_jugador = $_SESSION['id_jugador'];
if ( $pagina != 'gestion_pago'){
	header ("Location: ../cerrar_sesion.php");
}
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/pagos.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<!--<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>-->
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/pago_enviado.js" type="text/javascript"></script>
<style>

/*
.datos3{
	border:1px black solid;
}
.datos2{
	border:1px black solid;
}
.datos{
	border:1px black solid;
}*/
</style>
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
//CALCULAR EN QUE BD ESTA EL EQUIPO
$num_bdligas = numero_de_BDligas();
$cont = 0;
$bd = array();
$id_equipo = array();
$num_ligas_pago = 0;
for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
	if($i == 1){
		$_SESSION['bd'] = 'admin_liga';
	}
	else{
		$_SESSION['bd'] = 'admin_liga'.$i;
	}
	$equipos = array();
	$equipos = obten_arrayPerteneceAequipos($id_jugador);//obiene todos los equipos para una bd
	for($j=0; $j<count($equipos); $j++){//FOR EQUIPOS AADE TODOS LOS EQUIPOS DE TODAS LAS BD
		$id_liga = obten_consultaUnCampo('session','liga','equipo','id_equipo',$equipos[$j],'','','','','','','');
		$tipo_pago = obten_consultaUnCampo('session','tipo_pago','liga','id_liga',$id_liga,'','','','','','','');
		if($tipo_pago > 0){
			$num_ligas_pago++;
			$id_equipo[$cont] = $equipos[$j];
			$bd[$cont] = $_SESSION['bd'];
			$cont++;
		}
	}//FIN FOR DE EQUIPO
	
}//FIN FOR BD
if($num_ligas_pago > 0){
	//comienza a mostrar
	$db = new MySQL('unicas_liga');//LIGA PADEL
	for($j=0; $j<$cont; $j++){
		$consulta = $db->consulta("SELECT * FROM pago_admin WHERE equipo = '$id_equipo[$j]' AND bd = '$bd[$j]' ; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$_SESSION['bd'] = $bd[$j];
		$bloqueo = obten_consultaUnCampo('session','bloqueo','liga','id_liga',$resultados['liga'],'','','','','','','');
		$nueva_temporada = obten_consultaUnCampo('session','COUNT(id_nueva_temporada)','nueva_temporada','nueva',$resultados['liga'],'','','','','','','');
		if($bloqueo == 'N' || $nueva_temporada == 0){//evitar el pago de inscripciones bloqueadas por ligas generadas por nuevas temporadas
			$nombre = obten_consultaUnCampo('session','nombre','liga','id_liga',$resultados['liga'],'','','','','','','');
			//$nombre = obten_idUltimoSession('nombre','liga','id_liga',$resultados['liga'],'','','','','','','');
			//$num_division = obten_idUltimoSession('num_division','division','id_division',$resultados['division'],'','','','','','','');
			$num_division = obten_consultaUnCampo('session','num_division','division','id_division',$resultados['division'],'','','','','','','');
			//$liga = new Liga($resultados['liga'],'','','','','','','','','','','','','','','','');
			if($resultados['pagado'] == 'S'){$pagado = 'Si';}
			else{$pagado = 'No';}
			if($resultados['modo_pago'] == 'P'){$imagen = '../../../images/paypal.png';}
			else{$imagen = '../../../images/monedas.png';}
			$fec_anyo = substr($resultados['fecha'],0,4);
			$fec_mes = substr($resultados['fecha'],5,2);
			$fec_dia = substr($resultados['fecha'],8,2);
			if(obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$j],'','','','','','','') == $id_jugador){//est correcto
			//if(obten_idJugador3($id_equipo[$j],'jugador1') == $id_jugador){//est correcto
				$nom_j1 = substr(obtenNombreJugador($id_equipo[$j],'jugador1'),0,35);
				$nom_j2 = substr(obtenNombreJugador($id_equipo[$j],'jugador2'),0,35);
			}
			else{//intercambiamos para que el jugador que accede aparezca como primer jugador
				$nom_j1 = substr(obtenNombreJugador($id_equipo[$j],'jugador2'),0,35);
				$nom_j2 = substr(obtenNombreJugador($id_equipo[$j],'jugador1'),0,35);
			}
	?>
		<div class="caja"> 
			<div class="datos">
				<label class="datos_linea"><?php echo '<b>Liga:</b> '.substr($nombre,0,30); if(strlen($nombre) > 30){echo '..';}?></label>
				<label class="datos_linea"><?php echo '<b>Division:</b> '.$num_division;?></label> 
			</div>
			<div class="datos2">
				<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
				
			</div>
			<div class="datos2">
				<label class="datos_linea">
					<?php 
						echo '<b>Pagado:</b> '.$pagado;
						if($resultados['pagado'] == 'S'){
							echo ' (Completado)';
							/*if(strtotime(date('Y-m-d H:i:s')) < (strtotime($resultados['fecha']) + pasar_segundos(3)) ){//si estamos dentro de los 3 das que tenemos informacin
								$res = paypal_PDT_request($resultados['transaccion'],obten_identPaypal());
								$datos_rec = array();
								$datos_rec = obten_arrayResPaypal($res);
								echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[24]))).')';
							}//fin if dia
							else{
								echo ' (Completado)';
							}*/
						}//fin if pagado
					?>
				</label>
				<label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados['precio'].' &euro;';?></label>
				 <label class="datos_linea">
					<?php 
					if($resultados['pagado'] == 'S'){echo '<div class="alinear_titulo"><b>Tipo:</b>&nbsp;&nbsp;</div><img class="imagen" src="'.$imagen.'">';}
					?>
				</label>
			</div>
			<div class="datos">
				<label class="datos_linea"><b><?php echo substr($nom_j1,0,38);if(strlen($nom_j1) >= 38){echo '..';}?></b></label>
				<label class="datos_linea"><?php echo substr($nom_j2,0,38);if(strlen($nom_j2) >= 38){echo '..';}?></label>
			</div>
			<?php
			if($resultados['pagado'] == 'N'){//pagar
				//DE MOMENTO POR PAYPAL NO ES POSIBLE PAGAR AL ADMIN DE LA LIGA, YA QUE NO SE PROCESA LA VUELTA A LA WEB AUTOMATICAMENTE, SE PROCESA LA VUELTA AL CORREO BUYER YA QUE CADA CUENTA DE ADMINISTRADOR DEBERIA TENER CONFIGURADO EL RETORNO AUTOMATICO Y ES IMPOSIBLE, SOLO DEJAMOS ELMINIAR
			?>
			<div class="datos3">
				<?php
				if($resultados['modo_pago'] == 'P'){//si el modo de pago es online
					//$descrip_pago = 'Inscripcion en Liga: '.$nombre.' division: '.$num_division;
					$descrip_pago = 'Inscripcion en Liga: '.$nombre.' division: '.$num_division.' equipo: '.$nom_j1.' - '.$nom_j2;
					//muestra_formulario($descrip_pago,$resultados['id_pago_admin'],0.5,'http://miligadepadel.manuelbdv.es/mobile/jugador/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php',$resultados['receptor']);
					muestra_formulario(utf8_encode($descrip_pago),$resultados['id_pago_admin'],$resultados['precio'],'http://miligadepadel.manuelbdv.es/mobile/jugador/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php',$resultados['receptor']);
					//muestra_formulario($descrip_pago,$resultados['id_pago_admin'],$resultados['precio'],'http://miligadepadel.manuelbdv.es/mobile/jugador/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php','manu_oamuf-facilitator@hotmail.com');
				}//fin pago online
				else{
					echo '<label class="datos_linea"><b>Pago Presencial</b></label>';
				}
				?>
				
			</div>
			<div class="datos3">
			<?php		
				//NO SE PUEDE IMPLEMETAR POR EL IDENTIFICADOR DE PAYPAL, EN UN FUTURO SI SE HACEN LOS PAGOS A MI CUENTA SI
				//if(obten_consultaUnCampo('unicas_liga','recibir_pago','usuario','id_usuario',$resultados['usuario'],'','','','','','','') != 'M'){
			?>
				<!--<label class="datos_linea_boton">
					<input type="button" onClick="javascript: marcar_pagado(<?php //echo $resultados['id_pago_admin']; ?>);" value="Insertar Pago" class="boton" />
				</label>-->
			<?php //} ?>
				<label class="datos_linea_boton">
					<input type="button" onClick="javascript: eliminar_inscripcion(<?php echo $resultados['id_pago_admin']; ?>);" value="Eliminar" class="boton" />
				</label>
			</div>
			<?php
			}
			else{//ver factura
				if($resultados['precio'] > 0){
			?>
			<div class="datos3">
            	<a href="justificante.php?id_pago_admin=<?php echo $resultados['id_pago_admin'];?>&estado=normal" target="_parent" class="boton">&nbsp;&nbsp;Ver Justificante&nbsp;&nbsp;</a>
				<!--<label class="datos_linea_boton">
				<form name="<?php //echo $resultados['id_pago_admin'];?>" action="justificante.php" method="post" target="_blank">
						<input type="hidden" name="id_pago_admin" value="<?php //echo $resultados['id_pago_admin'];?>">
						<input type="hidden" name="estado" value="normal">
						<input type="submit" value="Ver Justificante" class="boton">
				 </form>
				</label>-->
			</div>
			<?php
				}//fin if
			}//fin else
			?> 
			<br>
			&nbsp;    
		</div>
<?php
		//unset();
		}//fin if incripciones de ligas bloqueadas por nueva temporada
	}//FIN FOR
}//FIN IF NUM_LIGAS_PAGO
else{
	echo '<div class="caja"><div class="datos">No existen pagos enviados</div></div>';
}
?>     
	
</div>
</body>
</html>