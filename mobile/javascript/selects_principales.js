// JavaScript Document
function ligasydivisiones(contenido) {//funcion para los selects principales de la pagina
	// Parametros para e combo1
	$("#ligas").change(function () {
   		$("#ligas option:selected").each(function () {
			//alert($(this).val());
				id_liga=$(this).val();
				$.post("../../desplegables/divisiones.php", { id_liga: id_liga }, function(data){
				$("#divisiones").html(data);
				cargar("#menuIzq","../menu_izq.php");
				cargar(".contenido",contenido);
				//$(".contenido").html("");
			});			
        });
   })
   // Parametros para cambio de division
	$("#divisiones").change(function () {
   		$("#divisiones option:selected").each(function () {
			//alert($(this).val());
				id_division=$(this).val();
				$.post("../../desplegables/divisiones.php", { id_division: id_division }, function(data){
				cargar("#menuIzq","../menu_izq.php");
				cargar(".contenido",contenido);
				//$(".contenido").html('cambio de division');
			});			
        });
   })
}

function cargar(div, desde){//funcion para cargar los div del contenido
     $(div).load(desde);
}