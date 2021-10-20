
function localizacion_ligas() {
	// Parametros para el pais IMPORTANTE ANTES ESTABA .change
	/*$("#pais").click(function () {
   		$("#pais option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("desplegables/provincias_ligas.php", { elegido: elegido }, function(data){
				$("#provincia").html(data);
				$("#ciudad").html("");
				$("#liga").html("");
				$("#division").html("");
			});			
        });
   })*/
	// Parametros para la ciudad .click
	$("#provincia").change(function () {
   		$("#provincia option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("desplegables/ciudades_ligas.php", { elegido: elegido }, function(data){
				$("#ciudad").html(data);
				$("#liga").html("");
				$("#division").html("");
				$("#mostrar_resultados").html("");
			});			
        });
   })
   // Parametros para la liga
   $("#ciudad").change(function () {
   		$("#ciudad option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("desplegables/ligas_encontradas.php", { elegido: elegido }, function(data){
				$("#liga").html(data);
				$("#division").html("");
				$("#mostrar_resultados").html("");
			});			
        });
   })
   // Parametros para la division
   $("#liga").change(function () {
   		$("#liga option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("desplegables/divisiones_encontradas.php", { elegido: elegido }, function(data){
				$("#division").html(data);
				$("#mostrar_resultados").html("");
			});			
        });
   })
   // Parametros para mostrar resultados
   $("#division").change(function () {
   		$("#division option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("desplegables/mostrar_resultados.php", { elegido: elegido }, function(data){
				$("#mostrar_resultados").html(data);
			});			
        });
   })
}