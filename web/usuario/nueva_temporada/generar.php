<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_secundarias.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/usuario.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$liga = unserialize($_SESSION['liga']);
$opcion = $_SESSION['opcion'];
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$movimientos = $liga->getValor('movimientos');
$usuario = unserialize($_SESSION['usuario']);
//if(!isset($tipo_pago)){$tipo_pago = $liga->getValor('tipo_pago');}
if ( $pagina != 'gestion_temporada' || $tipo_pago == 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
			if($usuario->getValor('bd') == 'admin_liga'){$bd = 0;}
			else{$bd = substr($usuario->getValor('bd'),-1,1);}
			//include_once ("../../funciones/f_recoger_post.php");
			$id_division = array();
			$num_division = array();
			$precio = array();
			$id_equipo = array();
			$ganados = array();
			$sets_aux = array();
			$sets_favor = array();
			$sets_contra = array();
			$cont = 0;
			$cont_div = 0;
			$db = new MySQL('session');//LIGA PADEL
            $consulta = $db->consulta("SELECT id_division,num_division FROM division WHERE liga = '".$id_liga."' AND bloqueo = 'N' ORDER BY num_division ; ");
            while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){//cargamos las divisones y precios
				//cargo generales de division
				$id_div_aux = $resultados['id_division'];
				$num_div_aux = $resultados['num_division'];
				$precio_aux = utf8_decode(limpiaTexto(trim($_POST['d'.$id_div_aux])));
				if($precio_aux == '' || $precio_aux == 0){$precio_aux = 1;}//si es 0 o vacio lo ponemos a 1
				//guardamos los datos par una sola liga temporalmente para ordenar la clasificacion e insertar en general
				$id_equipo_aux = array();
				$ganados_aux = array();
				$sets_aux = array();
				$sets_favor_aux = array();
				$sets_contra_aux = array();
				$cont_aux = 0;
				$db2 = new MySQL('session');//LIGA PADEL
				$consulta2 = $db2->consulta("SELECT id_equipo FROM equipo WHERE liga = '".$id_liga."' AND division = '".$id_div_aux."' ; ");
				while($resultados2 = $consulta2->fetch_array(MYSQLI_ASSOC)){//obtenemos los datos por division					
					$id_equipo_aux[$cont_aux] = $resultados2['id_equipo'];
					$ganados_aux[$cont_aux] = obten_consultaUnCampo('session','COUNT(id_partido)','partido','ganador',$resultados2['id_equipo'],'','','','','','','');//ganados
					$sets_aux = obten_sumaSets($resultados2['id_equipo'],'local');//solicita a favor local, visitantes en contra
					$sets_favor_aux[$cont_aux] = $sets_aux[0];//local a favor
					$sets_contra_aux[$cont_aux] = $sets_aux[1];//visitante en contra
					$sets_aux = obten_sumaSets($resultados2['id_equipo'],'visitante');//solicita a favor local, visitantes en contra
					$sets_favor_aux[$cont_aux] += $sets_aux[0];//local a favor
					$sets_contra_aux[$cont_aux] += $sets_aux[1];//visitante en contra
					$cont_aux++;
				}//fin while
				//ordenamos la clasificación de la division
				$aux_id = 0;
				$aux_ga = 0;
				$aux_sf = 0;
				$aux_sc = 0;
				for($i=0; $i<count($id_equipo_aux); $i++){
					for($j=$i+1; $j<count($id_equipo_aux); $j++){
						if($ganados_aux[$j] > $ganados_aux[$i]){//si el siguiente es mayor que el base, hago cambio
							//copio el base
							$aux_id = $id_equipo_aux[$i];
							$aux_ga = $ganados_aux[$i];
							$aux_sf = $sets_favor_aux[$i];
							$aux_sc = $sets_contra_aux[$i];
							//asigno el mayor a base
							$id_equipo_aux[$i] = $id_equipo_aux[$j];
							$ganados_aux[$i] = $ganados_aux[$j];
							$sets_favor_aux[$i] = $sets_favor_aux[$j];
							$sets_contra_aux[$i] = $sets_contra_aux[$j];
							//asigno el menor
							$id_equipo_aux[$j] = $aux_id;
							$ganados_aux[$j] = $aux_ga;
							$sets_favor_aux[$j] = $aux_sf;
							$sets_contra_aux[$j] = $aux_sc;
						}
						else if($ganados_aux[$j] == $ganados_aux[$i]){//si es igual miro sets
							if( ($sets_favor_aux[$j] - $sets_contra_aux[$j]) > ($sets_favor_aux[$i] - $sets_contra_aux[$i]) ){
								//copio el base
								$aux_id = $id_equipo_aux[$i];
								$aux_ga = $ganados_aux[$i];
								$aux_sf = $sets_favor_aux[$i];
								$aux_sc = $sets_contra_aux[$i];
								//asigno el mayor a base
								$id_equipo_aux[$i] = $id_equipo_aux[$j];
								$ganados_aux[$i] = $ganados_aux[$j];
								$sets_favor_aux[$i] = $sets_favor_aux[$j];
								$sets_contra_aux[$i] = $sets_contra_aux[$j];
								//asigno el menor
								$id_equipo_aux[$j] = $aux_id;
								$ganados_aux[$j] = $aux_ga;
								$sets_favor_aux[$j] = $aux_sf;
								$sets_contra_aux[$j] = $aux_sc;
							}
						}
						else{}
					}//fin for j
				}//fin for i
				//asignamos la division al los arrays generales
				for($i=0; $i<count($id_equipo_aux); $i++){
					$id_division[$cont] = $id_div_aux;
					$num_division[$cont] = $num_div_aux;
					$precio[$cont] = $precio_aux;
					$id_equipo[$cont] = $id_equipo_aux[$i];
					$ganados[$cont] = $ganados_aux[$i];
					$sets_favor[$cont] = $sets_favor_aux[$i];
					$sets_contra[$cont] = $sets_contra_aux[$i];
					$cont++;
				}//fin for i
				$cont_div++;
				unset($id_equipo_aux,$ganados_aux,$sets_aux,$sets_favor_aux,$sets_contra_aux,$cont_aux);
            }//fin while principal
			$cont_aux = 0;
			include_once ("../../funciones/f_conexion_email.php");
			for($i=0; $i<count($id_division); $i++){//HACER MOVIMIENTOS
				if($num_division[$i] == 1 &&  $i == 0){//campeón de 1a division
					$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
					$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
					$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
					$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');
					$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
					$mail->addReplyTo($usuario->getValor('email'));//Set who the message is to be sent to
					$asunto = utf8_decode('Nueva temporada para la Liga de Padel <'.$liga->getValor("nombre").'>.');
					$mail->Subject = $asunto;
					$linksi = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'S'.$id_equipo[$i].'F',13);
					$linkno = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'N'.$id_equipo[$i].'F',13);
					$cuerpo = '<br><br>¿Queréis defender el título en la Nueva Temporada?<br><br>';
					$cuerpo .= '<a style="width:30px;background-color:#039;margin-left:15%;margin-right:7%;padding: 5px 11px 5px 11px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linksi.'" target="_blank"><b>Si</b></a>
		<a style="width:30px;background-color:#039;margin-left:7%;padding: 5px 7px 5px 7px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linkno.'" target="_blank"><b>No</b></a><br><br>';
					$cuerpo .= '<br>División: 1<br>Precio: '.$precio[$i].'&euro;';
					$mail->AddBCC($email1);//añadimos al jugador1
					$mail->AddBCC($email2);//añadimos al jugador2
					$body = email_jugadorAdmin("<br>¡Enhorabuena sois los campeones de la División ".$num_division[$i]." de la Liga ".$liga->getValor("nombre")."!<br>",$cuerpo);
					$mail->msgHTML($body);
					$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
					$mail->send();
					unset($mail);
				}//fin if 1o 1a division
				else if($num_division[$i+$movimientos] != $num_division[$i] && $num_division[$i+$movimientos] != ''){//ascensos y que no sea la ultima division
					if($cont_aux == 0){//primer ascenso campeon
						//$id_equipo[$i+$movimientos] equipo que asciende
						$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$i+$movimientos],'','','','','','','');
						$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$id_equipo[$i+$movimientos],'','','','','','','');
						$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
						$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');
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
						$linksi = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'S'.$id_equipo[$i+$movimientos].'F',13);
						$linkno = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'N'.$id_equipo[$i+$movimientos].'F',13);
						$cuerpo = '<br><br>¿Queréis triunfar en la Nueva Temporada?<br><br>';
						$cuerpo .= '<a style="width:30px;background-color:#039;margin-left:15%;margin-right:7%;padding: 5px 11px 5px 11px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linksi.'" target="_blank"><b>Si</b></a>
			<a style="width:30px;background-color:#039;margin-left:7%;padding: 5px 7px 5px 7px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linkno.'" target="_blank"><b>No</b></a><br><br>';
						$cuerpo .= '<br>División: '.$num_division[$i].'<br>Precio: '.$precio[$i].'&euro;';
						$mail->AddBCC($email1);//añadimos al jugador1
						$mail->AddBCC($email2);//añadimos al jugador2
						$body = email_jugadorAdmin("<br>¡Enhorabuena, Campeones habéis ganado el ascenso a la División ".$num_division[$i]." de la Liga ".$liga->getValor("nombre")."!<br>",$cuerpo);
						$mail->msgHTML($body);
						$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail->send();
						unset($mail);
						if($movimientos > 1){//si solo hay un movimiento no incrementamos  para el siguiente ascenso en otra division
							$cont_aux++;
						}
					}//fin primer ascenso
					else{//siguiente ascenso
						//$id_equipo[$i+$movimientos] equipo que asciende
						$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$i+$movimientos],'','','','','','','');
						$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$id_equipo[$i+$movimientos],'','','','','','','');
						$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
						$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');
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
						$linksi = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'S'.$id_equipo[$i+$movimientos].'F',13);
						$linkno = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'N'.$id_equipo[$i+$movimientos].'F',13);
						$cuerpo = '<br><br>¿Queréis triunfar en la Nueva Temporada?<br><br>';
						$cuerpo .= '<a style="width:30px;background-color:#039;margin-left:15%;margin-right:7%;padding: 5px 11px 5px 11px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linksi.'" target="_blank"><b>Si</b></a>
			<a style="width:30px;background-color:#039;margin-left:7%;padding: 5px 7px 5px 7px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linkno.'" target="_blank"><b>No</b></a><br><br>';
						$cuerpo .= '<br>División: '.$num_division[$i].'<br>Precio: '.$precio[$i].'&euro;';
						$mail->AddBCC($email1);//añadimos al jugador1
						$mail->AddBCC($email2);//añadimos al jugador2
						$body = email_jugadorAdmin("<br>¡Enhorabuena, habéis ascendido a la División ".$num_division[$i]." de la Liga ".$liga->getValor("nombre")."!<br>",$cuerpo);
						$mail->msgHTML($body);
						$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail->send();
						unset($mail);
						if($cont_aux == $movimientos-1){//es el último ascenso
							$cont_aux = 0;
						}
						else{//aun quedan ascensos
							$cont_aux++;
						}
					}
					//cambios
					$id_equipo_aux = $id_equipo[$i];
					$ganados_aux = $ganados[$i];
					$sets_favor_aux = $sets_favor[$i];
					$sets_contra_aux = $sets_contra[$i];
					$id_equipo[$i] = $id_equipo[$i+$movimientos];
					$ganados[$i] = $ganados[$i+$movimientos];
					$sets_favor[$i] = $sets_favor[$i+$movimientos];
					$sets_contra[$i] = $sets_contra[$i+$movimientos];
					$id_equipo[$i+$movimientos] = $id_equipo_aux;
					$ganados[$i+$movimientos] = $ganados_aux;
					$sets_favor[$i+$movimientos] = $sets_favor_aux;
					$sets_contra[$i+$movimientos] = $sets_contra_aux ;
					//$mostrar .= 'ascenso/descenso ';
					unset($id_equipo_aux,$ganados_aux,$sets_aux,$sets_favor_aux,$sets_contra_aux);
				}//fin ascensos
				else{//descensos y normal
					//hay que comprobar que no sea ninguno de los que ha descendido
					if($num_division[$i-$movimientos] != $num_division[$i] && $num_division[$i] != 1){//descensos y que no sea la primera aqui ya estan los cambios hechos
						//$id_equipo[$i] equipo que desciende
						$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
						$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
						$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
						$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');		
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
						$linksi = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'S'.$id_equipo[$i].'F',13);
						$linkno = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'N'.$id_equipo[$i].'F',13);
						$cuerpo = '<br><br>¿Queréis revancha para la Nueva Temporada?<br><br>';
						$cuerpo .= '<a style="width:30px;background-color:#039;margin-left:15%;margin-right:7%;padding: 5px 11px 5px 11px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linksi.'" target="_blank"><b>Si</b></a>
			<a style="width:30px;background-color:#039;margin-left:7%;padding: 5px 7px 5px 7px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linkno.'" target="_blank"><b>No</b></a><br><br>';
						$cuerpo .= '<br>División: '.$num_division[$i].'<br>Precio: '.$precio[$i].'&euro;';
						$mail->AddBCC($email1);//añadimos al jugador1
						$mail->AddBCC($email2);//añadimos al jugador2
						$body = email_jugadorAdmin("<br>¡No se ha podido evitar el descenso, pero habéis realizado un buen Trabajo, Ánimo!<br>",$cuerpo);
						$mail->msgHTML($body);
						$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail->send();
						unset($mail);
					}
					else{
						$id_jug1 = obten_consultaUnCampo('session','jugador1','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
						$id_jug2 = obten_consultaUnCampo('session','jugador2','equipo','id_equipo',$id_equipo[$i],'','','','','','','');
						$email1 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug1,'','','','','','','');
						$email2 = obten_consultaUnCampo('unicas','email','jugador','id_jugador',$id_jug2,'','','','','','','');				
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
						$linksi = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'S'.$id_equipo[$i].'F',13);
						$linkno = 'http://miligadepadel.manuelbdv.es/web/v/v/valida_respuesta.php?id='.genera_id_url(100,$bd.'N'.$id_equipo[$i].'F',13);
						$cuerpo = '<br><br>¿Queréis luchar por el título en la Nueva Temporada?<br><br>';
						$cuerpo .= '<a style="width:30px;background-color:#039;margin-left:15%;margin-right:7%;padding: 5px 11px 5px 11px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linksi.'" target="_blank"><b>Si</b></a>
			<a style="width:30px;background-color:#039;margin-left:7%;padding: 5px 7px 5px 7px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linkno.'" target="_blank"><b>No</b></a><br><br>';
						$cuerpo .= '<br>División: '.$num_division[$i].'<br>Precio: '.$precio[$i].'&euro;';
						$mail->AddBCC($email1);//añadimos al jugador1
						$mail->AddBCC($email2);//añadimos al jugador2
						$body = email_jugadorAdmin("<br>¡Enhorabuena, habéis realizado una gran temporada en la División ".$num_division[$i]." de la Liga ".$liga->getValor("nombre")."!<br>",$cuerpo);
						$mail->msgHTML($body);
						$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail->send();
						unset($mail);
					}
				}
				insertar_regNuevaTemporada($id_liga,$id_division[$i],$id_equipo[$i],$i,$precio[$i]);
				//$mostrar .= $id_division[$i].'-'.$num_division[$i].'-'.$precio[$i].'-'.$id_equipo[$i].'-'.$ganados[$i].'-'.$sets_favor[$i].'-'.$sets_contra[$i].'||';
			}//fin for
			echo '1';
}

?>