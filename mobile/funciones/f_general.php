<?php
session_start();

function numero_de_BDligas(){
	return 1;
	//return 2;
}

function obten_anunciosPorLiga(){//devuelve el numero de anuncios por ciudad para calcular las GRATIS
	return 15;
}
//guardar en bd
function obten_identPaypal(){//devuelve el identificador de cliente paypal
    include_once ("../../funciones/f_obten.php");
	include_once ("../../../class/mysql.php");
	return obten_consultaUnCampo('unicas','c5','datos','id_datos',8,'','','','','','','');
}

function cuenta_admin(){//cuenta admin
	return base64_decode(obten_consultaUnCampo('unicas','c1','datos','id_datos',9,'','','','','','',''));
}
function cuenta_admin2(){//cuenta admin
	return base64_decode(obten_consultaUnCampo('unicas','c1','datos','id_datos',10,'','','','','','',''));
}

function  pasar_segundos($dias){
	$d = intval($dias) * 86400;
	return $d;
}

function valida_correo($correo){
	$retorno = false;
	$arroba = strpos($correo,'@');
	if($arroba != false){
		$punto = strpos($correo,'.');
		if($punto != false){
			$retorno = true;
		}
	}
	return $retorno;
}

function quitarCespeciales($cad){//elimina caracteres ? por ñ
	for($i=0; $i<strlen($cad); $i++){			
		if($cad[$i] == '?'){
			if($i == 0){
				$cad[$i] = utf8_decode('Ñ');
			}
			else{
				$cad[$i] = utf8_decode('ñ');
			}
		}
	}//fin for
	return $cad;
}

function quitarAcentos($cad){//elimina acentos
	for($i=0; $i<strlen($cad); $i++){	
		if($cad[$i] == utf8_decode('á')){$cad[$i] = 'a';}
		else if($cad[$i] == utf8_decode('à')){$cad[$i] = 'a';}
		else if($cad[$i] == utf8_decode('Á')){$cad[$i] = 'A';}
		else if($cad[$i] == utf8_decode('À')){$cad[$i] = 'A';}
		else if($cad[$i] == utf8_decode('é')){$cad[$i] = 'e';}
		else if($cad[$i] == utf8_decode('è')){$cad[$i] = 'e';}
		else if($cad[$i] == utf8_decode('É')){$cad[$i] = 'E';}
		else if($cad[$i] == utf8_decode('È')){$cad[$i] = 'E';}
		else if($cad[$i] == utf8_decode('í')){$cad[$i] = 'i';}
		else if($cad[$i] == utf8_decode('ì')){$cad[$i] = 'i';}
		else if($cad[$i] == utf8_decode('Í')){$cad[$i] = 'I';}
		else if($cad[$i] == utf8_decode('Ì')){$cad[$i] = 'I';}
		else if($cad[$i] == utf8_decode('ó')){$cad[$i] = 'o';}
		else if($cad[$i] == utf8_decode('ò')){$cad[$i] = 'o';}
		else if($cad[$i] == utf8_decode('Ó')){$cad[$i] = 'O';}
		else if($cad[$i] == utf8_decode('Ò')){$cad[$i] = 'O';}
		else if($cad[$i] == utf8_decode('ú')){$cad[$i] = 'u';}
		else if($cad[$i] == utf8_decode('ù')){$cad[$i] = 'u';}
		else if($cad[$i] == utf8_decode('Ú')){$cad[$i] = 'U';}
		else if($cad[$i] == utf8_decode('Ù')){$cad[$i] = 'U';}
		else{}
	}//fin for
	return $cad;
}

function obten_texto_posPubli($posi_publi){//obtiene el texto de la publicidad
	$texto = 'Columna';
	if(substr($posi_publi,1,1) == 'D'){$texto .= ' Derecha';}
	else{$texto .= ' Izquierda';}
	$texto .= ' Posición '.substr($posi_publi,0,1);
	return $texto;
}

function obten_precio_publicidad($pos){
	//$precios_base = array(0,25,27,27,25,21);
	$precios_base = array(0,20,22,22,20,16);
	return $precios_base[$pos];
}

function obten_plus_publicidad($precio_base,$tipo_pago){
	if($tipo_pago == 1){$precio_base += 5;}
	else if($tipo_pago == 2){$precio_base += 10;}
	else if($tipo_pago == 3){$precio_base += 15;}
	else{$precio_base += 0;}
	return $precio_base;
}

function obten_plus_publicidad_gratuita($suscripcion,$num_ligas){
	$precio_base = 20;//precio base 20 euros
	$precio = $precio_base + ($num_ligas*3);//por cada liga 3 euros
	if($suscripcion == 1){// 3 meses
		$precio_total = ($precio + ($precio*0.5)) / 4 ;// sin descuento sumamos el 50 % y se divide entre 4 para obtener 3 meses
	}
	else if($suscripcion == 2){// 6 meses
		$precio_total = ($precio + ($precio*0.30)) / 2 ;// con descuento sumamos el 30 % y se divide entre 4 para obtener 6 meses
	}
	else if($suscripcion == 3){// 12 meses
		$precio_total = $precio ;// con descuento sumamos el 10 % y se divide entre 4 para obtener 6 meses
	}
	else{
		$precio_total = $precio + ($precio*0.2) ;// total
	}
	return $precio_total;
}

function obten_estadoPubliGratis($estado){
	if($estado == 0){$retorno = 'Activo';}//Activo
	else{$retorno = 'Gongelado';}//Congelado
	return $retorno;
}

function vuelta_fecha($fecha){//entra aaaa mm dd  sale dd-mm-aaaa 
	$anyo = substr($fecha,0,4);
	$mes = substr($fecha,5,2);
	$dia = substr($fecha,8,2);
	return $dia.'-'.$mes.'-'.$anyo;
}

function insertar_fecha($fecha){//entra  dd mm aaaa  y sale aaaa-mm-dd
	$dia = substr($fecha,0,2);
	$mes = substr($fecha,3,2);
	$anyo = substr($fecha,6,4);
	return $anyo.'-'.$mes.'-'.$dia;
}
//REVISAR ESTA FUNCION ES REDUNDANTE
function insercion_fecha($fecha){//entra dd/mm/aaaa y sale aaaa-mm-dd
	$datos = array();
	$datos = explode("/",$fecha);
	return $datos[2].'-'.$datos[1].'-'.$datos[0];
}

function datepicker_fecha($fecha){//entra aaaa mm dd y sale dd/mm/aaaa
	return substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
}

function buscar_enArray($lista,$buscar){//pasamos cualquier array y la variable a buscar en el array
	 $tam = count($lista);
	 $r = -1;
	 for ($i=0; $i<$tam; $i++) { 
        if($lista[$i] == $buscar){//devuelve la posicion encontrada
			$r = $i;
			$i = $tam;
			break;
		} 
     }
	 return $r; 
}

function alerta($num_alertas){//muestra alertas
	if($num_alertas != 0){
		if($num_alertas > 9){
			$texto = '<span class="alerta2">'.$num_alertas.'</span>';
		}
		else{
			$texto = '<span class="alerta">'.$num_alertas.'</span>';
		}
	}
	else{$texto = '';}
	return $texto;
}

