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
if($pagina != 'gestion_pago' && $opcion != 0){
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
if(isset($_SESSION['mensaje_pago'])){
	echo '<div class="caja_pago"><img src="../../../images/ok.png" /><label>'.utf8_decode($_SESSION['mensaje_pago']).'</label></div>';
	unset($_SESSION['mensaje_pago']);
}
if(isset($_SESSION['mensaje_pago_error'])){
	echo '<div class="caja_pago"><img src="../../../images/error.png" /><label>'.utf8_decode($_SESSION['mensaje_pago_error']).'</label></div>';
	unset($_SESSION['mensaje_pago_error']);
}
if($tipo_pago > 0){
	$db = new MySQL('unicas');//LIGA PADEL
	$consulta = $db->consulta("SELECT * FROM pago_web WHERE usuario = '$id_usuario' AND estado = 'E' AND liga = '$id_liga' AND division = '$id_division' ORDER BY pagado,fecha; ");
	if($consulta->num_rows > 0){
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($resultados['pagado'] == 'S'){$pagado = 'Si';}
			else{$pagado = 'No';}
			if($resultados['modo_pago'] == 'P'){$imagen = '../../../images/paypal.png';}
			else if($resultados['modo_pago'] == 'M'){$imagen = '../../../images/monedas.png';}
			else{$imagen = '../../../images/free.png';}
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
        	<label class="datos_linea"><?php echo '<b>Liga:</b> '.substr($nombre,0,30); if(strlen($nombre) > 30){echo '..';}?></label>
            <label class="datos_linea">
				<?php
				if( $resultados['tipo'] == 'I'){echo '<b>Division:</b> Todas';}//si es ida y vuelta 
				else{echo '<b>Division:</b> '.$num_division;}
				?>
              </label> 
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
            <label class="datos_linea">
				<?php 
				if($resultados['pagado'] == 'S'){echo '<b>Fecha Pago:</b> ';}
				else{echo '<b>Fecha Limite:</b> ';}
				echo $fec_lim_dia.'-'.$fec_lim_mes.'-'.$fec_lim_anyo;
				?>
            </label>
        </div>
    	<div class="datos2">
        	<label class="datos_linea">
			<?php
				//AQUI SI ACTIVAMOS PORQUE SON LOS PAGOS A MI, Y SI QUE SE PUEDEN REVISAR
				echo '<b>Pagado:</b> '.$pagado;
				if($resultados['pagado'] == 'S' && $resultados['modo_pago'] == 'P'){
					if(strtotime(date('Y-m-d H:i:s')) < (strtotime($resultados['fecha_limite']) + pasar_segundos(3)) ){//si estamos dentro de los 3 das que tenemos informacin
						if($resultados['transaccion'] != ''){
							$res = paypal_PDT_request($resultados['transaccion'],obten_identPaypal());
							$datos_rec = array();
							$datos_rec = obten_arrayResPaypal($res);
							$pos = obten_posicion($datos_rec,'payment_status');
		 					if($pos != -1){$payment_status = obten_resDespuesIgual($datos_rec[$pos]);}
							echo ' ('.obten_estadoPago(strtolower($payment_status)).')';
							//if($resultados['tarjeta'] == ''){//pago con tarjeta
							/*if(obten_resDespuesIgual($datos_rec[25]) == ''){//para pago completado
								echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[24]))).')';
							}
							else{//para pago pendiente
								echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[25]))).')';
							}*/
						}
					}//fin de if dias
					else{
						echo ' (Completado)';
					}
				}//fin de pagado
			?>
            </label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos2">
        	<label class="datos_linea">
				<?php 
                if($resultados['pagado'] == 'S'){echo '<div class="alinear_titulo"><b>Tipo:</b>&nbsp;&nbsp;</div><img class="imagen" src="'.$imagen.'">';}
                ?>
            </label>
            <label class="datos_linea">
				<?php 
                if($resultados['posicion_publi'] != ''){
					echo '<b>Posicion Publicidad:</b> '.$resultados['posicion_publi'];
					$descrip_pago = 'Publicidad: Posicion '.$resultados['posicion_publi'].' en la liga '.$nombre;
				}
				else{
					if($resultados['tipo'] == 'L'){
						echo '<b>Pack Liga '.obten_equipos($tipo_pago).'</b>';
						$descrip_pago = 'Pack Liga '.obten_equipos($tipo_pago).': '.$nombre;
					}//pago de liga
					if($resultados['tipo'] == 'D'){
						echo '<b>Division Extra</b>';
						$descrip_pago = 'Pack Division extra: numero '.$num_division.' en la liga '.$nombre;
					}//division extra
					if($resultados['tipo'] == 'I'){
						echo '<b>Ida y vuelta</b>';
						$descrip_pago = 'Pack Ida y vuelta: en la liga '.$nombre;
					}//division extra
				}
                ?>
             </label>
        </div>
		<?php
		if($resultados['pagado'] == 'N'){//pagar
		?>
        <div class="datos3">
        	<?php
				//muestra_formulario($descrip_pago,$resultados['id_pago_web'],0.50,'http://miligadepadel.manuelbdv.es/web/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/web/recibir_pagos/pago_web.php',cuenta_admin()); 
				muestra_formulario($descrip_pago,$resultados['id_pago_web'],$resultados['precio'],'http://miligadepadel.manuelbdv.es/web/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/web/recibir_pagos/pago_web.php',cuenta_admin()); 
				//muestra_formulario($descrip_pago,$resultados['id_pago_web'],$resultados['precio'],'http://miligadepadel.manuelbdv.es/web/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/web/ep/pw.php','manu_oamuf-facilitator@hotmail.com');  
			//muestra_formulario($descrip_pago,$resultados['id_pago_web'].rand(),$resultados['precio'],'http://miligadepadel.manuelbdv.es/web/usuario/pago/gestion_pago.php','http://miligadepadel.manuelbdv.es/web/recibir_pagos/pago_web.php','manu_oamuf-facilitator@hotmail.com');
			 ?>
        </div>
        <?php
		}
		else if($resultados['modo_pago'] != 'G'){//ver factura
		?>
        <div class="datos3">
            <label class="datos_linea_boton">
                <form name="<?php echo $resultados['id_pago_web'];?>" action="factura.php" method="post" target="_blank">
                    <input type="hidden" name="id_pago_web" value="<?php echo $resultados['id_pago_web'];?>">
                    <input type="hidden" name="estado" value="normal">
                    <input type="submit" value="Ver Factura" class="boton">
                </form>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>
        <?php
		}
		else{}
		?>      
	</div>
<?php
		}//fin del while
	}//fin de if numero de resultados
//DEVOLUCIONES A JUGADORES
	$db = new MySQL('unicas_liga');
	$consulta = $db->consulta("SELECT * FROM pago_admin WHERE usuario = '$id_usuario' AND estado = 'E' AND liga = '$id_liga' AND division = '$id_division' AND equipo != 0 ORDER BY liga,division,fecha; ");
	if($consulta->num_rows > 0){
		while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			if($resultados['pagado'] == 'S'){$pagado = 'Si';}
			else{$pagado = 'No';}
			if($resultados['modo_pago'] == 'P'){$imagen = '../../../images/paypal.png';}
			else{$imagen = '../../../images/monedas.png';}
			$fec_anyo = substr($resultados['fecha'],0,4);
			$fec_mes = substr($resultados['fecha'],5,2);
			$fec_dia = substr($resultados['fecha'],8,2);
			$nom = array();
			$nom = explode('-',$resultados['datos']);
			$nom_j1 = $nom[0];
			$nom_j2 = $nom[1];
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
                if($resultados['pagado'] == 'S'){echo '<div class="alinear_titulo"><b>Devolucin:</b>&nbsp;&nbsp;</div><img class="imagen" src="'.$imagen.'">';}
                ?>
            </label>
        </div>
    	<div class="datos2">
        	<label class="datos_linea">
			<?php 
				echo '<b>Pagado:</b> '.$pagado;
				if($resultados['pagado'] == 'S'){
					if(strtotime(date('Y-m-d H:i:s')) < (strtotime($resultados['fecha']) + pasar_segundos(3)) ){//si estamos dentro de los 3 das que tenemos informacin
						$res = paypal_PDT_request($resultados['transaccion'],obten_identPaypal());
						$datos_rec = array();
						$datos_rec = obten_arrayResPaypal($res);
						$pos = obten_posicion($datos_rec,'payment_status');
		 				if($pos != -1){$payment_status = obten_resDespuesIgual($datos_rec[$pos]);}
						echo ' ('.obten_estadoPago(strtolower($payment_status)).')';
						/*if($resultados['tarjeta'] != ''){//pago con tarjeta
							echo ' ('.obten_estadoPago(strtolower($datos_rec[7])).')';
						}
						else{//pago con cuenta
							echo ' ('.obten_estadoPago(strtolower($datos_rec[25])).')';
						}*/
					}//fin if dia
					else{
						echo ' (Completado)';
					}
				}//fin if pagado
			?>
            </label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo substr($nom_j1,0,23);if(strlen($nom_j1) >= 23){echo '..';}?></label>
            <label class="datos_linea"><?php echo substr($nom_j2,0,23);if(strlen($nom_j2) >= 23){echo '..';}?></label>
        </div>
        <div class="datos3">
        <!-- EL PAGO ENVIADO SIEMPRE ES UNA ELIMINACIN, Y ESTA NO SE EFECTUA SI NO SE HA PAGADO, CON LO CUAL SIEMPRE ESTARA PAGADA-->
            <label class="datos_linea_boton">
                <form name="<?php echo $resultados['id_pago_admin'];?>" action="justificante.php" method="post" target="_blank">
                    <input type="hidden" name="id_pago_admin" value="<?php echo $resultados['id_pago_admin'];?>">
                    <input type="hidden" name="estado" value="devolucion">
                    <input type="submit" value="Ver Devolucin" class="boton">
                </form>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>     
	</div>

<?php
		}//fin del while devoluciones
	}//fin de if numero de devoluciones
}//FIN IF TIPO_PAGO
?>
</div>
</body>
</html>