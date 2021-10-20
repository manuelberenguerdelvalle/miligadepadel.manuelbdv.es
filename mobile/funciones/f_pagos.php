<?php
session_start();
function muestra_formulario($descrip_pago,$id_pago,$precio,$url_retorno,$url_notificacion,$buyer){
	echo'
	<label class="datos_linea_boton">
    	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="'.$buyer.'"><!-- email vendedor -->
        <input type="hidden" name="item_name" value="'.$descrip_pago.'">
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" name="item_number" value="'.$id_pago.'">
        <input type="hidden" name="amount" value="'.$precio.'">
        <input type="hidden" name="no_shipping" value="1"><!-- Evitar que paypal pregunte por una dirección de entrega si se está adquiriendo de un producto virtual -->
        <input type="hidden" name="return" value="'.$url_retorno.'">
		<input type="hidden" name="cancel_return" value="'.$url_retorno.'">
        <input type="hidden" name="invoice" id="invoice" value="'.$id_pago.'" >
		<input type="hidden" name="notify_url" id="notify_url" value="'.$url_notificacion.'" > <!-- url donde quieres recibir que no sea la estandar -->
		<input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet." class="boton_pago">
		<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
        </form>
    </label>';
	
	//<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
	//<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	//<input type="hidden" name="hosted_button_id" value="9YLZMYRDPJUYC"><!-- id boton vendedor -->
	//<input type="hidden" name="business" value="'.$buyer.'"><!-- email vendedor -->
	
	/*echo '
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="9YLZMYRDPJUYC"><!-- id boton vendedor -->
	<input type="hidden" name="item_name" value="'.$descrip_pago.'">
	<input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="item_number" value="'.$id_pago.'">
    <input type="hidden" name="amount" value="'.$precio.'">
	<input type="hidden" name="no_shipping" value="1"><!-- Evitar que paypal pregunte por una dirección de entrega si se está adquiriendo de un producto virtual -->
    <input type="hidden" name="return" value="'.$url_retorno.'">
	<input type="hidden" name="cancel_return" value="'.$url_retorno.'">
    <input type="hidden" name="invoice" id="invoice" value="'.$id_pago.'" >
	<input type="hidden" name="notify_url" id="notify_url" value="'.$url_notificacion.'" > <!-- url donde quieres recibir que no sea la estandar -->
	<input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
	<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
	</form>';*/
	
	
	/*<!-- http://miligadepadel.manuelbdv.es/usuario/pago/gestion_pago_web.php-->
		<input name="cmd" type="hidden" value="_cart">
        <input name="upload" type="hidden" value="1">
        <input name="business" type="hidden" value="vende_1316441080_per@gmail.com">
        <input name="shopping_url" type="hidden" value="http://localhost/carro/productos.php">
        <input name="currency_code" type="hidden" value="EUR">
        <input name="return" type="hidden" value="http://localhost/carro/exito.php">
        <input type='hidden' name='cancel_return' value='http://localhost/carro/errorPaypal.php'>
        <input name="notify_url" type="hidden" value="http://localhost/carro/paypalipn.php">
        <input name="rm" type="hidden" value="2">
	
        <!--<INPUT TYPE="hidden" NAME="first_name" VALUE="John">
        <INPUT TYPE="hidden" NAME="last_name" VALUE="Doe">
        <INPUT TYPE="hidden" NAME="address1" VALUE="9 Elm Street">
        <INPUT TYPE="hidden" NAME="address2" VALUE="Apt 5">
        <INPUT TYPE="hidden" NAME="city" VALUE="Berwyn">
        <INPUT TYPE="hidden" NAME="state" VALUE="PA">
        <INPUT TYPE="hidden" NAME="zip" VALUE="19312">
        <INPUT TYPE="hidden" NAME="lc" VALUE="ES">
        <INPUT TYPE="hidden" NAME="email" VALUE="buyer@domain.com">
        <INPUT TYPE="hidden" NAME="night_phone_a" VALUE="610">
        <INPUT TYPE="hidden" NAME="night_phone_b" VALUE="555">-->*/
}
function muestra_formulario_sinboton($descrip_pago,$id_pago,$precio,$url_retorno,$url_notificacion,$buyer){
	echo'
    	<form id="form_paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="'.$buyer.'">
        <input type="hidden" name="item_name" value="'.$descrip_pago.'">
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" id="item_name" name="item_number" value="'.$id_pago.'">
        <input type="hidden" name="amount" value="'.$precio.'">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="return" value="'.$url_retorno.'">
		<input type="hidden" name="cancel_return" value="'.$url_retorno.'">
        <input type="hidden" name="invoice" id="invoice" value="'.$id_pago.'" >
		<input type="hidden" name="notify_url" id="notify_url" value="'.$url_notificacion.'" >
		<input name="shopping_url" type="hidden" value="'.$url_retorno.'">
		';
	/*SANDBOX
	echo'
    	<form id="form_paypal" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="'.$buyer.'">
        <input type="hidden" name="item_name" value="'.$descrip_pago.'">
        <input type="hidden" name="currency_code" value="EUR">
        <INPUT TYPE="hidden" NAME="item_number" VALUE="'.$id_pago.'">
        <input type="hidden" name="amount" value="'.$precio.'">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="return" value="'.$url_retorno.'">
		<input type="hidden" name="cancel_return" value="'.$url_retorno.'">
        <input type="hidden" name="invoice" id="invoice" value="'.$id_pago.'" >
		<input type="hidden" name="notify_url" id="notify_url" value="'.$url_notificacion.'" >
		<input name="shopping_url" type="hidden" value="'.$url_retorno.'">
        ';
		*/
}
function paypal_PDT_request($tx,$pdt_identity_token) {
    $request = curl_init();

    // Set request options
    curl_setopt_array($request, array
        (
          //CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
		  CURLOPT_URL => 'https://www.paypal.com/cgi-bin/webscr',
          CURLOPT_POST => TRUE,
          CURLOPT_POSTFIELDS => http_build_query(array
              (
                'cmd' => '_notify-synch',
                'tx' => $tx,
                'at' => $pdt_identity_token,
              )
          ),
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_HEADER => FALSE,
          //CURLOPT_SSL_VERIFYPEER => TRUE,
          //CURLOPT_CAINFO => 'cacert.pem',
        )
    );

    // Realizar la solicitud y obtener la respuesta
    // y el código de status
    $response = curl_exec($request);
    $status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

    // Cerrar la conexión
    curl_close($request);
    return $response;
}

