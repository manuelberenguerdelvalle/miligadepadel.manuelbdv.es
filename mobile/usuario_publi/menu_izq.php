<?php
include_once ("../../class/mysql.php");
include_once ("../funciones/f_general.php");
include_once ("../funciones/f_obten.php");
include_once ("../funciones/f_secundarias.php");
include_once ("../../class/usuario_publi.php");
session_start();
$usuario_publi = unserialize($_SESSION['usuario_publi']);
$num_pagos = obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','bd','ligas','usuario',$usuario_publi->getValor('id_usuario_publi'),'','','','','');
$alerta_pago_env = obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','bd','ligas','usuario',$usuario_publi->getValor('id_usuario_publi'),'pagado','N','estado','E','');
//pago recibido de la web (poco usual)
$alerta_pago_rec = obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','bd','ligas','usuario',$usuario_publi->getValor('id_usuario_publi'),'pagado','S','estado','R','');
$num_publi = obten_consultaUnCampo('unicas','COUNT(id_publicidad_gratis)','publicidad_gratis','usuario_publi',$usuario_publi->getValor('id_usuario_publi'),'','','','','','','');


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
	$inicio = '
	<div class="container">
		<ul id="nav">';
	$menu = '
			<li><a href="#">Datos'.alerta($alerta_liga).'</a>
				<ul class="subs">
					<li><a href="../datos/gestion_datos.php">Ver/Modificar&nbsp;'.alerta($alerta_liga_ver).'</a></li>
				</ul>
			</li>
			<li><a href="#">Patrocinar</a>
				<ul class="subs">
					<li><a href="../publicidad/gestion_publicidad.php?id='.genera_id_url(50,1,13).'">Insertar&nbsp;</a></li>';
	if($num_publi > 0){
		$menu .= '
					<li><a href="../publicidad/gestion_publicidad.php?id='.genera_id_url(50,0,13).'">Ver/Modificar&nbsp;</a></li>';
	}
	$menu .= '
				</ul>
			</li>';
	if($num_pagos > 0){
		if($alerta_pago_rec > 0 && $alerta_pago_env == 0){
			$menu .= '
			<li><a href="#">Pagos&nbsp;'.alerta_verde($alerta_pago_env+$alerta_pago_rec).'</a>';
		}
		else{
			$menu .= '
			<li><a href="#">Pagos&nbsp;'.alerta($alerta_pago_env+$alerta_pago_rec).'</a>';
		}
		$menu .=
				'<ul class="subs">
					<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,0,13).'">Enviados&nbsp;'.alerta($alerta_pago_env).'</a></li>';
		if($alerta_pago_rec > 0){
			$menu .= '
					<li><a href="../pago/gestion_pago.php?id='.genera_id_url(50,1,13).'">Recibidos&nbsp;'.alerta_verde($alerta_pago_rec).'</a></li>';
		}
		$menu .= '
				</ul>
			</li>';
	}//fin if num_pagos
	$final = '
		</ul>
	</div>';
	echo $inicio.$menu.$final;
?>
</body>
</html>