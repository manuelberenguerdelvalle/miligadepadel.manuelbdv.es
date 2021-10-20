<?php 
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/publicidad_gratis.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if ( $pagina != 'gestion_pago' || $opcion != 1){
	header ("Location: ../cerrar_sesion.php");
}
$id_usuario_publi = $_SESSION['id_usuario_publi'];

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
$db = new MySQL('unicas');//LIGA PADEL
$consulta = $db->consulta("SELECT * FROM pago_web WHERE usuario = '$id_usuario_publi' AND bd = 'ligas' AND tipo = 'P'  AND estado = 'R' ORDER BY pagado,fecha; ");
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
		$provincia = obtenLocalizacion(2,$resultados['liga']);
		$ciudad = obtenLocalizacion(3,$resultados['division']);
		$descrip_pago = 'Suscripcin Publicidad: '.$ciudad.' ('.$provincia.')';
	?>
	<div class="caja"> 
    	<div class="datos">
        	<label class="datos_linea"><?php echo '<b>Provincia:</b> '.substr($provincia,0,30); if(strlen($provincia) >= 30){echo '..';}?></label>
            <label class="datos_linea"><?php echo '<b>Ciudad:</b> '.substr($ciudad,0,30); if(strlen($ciudad) >= 30){echo '..';}?></label> 
        </div>
        <div class="datos2">
        	<label class="datos_linea"><?php echo '<b>Fecha:</b> '.$fec_dia.'-'.$fec_mes.'-'.$fec_anyo;?></label>
            <label class="datos_linea">
				<?php 
				if($resultados['pagado'] == 'S'){echo '<b>Fecha Pago:</b> ';}
				else{echo '<b>Fecha Lmite:</b> ';}
				echo $fec_lim_dia.'-'.$fec_lim_mes.'-'.$fec_lim_anyo;
				?>
            </label>
        </div>
    	<div class="datos3">
        	<label class="datos_linea">
				<?php
					echo '<b>Pagado:</b> '.$pagado;
					if($resultados['pagado'] == 'S'){
						if(strtotime(date('Y-m-d H:i:s')) < (strtotime($resultados['fecha_limite']) + pasar_segundos(3)) ){//si estamos dentro de los 3 das que tenemos informacin
							$res = paypal_PDT_request($resultados['transaccion'],obten_identPaypal());
							$datos_rec = array();
							$datos_rec = obten_arrayResPaypal($res);
							$pos = obten_posicion($datos_rec,'payment_status');
		 					if($pos != -1){$payment_status = obten_resDespuesIgual($datos_rec[$pos]);}
							echo ' ('.obten_estadoPago(strtolower($payment_status)).')';
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
                if($resultados['pagado'] == 'S'){echo '<div class="alinear_titulo"><b>Devolucin:</b>&nbsp;&nbsp;</div><img class="imagen" src="'.$imagen.'">';}
                ?>
            </label>
            <label class="datos_linea">
				<?php
				//echo '<b>Devolucin</b> ';
				?>
             </label>
        </div>
        <div class="datos4">
        	<a href="factura.php?id_pago_web=<?php echo $resultados['id_pago_web'];?>&estado=devolucion" target="_parent" class="boton">&nbsp;&nbsp;Ver Factura&nbsp;&nbsp;</a>
        	<!--<label class="datos_linea_boton">
                <form name="<?php //echo $resultados['id_pago_web'];?>" action="factura.php" method="post" target="_blank">
                    <input type="hidden" name="id_pago_web" value="<?php //echo $resultados['id_pago_web'];?>">
                    <input type="hidden" name="estado" value="devolucion">
                    <input type="submit" value="Ver Devolucin" class="boton">
                </form>
            </label>
        	<label class="datos_linea">&nbsp;</label>-->
        </div>      
	</div>
<?php
	}//fin del while
}//fin de if numero de resultados
?>
</div>
</body>
</html>