function obten_arrayResPaypal($resultado){
	 $datos = array();
	 $pos_ant = -1;
	 $cont = 0;
	 for($i=0; $i<strlen($resultado); $i++){
		if(ord($resultado[$i]) == '10'){
			$datos[$cont] = utf8_encode(urldecode(substr($resultado,$pos_ant+1,($i-1)-$pos_ant)));
			$cont++;
			$pos_ant = $i;
		}	
	 }
	 return $datos;
}

function obten_posicion($array,$texto){
	for($i=0; $i<count($array); $i++){
		//$pos = strpos($array[$i],$texto);
		//echo $i.'-'.$array[$i].'-'.$pos.'*<br>';
		if(strpos($array[$i],$texto) !== false){
			break;
		}
	}
	if($i == count($array)){$i = -1;}//no ha encontrado nada en el array
	return $i;
}

function obten_resDespuesIgual($campo){
	$pos = strpos($campo,'=');
	return substr($campo,$pos+1);
}

function obten_estadoPago($est){
	$trad = '';
	if($est == 'canceled'){$trad = 'Cancelado';}
	else if($est == 'created'){$trad = 'Creado';}
	else if($est == 'completed'){$trad = 'Completado';}
	else if($est == 'incomplete'){$trad = 'Incompleto';}
	else if($est == 'error'){$trad = 'Error';}
	else if($est == 'reversalerror'){$trad = 'Error devolucion';}
	else if($est == 'processing'){$trad = 'Procesando';}
	else if($est == 'pending'){$trad = 'Pendiente';}
	else{$trad = 'No disponible';}
	/*
	CANCELED – The Preapproval agreement was cancelled
	CREATED – The payment request was received; funds will be transferred once the payment is approved
	COMPLETED – The payment was successful
	INCOMPLETE – Some transfers succeeded and some failed for a parallel payment or, for a delayed chained payment, secondary receivers have not been paid
	ERROR – The payment failed and all attempted transfers failed or all completed transfers were successfully reversed
	REVERSALERROR – One or more transfers failed when attempting to reverse a payment
	PROCESSING – The payment is in progress
	PENDING – The payment is awaiting processing*/
	return $trad;
}
/*
CON CUENTA PAYPAL VERDADERO

SUCCESS           -0
transaction_subject=            -1
payment_date=12%3A09%3A45+Jan+31%2C+2016+PST            -2
txn_type=web_accept            -3
last_name=Navarrete            -4
residence_country=ES            -5
item_name=Publicidad%3A+Posici%F3n+4D+en+la+liga+Club+de+padel+santa+claus            -6
payment_gross=            -7
mc_currency=EUR            -8
business=pagos%40miligadepadel.manuelbdv.es           -9
payment_type=instant            -10
protection_eligibility=Ineligible            -11
payer_status=unverified            -12
tax=0.00            -13
payer_email=gemang%40hotmail.es           -14
txn_id=9YB08729TX536901X           -15
quantity=1            -16
receiver_email=pagos%40miligadepadel.manuelbdv.es            -17
first_name=Gema            -18
invoice=87            -19
payer_id=XM925RW7G3QLE           -20
receiver_id=78DBYRJXMTNNE            -21
item_number=87            -22
handling_amount=0.00            -23
payment_status=Completed            -24
payment_fee=            -25
mc_fee=0.37            -26
shipping=0.00            -27
mc_gross=0.50           -28
custom=            -29
charset=windows-1252            -30


DEVOLUCION PAYPAL

CUENTA EMISORA DE DEVOLUCION

SUCCESS - 0
transaction_subject= - 1
payment_date=11:13:29 Apr 11, 2016 PDT - 2
txn_type=web_accept - 3
last_name=berenguer valle - 4
residence_country=ES - 5
item_name=Inscripcion en Liga: Club de padel spanishgo division: 1 - 6
payment_gross= - 7
mc_currency=EUR - 8
business=pagos@miligadepadel.manuelbdv.es - 9
payment_type=instant - 10
protection_eligibility=Ineligible - 11
payer_status=verified - 12
tax=0.00 - 13
payer_email=manu_oamuf@hotmail.com - 14
txn_id=2GT13338VJ197461N - 15
quantity=1 - 16
receiver_email=pagos@miligadepadel.manuelbdv.es - 17
first_name=manuel - 18
invoice=Inscripcion -1-2016-04-11 18:11:27 - 19
payer_id=KUCNX3NMYG4M8 - 20
receiver_id=78DBYRJXMTNNE - 21
item_number=Inscripcion -1-2016-04-11 18:11:27 - 22
handling_amount=0.00 - 23
payment_status=Refunded - 24
payment_fee= - 25
mc_fee=0.37 - 26
shipping=0.00 - 27
mc_gross=0.50 - 28
custom= - 29
charset=windows-1252 - 30


CUENTA INICIAL QUE RECIBE LA DEVOLUCION

SUCCESS - 0
transaction_subject= - 1
txn_type=web_accept - 2
payment_date=12:10:03 Apr 11, 2016 PDT - 3
last_name=berenguer valle - 4
residence_country=ES - 5
item_name=Inscripcion en Liga: Club de padel spanishgo division: 1 - 6
payment_gross= - 7
mc_currency=EUR - 8
payment_type=instant - 9
protection_eligibility=Ineligible - 10
payer_status=verified - 11
tax=0.00 - 12
payer_email=manu_oamuf@hotmail.com - 13
txn_id=7NN21288PV869081L - 14
quantity=1 - 15
receiver_email=pagos@miligadepadel.manuelbdv.es - 16
first_name=manuel - 17
parent_txn_id=2GT13338VJ197461N - 18
invoice=0000001 - 19
payer_id=KUCNX3NMYG4M8 - 20
receiver_id=78DBYRJXMTNNE - 21
memo=devolucion - 22
item_number=Inscripcion -1-2016-04-11 18:11:27 - 23
handling_amount=0.00 - 24
payment_status=Completed - 25
shipping=0.00 - 26
mc_gross=-0.50 - 27
custom= - 28
charset=windows-1252 - 29



CON CUENTA BANCARIA CONFIRMADA VERDADERO

SUCCESS      -0
transaction_subject=       -1
payment_date=03%3A23%3A58+Feb+06%2C+2016+PST       -2
txn_type=web_accept       -3
last_name=Berenguer       -4
residence_country=ES       -5
item_name=Publicidad%3A+Posici%F3n+3I+en+la+liga+Club+de+padel+santa+claus       -6
payment_gross=       -7
mc_currency=EUR       -8
business=pagos%40miligadepadel.manuelbdv.es       -9
payment_type=instant       -10
protection_eligibility=Ineligible       -11
payer_status=verified       -12
tax=0.00       -13
payer_email=avi_nenika%40hotmail.com      -14
txn_id=9PB0296135584951M       -15
quantity=1       -16
receiver_email=pagos%40miligadepadel.manuelbdv.es       -17
first_name=Ana+Visitacion       -18
invoice=88       -19
payer_id=XZUX8H9PVVLF2       -20
receiver_id=78DBYRJXMTNNE       -21
item_number=88       -22
handling_amount=0.00       -23
payment_status=Completed       -24
payment_fee=       -25
mc_fee=0.37       -26
shipping=0.00       -27
mc_gross=0.50       -28
custom=       -29
charset=windows-1252      -30


 function sanear_string($string) { $string = trim($string); $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string ); $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); //Esta parte se encarga de eliminar cualquier caracter extraño 
 $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", ".", " "), '', $string ); return $string; }*/ 
?>