function alerta_verde($num_alertas){//muestra alertas
	if($num_alertas != 0){
		if($num_alertas > 9){
			$texto = '<span class="alerta2_verde">'.$num_alertas.'</span>';
		}
		else{
			$texto = '<span class="alerta_verde">'.$num_alertas.'</span>';
		}
	}
	else{$texto = '';}
	return $texto;
}

function genera_id_url($largo,$num,$posicion){//genera la url
	//$caracteres = "0123456789"; //posibles caracteres a usar
	$caracteres = "01Y23w45M67l89O"; //posibles caracteres a usar
	$cadena = ""; //variable para almacenar la cadena generada
	for($i=1; $i<=$largo; $i++){
		if($i == $posicion){
			$cadena .= $num;
		}
		else{
			$pos = rand(0,9);
			$cadena .= substr($caracteres,$pos,1);
			//Extraemos 1 caracter de los caracteres entre el rango 0 a Numero de letras que tiene la cadena 
		}
	}
	return codifica($cadena);
}

function obten_idDivisionUrl($url){//funcion que tiene la url y obtiene el id_division para mostrar la division/liga
	//hay que buscar a partir de $inicio la primera F
	$n = decodifica($url);
	$pos = strpos($n, 'F',12);
	return substr($n,12,$pos-12);
}

function genera_pass($tam){
	$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
	//$numerodeletras=10; //numero de letras para generar el texto
	$cadena = ""; //variable para almacenar la cadena generada
	for($i=0;$i<$tam;$i++)
	{
		$cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
		entre el rango 0 a Numero de letras que tiene la cadena */
	}
	echo $cadena;
}

function limpiaTexto($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql
	$caracteres = array('=""','= ""','"', "'", "=''", "= ''", "%", " OR ", " or ", " AND ", " and ", "=", "<", ">", "`", "+", ",", ";", ":", "*", " FROM ", " from ", " WHERE ", " where ", " UNION SELECT ", " union select ", "&", " LIKE ", " like ");
	$texto = trim($valor);
	$num = count($caracteres);
	for($i=0; $i<$num; $i++){
		$texto = str_replace($caracteres[$i], " ", $texto);
	}
	return $texto;
}

function limpiaTexto2($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql REDUCIDO dejando más caracteres
	$caracteres = array("'.'",'"."','=""','= ""', "=''", "= ''", "%", " OR ", " or ", " AND ", " and ", "`", ";", "*", " FROM ", " from ", " WHERE ", " where ", " UNION SELECT ", " union select ", "&", " LIKE ", " like ");
	$texto = trim($valor);
	$num = count($caracteres);
	for($i=0; $i<$num; $i++){
		$texto = str_replace($caracteres[$i], " ", $texto);
	}
	return $texto;
}

function limpiaTexto3($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql REDUCIDO dejando más caracteres
	$caracteres = array(" OR ", " or ", " AND ", " and ", "`", ";", "*", "FROM", "from", "WHERE", "where", "UNION SELECT", "union select", "LIKE", "like","&nbsp;","select","SELECT");
	$texto = trim($valor);
	$num = count($caracteres);
	for($i=0; $i<$num; $i++){
		$texto = str_replace($caracteres[$i], " ", $texto);
	}
	return $texto;
}

function comprobar_pagina($pagina){//comprueba pagina principal
	if ($pagina != 'inicio' && $pagina != 'gestion_liga' && $pagina != 'gestion_division' && $pagina != 'gestion_pista' && $pagina != 'gestion_arbitro' && $pagina != 'gestion_partido' && $pagina != 'gestion_disputa' && $pagina != 'gestion_noticia' && $pagina != 'gestion_cuenta' && $pagina != 'gestion_sancion' && $pagina != 'gestion_regla' && $pagina != 'gestion_inscripcion' && $pagina != 'gestion_pago' && $pagina != 'gestion_publicidad' && $pagina != 'gestion_temporada' && $pagina != 'gestion_puntuacion' && $pagina != 'gestion_puntos'){
		header ("Location: ../cerrar_sesion.php");
	}
}

function comprobar_pagina_jugador($pagina){//comprueba pagina principal
	if ($pagina != 'index' && $pagina != 'inicio' && $pagina != 'gestion_datos' && $pagina != 'gestion_ligas' && $pagina != 'gestion_pago' && $pagina != 'gestion_seguro' && $pagina != 'gestion_contacto' && $pagina != 'gestion_puntos'){
		header ("Location: ../cerrar_sesion.php");
	}
}

function comprobar_pagina_publicidad($pagina){//comprueba pagina principal
	if ($pagina != 'index' && $pagina != 'inicio' && $pagina != 'gestion_datos' && $pagina != 'gestion_ligas' && $pagina != 'gestion_publicidad' && $pagina != 'gestion_pago'){
		header ("Location: ../cerrar_sesion.php");
	}
}

function obten_ip(){//obtiene ip
	$ip=$_SERVER['REMOTE_ADDR'];
	return $ip;
}

function obten_equipos($tipo_pago){//obtiene el numero de equipos
//HAY QUE CAMBIAR LOS MAXIMOS DE EQUIPO
//0=6/8		1=10	2=15	3=20
	if($tipo_pago == 1){
		$max_equipos = 10;
	}
	else if($tipo_pago == 2){
		$max_equipos = 15;
	}
	else if($tipo_pago == 3){
		$max_equipos = 20;
	}
	else{
		$max_equipos = 8;
	}
	return $max_equipos;
}

function obten_precio($tipo_pago){//obtiene el precio
	if($tipo_pago == 1){
		$precio = 30;
	}
	else if($tipo_pago == 2){
		$precio = 40;
	}
	else if($tipo_pago == 3){
		$precio = 50;
	}
	else{
		$precio = 0;
	}
	return $precio;
}

function obten_tipoPuntos($tipo){//OBTIENE EL TIPO DE PUNTOS INSERTADO
	if($tipo == 0){$r = 'Inscripci&oacute;n';}
	else if($tipo == 1){$r = 'Amistoso';}
	else if($tipo == 2){$r = 'Torneo';}
	else if($tipo == 3){$r = 'Pase 16avos';}
	else if($tipo == 4){$r = 'Pase octavos';}
	else if($tipo == 5){$r = 'Pase cuartos';}
	else if($tipo == 6){$r = 'Semifinales';}
	else if($tipo == 7){$r = 'Pase final';}
	else if($tipo == 8){$r = 'Ganador';}
	else if($tipo == 9){$r = 'Subcampe&oacute;n';}
	else if($tipo == 10){$r = 'Tercero';}
	else if($tipo == 11){$r = 'Cuarto';}
	else{$r = 'Manual';}//manual
	return $r;
}

function obten_tipoArticulo($tipo){//OBTIENE EL TIPO DE ARTICULO  PARA FACTURAS
	if($tipo == 'L'){$art = 1;}//LIGA
	else if($tipo == 'D'){$art = 2;}//DIVISION
	else if($tipo == 'P'){$art = 3;}//PUBLICIDAD
	else{$art = 4;}//IDA Y VUELTA
	return $art;
}

function codifica($cadena){//encripta
	$nueva = base64_encode($cadena);
	return $nueva;
}

function decodifica($cadena){//desencripta
	$nueva = base64_decode($cadena);
	return $nueva;
}

