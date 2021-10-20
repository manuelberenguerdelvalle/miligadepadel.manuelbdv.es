// JavaScript Document
	document.write('<style type="text/css">.principal{width:');
	document.write(screen.width-1);
	document.write('px !important;height:');
	document.write(screen.height-137);
	if (screen.width >= 1366){
		document.write('px !important;font-size:18px;}</style>');
	}
	else if (screen.width < 1366 && screen.width >= 1280){
		document.write('px !important;font-size:16px;}</style>');
	}
	else if (screen.width < 1280 && screen.width >= 1024){
		document.write('px !important;font-size:14px;}</style>');
	}
	else{
		document.write('px !important;font-size:12px;}</style>');
	}