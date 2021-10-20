<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/pago_web.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/notificacion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$liga = unserialize($_SESSION['liga']);
//$opcion = $_SESSION['opcion'];
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$usuario = unserialize($_SESSION['usuario']);
//if(!isset($tipo_pago)){$tipo_pago = $liga->getValor('tipo_pago');}
if ( $pagina != 'gestion_temporada' || $tipo_pago == 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	//genero pago de nueva liga y division 1 la que entra y el pago.
	$new_liga = new Liga(NULL,substr($liga->getValor('nombre'),0,36).' NEW',date('Y-m-d H:i:s'),$liga->getValor('ciudad'),$liga->getValor('provincia'),$liga->getValor('pais'),$liga->getValor('usuario'),$liga->getValor('tipo_pago'),'N',$liga->getValor('vista'),$liga->getValor('pass'),$liga->getValor('auto_completar'),$liga->getValor('movimientos'),'N',$liga->getValor('genero'),$liga->getValor('idayvuelta'),$liga->getValor('estilo'));
	$new_liga->insertar();
	$id_new_liga = obten_consultaUnCampo('session','id_liga','liga','usuario',$usuario->getValor('id_usuario'),'','','','','','','ORDER BY id_liga DESC');
	//updatear nueva_temporada
	realiza_updateGeneral('session','nueva_temporada','nueva="'.$id_new_liga.'"','liga',$id_liga,'','','','','','','','','');
	$new_division = new Division(NULL,date('Y-m-d H:i:s'),obten_consultaUnCampo('session','precio','nueva_temporada','liga',$id_liga,'posicion',0,'','','','',''),$id_new_liga,NULL,1,obten_equipos($tipo_pago),'N','N');
	$new_division->insertar();
	$id_new_division = obten_consultaUnCampo('session','id_division','division','liga',$id_new_liga,'num_division',1,'','','','','');
	$id_division = obten_consultaUnCampo('session','division','nueva_temporada','liga',$id_liga,'posicion',0,'','','','','');
	realiza_updateGeneral('session','nueva_temporada','nueva_div="'.$id_new_division.'"','liga',$id_liga,'division',$id_division,'','','','','','','');
	//insertar nueva division de nueva temporada
	//$num_divs = obten_consultaUnCampo('session','COUNT(DISTINCT(division))','nueva_temporada','liga',$id_liga,'','','','','','','');//numero de divisiones
	if($usuario->getValor('cuenta_paypal') != ''){$email_ins = $usuario->getValor('cuenta_paypal');}
	else{$email_ins = $usuario->getValor('email');}
	$fecha = date('Y-m-d H:i:s');
	$pago = new Pago_web(NULL,$usuario->getValor('bd'),$id_new_liga,$id_new_division,'L',NULL,obten_precio($tipo_pago),'P','N',cuenta_admin(),$email_ins,$usuario->getValor('id_usuario'),$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
	$pago->insertar();
	if($liga->getValor('idayvuelta') == 'S'){//si hay ida y vuelta generamos pago
		unset($pago);
		$pago = new Pago_web(NULL,$usuario->getValor('bd'),$id_new_liga,$id_new_division,'I',NULL,10,'P','N',cuenta_admin(),$email_ins,$usuario->getValor('id_usuario'),$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
	$pago->insertar();
	}
	//$id_division = obten_consultaUnCampo('session','division','nueva_temporada','liga',$id_liga,'posicion',0,'','','','','');//id_divisoin
	unset($pago,$new_division);
	//genero resto de divisones
	$num_division = 1;
	$div_ant = '';
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT division,posicion FROM nueva_temporada WHERE liga = '".$id_liga."' ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['division'] != $div_ant && $div_ant != ''){//cramos las divisiones a partir de la segunda
			$num_division++;
			$new_division = new Division(NULL,date('Y-m-d H:i:s'),obten_consultaUnCampo('session','precio','nueva_temporada','liga',$id_liga,'posicion',$resultados['posicion'],'','','','',''),$id_new_liga,NULL,$num_division,obten_equipos($tipo_pago),'N','N');
			$new_division->insertar();
			$id_new_division = obten_consultaUnCampo('session','id_division','division','liga',$id_new_liga,'num_division',$num_division,'','','','','');
			//insertar nueva division de nueva temporada
			$pago = new Pago_web(NULL,$usuario->getValor('bd'),$id_new_liga,$id_new_division,'D',NULL,5,'P','N',cuenta_admin(),$email_ins,$usuario->getValor('id_usuario'),$fecha,fecha_suma($fecha,'','',3,'','',''),'','','E');
	$pago->insertar();
			realiza_updateGeneral('session','nueva_temporada','nueva_div="'.$id_new_division.'"','liga',$id_liga,'division',$resultados['division'],'','','','','','','');
			unset($pago,$new_division);
		}
		$div_ant = $resultados['division'];//division anterior
	}//fin while
	//creamos el correo que enviamos con copia oculta
	include_once ("../../funciones/f_conexion_email.php");
	//creamos inscripciones equipos y pagos
	//$num_division = 1;
	$no_continuan = 0;//equipos que no continuan la liga para descartarlos
	$consulta = $db->consulta("SELECT id_nueva_temporada,division,nueva_div,equipo,precio,respuesta,posicion FROM nueva_temporada WHERE liga = '".$id_liga."' ; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
			//cargo equipo y creo nuevo y obtengo id_equipo
			$equipo = new Equipo($resultados['equipo'],'','','','','','','','','');
			$new_equipo = new Equipo(NULL,$equipo->getValor('jugador1'),$equipo->getValor('seguro_jug1'),$equipo->getValor('jugador2'),$equipo->getValor('seguro_jug2'),$id_new_liga,$resultados['nueva_div'],'N',0,obten_fechaHora());
			$new_equipo->insertar();
			$id_equipo = obten_consultaUnCampo('session','id_equipo','equipo','liga',$id_new_liga,'division',$resultados['nueva_div'],'jugador1',$equipo->getValor('jugador1'),'jugador2',$equipo->getValor('jugador2'),'');
			//obtenemos inscripcion antigua, creo nueva 
			$id_ins = obten_consultaUnCampo('session','id_inscripcion','inscripcion','liga',$id_liga,'id_jugador1',$equipo->getValor('jugador1'),'id_jugador2',$equipo->getValor('jugador2'),'','','');
			$inscripcion = new Inscripcion($id_ins,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
			//$inscripcion = new Inscripcion('',$resultados['division'],$id_liga,'','','',$equipo->getValor('jugador1'),'','','','','','','','','','','','',$equipo->getValor('jugador2'),'','','','','','','','','','','','');
			//coger emails de jugador
			$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$equipo->getValor('jugador1'),'','','','','','','');
			$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$equipo->getValor('jugador2'),'','','','','','','');
			$new_inscripcion = new Inscripcion(NULL,$resultados['nueva_div'],$id_new_liga,$inscripcion->getValor('pago'),$resultados['precio'],'N',$inscripcion->getValor('id_jugador1'),$inscripcion->getValor('dni1'),$inscripcion->getValor('nombre1'),$inscripcion->getValor('apellidos1'),$inscripcion->getValor('password1'),$inscripcion->getValor('direccion1'),$inscripcion->getValor('fec_nac1'),$inscripcion->getValor('ciudad1'),$inscripcion->getValor('provincia1'),$inscripcion->getValor('pais1'),$inscripcion->getValor('telefono1'),$email1,$inscripcion->getValor('genero1'),$inscripcion->getValor('id_jugador2'),$inscripcion->getValor('dni2'),$inscripcion->getValor('nombre2'),$inscripcion->getValor('apellidos2'),$inscripcion->getValor('password2'),$inscripcion->getValor('direccion2'),$inscripcion->getValor('fec_nac2'),$inscripcion->getValor('ciudad2'),$inscripcion->getValor('provincia2'),$inscripcion->getValor('pais2'),$inscripcion->getValor('telefono2'),$email2,$inscripcion->getValor('genero2'));
			$new_inscripcion->insertar();
			//creamos el pago
			$pago_admin = new Pago_admin(NULL,$id_new_liga,$resultados['nueva_div'],$usuario->getValor('bd'),$id_equipo,$resultados['precio'],$inscripcion->getValor('pago'),'N',$usuario->getValor('cuenta_paypal'),$usuario->getValor('id_usuario'),$inscripcion->getValor('email1'),obten_fechaHora(),'','','',$equipo->getValor('jugador1'),$equipo->getValor('jugador2'),'R');
			$pago_admin->insertar();
			//creamos notificacion
			$notificacion = new Notificacion('',$usuario->getValor('id_usuario'),$id_new_liga,$resultados['nueva_div'],'modificar_inscripcion.php',date('Y-m-d H:i:s'),'N');
			$notificacion->insertar();
			$mail->AddBCC($email1);
			$mail->AddBCC($email2);
			unset($equipo,$new_equipo,$id_equipo,$inscripcion,$new_inscripcion,$id_ins,$pago_admin,$notificacion,$email1,$email2);
	}//fin while
	//bloquear antigua liga, divisiones
	$cambio_nom = substr($liga->getValor('nombre'),0,32).date('Y').date('m').date('d');
	realiza_updateGeneral('session','liga','nombre = "'.$cambio_nom.'"','id_liga',$id_liga,'','','','','','','','','');
	realiza_updateGeneral('session','liga','bloqueo = "S"','id_liga',$id_liga,'','','','','','','','','');
	realiza_updateGeneral('session','division','bloqueo = "S",comienzo = "T"','liga',$id_liga,'','','','','','','','','');
	//asignamos nueva liga
	$consulta = $db->consulta("SELECT * FROM `liga` WHERE `id_liga` = '$id_new_liga' AND `bloqueo` = 'N'; ");
	if($consulta->num_rows > 0){//si hay al menos 1
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$liga = new Liga($resultados['id_liga'],$resultados['nombre'],$resultados['fec_creacion'],$resultados['ciudad'],$resultados['provincia'],$resultados['pais'],$resultados['usuario'],$resultados['tipo_pago'],$resultados['pagado'],$resultados['vista'],$resultados['pass'], $resultados['auto_completar'],$resultados['movimientos'],$resultados['bloqueo'],$resultados['genero'],$resultados['idayvuelta'],$resultados['estilo']);
		 $_SESSION['liga'] = serialize($liga);
		 $id_liga = $liga->getValor('id_liga');
	}
	//asignamos division 1
	$consulta = $db->consulta("SELECT * FROM `division` WHERE `liga` = '$id_new_liga' AND num_division = '1'; ");
		if($consulta->num_rows > 0){//si hay al menos una division
			$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
			$division = new Division($resultados['id_division'],$resultados['fec_creacion'],$resultados['precio'],$resultados['liga'],$resultados['suscripcion'],$resultados['num_division'],$resultados['max_equipos'],$resultados['comienzo'],$resultados['bloqueo']);
			$id_division = $division->getValor('id_division');
			$_SESSION['division'] = serialize($division);
		}
		
		$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
		$mail->addReplyTo($usuario->getValor('email'));//Set who the message is to be sent to
		//$mail->addAddress('manu_oamuf@hotmail.com');
		$asunto = utf8_decode('Nueva Temporada generada de la  en la Liga <'.utf8_encode($new_liga->getValor("nombre")).'>.');
		$mail->Subject = $asunto;
		$cuerpo = '<br><br>Para completar la inscripción debes realizar el pago del mismo medio que realizaste el pago para la anterior temporada, presencial al administrador o si tiene activado el pago online a trav&eacute;s de paypal entrar en tu menu de jugador, y realizar el pago de la nueva inscripción.<br><br>';
		$cuerpo .= '¡Suerte para la Nueva Temporada!<br><br>Importante: Es posible que se produzcan ascensos automáticos si hay equipos que han decidido no continuar en la liga<br><br>';
		$body = email_jugadorAdmin("<br>¡Comienza la Nueva Temporada!<br>",$cuerpo);
		$mail->msgHTML($body);
		$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
		$mail->send();
	echo '1';
}

?>