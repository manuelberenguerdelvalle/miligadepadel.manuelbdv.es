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
		var cod_html = '<div class="poptitulo"><h2>Canal Ligas Premium</h2></div><div class="popcentro"><iframe id="video" class="video"  src="https://www.youtube.com/embed/l6GCyR5foOI" frameborder="0" allowfullscreen></iframe><div class="partes"><a class="link_canal" href="#" onClick="cambia_video(1);">1 - Registro</a><br><a class="link_canal" href="#" onClick="cambia_video(2);">2 - Pagos</a><br><a class="link_canal" href="#" onClick="cambia_video(3);">3 - Configuración inicial</a><br><a class="link_canal" href="#" onClick="cambia_video(4);">4 - Configurar inscripciones con pago online</a><br><a class="link_canal" href="#" onClick="cambia_video(5);">5 - Fechas de inscripción</a><br><a class="link_canal" href="#" onClick="cambia_video(6);">6 - Inscripción pago presencial</a><br><a class="link_canal" href="#" onClick="cambia_video(7);">7 - Inscripción pago online</a><br><a class="link_canal" href="#" onClick="cambia_video(8);">8 - Crear publicidad</a><br><a class="link_canal" href="#" onClick="cambia_video(9);">9 - Crear calendario y partidos</a><br><a class="link_canal" href="#" onClick="cambia_video(10);">10 - Información de la liga</a><br><a class="link_canal" href="#" onClick="cambia_video(11);">11 - Autocompletado</a><br><a class="link_canal" href="#" onClick="cambia_video(12);">12 - Privacidad</a><br><a class="link_canal" href="#" onClick="cambia_video(13);">13 - Crear noticia</a><br><a class="link_canal" href="#" onClick="cambia_video(14);">14 - Tickets</a><br><a class="link_canal" href="#" onClick="cambia_video(15);">15 - Nueva temporada parte1</a><br><a class="link_canal" href="#" onClick="cambia_video(16);">16 - Nueva temporada parte2</a></div></div><div class="poppie"></div>';
		document.getElementById('content_popup').innerHTML = cod_html;
	}
	else{
		var cod_html = '<div class="poptitulo"><h2>Canal Ligas Gratis</h2></div><div class="popcentro"><iframe id="video" class="video"  src="https://www.youtube.com/embed/Zu-LHBQOAXk" frameborder="0" allowfullscreen></iframe><div class="partes"><a class="link_canal" href="#" onClick="cambia_video(21);">1 - Registro</a><br><a class="link_canal" href="#" onClick="cambia_video(22);">2 - Configuración inicial</a><br><a class="link_canal" href="#" onClick="cambia_video(23);">3 - Fecha de inscripción</a><br><a class="link_canal" href="#" onClick="cambia_video(24);">4 - Inscripciones</a><br><a class="link_canal" href="#" onClick="cambia_video(25);">5 - Crear calendario y partidos</a><br><a class="link_canal" href="#" onClick="cambia_video(26);">6 - Información de la liga</a><br><a class="link_canal" href="#" onClick="cambia_video(27);">7 - Autocompletado</a><br><a class="link_canal" href="#" onClick="cambia_video(28);">8 - Crear noticia</a><br><a class="link_canal" href="#" onClick="cambia_video(29);">9 - Tickets</a></div></div><div class="poppie"></div>';
		document.getElementById('content_popup').innerHTML = cod_html;
	}
	$('#content_popup').bPopup('');
}
function cambia_video(parte){
	//PARA LAS PREMIUM
	if(parte == 1){
		$('#video').attr("src", "https://www.youtube.com/embed/l6GCyR5foOI");
	}
	else if(parte == 2){
		$('#video').attr("src", "https://www.youtube.com/embed/pgJpBnnzSSc");
	}
	else if(parte == 3){
		$('#video').attr("src", "https://www.youtube.com/embed/S8onp8s5oa8");
	}
	else if(parte == 4){
		$('#video').attr("src", "https://www.youtube.com/embed/_azUunwl5_8");
	}
	else if(parte == 5){
		$('#video').attr("src", "https://www.youtube.com/embed/vHmtBJzLGQM");
	}
	else if(parte == 6){
		$('#video').attr("src", "https://www.youtube.com/embed/tzNw5pO4ldY");
	}
	else if(parte == 7){
		$('#video').attr("src", "https://www.youtube.com/embed/7Ayn9ocPfJc");
	}
	else if(parte == 8){
		$('#video').attr("src", "https://www.youtube.com/embed/H7mnI3yStII");
	}
	else if(parte == 9){
		$('#video').attr("src", "https://www.youtube.com/embed/khdOgYyupTE");
	}
	else if(parte == 10){
		$('#video').attr("src", "https://www.youtube.com/embed/OGlxsjh0RWc");
	}
	else if(parte == 11){
		$('#video').attr("src", "https://www.youtube.com/embed/BRSM227RY5A");
	}
	else if(parte == 12){
		$('#video').attr("src", "https://www.youtube.com/embed/wXmcZZSz7Pc");
	}
	else if(parte == 13){
		$('#video').attr("src", "https://www.youtube.com/embed/FgrncvrauFU");
	}
	else if(parte == 14){
		$('#video').attr("src", "https://www.youtube.com/embed/s1-7kkgfKj4");
	}
	else if(parte == 15){
		$('#video').attr("src", "https://www.youtube.com/embed/Npv-SAspU-s");
	}
	else if(parte == 16){
		$('#video').attr("src", "https://www.youtube.com/embed/VvAvk_GMGh4");
	}
	//PARA LAS GRATIS
	else if(parte == 21){
		$('#video').attr("src", "https://www.youtube.com/embed/Zu-LHBQOAXk");
	}
	else if(parte == 22){
		$('#video').attr("src", "https://www.youtube.com/embed/TpOqUtHOrKw");
	}
	else if(parte == 23){
		$('#video').attr("src", "https://www.youtube.com/embed/ls_cHvcO49g");
	}
	else if(parte == 24){
		$('#video').attr("src", "https://www.youtube.com/embed/rJA8mCMIaPU");
	}
	else if(parte == 25){
		$('#video').attr("src", "https://www.youtube.com/embed/MuKQyx0chpU");
	}
	else if(parte == 26){
		$('#video').attr("src", "https://www.youtube.com/embed/CCPmjE-v1X8");
	}
	else if(parte == 27){
		$('#video').attr("src", "https://www.youtube.com/embed/OeLZ2zlJTxA");
	}
	else if(parte == 28){
		$('#video').attr("src", "https://www.youtube.com/embed/07ie_shJodc");
	}
	else if(parte == 29){
		$('#video').attr("src", "https://www.youtube.com/embed/eQO-M81Qpi4");
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