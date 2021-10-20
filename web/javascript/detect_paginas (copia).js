// JavaScript Document
	/*if (screen.width > 1366){
		
		document.write('<style type="text/css">.principal{width:1366px !important;height:650px !important;font-size:18px;margin-left:');
		document.write((screen.width-1366)/2);
		document.write('px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1366/resolucion1366x768.css" />');
	}*/
	alert(screen.width);
	if (screen.width >= 1920){
		/*document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:18px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1366/resolucion1366x768.css" />');*/
		document.write('<style type="text/css">.principal{width:1920px !important;height:1080px !important;font-size:20px;margin-left:');
		document.write((screen.width-1920)/2);
		document.write('px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1920/resolucion1920x1080.css" />');
	}
	
	else if (screen.width < 1920  && screen.width >= 1440){
		document.write('<style type="text/css">.principal{width:1365px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:17px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1366/resolucion1366x768.css" />');
	}
	else if (screen.width < 1440  && screen.width >= 1366){
		/*document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:18px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1366/resolucion1366x768.css" />');*/
		document.write('<style type="text/css">.principal{width:1365px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:17px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1366/resolucion1366x768.css" />');
	}
	else if (screen.width < 1366 && screen.width >= 1280){
		document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-120);
		document.write('px !important;font-size:16px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1280/resolucion1280x768.css" />');
	}
	else if (screen.width < 1280 && screen.width >= 1024){
		document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-110);
		document.write('px !important;font-size:13px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1024/resolucion1024x768.css" />');
	}
	else{
		document.write('<style type="text/css">.principal{width:');
		document.write(screen.width-1);
		document.write('px !important;height:');
		document.write(screen.height-110);
		document.write('px !important;font-size:10px;}</style>');
		document.write('<link rel="stylesheet" type="text/css" href="../../css/resolucion1024/resolucion1024x768.css" />');
	}