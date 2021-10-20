<?php
function crea_cabecera(){
	$t = '
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<title>miligadepadel.manuelbdv.es</title>
	<STYLE>
		a {text-decoration: none;} 
		a:hover {text-decoration: underline;}
	</STYLE>
	</head>
	<body style="padding: 0px;margin: 0 auto;font-family: Georgia, "Times New Roman", Times, serif;font-size:25px;">
		<div style="width:700px;height:100px;background-color: #8989FE;border-top:1px #8989FE solid;border-left:1px #8989FE solid;border-right:1px #8989FE solid;">
			<div style="margin-left:5%;font-weight:bold;letter-spacing:2px;color: #ffffff;text-shadow: 0px 2px 3px #555;font-size:30px;"><br><span>&nbsp;&nbsp;&nbsp;miligadepadel.manuelbdv.es</div>
		</div>
	';
	return $t;
}

function crea_pie(){
	$t = '
		<div style="width:700px;background-color:#EEEEEE;border:1px #8989FE solid;">
			<div style="font-size:10px;">Advertencia legal: La información contenida en este email y, en su caso los ficheros anexos son confidenciales; y se dirige únicamente al destinatario. Si Usted no es el destinatario, lo ha recibido por error o tiene conocimiento del mismo por cualquier motivo, le rogamos nos lo comunique de forma inmediata y proceda a destruirlo o borrarlo, y en todo caso se abstenga de utilizar, reproducir, alterar, archivar o comunicar a terceros el presente email y ficheros anexos. miligadepadel.manuelbdv.es le informa de que todos los datos recibidos a través de formularios electrónicos y/o mediante correo electrónico serán incluidos en ficheros de la entidad, y tratados con estricta confidencialidad de acuerdo con la Política de Privacidad y de Seguridad de la Empresa, así como con la Ley Orgánica 15/1999, de 13 de diciembre, de Protección de Datos de carácter personal. La finalidad de su creación, existencia y mantenimiento es el tratamiento de los datos de carácter personal con el fin de dar cumplimiento a los objetivos de la entidad. Para ejercitar los derechos de oposición, acceso, rectificación y cancelación, puede contactar con nosotros en la dirección de correo electrónico: info@miligadepadel.manuelbdv.es</div>
		</div>
	</body>
	</html>
	';
	return $t;
}

function email_registro($link,$usuario,$password){
	$t = crea_cabecera();
	$t .= '
		<div style="width:700px;border-top:1px #8989FE solid;border-left:1px #8989FE solid;border-right:1px #8989FE solid;">
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-weight:bold; font-size:25px;"><br>&iexcl; Bienvenido a miligadepadel !</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:22px;">Ya solo te queda un paso, verifica tu registro haciendo click <a style="font-size:23px;" href="'.$link.'" target="_blank"><b>aquí.</b></a><br>&nbsp;</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:22px;border-top:2px #00CC33 dotted;"><br>Tus datos de acceso son:</div>
			<div style="margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:22px;"><span style="color: #181C83;">Usuario:&nbsp;</span><span style="color: #093;">'.$usuario.'</span></div>
			<div style="margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:22px;border-bottom:2px #00CC33 dotted;"><span style="color: #181C83;">Contrase&ntilde;a:&nbsp;</span><span style="color: #093;">'.$password.'</span><br>&nbsp;</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:20px;"><br>Por favor, conserva este mensaje en un lugar seguro para consultarlo en el futuro.</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:20px;">&nbsp;</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:20px;">Si tiene cualquier pregunta, comentario o sugerencia siempre puede contactar con nosotros a través de info@miligadepadel.manuelbdv.es<br>&nbsp;</div>
		</div>
	';
	$t .= crea_pie();
	return utf8_decode($t);
}

function email_conf_registro(){
	$t = crea_cabecera();
	$t .= '
		<div style="width:700px;border-top:1px #8989FE solid;border-left:1px #8989FE solid;border-right:1px #8989FE solid;">
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-weight:bold; font-size:25px;"><br>&iexcl; Registro Completado !</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:22px;">A que esperas para configurar tu Liga de Padel, accece con tu usuario y contraseña haciendo click en el siguiente enlace <a style="font-size:23px;" href="http://miligadepadel.manuelbdv.es" target="_blank"><b>aquí.</b></a><br>&nbsp;</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:20px;">&nbsp;</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:20px;">Si tiene cualquier pregunta, comentario o sugerencia siempre puede contactar con nosotros a través de info@miligadepadel.manuelbdv.es<br>&nbsp;</div>
		</div>
	';
	$t .= crea_pie();
	return utf8_decode($t);
}

function email_jugadorAdmin($cabecera,$mensaje){
	$t = crea_cabecera();
	$t .= '
		<div style="width:700px;border-top:1px #8989FE solid;border-left:1px #8989FE solid;border-right:1px #8989FE solid;">
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-weight:bold; font-size:25px;"><br>'.$cabecera.'</div>
			<div style="color: #181C83;margin-left:7%;margin-right:7%;margin-top:3%;padding-left: 5%;font-size:22px;"><br>'.$mensaje.'<br>&nbsp;<br>&nbsp;</div>
		</div>
	';
	$t .= crea_pie();
	return utf8_decode($t);
}
?>