function obten_edad($fecha){
	$anyo = substr($fecha,0,4);
	$res = date('Y')-$anyo;
	return $res;
}

function condiciones_generales(){
	$texto = '
				<p>1. INTRODUCCI&Oacute;N</p>
            	<p>&bull;&nbsp;El uso de nuestro Portal implica la aceptaci&oacute;n y adhesi&oacute;n a todas nuestras Condiciones de Uso y a nuestra Pol&iacute;tica de Privacidad. Te recomendamos que las leas detenidamente.</p>
                <p>&bull;&nbsp;Nuestros Servicios son muy diversos, por lo que en ocasiones se pueden aplicar condiciones o requisitos de productos adicionales (incluidas restricciones de edad). Las condiciones adicionales estar&aacute;n disponibles junto con los Servicios pertinentes y formar&aacute;n parte del acuerdo que estableces con Miligadepadel al usar nuestros servicios.</p>
                <p>2. DEFINICI&Oacute;N DEL SERVICIO DE MILIGADEPADEL</p>
                <p>&bull;&nbsp;El Servicio de Milligadepadel consiste en la gesti&oacute;n, por parte del usuario administrador de ligas de padel profesionales o particulares. El administrador interact&uacute;a directamente con los jugadores que se inscriben en sus ligas.</p>
                <p>&bull;&nbsp;Para registrarse como usuario administrador de ligas en Miligadepadel se ha de completar el formulario de "Crear Nueva Liga". Una vez seleccionada el tipo de liga, deber&aacute; rellenar los datos del formulario y se crear&aacute; un nuevo usuario, que recibir&aacute; un e-mail de notificaci&oacute;n donde deber&aacute; activar su cuenta. A partir de ese momento los datos aportados por el administrador quedar&aacute;n registrados, y ser&aacute;n tratados de conformidad con nuestra Pol&iacute;tica de Privacidad con total exclusividad y privacidad para esta web. Con el correo electr&oacute;nico que ha facilitado el usuario administrador y la clave de acceso, puede acceder a su &aacute;rea seleccionando "Administrador" en el inicio e insertar el e-mail y contrase&ntilde;a con los que se registr&oacute;.</p>
                <p>&bull;&nbsp;Para registrarse como usuario patrocinador en Miligadepadel se ha de completar el formulario de "Crear Patrocinador". Puede acceder directamente a su panel de control seleccionando "Patrocinador" e insertando el e-mail y contrase&ntilde;a, una vez dentro puede seleccionar las ciudades donde quiere patrocinarse y el tipo de suscripci&oacute;n (pagos realizados a trav&eacute;s de paypal).</p>
                <p>&bull;&nbsp;Para registrarse como usuario jugador en Miligadepadel se ha de completar el formulario al inscribirse por primera vez en una liga y divisi&oacute;n correspondiente seleccionado la provincia, municipio, nombre de liga y divisi&oacute;n. Para el resto de inscripciones solo es necesario el e-mail y nombre. Puede acceder directamente a su panel de control seleccionando "Jugador" e insertando el e-mail y contrase&ntilde;a, una vez dentro puede gestionar todos sus pagos realizados o por realizar (siempre que est&eacute; disponible el pago online habilitado por el administrador) as&iacute; como gestionar las incripciones en ligas, contactar con los administradores de las ligas.</p>
                <p>3. USO DEL PORTAL Y SUS SERVICIOS</p>
                <p>Los usuarios se comprometen a utilizar el Portal, sus contenidos y servicios conforme con</p>
                <p>1.- La Ley o cualesquiera otras normas del ordenamiento jur&iacute;dico aplicable</p>
                <p>2.- Las presentes Condiciones de Uso</p>
                <p>3.- Las Normas de Funcionamiento</p>
                <p>4.- Las buenas costumbres</p>
                <p>5.- El orden p&uacute;blico</p>
                <p>&bull;&nbsp;Asimismo, los usuarios se comprometen expresamente a no destruir, alterar, inutilizar o, de cualquier otra forma, da&ntilde;ar los datos, programas o documentos electr&oacute;nicos y dem&aacute;s que se encuentren en el Portal de miligadepadel.manuelbdv.es.</p>
                <p>&bull;&nbsp;Los usuarios se comprometen a no obstaculizar el acceso de otros usuarios al servicio de acceso mediante el consumo masivo de los recursos inform&aacute;ticos a trav&eacute;s de los cuales Miligadepadel presta el servicio, as&iacute; como realizar acciones que da&ntilde;en, interrumpan o generen errores en dichos sistemas.</p>
                <p>&bull;&nbsp;Los usuarios se comprometen a no utilizar en este portal ning&uacute;n material de car&aacute;cter ofensivo, abusivo o perjucidial contra terceras personas registradas o no en el portal y que sean contrarias a la ley, el orden p&uacute;blico o que causen o sean susceptibles de causar cualquier tipo de alteraci&oacute;n en los sistemas inform&aacute;ticos de Miligadepadel o de terceros.</p>
                <p>&bull;&nbsp;Al contactar los usuarios aceptan que se les env&iacute;e emails al correo electr&oacute;nico solicitando informaci&oacute;n, consultas o preguntas.</p>
                <p>4. PROPIEDAD INTELECTUAL E INDUSTRIAL</p>
                <p>El Portal aloja contenidos tanto propios como contenidos creados por los usuarios. El Portal est&aacute; protegido por leyes de propiedad intelectual y por los tratados internacionales en la materia. El contenido que se muestra en o a trav&eacute;s del sitio web est&aacute; protegido en su condici&oacute;n de obra colectiva y / o compilaci&oacute;n, de acuerdo con las leyes de propiedad intelectual y los tratados internacionales sobre la materia.</p>
                <p>&bull;&nbsp;Salvo que fuera autorizado por Miligadepadel o a menos que ello resulte legalmente permitido, el usuario no podr&aacute; copiar, modificar, distribuir, vender, alquilar o explotar de cualquier otra forma contenidos del Portal. Asimismo el usuario no puede llevar a cabo operaciones de desensamblaje o descompilaci&oacute;n, ingenier&iacute;a inversa o cualquier otra operaci&oacute;n destinada a obtener cualquier c&oacute;digo fuente contenido en este Portal.</p>
                <p>&bull;&nbsp;Al subir fotograf&iacute;as al Portal, los usuarios ceden a Miligadepadel los derechos de explotaci&oacute;n de propiedad intelectual sobre las mismas, por lo que Miligadepadel podr&aacute; eliminarlas en casos en los que se infrinjan las normas de buen uso.</p>
                <p>&bull;&nbsp;Los usuarios garantizan que son plenos titulares de los derechos que se ceden a Miligadepadel en virtud de esta cl&aacute;usula, y que la eliminaci&oacute;n de los mismos por Miligadepadel no supondr&aacute; violaci&oacute;n alguna de derechos de propiedad intelectual, ni de imagen, ni, en general, de ninguna otra clase, que correspondan a cualesquiera terceros, oblig&aacute;ndose a indemnizar y a mantener indemne a Miligadepadel en caso de infracci&oacute;n.</p>
                <p>5. EXCLUSI&Oacute;N DE GARANTIAS Y RESPONSABILIDAD</p>
                <p>A. Disponibilidad y Continuidad del Portal y los Servicios</p>
                <p>&bull;&nbsp;Miligadepadel no garantiza la disponibilidad, el acceso y/o la continuidad del funcionamiento del Portal y de sus Servicios. Asimismo, Miligadepadel no ser&aacute; responsable, con los l&iacute;mites establecidos por la Ley, de los da&ntilde;os y perjuicios causados al Usuario como consecuencia de la indisponibilidad, fallos de acceso y falta de continuidad del Portal y de sus Servicios.</p>
                <p>B. Contenidos y Servicios de Miligadepadel</p>
                <p>&bull;&nbsp;Miligadepadel responder&aacute; &uacute;nica y exclusivamente de los Servicios que preste por s&iacute; misma y de los contenidos directamente originados por Miligadepadel e identificados con su copyright. Dicha responsabilidad quedar&aacute; excluida en los casos en que concurran causas de fuerza mayor o en los supuestos en que la configuraci&oacute;n de los equipos del Usuario no sea la adecuada para permitir el correcto uso de los servicios de Internet prestados por Miligadepadel.</p>
                <p>&bull;&nbsp;Sin perjuicio de lo dispuesto, la posible responsabilidad de Miligadepadel frente al usuario o frente a terceros se limita al  precio total del servicio contratado que origin&oacute; la responsabilidad, con exclusi&oacute;n, en todo caso, de cualquier tipo de responsabilidad por da&ntilde;os indirectos o por lucro cesante..</p>
                <p>&bull;&nbsp;El Portal no se hace responsable de la posible aparici&oacute;n de ligas indexadas en buscadores ajenos al portal, una vez se hayan dado de baja de nuestras bases de datos.</p>
                <p>C. Contenidos y Servicios de Terceros</p>
                <p>&bull;&nbsp;Miligadepadel no revisa o controla previamente, aprueba ni hace propios los contenidos, productos, servicios, opiniones, comunicaciones, datos, archivos y cualquier clase de informaci&oacute;n de terceros recogidos en el Portal. Asimismo, no garantiza la licitud, fiabilidad, utilidad, veracidad, exactitud, exhaustividad y actualidad de los contenidos, informaciones y servicios de terceros en el Portal. Miligadepadel tampoco garantiza de ninguna forma que los Usuarios del Portal utilicen los contenidos y/o servicios del mismo conforme con la ley, las normativas aplicables, el orden publico ni las presentes Condiciones de Uso.</p>
                <p>&bull;&nbsp;Miligadepadel no se responsabiliza de los contenidos volcados o los actos cometidos por otros usuarios. Tampoco se responsabiliza de cualquier da&ntilde;o o perjuicio como consecuencia de la presencia de virus u otros elementos en los contenidos y servicios prestados por terceros. Asimismo Miligadepadel no responder&aacute; de los da&ntilde;os y perjuicios de cualquier naturaleza derivados del uso negligente o malintencionado de las cuentas de correo electr&oacute;nico utilizadas.</p>
                <p>&bull;&nbsp;En cualquier caso Miligadepadel no ser&aacute; responsable, ni indirectamente ni subsidiariamente, de la perdida econ&oacute;mica o reputaci&oacute;n, ni de ninguna clase de da&ntilde;os especiales, indirectos o emergentes, resultantes de la utilizaci&oacute;n del sitio web por parte del usuario.</p>
                <p>&bull;&nbsp;La exoneraci&oacute;n de responsabilidad se&ntilde;alada en el p&aacute;rrafo anterior ser&aacute; de aplicaci&oacute;n en el caso de que Miligadepadel no tenga conocimiento efectivo de que la actividad o la informaci&oacute;n almacenada es il&iacute;cita o de que lesiona bienes o derechos de un tercero susceptibles de indemnizaci&oacute;n, o si la tuviesen act&uacute;e con diligencia para retirar los datos y contenidos o hacer imposible el acceso a ellos.</p>
                <p>6. CONTRATACI&Oacute;N DE SERVICIOS VINCULADOS A PATROCINADOR</p>
                <p>&bull;&nbsp;La contrataci&oacute;n del servicio "Patrocinador" vinculado a un anuncio publicado en el Portal s&oacute;lo producir&aacute; efectos DURANTE el periodo de vigencia del anuncio.</p>
                <p>7. CONTRATACI&Oacute;N CON TERCEROS A TRAV&Eacute;S DEL PORTAL</p>
                <p>&bull;&nbsp;El Usuario Administrador reconoce y acepta que cualquier relaci&oacute;n contractual o extracontractual que formalice con los jugadores/as o anunciantes, as&iacute; como su participaci&oacute;n en ligas o servicios, se entienden realizados &uacute;nica y exclusivamente entre el Usuario administrador y jugadores/as o anunciantes. El Usuario administrador acepta por lo tanto que Miligadepadel no tiene ning&uacute;n tipo de responsabilidad sobre los da&ntilde;os o perjuicios de cualquier naturaleza ocasionados con motivo de sus negociaciones, conversaciones y/o relaciones contractuales o extracontractuales con los jugadores/as, anunciantes o terceras personas f&iacute;sicas o jur&iacute;dicas contactadas a trav&eacute;s del Portal.</p>
                <p>8. ENLACES DE TEXTO Y ENLACES GR&Aacute;FICOS</p>
                <p>&bull;&nbsp;El Portal pone a disposici&oacute;n de sus usuarios administradores y patrocinadores dispositivos t&eacute;cnicos de enlace de texto y/o enlaces gr&aacute;ficos que permiten a los usuarios el acceso a p&aacute;ginas web titularidad de otras entidades. Estos enlaces est&aacute;n debidamente se&ntilde;alados como publicidad en el Portal.</p>
                <p>&bull;&nbsp;Los usuarios previamente autorizados reconocen y aceptan que la utilizaci&oacute;n de los contenidos de las p&aacute;ginas web enlazadas ser&aacute; bajo su exclusivo riesgo y responsabilidad y exonera al portal Miligadepadel de cualquier responsabilidad sobre disponibilidad t&eacute;cnica de las p&aacute;ginas web enlazadas, la calidad, fiabilidad, exactitud y/o veracidad de los servicios, informaciones, elementos y/o contenidos a los que el usuario pueda acceder en las mismas.</p>
                <p>&bull;&nbsp;Miligadepadel no ser&aacute; responsable indirecta ni subsidiariamente de los da&ntilde;os y perjuicios de cualquier naturaleza derivados de a) el funcionamiento, indisponibilidad, inaccesibilidad y la ausencia de continuidad de las p&aacute;ginas web enlazadas; b) la falta de mantenimiento y actualizaci&oacute;n de los contenidos y servicios contenidos en las p&aacute;ginas web enlazadas; c) la falta de calidad, inexactitud, ilicitud, inutilidad de los contenidos y servicios de las p&aacute;ginas web enlazadas.</p>
                <p>9. DERECHO DE LIMITAR O PONER FIN AL SERVICIO DE MILIGADEPADEL</p>
                <p>&bull;&nbsp;Miligadepadel se reserva el derecho, ejercitable en cualquier momento y de modo discrecional a rechazar cualquier anuncio o compromiso de ubicaci&oacute;n de un anuncio o liga en una localidad determinada. Miligadepadel tambi&eacute;n se reserva el derecho de eliminar cualquier anuncio o liga del Portal sin necesidad de avisar previamente a los usuarios y/o anunciantes siempre que infrinjan las normas de funcionamiento.</p>
                <p>&bull;&nbsp;Miligadepadel puede denegar o poner fin a su servicio y adoptar medidas t&eacute;cnicas y legales para mantener a los usuarios alejados del Portal si creemos que est&aacute;n creando problemas o actuando de forma contraria al esp&iacute;ritu o la forma de nuestras normas y condiciones de uso, todo ello con independencia de cualquier pago realizado por el uso del Portal o servicios complementarios. Sin embargo, decidamos o no retirar el acceso al sitio web de un usuario, no aceptamos ninguna responsabilidad por el uso no autorizado o ilegal del sitio web por los usuarios, tal y como se describe en los p&aacute;rrafos anteriores.</p>
                <p>10. INDEMNIZACI&Oacute;N POR USO ABUSIVO</p>
                <p>&bull;&nbsp;Miligadepadel se reserva el derecho, ejercitable en cualquier momento a cobrar una indemnizaci&oacute;n del precio &iacute;ntegro por los da&ntilde;os causados como consecuencia de contravenir las presentes Condiciones de Uso o Normas de funcionamiento. Miligadepadel tambi&eacute;n podr&aacute; cobrar dicha indemnizaci&oacute;n, tanto al administrador de la liga, jugadores, patrocinadores como terceras personas o empresas que faciliten con su ayuda el uso abusivo de los servicios, podr&aacute;n ser requeridos para el pago de esta indemnizaci&oacute;n.</p>
                <p>11. VARIOS</p>
                <p>A. Modificaciones en el Servicio y Condiciones de Uso</p>
                <p>&bull;&nbsp;Miligadepadel se reserva el derecho a realizar cambios en el Portal, pudiendo modificar, suprimir e incluir, unilateralmente y sin previo aviso, nuevos contenidos as&iacute; como la forma en que estos aparezcan presentados y localizados..</p>
                <p>&bull;&nbsp;Asimismo, Miligadepadel se reserva el derecho a realizar cambios las presentes Condiciones de Uso en cualquier momento. El usuario quedar&aacute; sujeto a las nuevas Condiciones de Uso que hayan sido publicadas en el momento en que acceda o utilice los servicios del Portal.</p>
                <p>&bull;&nbsp;Si alguna de las presentes condiciones resulta invalidada, nula o inaplicable por cualquier motivo, dicha condici&oacute;n quedar&aacute; excluida y no afectar&aacute; a la validez ni la aplicabilidad del resto de condiciones.</p>
                <p>B. Precios de los Servicios</p>
                <p>&bull;&nbsp;Los precios de los servicios ser&aacute;n establecidos en las correspondientes p&aacute;ginas del Portal para cada servicio.</p>
                <p>C. Menores de Edad</p>
                <p>&bull;&nbsp;Con car&aacute;cter general, para hacer uso de los Servicios del Portal los menores de edad deben haber obtenido previamente la autorizaci&oacute;n de sus padres, tutores o representantes legales, quienes ser&aacute;n responsables de todos los actos realizados a trav&eacute;s del Portal por los menores a su cargo.</p>
                <p>12. DURACI&Oacute;N Y TERMINACI&Oacute;N</p>
                <p>&bull;&nbsp;La prestaci&oacute;n de los servicios y/o contenidos del Portal tiene una duraci&oacute;n indefinida.</p>
                <p>&bull;&nbsp;Sin perjuicio de lo anterior, y adem&aacute;s de por las causas establecidas legalmente, Miligadepadel est&aacute; facultada para dar por terminada, suspender o interrumpir unilateralmente, en cualquier momento y sin necesidad de preaviso, la prestaci&oacute;n del servicio y del Portal y/o cualquiera de los servicios..</p>
                <p>13. JURISDICCI&Oacute;N</p>
                <p>&bull;&nbsp;La ley aplicable en caso de disputa o conflicto de interpretaci&oacute;n de los t&eacute;rminos que conforman estas Condiciones de Uso, as&iacute; como cualquier cuesti&oacute;n relacionada con los servicios del Portal, ser&aacute; la ley espa&ntilde;ola..</p>
                <p>&bull;&nbsp;Para la resoluci&oacute;n de cualquier controversia que pudiera surgir con ocasi&oacute;n del uso del Portal y sus servicios, las partes acuerdan someterse a la jurisdicci&oacute;n de los Juzgados y Tribunales de la ciudad de Alicante (Espa&ntilde;a), y sus superiores jer&aacute;rquicos, con expresa renuncia a otros fueros si lo tuvieren y fueran diferentes de los rese&ntilde;ados..</p>
                <p>NORMAS DE FUNCIONAMIENTO</p>
				<p>&bull;&nbsp;El portal no se hace responsable de cualquier funcionamiento incorrecto del sistema ya bien sea por error en la conexi&oacute;n de internet o por problemas con el dispositivo.</p>
                <p>1. Administrador de Liga</p>
                <p>&bull;&nbsp;El administrador se compromete a realizar las gestiones de la manera m&aacute;s responsable y justa posible.</p>
                <p>&bull;&nbsp;El administrador est&aacute; de acuerdo en asumir toda la responsabilidad legal sobre las ligas que gestiona o ha gestionado.</p>
                <p>&bull;&nbsp;El administrador acepta que las ligas gratuitas finalizadas o abandonas por 1 mes son eliminadas autom&aacute;ticamente del sistema.</p>
                <p>&bull;&nbsp;Por seguridad, el sistema genera noticias autom&aacute;ticas con todas las modificaciones realizadas sobre las ligas y divisiones.</p>
				<p>&bull;&nbsp;Por seguridad, para activar el modo de pagos online a trav&eacute;s de paypal es obligatorio solicitarlo a atencionalcliente(arroba)miligadepadel.manuelbdv.es adjuntando una foto/fotocopia del NIF o NIE del administrador/a.</p>
                <p>&bull;&nbsp;El administrador tiene el derecho de admisi&oacute;n sobre los participantes de su liga.</p>
                <p>&bull;&nbsp;El administrador est&aacute; obligado de realizar la devoluci&oacute;n del pago (si lo hubiera) por la misma v&iacute;a (online o presencial) que lo ha recibido en caso de no admitir en su liga a un equipo inscrito.</p>
                <p>&bull;&nbsp;El administrador tiene el derecho a decidir si devolver o no el importe de la inscripci&oacute;n s&oacute;lo en el caso de que lo solicite el equipo inscrito y no tengan un motivo firme para realizar dicha devoluci&oacute;n.</p>
                <p>&bull;&nbsp;El administrador acepta ser el &uacute;nico responsable legal de los pagos online o presenciales recibidos por parte de los jugadores que se inscriben en sus ligas.</p>
                <p>&bull;&nbsp;El administrador acepta que esta web pueda facilitar sus datos personales a jugadores s&oacute;lo en caso de situaci&oacute;n irregular o indicios de fraude con pagos (online y presencial) recibidos por jugadores, para que los jugadores puedan tomar las medidas legales pertinentes.</p>
                <p>&bull;&nbsp;El administrador acepta que la eliminaci&oacute;n de su cuenta solo ser&aacute; posible cuando todas las ligas est&eacute;n finalizadas o no tengan inscritos, evitando de esta manera perjudicar o realizar actividades fraudulentas sobre los jugadores.</p>
                <p>&bull;&nbsp;El administrador acepta que por seguridad est&aacute; web guarde un hist&oacute;rico de sus cambios en datos personales.</p>
                <p>&bull;&nbsp;En esta web sus datos personales son totalmente privados y no ser&aacute;n utilizados para ning&uacute;n fin ajeno a esta web.</p>
                <p>&bull;&nbsp;Esta web tiene el derecho de inhabilitar a cualquier administrador que no respete las condiciones legales de esta web o realice actividades fraudulentas.</p>
                <p>&bull;&nbsp;Esta web almacena la Ip al acceder al panel de usuario s&oacute;olo y exclus&iacute;vamente para utilizar en casos de acciones fraudulentas.</p>
                <p>&bull;&nbsp;Esta web no se hace responsable ante el delito de robo de cualquier informaci&oacute;n alojada en nuestra base de datos, y emprender&aacute; acciones legales contra los atacantes.</p>
                <p>2. Jugador</p>
                <p>&bull;&nbsp;El jugador/a debe asegurarse de inscribirse en la liga adecuada y conocer al administrador, organizaci&oacute;n o club que lo gestiona sobre todo si requiere pago online por inscripci&oacute;n.</p>
                <p>&bull;&nbsp;El jugador/a dispone de un panel de control personalizado en el que podr&aacute; gestionar algunos de sus datos personales, foto de perfil, justificantes de pagos y ver las estad&iacute;sticas de sus ligas inscritas.</p>
                <p>&bull;&nbsp;Para inscribirse en cualquier liga es necesario ser mayor de 18 a&ntilde;os, o est&aacute; confirmando que sus padres/tutores est&aacute;n informados y son responsables de su inscripci&oacute; en cualquier liga de miligadepadel.manuelbdv.es que queda exenta de cualquier responsabilidad sobre los menores que utilicen el portal.</p>
                <p>&bull;&nbsp;Al registrarse como nuevo jugador, &eacute;ste debe saber que est&aacute; aceptando que los administradores de las ligas en las que se inscribe tienen acceso a algunos de tus datos, utilizados expresamente para contactar con usted en temas relacionados con la liga..</p>
                <p>&bull;&nbsp;Este portal recomienda tener acceso al correo electr&oacute;nico con el que se ha efectuado el registro en esta web, y revisar la bandeja de entrada con frecuencia, ya que podr&aacute; recibir e-mails sobre sus ligas por parte de administrador/es.</p>
                <p>&bull;&nbsp;Con la inscripci&oacute;n el jugador/a acepta recibir informaci&oacute;n acerca de las ligas inscritas incluyendo informaci&oacute;n importante sobre miligadepadel.manuelbdv.es.</p>
                <p>&bull;&nbsp;Una vez el jugador/a decide eliminar su perfil, todos los datos ser&aacute;n eliminados inmediatamente y de forma irreversible.</p>
                <p>&bull;&nbsp;Los datos del perfil de los jugadores/as alojados en este portal son totalmente privados y solo se utilizar&aacute;n en esta web, no ser&aacute;n facilitados a terceros bajo ning&uacute;n concepto.</p>
                <p>&bull;&nbsp;El/La jugador/a participante se compromete a utilizar los servicios ofrecidos de manera responsable y de acuerdo a nuestras normas o podr&aacute; ser eliminado del portal.</p>
                <p>&bull;&nbsp;El/la jugador/a se compromete a facilitar el correcto funcionamiento de las ligas inscritas y no entorpecerlas o dificultarlas.</p>
                <p>&bull;&nbsp;El/la jugador/a debe hacer un uso responsable de la comunicaci&oacute;n v&iacute;a e-mail con el/los administrador/es y acepta que es el responsable del contenido enviado, eximiendo de toda responsabilidad al portal miligadepadel.manuelbdv.es.</p>
                <p>&bull;&nbsp;Esta web se ha desarrollado estableciendo restricciones para ofrecer la m&aacute;xima seguridad para los/las jugadores/as participantes.</p>
                <p>&bull;&nbsp;Esta web ofrece la posibilidad de que los jugadores/as realicen el pago online de inscripci&oacute;n al administrador de la liga a trav&eacute;s de Paypal, s&oacute;lo si el administrador configura los requerimientos necesarios, siendo el aldministrador el total responsable ya que es el que recibe el pago.</p>
                <p>&bull;&nbsp;El pago online de inscripciones se ha de realizar solo una vez por un jugador/a del equipo.</p>
                <p>&bull;&nbsp;La devoluci&oacute;n de pagos online se han de tramitar con el administrador, este solicita la eliminaci&oacute;n y los jugadores deben comprobar que han recibido la devoluci&oacute;n v&iacute;a PayPal, y una vez verificada la gesti&oacute;n de devoluci&oacute;n alguno de los miembros del equipo deben pulsar sobre "Si" sobre el correo electr&oacute;nico recibido e inmediatamente se eliminar&aacute; la inscripci&oacute;n, de lo contrario pulse "No".</p>
                <p>&bull;&nbsp;Si el equipo solicita la devoluci&oacute;n una vez realizado el pago de la inscripci&oacute;n el administrador tiene el derecho a decidir si realizar la devoluci&oacute;n o no.</p>
                <p>&bull;&nbsp;El administrador tiene el derecho de adminsi&oacute;n sobre los equipos inscritos, pero si ha recibido un pago online o presencial y no desea admitir una participaci&oacute;n est&aacute; obligado a realizar la devoluci&oacute;n &iacute;ntegra del importe de pago.</p>
                <p>&bull;&nbsp;El jugador acepta que para que se active la eliminaci&oacute;n de su perfil solo ser&aacute; posible cuando todas las ligas en las que est&aacute; inscrito se encuentren finalizadas o las inscripciones no est&eacute;n pagadas, evitando de esta manera alterar la liga o realizar actividades fraudulentas.</p>
                <p>&bull;&nbsp;Esta web no recibe comisiones ni pagos de los jugadores/as, por lo tanto no se hace responsable de ning&uacute;n pago online o presencial que los jugadores/as realicen a los administradores de la liga en la que se han inscrito.</p>
                <p>&bull;&nbsp;Este portal web se compromete ante indicios o casos fraudulentos a facilitar los datos necesarios del infractor para formalizar una posible denuncia judicial.</p>
                <p>&bull;&nbsp;Esta portal web almacena la Ip al acceder al panel de jugador s&oacute;olo y exclus&iacute;vamente para utilizar en casos de acciones fraudulentas.</p>
                <p>&bull;&nbsp;Esta portal web no se hace responsable ante el delito de robo de cualquier informaci&oacute;n alojada en nuestra base de datos, y emprender&aacute; acciones legales contra los atacantes.</p>
                <p>3. Patrocinador</p>
                <p>&bull;&nbsp;El Patrocinador est&aacute; de acuerdo en asumir toda la responsabilidad legal sobre el contenido de las im&aacute;genes y enlaces insertados.</p>
                <p>&bull;&nbsp;Esta web dispone de un control de anuncios para asegurar siempre la publicaci&oacute;n equitativa de publicidad para cada ciudad.</p>
                <p>&bull;&nbsp;Puede realizar tantos anuncios por ciudad como desee.</p>
                <p>&bull;&nbsp;Los anuncios pueden verse congelados en el caso de no haber en ese momento ligas activas para la ciudad seleccionada, tendr&aacute; la opci&oacute;n de cambiar a una ciudad m&aacute;s cercana o esperar nuevas ligas activas, el tiempo de espera ser&aacute; a&ntilde;adido autom&aacute;ticamente.</p>
                <p>&bull;&nbsp;Los precios de anuncio var&iacute;an en funci&oacute;n de las ligas que tiene cada ciudad.</p>
                <p>&bull;&nbsp;En esta web sus datos personales son totalmente privados y no ser&aacute;n utilizados para ning&uacute;n fin ajeno a esta web.</p>
                <p>&bull;&nbsp;En caso de cualquier indicio de actividad delictiva, esta web podr&aacute; facilitar sus datos personales para realizar las acciones legales oportunas.</p>
                <p>&bull;&nbsp;El pago de los anuncios se realiza a trav&eacute;s de la platarforma de pagos seguros PayPal.</p>
                <p>&bull;&nbsp;El patrocinador acepta que la eliminaci&oacute;n de su cuenta solo ser&aacute; posible cuando todos los anuncios est&eacute;n finalizados o no se hayan realizado el pago.</p>
                <p>&bull;&nbsp;Esta web puede cancelar en cualquier momento un anuncio en el que se hayan realizado irregularidades en el pago.</p>
                <p>&bull;&nbsp;Una vez que su anuncio es publicado ya no es posible la devoluci&oacute;n del pago realizado.</p>
				<p>&bull;&nbsp;Los anuncios pueden quedarse en estado congelado debido a la falta de ligas activas para una ciudad, puede elegir cambiar a una ciudad m&aacute;s cercana o esperar ligas activas, el tiempo de congelaci&oacute;n se le abonar&aacute; autom&aacute;ticamente a la fecha de fin del anuncio.</p>
                <p>&bull;&nbsp;Esta web tiene el derecho de inhabilitar a cualquier patrocinador que no respete las condiciones legales de esta web o realice actividades fraudulentas.</p>
                <p>&bull;&nbsp;Esta web almacena la Ip al acceder al panel de patrocinador s&oacute;olo y exclus&iacute;vamente para utilizar en casos de acciones fraudulentas.</p>
                <p>&bull;&nbsp; Esta web no se hace responsable ante el delito de robo de cualquier informaci&oacute;n alojada en nuestra base de datos, y emprender&aacute; acciones legales contra los atacantes.</p>                
                <p>POLITICA DE PRIVACIDAD DE MILIGADEPADEL Y USO DE COOKIES</p>
                <p>&bull;&nbsp;La presente Pol&iacute;tica de Privacidad tiene por objeto describir la pol&iacute;tica de protecci&oacute;n de datos de Miligadepadel (en adelante, "Miligadepadel") propietaria de las p&aacute;ginas web ubicadas bajo el dominio www.miligadepadel.com, miligadepadel.manuelbdv.es (en adelante, el "Portal").<!-- con la direcci&oacute;n Miligadepadel, Apartado postal 29059, Madrid y con N&uacute;mero de Identificaci&oacute;n Fiscal B86326758 e inscrita en el Registro Mercantil de Madrid, Tomo 29473, Folio 100, Secci&oacute;n 8, Hoja M530465..--></p>
                <p>&bull;&nbsp;Los datos aportados por los usuarios a Miligadepadel son incluidos en nuestros ficheros registrados ante la Agencia Espa&ntilde;ola de Protecci&oacute;n de Datos (AEPD). La AEPD se encarga de velar por el cumplimiento de las leyes sobre privacidad y protecci&oacute;n de datos y de garantizar la seguridad y privacidad de sus datos.</p>
                <p>1. INFORMACI&Oacute;N SOBRE DATOS RECABADOS POR MILIGADEPADEL</p>
                <p>&bull;&nbsp;Los servidores de Miligadepadel est&aacute;n situados en Espa&ntilde;a y recabamos y almacenamos en ficheros tanto informaci&oacute;n facilitada por los usuarios como datos obtenidos a trav&eacute;s de la utilizaci&oacute;n del Portal.</p>
                <p>&bull;&nbsp;Por tanto, mediante el acceso al Portal o la utilizaci&oacute;n de sus servicios el usuario consiente y autoriza expresamente a que los datos de car&aacute;cter personal facilitados, sean incorporados a la base de datos de Miligadepadel, y que sean tratados exclusivamente de conformidad con los fines establecidos en la presente Pol&iacute;tica de Privacidad, as&iacute; como las Condiciones de Uso.</p>
                <p>&bull;&nbsp;En el supuesto de que el usuario tuviera que facilitar a Miligadepadel datos personales de terceros, deber&aacute; asegurarse de contar con el consentimiento expreso de dicho tercero, habi&eacute;ndole informado de a qui&eacute;n se van a facilitar los datos, la finalidad y la posibilidad de que Miligadepadel se ponga en contacto con ellos.</p>
                <p>&bull;&nbsp;La falta de suministro de aquellos datos que sean obligatorios para la prestaci&oacute;n de los servicios o el suministro de datos incorrectos, inexactos o no actualizados imposibilitar&aacute; la prestaci&oacute;n de los servicios por parte de Miligadepadel.</p>
                <p>1) INFORMACI&Oacute;N FACILITADA POR EL USUARIO</p>
                <p>&bull;&nbsp;Para administrar, jugar o patrocinar una liga, el usuario tiene que proporcionar datos personales como, por ejemplo, la direcci&oacute;n del correo electr&oacute;nico, el nombre y como usuario administrador de ligas puede que el nif. Para disfrutar de determinados servicios que ofrece Miligadepadel en su Portal, es posible que sea redirigido a la plataforma de pago paypal para realizar pagos y posteriormente volver a Miligadepadel para verificar el pago.
