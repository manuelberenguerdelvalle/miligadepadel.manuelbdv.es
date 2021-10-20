<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if($pagina != 'gestion_pago' || $opcion != 1){
	header ("Location: ../cerrar_sesion.php");
}

$usuario = unserialize($_SESSION['usuario']);
$id_usuario = $usuario->getValor('id_usuario');

$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$nombre = $liga->getValor('nombre');
$tipo_pago = $liga->getValor('tipo_pago');

$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$num_division = $division->getValor('num_division');
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
if($tipo_pago > 0){
	//UPDATE A TODOS LOS PAGOS RECIBIDOS AL VERLOS
	$alerta_pago_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','liga',$id_liga,'division',$id_division,'seccion','modificar_pago_recibido.php','leido','N','');
	if($alerta_pago_rec > 0){
		realiza_updateGeneral('session','notificacion','leido="S"','liga',$id_liga,'division',$id_division,'seccion','modificar_pago_recibido.php','','','','','');
	}
	$db = new MySQL('unicas_liga');
	$consulta = $db->consulta("SELECT * FROM pago_admin WHERE usuario = '$id_usuario' AND estado = 'R' AND liga = '$id_liga' AND division = '$id_division' AND equipo != 0 ORDER BY liga,division,fecha; ");
	if($consulta->num_rows > 0){//modificacion
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($resultados['pagado'] == 'S'){$pagado = 'Si';}
			else{$pagado = 'No';}
			if($resultados['modo_pago'] == 'P'){$imagen = '../../../images/paypal.png';}
			else{$imagen = '../../../images/monedas.png';}
			$fec_anyo = substr($resultados['fecha'],0,4);
			$fec_mes = substr($resultados['fecha'],5,2);
			$fec_dia = substr($resultados['fecha'],8,2);
			$nom_j1 = obtenNombreJugador($resultados['equipo'],'jugador1');
			$nom_j2 = obtenNombreJugador($resultados['equipo'],'jugador2');
	?>
	<div class="caja"> 
    	<div class="datos">
        	<label class="datos_linea"><?php echo '<b>Liga:</b> '.substr($nombre,0,30); if(strlen($nombre) >= 30){echo '..';}?></label>
            <label class="datos_linea"><?php echo '<b>Division:</b> '.$num_division;?></label> 
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
            <label class="datos_linea">
				<?php 
                if($resultados['pagado'] == 'S'){echo '<div class="alinear_titulo"><b>Tipo:</b>&nbsp;&nbsp;</div><img class="imagen" src="'.$imagen.'">';}
                ?>
            </label>
        </div>
    	<div class="datos2">
        	<label class="datos_linea">
			<?php 
				echo '<b>Pagado:</b> '.$pagado;
				/*if($resultados['pagado'] == 'S' && $resultados['modo_pago'] == 'P'){
					if(strtotime(date('Y-m-d H:i:s')) < (strtotime($resultados['fecha']) + pasar_segundos(3)) ){//si estamos dentro de los 3 das que tenemos informacin
						$res = paypal_PDT_request($resultados['transaccion'],obten_identPaypal());
						$datos_rec = array();
						$datos_rec = obten_arrayResPaypal($res);
						$pos = obten_posicion($datos_rec,'payment_status');
		 				if($pos != -1){$payment_status = obten_resDespuesIgual($datos_rec[$pos]);}
						echo ' ('.obten_estadoPago(strtolower($payment_status)).')';
						/*if($resultados['tarjeta'] != ''){//pago con tarjeta
							echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[24]))).')';
						}
						else{//pago con cuenta
							echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[25]))).')';
						}
					}//fin if dias
					else{
						echo ' (Completado)';
					}
				}*/
			?>
            </label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo substr($nom_j1,0,23);if(strlen($nom_j1) >= 23){echo '..';}?></label>
            <label class="datos_linea"><?php echo substr($nom_j2,0,23);if(strlen($nom_j2) >= 23){echo '..';}?></label>
        </div>
		<?php
		if($resultados['pagado'] == 'N'){//pagar
		?>
        <div class="datos3">
            <label class="datos_linea">
            	<?php //echo 'Realizar Pago';?>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>
        <?php
		}
		else{//ver factura
		?>
        <div class="datos3">
            <label class="datos_linea">
                <form name="<?php echo $resultados['id_pago_admin'];?>" action="justificante.php" method="post" target="_blank">
                    <input type="hidden" name="id_pago_admin" value="<?php echo $resultados['id_pago_admin'];?>">
                    <input type="hidden" name="estado" value="normal">
                    <input type="submit" value="Ver Justificante" class="boton">
                </form>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>
        <?php
		}
		?>      
	</div>
<?php
		}//fin del while
	}//fin de if numero de resultados
	//DEVOLUCIONESDE WEB
	$db = new MySQL('unicas');//LIGA PADEL
	$consulta = $db->consulta("SELECT * FROM pago_web WHERE usuario = '$id_usuario' AND estado = 'R' AND liga = '$id_liga' AND division = '$id_division' ORDER BY pagado,fecha; ");
	if($consulta->num_rows > 0){
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($resultados['pagado'] == 'S'){$pagado = 'Si';}
			else{$pagado = 'No';}
			if($resultados['modo_pago'] == 'P'){$imagen = '../../../images/paypal.png';}
			else{$imagen = '../../../images/monedas.png';}
			$fec_anyo = substr($resultados['fecha'],0,4);
			$fec_mes = substr($resultados['fecha'],5,2);
			$fec_dia = substr($resultados['fecha'],8,2);
			$fec_lim_anyo = substr($resultados['fecha_limite'],0,4);
			$fec_lim_mes = substr($resultados['fecha_limite'],5,2);
			$fec_lim_dia = substr($resultados['fecha_limite'],8,2);
			if($fec_lim_anyo == '0000'){
				$fec_lim_anyo = '';
				$fec_lim_mes = 'No hay';
				$fec_lim_dia = '';
			}
?>
	<div class="caja"> 
    	<div class="datos">
        	<label class="datos_linea"><?php echo '<b>Liga:</b> '.substr($nombre,0,30); if(strlen($nombre) >= 30){echo '..';}?></label>
            <label class="datos_linea"><?php echo '<b>Division:</b> '.$num_division;?></label> 
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
            <label class="datos_linea"><?php echo '<b>Fecha Limite:</b> '.$fec_lim_dia.'-'.$fec_lim_mes.'-'.$fec_lim_anyo;?></label>
        </div>
    	<div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Pagado:</b> '.$pagado;?></label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos2">
        	<label class="datos_linea">
				<?php 
                if($resultados['pagado'] == 'S'){echo '<div class="alinear_titulo"><b>Devolucin:</b>&nbsp;&nbsp;</div><img class="imagen" src="'.$imagen.'">';}
                ?>
            </label>
            <label class="datos_linea">
				<?php 
                if($resultados['posicion_publi'] != ''){echo '<b>Posicin Publicidad:</b> '.$resultados['posicion_publi'];}
				else{
					if($resultados['tipo'] == 'L'){echo '<b>Pack Liga '.obten_equipos($tipo_pago).'</b>';}//pago de liga
					if($resultados['tipo'] == 'D'){echo '<b>Divisin Extra</b>';}//division extra
				}
                ?>
             </label>
        </div>
		<?php
		if($resultados['pagado'] == 'N'){//pagar
		?>
        <div class="datos3">
            <label class="datos_linea">
            	<?php //echo 'Realizar Pago';?>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>
        <?php
		}
		else{//ver factura
		?>
        <div class="datos3">
            <label class="datos_linea">
                <form name="<?php echo $resultados['id_pago_web'];?>" action="factura.php" method="post" target="_blank">
                    <input type="hidden" name="id_pago_web" value="<?php echo $resultados['id_pago_web'];?>">
                    <input type="hidden" name="estado" value="devolucion">
                    <input type="submit" value="Ver Devolucion" class="boton">
                </form>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>
        <?php
		}
		?>      
	</div>

<?php
		}//fin del while devolucion
	}//fin de if numero de resultados devolucion
}//FIN IF TIPO_PAGO
?>
</div>
</body>
</html>