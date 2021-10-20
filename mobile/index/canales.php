<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administracion de Ligas de Padel miligadepadel.manuelbdv.es</title>
<script src="../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script language="javascript">
function abrir_bpopup(canal){
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
}
</script>
<link rel="stylesheet" type="text/css" href="../css/bpopup.css" />
<style>
.canal {	
	width:99% !important;
	margin-top:15%;
	/*border:1px black solid;*/
	float:left;
}
.link_canal {
	text-decoration:none;
	color: #181C83;
	/*color:#e52d27;*/
}
.link_canal:hover {
	text-decoration: underline;
	/*color: #121562;*/
	/*color:#e52d27;*/
}
.video {
	width:60% !important;
	height:91% !important;
	margin-left:20%;
	margin-top:1%;
	-webkit-box-shadow: 0px 0px 5px 3px rgba(0,0,0,1);
	-moz-box-shadow:    0px 0px 5px 3px rgba(0,0,0,1);
	box-shadow:         0px 0px 5px 3px rgba(137,137,254,1);
	float:left;
}
.partes {
	width:18% !important;
	height:85% !important;
	margin-top:2%;
	/*border:1px black solid;*/
	float:right;
}
</style>
</head>

<body>

<div class="canal"><a class="link_canal" href="#" target="_parent" onClick="abrir_bpopup('premium');">-Ver Canal Ligas Premium</a></div>
<div class="canal"><a class="link_canal" href="#" target="_parent" onClick="abrir_bpopup('gratis');">-Ver Canal Ligas Gratis</a></div>

<div id="content_popup">

</div>

</body>
</html>