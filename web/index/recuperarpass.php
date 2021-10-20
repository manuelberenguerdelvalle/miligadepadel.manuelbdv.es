<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administracion de Ligas de Padel miligadepadel.manuelbdv.es</title>
<script src="../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script language="javascript">
/*function abrir_bpopup(canal){
	if(canal == 'premium'){
		var cod_html = '<div class="poptitulo"><h2>Canal Ligas Premium</h2></div><div class="popcentro"><iframe id="video" class="video"  src="https://www.youtube.com/embed/bDFiwI53UM4" frameborder="0" allowfullscreen></iframe><div class="partes"><a class="link_canal" href="#" onClick="cambia_video(11);">1 - Configuración inicial</a><br><a class="link_canal" href="#" onClick="cambia_video(12);">2 - Confirmación e Inscripciones</a></div></div><div class="poppie"></div>';
		document.getElementById('content_popup').innerHTML = cod_html;
	}
	else{
		var cod_html = '<div class="poptitulo"><h2>Canal Ligas Gratis</h2></div><div class="popcentro"><iframe id="video" class="video"  src="https://www.youtube.com/embed/vFQN9dCl5Hw" frameborder="0" allowfullscreen></iframe><div class="partes"><a class="link_canal" href="#" onClick="cambia_video(21);">1 - Configuración inicial</a><br><a class="link_canal" href="#" onClick="cambia_video(22);">2 - Confirmación e Inscripciones</a></div></div><div class="poppie"></div>';
		document.getElementById('content_popup').innerHTML = cod_html;
	}
	$('#content_popup').bPopup('');
}
function cambia_video(parte){
	//PARA LAS PREMIUM
	if(parte == 11){
		$('#video').attr("src", "https://www.youtube.com/embed/bDFiwI53UM4");
	}
	else if(parte == 12){
		$('#video').attr("src", "https://www.youtube.com/embed/MNbfnHAjMks");
	}
	//PARA LAS GRATIS
	else if(parte == 21){
		$('#video').attr("src", "https://www.youtube.com/embed/vFQN9dCl5Hw");
	}
	else if(parte == 22){
		$('#video').attr("src", "https://www.youtube.com/embed/s6v-bUY_wS8");
	}
	else{
	}
}*/
</script>
<link rel="stylesheet" type="text/css" href="../css/bpopup.css" />
<style>
.input_text_liga {
	width:95%;
	height:95% !important;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:5px;
	font-weight:bold;
	font-style:italic;
	border:1px #8989FE solid;
}
</style>
</head>

<body>

<div id="content_popup">
				<div class="poptitulo"><h2>Recuperar tu contraseña</h2></div>
				<div class="popcentro">
					<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
                    <label class="caja_texto">&nbsp;</label><label class="caja_input">Introduzca su e-mail de registro</label><label id="errorContacto" class="caja_error">&nbsp;</label>
					<label class="caja_texto">Tu email:</label><label class="caja_input"><input name="contacto"  type="text" class="input_text_liga" ></label><label id="errorContacto" class="caja_error">*</label>
					</form>
				</div>
				<div class="poppie">
					<span class="button b-close"><span><a class="env" href="#"  onclick="enviar();">ENVIAR</a></span></span>
				</div>
</div>
<a href="#" onClick="$('#content_popup').bPopup('')">&iquest;Has olvidado tu contrase&ntilde;a?</a>
</body>
</html>