Miligadepadel se reserva el derecho de comprobar la identidad de los usuarios mediante verificaci&oacute;n por tel&eacute;fono o e-mail.</p>
                <p>&bull;&nbsp;Los contactos establecidos en Miligadepadel ser&aacute; a trav&eacute;s de su direcci&oacute;n del correo electr&oacute;nico y nunca se facilitar&aacute; el correo electr&oacute;nico del emisor.</p>
                <p>2) DATOS OBTENIDOS A TRAV&Eacute;S DE LA UTILIZACI&Oacute;N DE NUESTROS SERVICIOS</p>
                <p>&bull;&nbsp;Cada vez que uses nuestros servicios o que consultes nuestro contenido, es posible que obtengamos y que almacenemos determinada informaci&oacute;n en los registros del servidor de forma autom&aacute;tica. Estos datos podr&aacute;n incluir:.</p>
                <p>&bull;&nbsp;informaci&oacute;n sobre el acceso (por ejemplo, si accedes des pc, m&oacute;vil, tablet...etc) y tipo de navegador,</p>
                <p>&bull;&nbsp;informaci&oacute;n sobre el acceso de los usuarios para evitar suplantaci&oacute;n de identidad,</p>
                <p>&bull;&nbsp;la direcci&oacute;n IP.</p>
                <p>3) COOKIES</p>
                <p>&bull;&nbsp;Este portal no utiliza cookies.</p>
                <p>4) USO DE LOS DATOS RECOGIDOS</p>
                <p>&bull;&nbsp;Los datos que recogemos a trav&eacute;s de todos nuestros servicios se utilizan para prestar, mantener, personalizar, proteger y mejorar los servicios de Miligadepadel, desarrollar nuevos servicios y velar por la protecci&oacute;n de Miligadepadel y de nuestros usuarios.</p>
                <p>&bull;&nbsp;Todos los datos facilitados que no tienen car&aacute;cter personal, como por ejemplo, la descripci&oacute;n del bien, del animal, del servicio, etc., las caracter&iacute;sticas y los atributos del mismo, el precio, el nombre de contacto y el n&uacute;mero de tel&eacute;fono son, como es obvio por la naturaleza del servicio, p&uacute;blicos.</p>
                <p>&bull;&nbsp;Los t&eacute;rminos recogidos en la Pol&iacute;tica de Privacidad y, en concreto, el deber de confidencialidad son de obligado cumplimiento para todos los destinatarios de la informaci&oacute;n recogida, incluyendo el personal contratado por Miligadepadel y aquellos terceros, que en virtud de un contrato de prestaci&oacute;n de servicios, tengan acceso a datos de car&aacute;cter personal y a los equipos o sistemas de Miligadepadel.</p>
                <p>5) CESI&Oacute;N DE DATOS A TERCEROS</p>
                <p>&bull;&nbsp;Miligadepadel no comunica, vende, alquila o comparte los datos de car&aacute;cter personal de los usuarios con empresas, organizaciones o personas f&iacute;sicas ajenas a Miligadepadel.</p>
                <p>&bull;&nbsp;Dicho consentimiento no ser&aacute; necesario para la comunicaci&oacute;n de datos en los supuestos en los que la Ley los permite expresamente.</p>
                <p>&bull;&nbsp;Si Miligadepadel participa en una fusi&oacute;n, adquisici&oacute;n o venta de activos, nos aseguraremos de mantener la confidencialidad de los datos personales e informaremos a los usuarios afectados antes de que sus datos personales sean transferidos o pasen a estar sujetos a una pol&iacute;tica de privacidad diferente.</p>
                <p>6) UTILIZACI&Oacute;N DE LA INFORMACI&Oacute;N EN EL PORTAL</p>
                <p>&bull;&nbsp;Los usuarios jugadores de Miligadepadel solo pueden utilizar los datos de car&aacute;cter personal publicados en el Portal para tratar con usuarios administradores de ligas y viceversa, no para enviar informaci&oacute;n publicitaria no solicitada o correo basura ni recoger datos de car&aacute;cter personal de una persona que no lo haya autorizado.</p>
                <p>&bull;&nbsp;Miligadepadel puede revisar o filtrar autom&aacute;tica o manualmente los mensajes de los usuarios enviados a trav&eacute;s del Portal para controlar las actividades maliciosas o el contenido prohibido.</p>
                <p>7) ACCESO, RECTIFICACI&Oacute;N, CANCELACI&Oacute;N Y OPOSICI&Oacute;N</p>
                <p>&bull;&nbsp;El usuario puede acceder y/o rectificar la mayor&iacute;a de sus datos de car&aacute;cter personal accediendo a los mismos a trav&eacute;s del Portal. Para acceder o rectificar cualquier otro dato que no pueda consultarse a trav&eacute;s de la p&aacute;gina web, as&iacute; como ejercitar los derechos de cancelaci&oacute;n u oposici&oacute;n, el usuario puede comunicarse con info(arroba)miligadepadel.manuelbdv.es. Miligadepadel cancelar&aacute; los datos de car&aacute;cter personal cuando ya no se necesitan para los fines descritos anteriormente. La cancelaci&oacute;n da lugar al bloqueo de los datos, conserv&aacute;ndose &uacute;nicamente aqu&eacute;llos necesarios para la atenci&oacute;n de las posibles responsabilidades nacidas del tratamiento, durante el plazo de prescripci&oacute;n de &eacute;stas.</p>
                <p>8) SEGURIDAD</p>
                <p>&bull;&nbsp;Los servidores de Miligadepadel est&aacute;n ubicadas a trav&eacute;s de una empresa externa que aseguran la protecci&oacute;n de sus servidores y de los datos que los contienen.</p>
				<p>9) MODIFICACIONES</p>
                <p>&bull;&nbsp;La Pol&iacute;tica de Privacidad de Miligadepadel se podr&aacute; modificar en cualquier momento. No limitaremos los derechos que corresponden al usuario con arreglo a la presente Pol&iacute;tica de Privacidad sin tu expreso consentimiento. Publicaremos todas las modificaciones de la presente Pol&iacute;tica de Privacidad en esta p&aacute;gina y, si son significativas, efectuaremos una notificaci&oacute;n m&aacute;s destacada (por ejemplo, te enviaremos una notificaci&oacute;n por correo electr&oacute;nico si la modificaci&oacute;n afecta a determinados servicios).</p>  
	';
	return $texto;
}

