// JavaScript Document
var navInfo = window.navigator.appVersion.toLowerCase();
var tipo = 'desktop';
_find = function(needle) {
    return navInfo.indexOf(needle) != -1;
};
if(_find('win')){
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
else if(_find('linux')){
	tipo = 'desktop';
}
else if(_find('mac')){
	tipo = 'desktop';
}
else if(_find('iphone') || _find('ipod')){
	tipo = 'mobile';
}
else if(_find('ipad')){
	tipo = 'tablet';
}
else if(_find('android')){
	if(_find('mobile')){
		tipo = 'mobile';
	}
	else{
		tipo = 'tablet';
	}
}
else if(_find('blackberry')){
	tipo = 'mobile';
}
else{
	tipo = 'desktop';
}
if(tipo == 'desktop'){
		document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-25);
		document.write('px !important;height:');
		document.write(screen.height-145);
		document.write('px !important;font-size:20px;}</style>');	
}