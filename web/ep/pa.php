<?php
//AQUI RECIBIMOS EL ESTADO DEL LOS PAGO_ADMIN
//http://miligadepadel.manuelbdv.es/web/ep/pw.php
include_once ("../../class/mysql.php");
include_once ("../../class/datos.php");
include_once ("../../class/pago_admin.php");
include_once ("../../class/equipo.php");
include_once ("../../class/inscripcion.php");
include_once ("../funciones/f_email.php");
include_once ("../funciones/f_general.php");
include_once ("../funciones/f_obten.php");
require_once ("../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../PHPMailer/class.smtp.php");

$datos = new Datos(1,'','','','','','');
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = $datos->getValor('c2');
$mail->Port = 465;
$mail->Username = $datos->getValor('c1');
$mail->Password = $datos->getValor('password');
//mail('manu_oamuf@hotmail.com', 'antes', 'mierda');
 /*----------------------------------------------------------------------------
		 REVISAR LA OPCION DE ENVIAR CON OTRO CORREO ELECTRONICO, PARA TENER Y DUPLICAR 
		 LA CONEXION, PARA PODER ASEGURAR LOS DE LA WEB NORMAL
		 -----------------------------------------------------------------------------*/
    // Primera comprobación. Cerraremos este if más adelante
    if($_POST){
        // Obtenemos los datos en formato variable1=valor1&variable2=valor2&...
        $raw_post_data = file_get_contents('php://input');

        // Los separamos en un array
        $raw_post_array = explode('&',$raw_post_data);

        // Separamos cada uno en un array de variable y valor
        $myPost = array();
        foreach($raw_post_array as $keyval){
            $keyval = explode("=",$keyval);
            if(count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        // Nuestro string debe comenzar con cmd=_notify-validate
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')){
            $get_magic_quotes_exists = true;
        }
        foreach($myPost as $key => $value){
            // Cada valor se trata con urlencode para poder pasarlo por GET
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value)); 
            } else {
                $value = urlencode($value);
            }

            //Añadimos cada variable y cada valor
            $req .= "&$key=$value";
        }
		 $ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');   // Esta URL debe variar dependiendo si usamos SandBox o no. Si lo usamos, se queda así.
        //$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');         // Si no usamos SandBox, debemos usar esta otra linea en su lugar.
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if( !($res = curl_exec($ch)) ) {
            // Ooops, error. Deberiamos guardarlo en algún log o base de datos para examinarlo después.
            curl_close($ch);
            exit;
        }
        curl_close($ch);
		if (strcmp ($res, "VERIFIED") == 0) {
			 if(strpos($_POST['invoice'],'-') != false){//aqui entra en la inscripcion inicial y devolucion
				$divide = array();
			 	$divide = explode('-',$_POST['invoice']);//pago_admin con el DEV-id_pago_admin
				if($divide[0] == 'DEV'){//si es devolucion el formato es DEV-id_pago_admin
					$id_pago_admin = limpiaTexto($divide[1]);
				}
				else{//si viene de la inscripcion el formato es Inscripcion - $usuario - YYYY-MM-DD HH:MM:SS
					$fec_captur = $divide[2].'-'.$divide[3].'-'.$divide[4];
					$id_pago_admin = obten_consultaUnCampo('unicas_liga','id_pago_admin','pago_admin','usuario',$divide[1],'pagado','N','estado','R','fecha',$fec_captur,'ORDER BY id_pago_admin DESC');
				}
			 }
			 else{//aqui entra cuando es a través del panel jugador
				$id_pago_admin = $_POST['invoice'];//Obtener la subscripción identificada por $_POST['invoice']
			 }
			 $pago_admin = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','','');
			  /*
			OPTIMIZAR TRATAMIENTO
			IF
			Denied: Denegado si anteriormente estaba pendiente el motivo en las variables pending_reason o Fraud_Management_Filters_x variable.
			Expired: Autorizacion caducada y no capturada.
			Failed: Fallo.
			Voided: anulado
			Declined: declinado
			ELSE
			 Processed: A payment has been accepted.
			 Canceled_Reversal=si hay una disputa y ganas
			 Completed: The payment has been completed, and the funds have been added successfully to your account balance
			 Pending: The payment is pending..
			Created: A German ELV payment is made using Express Checkout.
			Refunded: Has reembolsado el pago.
			Reversed: El pago se ha devuelto al comprado el motivo en la variable ReasonCode.
			 */
			 if ($_POST['payment_status'] == 'Failed' || $_POST['payment_status'] == 'Denied' || $_POST['payment_status'] == 'Expired' || $_POST['payment_status'] == 'Voided' || $_POST['payment_status'] == 'Declined') {// SI FALLA EL PAGO HAY QUE BLOQUEAR Y ENVIAR CORREO AL EMISOR Y RECEPTOR
					 session_start();
					 $_SESSION['bd'] = $pago_admin->getValor('bd');
					 $equipo = new Equipo($pago_admin->getValor('equipo'),'','','','','','','','','');
					 $equipo->setValor('pagado','N');
					 $equipo->modificar();
					 $inscripcion = new Inscripcion('',$equipo->getValor('division'),$equipo->getValor('liga'),'','','',$equipo->getValor('jugador1'),'','','','','','','','','','','','',$equipo->getValor('jugador2'),'','','','','','','','','','','','');
					 $inscripcion->setValor('pagado','N');
					 $inscripcion->modificar();
					 $pago_admin->setValor('pagado','N');
					 $pago_admin->modificar();
					 ///ENVIAR CORREO DE ERROR
					$mail->AddBCC($pago_admin->getValor('emisor'));//enviarmos al emisor de cuenta en la web
					$mail->AddBCC($pago_admin->getValor('receptor'));//enviarmos al receptor
					$mail->AddBCC(obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$pago_admin->getValor('usuario'),'','','','','','',''));//enviarmos al email del administrador de la liga
					$mail->AddBCC(obten_consultaUnCampo('unicas','email','jugador','id_jugador',$pago_admin->getValor('jugador1'),'','','','','','',''));//enviarmos al email jugador 1
					$mail->AddBCC(obten_consultaUnCampo('unicas','email','jugador','id_jugador',$pago_admin->getValor('jugador2'),'','','','','','',''));//enviarmos al email jugador 2
					$mail->AddBCC(cuenta_admin2());//enviarmos a la cuenta de pagos a administradores
             }//fin pago verificado pero en error
			else{//recibo correo de cualquier estado
					$mail->addAddress(cuenta_admin2());
			}//fin else
			$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
			$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
			//$mail->addAddress('manu_oamuf@hotmail.com');
			$asunto = utf8_decode('El Pago por inscripción nº '.$id_pago_admin.' esta en estado '.$_POST["payment_status"]);
			$mail->Subject = $asunto;
			$cuerpo = '<br><br>Detalles del Pago por inscripción:<br><br>';
			$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
			$cuerpo .= 'Fecha: '.$_POST["payment_date"].'.<br><br>';
			$cuerpo .= 'Fecha Actualización: '.date('Y-m-d H:i:s').'.<br><br>';
			$cuerpo .= 'Descripción : '.$_POST["item_name"].'.<br><br>';
			$cuerpo .= 'Precio : '.$_POST["mc_gross"].' EUR (con I.V.A).<br><br>';
			$cuerpo .= 'Emisor : '.$_POST["payer_email"].'.<br><br>';
			$cuerpo .= 'Transacción : '.$_POST["txn_id"].'.<br><br>';
			$body = email_jugadorAdmin("<br>Actualización del pago a estado ".$_POST['payment_status']."<br>",$cuerpo);
			$mail->msgHTML($body);//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			$mail->send();
        }//fin verificado
		else if (strcmp ($res, "INVALID") == 0) {
            // El estado que devuelve es INVALIDO, la información no ha sido enviada por PayPal. Deberías guardarla en un log para comprobarlo después
			$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
			$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
			$mail->addAddress(cuenta_admin2());
			$asunto = utf8_decode('El Pago por inscripción nº '.$id_pago_admin.' esta en estado '.$_POST["payment_status"]);
			$mail->Subject = $asunto;
			$cuerpo = '<br><br>Detalles del Pago por inscripción:<br><br>';
			$cuerpo .= 'Pago nº: '.$id_pago_admin.'.<br><br>';
			$cuerpo .= 'Fecha: '.$_POST["payment_date"].'.<br><br>';
			$cuerpo .= 'Fecha Actualización: '.date('Y-m-d H:i:s').'.<br><br>';
			$cuerpo .= 'Descripción : '.$_POST["item_name"].'.<br><br>';
			$cuerpo .= 'Precio : '.$_POST["mc_gross"].' EUR (con I.V.A).<br><br>';
			$cuerpo .= 'Emisor : '.$_POST["payer_email"].'.<br><br>';
			$cuerpo .= 'Transacción : '.$_POST["txn_id"].'.<br><br>';
			$body = email_jugadorAdmin("<br>PAGO INVALIDO<br>",$cuerpo);
			$mail->msgHTML($body);//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			$mail->send();
        }
		else{} 
    } 
	else {  // Si no hay datos $_POST
		$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
		$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
		$mail->addAddress($datos->getValor('c2'));
		$mail->addAddress('manuel.berdelva@gmail.com');
		$asunto = utf8_decode('ACCESO A PW SIN DATOS POST');
		$mail->Subject = $asunto;
		$cuerpo = '<br><br>Intento de acceso a pw sin datos post.<br><br>';
		$body = email_jugadorAdmin("<br>ACCESO A PW SIN DATOS POST<br>",$cuerpo);
		$mail->msgHTML($body);//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';
		$mail->send();
        // Podemos guardar la incidencia en un log, redirigir a una URL...
    }
session_destroy();
?>
