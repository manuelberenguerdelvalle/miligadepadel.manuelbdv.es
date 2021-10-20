<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/usuario.php");
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
//if ( $pagina != 'gestion_temporada' || $tipo_pago == 0 ){
if ( $pagina != 'gestion_temporada' ){
	header ("Location: ../cerrar_sesion.php");
}
else {
			include_once ("../../funciones/f_recoger_post.php");
			//$liga,$posicion,$id_nueva_temporada,$equipo;
			$id_nt = array();
			$equipos = array();
			$divisiones = array();
			$posiciones = array();
			$precios = array();
			$cont = 0;
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM nueva_temporada WHERE liga = '".$id_liga."' AND posicion >= '".$posicion."' ORDER BY posicion ; ");
			 while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
				 //en posicion 0 esta el que vamos a elminar
			 	$id_nt[$cont] = $resultados['id_nueva_temporada'];
				$equipos[$cont] = $resultados['equipo'];
				$divisiones[$cont] = $resultados['division'];
				$posiciones[$cont] = $resultados['posicion'];
				$precios[$cont] = $resultados['precio'];
				$cont++;
			 }
			include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
			unset($mail);
			for($i=0; $i<count($id_nt); $i++){
				if($divisiones[$i] != $divisiones[$i+1] && $divisiones[$i+1] != ''){//si hay cambio de division
					$update = 'division = "'.$divisiones[$i].'",posicion = "'.$posiciones[$i].'",precio = "'.$precios[$i].'"';
					$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$equipos[$i+1],'','','','','','','');
					$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$equipos[$i+1],'','','','','','','');
					$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
					$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');
					//enviar correo al equipo eliminado
					$mail = new PHPMailer;
					$mail->IsSMTP();
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = "ssl";
					$mail->Host = $datos->getValor('c2');
					$mail->Port = 465;
					$mail->Username = $datos->getValor('c1');
					$mail->Password = $datos->getValor('password');
					$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
					$mail->addReplyTo($usuario->getValor('email'));//Set who the message is to be sent to
					$asunto = utf8_decode('Nueva temporada para la Liga de Padel <'.$liga->getValor("nombre").'>.');
					$mail->Subject = $asunto;
					$cuerpo = '<br>Habéis ascendido automáticamente en la Nueva Temporada de '.$liga->getValor("nombre").', ya que un equipo de la división superior se ha dado de baja.<br><br>';
					$mail->AddBCC($email1);//añadimos al jugador1
					$mail->AddBCC($email2);//añadimos al jugador2
					$body = email_jugadorAdmin("¡Enhorabuena, habéis ascendido en la Liga ".$liga->getValor("nombre")."!<br>",$cuerpo);
					$mail->msgHTML($body);
					$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
					$mail->send();
					unset($mail);
				}
				else{
					$update = 'posicion = "'.$posiciones[$i].'"';
				}
				if($id_nt[$i+1] != ''){
					realiza_updateGeneral('session','nueva_temporada',$update,'id_nueva_temporada',$id_nt[$i+1],'','','','','','','','','');
				}
			}
			realiza_deleteGeneral('session','nueva_temporada','id_nueva_temporada',$id_nueva_temporada,'','','','','','','','','');
			$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$equipo,'','','','','','','');
			$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$equipo,'','','','','','','');
			$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
			$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');
			//enviar correo al equipo eliminado
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "ssl";
			$mail->Host = $datos->getValor('c2');
			$mail->Port = 465;
			$mail->Username = $datos->getValor('c1');
			$mail->Password = $datos->getValor('password');
			$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
			$mail->addReplyTo($usuario->getValor('email'));//Set who the message is to be sent to
			$asunto = utf8_decode('Nueva temporada para la Liga de Padel <'.$liga->getValor("nombre").'>.');
			$mail->Subject = $asunto;
			$cuerpo = '<br>Le confirmamos que no participará en la Nueva Temporada de '.$liga->getValor("nombre").', el administrador no cuenta con su equipo para la próxima temporada.<br><br>';
			$cuerpo .= 'Esperamos verle de nuevo en otra Liga o Torneo.<br>';
			$mail->AddBCC($email1);//añadimos al jugador1
			$mail->AddBCC($email2);//añadimos al jugador2
			$body = email_jugadorAdmin("<br>¡Gracias por utilizar miligadepadel.manuelbdv.es!<br>",$cuerpo);
			$mail->msgHTML($body);
			$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
			$mail->send();
			unset($mail);
}

?>