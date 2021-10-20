// JavaScript Document
	/*if (screen.width > 1366){
		
		document.write('<style type="text/css">.principal{width:1366px !important;height:650px !important;font-size:18px;margin-left:');
		document.write((screen.width-1366)/2);
		document.write('px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1366/resolucion1366x768.css" />');
	}*/
	//alert(screen.width+'-'+screen.height);
	var tam_texto = 0;
	var dif = screen.width-screen.height;
	if (screen.width >= 1920){tam_texto = 19;}
	else if (screen.width < 1920 && screen.width >= 1440){tam_texto = 18;}
	else if (screen.width < 1440 && screen.width >= 1366){tam_texto = 16;}
	else if (screen.width < 1366 && screen.width >= 1280){tam_texto = 14;}
	else if (screen.width < 1280 && screen.width >= 1024){tam_texto = 12;}
	else{tam_texto = 10;}
	if(dif > 450){//16:9
		//alert('entra 16:9');
		document.write('<style type="text/css">.principal{width:')
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:');
		document.write(tam_texto);
		document.write('px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion_169.css" />');
	}
	else{//4.3
		//alert('entra 4:3');
		/*if (screen.width >= 1920){tam_texto = 20;}
		else if (screen.width < 1920 && screen.width >= 1440){tam_texto = 19;}
		else if (screen.width < 1440 && screen.width >= 1366){tam_texto = 17;}
		else if (screen.width < 1366 && screen.width >= 1280){tam_texto = 16;}
		else if (screen.width < 1280 && screen.width >= 1024){tam_texto = 13;}
		else{tam_texto = 10;}*/
		document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:');
		document.write(tam_texto);
		document.write('px;}</style>');
		if (screen.width >= 1152){
			document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion_43.css" />');
		}
		else{
			document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion_43_2.css" />');
		}
	}
	