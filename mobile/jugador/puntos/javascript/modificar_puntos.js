// JavaScript Document
//$(document).ready(function(){
	function comprueba(id_liga,tipo_ranking){
		var cuadro = $('#cuadro'+id_liga);
		var linea = document.getElementById('linea'+id_liga);
		var cuadro_jug = document.getElementById('cuadro'+id_liga);
		if (cuadro.is(':visible')){
			linea.style.backgroundColor = '#FFFFFF';
			cuadro_jug.style.backgroundColor = '#FFFFFF';
			ocultar(cuadro);
			//alert('el div ahora esta mostrado');
		}
		else{
			if(tipo_ranking == 'M'){//ranking masculino
				linea.style.backgroundColor = '#EDEDFC';
				cuadro_jug.style.backgroundColor = '#F8F8FD';
			}
			else{//ranking femenino
				linea.style.backgroundColor = '#FCEDF2';
				cuadro_jug.style.backgroundColor = '#FDF9FA';
			}
			mostrar(cuadro);
			//alert('el div esta oculto');
		}
		
	}//fin comprueba
//});//FIN DEL READY









