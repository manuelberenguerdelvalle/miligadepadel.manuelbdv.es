<?php
include_once ("../../class/mysql.php");
include_once ("../funciones/f_general.php");
include_once ("../funciones/f_obten.php");
include_once ("../funciones/f_secundarias.php");
include_once ("../../class/usuario.php");
session_start();
$id_jugador = $_SESSION['id_jugador'];
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="../../css/menu_panel_usuario.css" media="screen">
</head>
<body>
<?php
$num_bdligas = numero_de_BDligas();
$alerta_pago_env = 0;
$alerta_pago_rec = 0;
$num_ligas_activas = 0;
for($i=1; $i<=$num_bdligas; $i++){//FOR BD 
	if($i == 1){
		$_SESSION['bd'] = 'admin_liga';
	}
	else{
		$_SESSION['bd'] = 'admin_liga'.$i;
	}
	$equipos = array();
	$equipos = obten_arrayPerteneceAequipos($id_jugador);
	for($j=0; $j<count($equipos); $j++){//FOR EQUIPOS
		//obtiene el estado del pago
		$pagado = obten_consultaUnCampo('unicas_liga','pagado','pago_admin','equipo',$equipos[$j],'bd',$_SESSION['bd'],'','','','','');
		if($pagado == 'N'){
			$alerta_pago_env++;
		}
		else{//si ya esta pagado no puede eliminar cuenta
			$num_ligas_activas++;
		}
		$liga= obten_consultaUnCampo('session','liga','equipo','id_equipo',$equipos[$j],'','','','','','','');
		$division= obten_consultaUnCampo('session','division','equipo','id_equipo',$equipos[$j],'','','','','','','');
		$num_partidos_act_liga = obten_numPartidosActivosLiga($liga);
		$num_partidos = obten_consultaUnCampo('session','COUNT(id_partido)','partido','division',$division,'','','','','','','');
		if($num_partidos > 0 && $num_partidos_act_liga > 0){//si la liga faltan partidos por jugar no puede eliminar cuenta
			$num_ligas_activas++;
		}		
		//COMPROBAR INSCRIPCION
	}//FIN FOR DE EQUIPO
	$alerta_pago_rec  += obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','usuario',$id_jugador,'seccion','pago_recibido.php','leido','N','','','');
	$num_puntos = obten_numRegPuntos($id_jugador);
}//FIN FOR BD
if($num_ligas_activas == 0){$_SESSION['habilita_elim'] = 0;}
else{$_SESSION['habilita_elim'] = $num_ligas_activas;}
/*COMPROBACIONES:
-mostrar Mis Ligas --> Ver ,si se est jugando en alguna liga
-mostrar Pagos --> Enviados, los pagos enviados si hay
-mostrar Pagos --> Recibidos, los pagos recibidos si hay
*/ 
	//HAY QUE SACAR LOS equipos del jugador y  ver si tiene pagos pendientes
	//$num_pago_recibido = obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','liga',$id_liga,'division',$id_division,'bd',$bd,'receptor',$email,'');
	$inicio = '
	<div class="container">
		<ul id="nav">';
	$menu = '
			<li><a href="#">Datos'.alerta($alerta_liga).'</a>
				<ul class="subs">
					<li><a href="../datos/gestion_datos.php">Ver/Modificar&nbsp;'.alerta($alerta_liga_ver).'</a></li>';
/*	if($num_ligas_activas == 0){
		$menu .= '
					<li><a href="../datos/gestion_datos.php">Eliminar Cuenta&nbsp;</a></li>';
	}*/
	$menu .= '			
				</ul>
			</li>
			<li><a href="#">Mis Ligas'.alerta($alerta_liga).'</a>
				<ul class="subs">
					<li><a href="../ligas/gestion_ligas.php">Ver&nbsp;</a></li>
				</ul>
			</li>
			<li><a href="#">Pagos'.alerta($alerta_pago_rec+$alerta_pago_env).'</a>
				<ul class="subs">
					<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,0,13).'">Enviados&nbsp;'.alerta($alerta_pago_env).'</a></li>';
	if($alerta_pago_rec > 0){
		$menu .= '
					<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,1,13).'">Recibidos&nbsp;'.alerta($alerta_pago_rec).'</a></li>';
	}
	$menu .= '	
				</ul>
			</li>';
	if($num_puntos > 0){
		$menu .= '	
			<li><a href="#">Puntos</a>
				<ul class="subs">
					<li><a href="../puntos/gestion_puntos.php?id='.genera_id_url(50,0,13).'">Ver&nbsp;</a></li>
				</ul>
			</li>';
	}
	$menu .= '	
			<li><a href="#">Seguro'.alerta($alerta_liga).'</a>
				<ul class="subs">
					<li><a href="../seguro/gestion_seguro.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;</a></li>
				</ul>
			</li>';
	$final = '
		</ul>
	</div>';
	echo $inicio.$menu.$final;
/*if($liga != ''){
	if($tipo_pago == 0){
		echo $normales.$final;
	}
	else{
		echo $normales.$especiales.$final;
	}
}*/
//echo 'hola caracola';
?>
</body>
</html>