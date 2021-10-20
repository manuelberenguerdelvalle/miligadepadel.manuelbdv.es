// JavaScript Document
function localizacion() {
	// Parametros para el pais IMPORTANTE ANTES ESTABA .change
	$("#pais").click(function () {
   		$("#pais option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("../../desplegables/provincias.php", { elegido: elegido }, function(data){
				$("#provincia").html(data);
				$("#ciudad").html("");
			});			
        });
   })
	// Parametros para la provincia
	$("#provincia").click(function () {
   		$("#provincia option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("../../desplegables/ciudades.php", { elegido: elegido }, function(data){
				$("#ciudad").html(data);
			});			
        });
   })
}
