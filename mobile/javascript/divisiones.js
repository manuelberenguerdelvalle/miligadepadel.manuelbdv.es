// JavaScript Document
$(document).ready(function(){
	// Parametros para e pais
   $("#liga").change(function () {
   		$("#liga option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("../desplegables/divisiones.php", { elegido: elegido }, function(data){
				$("#division").html(data);
			});			
        });
   })
});