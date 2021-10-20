<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
session_start();
$pagina = $_SESSION['pagina'];
$id_jugador = $_SESSION['id_jugador'];
$nombre = $_SESSION['nombre'];
$apellidos = $_SESSION['apellidos'];
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
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="javascript/pago_enviado.js" type="text/javascript"></script>

</head>
<body>
<div class="cont_principal">
<?php
//comienza a mostrar
$nom_comp = $nombre.' '.$apellidos;
$db = new MySQL('unicas_liga');//LIGA PADEL
//$consulta = $db->consulta("SELECT * FROM pago_admin WHERE equipo = -1  AND datos LIKE '%$nom_comp%'; ");
$consulta = $db->consulta("SELECT * FROM pago_admin WHERE equipo = -1  AND (jugador1 = '$id_jugador' OR jugador2 = '$id_jugador'); ");
if($consulta->num_rows > 0){
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$_SESSION['bd'] = $resultados['bd'];
		//UPDATE A TODOS LOS PAGOS RECIBIDOS AL VERLOS
		$alerta_pago_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','usuario',$id_jugador,'seccion','pago_recibido.php','leido','N','','','');
		if($alerta_pago_rec > 0){
			realiza_updateGeneral('session','notificacion','leido="S"','liga',$resultados['liga'],'division',$resultados['division'],'seccion','modificar_pago_recibido.php','','','','','');
		}
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
		$nombres = array();
		$nombres = explode('-',$resultados['datos']);
		//echo $nombres[0].'-'.$nombres[1];
		if($nombres[0] == $nom_comp){
			$nom_j1 = $nombres[0];
			$nom_j2 = $nombres[1];
		}
		else{//intercambiamos para que el jugador que accede aparezca como primer jugador
			$nom_j1 = $nombres[1];
			$nom_j2 = $nombres[0];
		}
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
						}//fin if dia
						else{
							echo ' (Completado)';
						}
					}//fin if pagado
				?>
            </label>
            <label class="datos_linea"><?php echo '<b>Precio:</b> '.$resultados['precio'].' &euro;';?></label>
        	
        </div>
        <div class="datos">
        	<label class="datos_linea"><b><?php echo substr($nom_j1,0,23);if(strlen($nom_j1) >= 23){echo '..';}?></b></label>
            <label class="datos_linea"><?php echo substr($nom_j2,0,23);if(strlen($nom_j2) >= 23){echo '..';}?></label>
        </div>
        <div class="datos3">
            <label class="datos_linea">
            <form name="<?php echo $resultados['id_pago_admin'];?>" action="justificante.php" method="post" target="_blank">
                    <input type="hidden" name="id_pago_admin" value="<?php echo $resultados['id_pago_admin'];?>">
                    <input type="hidden" name="estado" value="devolucion">
                    <input type="submit" value="Ver Devolucion" class="boton">
             </form>
            </label>
        	<label class="datos_linea">&nbsp;</label>
        </div>     
	</div>
<?php
	}//FIN WHILE
}//FIN NUM_ROWS
?>     
	
</div>
</body>
</html>