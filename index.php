<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ligas de padel miligadepadel.manuelbdv.es</title>
<!--<script src="config/detect_tipo.js" type="text/javascript"></script>-->
</head>

<body>
<script type="text/javascript">
	function obten_tipo(navInfo) {
		//var navInfo = window.navigator.appVersion.toLowerCase();
		var tipo = 'desktop';
		_find = function(needle) {
			return navInfo.indexOf(needle) != -1;
		};
		if(_find('mac') ||  _find('iphone')){
			if(_find('mobile')){
				tipo = 'mobile';
			}
			else{
				tipo = 'desktop';
			}
		}
		else if(_find('ipod')){
			tipo = 'mobile';
		}
		else if(_find('ipad')){
			tipo = 'mobile';
		}
		else if(_find('blackberry')){
			tipo = 'mobile';
		}
		else if(_find('win')){
			if(_find('phone')){
				tipo = 'mobile';
			}
			else if(_find('touch')){
				tipo = 'tablet';
			}
			else{
				tipo = 'desktop';
			}
		}
		else if(_find('android')){
			if(_find('mobile')){
				tipo = 'mobile';
			}
			else{
				tipo = 'tablet';
			}
		}
		else if(_find('linux')){
			tipo = 'desktop';
		}
		else{
			tipo = 'desktop';
		}
		return tipo;
	}//fin funcion obten_tipo
	var navInfo = window.navigator.appVersion.toLowerCase();
	var tipo = obten_tipo(navInfo);
	var datos = "?a="+screen.width+"&b="+screen.height;
	//alert(navInfo+'-'+tipo);
	if(tipo == 'mobile'){
		window.location="http://miligadepadel.manuelbdv.es/mobile/index.php"+datos;
	}
	else if(tipo == 'tablet'){
		window.location="http://miligadepadel.manuelbdv.es/mobile/index.php"+datos;
		//window.location="http://miligadepadel.manuelbdv.es/tablet/index.php";
	}
	else{
		if(navInfo.indexOf('trident/') != -1){//no soportado
			window.location="http://miligadepadel.manuelbdv.es/web/nosoportado.php";
		}
		else{
			window.location="http://miligadepadel.manuelbdv.es/web/index.php";
		}
		
	}
</script>
</body>
</html>