function obtenLocalizacion($tipo,$id){//obtiene el nombre de pais, provincia o municipio
	$db = new MySQL('unicas');//UNICAS
	if($tipo == 1){//OBTENGO EL PAIS
		$consulta = $db->consulta("SELECT Name FROM paises WHERE Code = '$id'; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$retorno = $resultados['Name'];
	}
	else if($tipo == 2){//OBTENGO LA PROVINCIA
		$consulta = $db->consulta("SELECT provincia FROM provincias WHERE id = '$id'; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$retorno = utf8_encode($resultados['provincia']);
	}
	else{//OBTENGO LA CIUDAD
		$consulta = $db->consulta("SELECT municipio FROM municipios WHERE id = '$id'; ");
		$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
		$retorno = $resultados['municipio'];
	}
	return $retorno;
}

function letraNIF ($dni) {/* Obtiene letra del NIF a partir del DNI */
  $valor= (int) ($dni / 23);
  $valor *= 23;
  $valor= $dni - $valor;
  $letras= "TRWAGMYFPDXBNJZSQVHLCKEO";
  $letraNif= substr ($letras, $valor, 1);
  return $letraNif;
}

function formatoImagen($valor){//Funcion que sirve para limpiar contenido peligroso para inyección sql
	$retorno = false;
	$caracteres = array('.jpg', ".gif", ".png", ".bmp", ".jpeg");
	for($i=0; $i<count($caracteres); $i++){
		if(strrpos($valor, $caracteres[$i]) !== false){
			$retorno = true;
		}
	}
	return $retorno;
}

function detect()
{
	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
	$os=array("WIN","MAC","LINUX");
	
	# definimos unos valores por defecto para el navegador y el sistema operativo
	$info['browser'] = "OTHER";
	$info['os'] = "OTHER";
	
	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent)
	{
		$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
		$f = $s + strlen($parent);
		$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		if ($s)
		{
			$info['browser'] = $parent;
			$info['version'] = $version;
		}
	}
	
	# obtenemos el sistema operativo
	foreach($os as $val)
	{
		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
			$info['os'] = $val;
	}
	
	# devolvemos el array de valores
	return $info;
}

?>