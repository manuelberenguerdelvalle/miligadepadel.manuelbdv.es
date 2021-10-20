// JavaScript Document
formulario = new formularioLiga("error","error","error","null","null");
	function enviar(){
		if(formulario.obtenTotal()){
			var dataString = $("#formulario_contacto").serialize();
			//var url = "../../usuario/contacto/enviar_email.php";
			var url = "../../contacto/c/enviar_email.php";
			//alert(dataString);
			$.ajax({
						   type: "POST",
						   url: url,
						   data: dataString, // Adjuntar los campos del formulario enviado.
						   success: function(data)
						   {   
								swal({   
									title: "Correo electronico enviado correctamente",   
									text: "En breve revisaremos su consulta y contactaremos con usted, gracias.",   
									type: "success",   
									showCancelButton: false,   
									confirmButtonColor: "#69fb5a",   
									confirmButtonText: "OK",   
									closeOnConfirm: false }, 
									function(){   
										setTimeout ("window.location.reload();", 500);
								});
						   }
			});
		}//fin if
	}//fin function