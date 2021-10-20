// JavaScript Document
function localizacion2() {
	// Parametros para el pais IMPORTANTE ANTES ESTABA .change
	$("#pais2").change(function () {
		//alert('hola');
   		$("#pais2 option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				//elegido2=$(this).val();
				//$.post("../../desplegables/provincias2.php", { elegido2: elegido2 }, function(data){
				$.post("../../desplegables/provincias.php", { elegido: elegido }, function(data){
				$("#provincia2").html(data);
				$("#ciudad2").html("");
			});		
        });
   })
	// Parametros para la provincia
	$("#provincia2").change(function () {
   		$("#provincia2 option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("../../desplegables/ciudades.php", { elegido: elegido }, function(data){
				//$.post("../../desplegables/ciudades2.php", { elegido: elegido }, function(data){
				$("#ciudad2").html(data);
			});			
        });
   })
}
