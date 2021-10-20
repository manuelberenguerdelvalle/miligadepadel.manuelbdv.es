<?php
include_once ("../../funciones/f_conexion.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
session_start();
if(isset($_SESSION['jugador'])){ 
	$jugador = unserialize($_SESSION['jugador']);
	$dni = $jugador->getValor("dni");
	if($dni == 0){$dni = '';}
	$nombre = $jugador->getValor("nombre");
	$apellidos = $jugador->getValor("apellidos");
	$password = $jugador->getValor("password");
	$direccion = $jugador->getValor("direccion");
	$fec_nac = $jugador->getValor("fec_nac");
	$zona_juego = $jugador->getValor("zona_juego");
	$anyo = substr($fec_nac,0,4);
	$mes = substr($fec_nac,5,2);
	$dia = substr($fec_nac,8,2);
	$ciudad = $jugador->getValor("ciudad");
	$provincia = $jugador->getValor("provincia");
	$pais = $jugador->getValor("pais");
	$telefono = $jugador->getValor("telefono");
	if($telefono == 0){$telefono = '';}
	$email = $jugador->getValor("email");
	$genero = $jugador->getValor("genero");
	if($genero == 'M'){$genero = 'Masculino';}
	else{$genero = 'Femenino';}
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ob_clean();
clearstatcache();
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_datos.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<!--<link rel="stylesheet" type="text/css" href="../../css/hover.css" />-->
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script>
//se utiliza 14 de 15, el 15 = 'null'
formulario = new formularioGeneralQuince('null','null','null','null','null','null','null','null','null','null','null','null','null','null','null');
</script>
<script src="javascript/modificar_datos.js" type="text/javascript"></script>
<script src="../../../jcrop/js/jquery.Jcrop.js"></script>
<script type="text/javascript">
	/*
	$('#crop-img').Jcrop({
		aspectRatio: 1,
		boxWidth: anchoCaja,
		boxHeight: altoCaja,
		trueSize: [anchoReal, altoReal],
		onSelect: function(coords)
		{
			console.log(coords)
		}
	});
	*/
	  $(function(){
	
		$('#cropbox').Jcrop({
		  aspectRatio: 1,
		  onSelect: updateCoords
		});
	
	  });
	
	  function updateCoords(c){
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	  }
	
	function checkCoords(){
		if (parseInt($('#w').val())) return true;
		alert('Por favor seleccione la parte de la imagen que desea guardar.');
		return false;
	}
	
	$(function(){
		 $("#btn_enviar_foto").click(function(){ 
			 if(checkCoords()){
				var url = "crop.php"; // El script a dnde se realizar la peticin.
				$.ajax({
					   type: "POST",
					   url: url,
					   data: $("#form_foto").serialize(), // Adjuntar los campos del formulario enviado.
					   success: function(data)
					   {
						   if(data != ''){
								window.location.reload();
							}
							else{
								$(".selec_foto").html(''); 
								$(".selec_foto").html('Ha habido un error por favor, vuelva a intentarlo.');
								setTimeout ("window.location.reload();", 2500);
							}
					   }
				});
			 }//fin if formulario
			return false; // Evitar ejecutar el submit del formulario.
		 });
	});//fin enviar_foto
	function enviar_seleccionada(){ 
			var file = $("#imagen1")[0].files[0];//obtenemos un array con los datos del archivo
			var fileName = file.name;//obtenemos el nombre del archivo
			var fileExtension = fileName.substring(fileName.lastIndexOf('.'));//obtenemos la extensin del archivo
			//var fileSize = file.size;//obtenemos el tamao del archivo
			 //var fileType = file.type;//obtenemos el tipo de archivo image/png ejemplo
			//if(extensionImagen(fileExtension) && file.size <= 5000000){//formato valido y tamao menos a 5Mb
			if(extensionImagen(fileExtension)){//formato valido y tamao menos a 5Mb
				//alert(file.size);
				$(".selec_foto").html(''); 
				//$(".selec_foto").html('Cargando, Por favor espere...'); // Mostrar la respuestas del script PHP.
				$(".selec_foto").html('<div style="float:left;">Cargando, Por favor espere...</div><div style="margin-left:2%;float:left;"><img src="../../../images/28.gif" width="100" /></div>');
					var formData=new FormData($('#form_selec')[0]);
					$.ajax({
						url:'foto_grande.php',
						type:'POST',
						data:formData,
						cache:false,
						contentType:false,
						processData:false,
						success: function(data){
							if(data != ''){
								window.location.reload();
							}
							else{
								$(".selec_foto").html(''); 
								$(".selec_foto").html('Ha habido un error por favor, vuelva a intentarlo.');
								setTimeout ("window.location.reload();", 2500);
							}
						},
						error:function(){
							$(".selec_foto").html(''); 
							$(".selec_foto").html('Ha habido un error por favor, vuelva a intentarlo.');
							setTimeout ("window.location.reload();", 2500);
						}
					});
			}//fin if extension y tamao
			else{
				$(".selec_foto").html(''); 
				$(".selec_foto").html('La imagen es demasiado pesada, seleccione otra mas ligera.');
			}
	}//fin enviar seleccionada
</script>

</head>
<body>
<div class="columna1">
    <div class="cuadroTexto">Nombre:</div>
    <div class="cuadroTexto">Apellidos:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio. Introduce tu email habitual, lo necesitar&aacute;s para el acceso y gesti&oacute;n del men&uacute;.');" onMouseOut="hiddenDiv()" style="display:table;">Email:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional. Es importante indicar un m&oacute;vil v&aacute;lido.');" onMouseOut="hiddenDiv()" style='display:table;'>Tel&eacute;fono:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Introduce la contrasea actual.');" onMouseOut="hiddenDiv()" style="display:table;">Pass Actual:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Introduce la nueva contrasea');" onMouseOut="hiddenDiv()" style="display:table;">Pass Nueva:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si desea cambiar su contrase&ntilde;a, Repite la nueva contrasea');" onMouseOut="hiddenDiv()" style="display:table;">Repetir:</div>
	<div class="cuadroTexto">Nif:</div>
	<div class="cuadroTexto">Nacimiento:</div>
    <div class="cuadroTexto">Zona Juego:</div>
    <div class="cuadroTexto">Direcci&oacute;n:</div>
    <div class="cuadroTexto">G&eacute;nero:</div>
    <div class="cuadroTexto">Pa&iacute;s:</div>
    <div class="cuadroTexto">Provincia:</div>
    <div class="cuadroTexto">Ciudad:</div>
</div>
<div id="flotante"></div>
<!-- COMPROBAR CAMPOS DESHABILITADOS, CUENTA PAYPAL, TELEFONO, DNI....
BLOQUEAR TODOS MENOS CONTRASEA EN EL CASO DE TENER AL MENOS UNA LIGA DE PAGO
-->
<div class="columna2">
	<span><form id="form" action="actualiza_cuenta.php" method="post" name="formulario"></span>
	<span class="cuadroInputs"><input type="text" value="<?php echo ucfirst($nombre); ?>" class="input_text_liga_disabled" disabled></span>
    <span class="cuadroInputs"><input type="text" value="<?php echo ucfirst($apellidos); ?>" class="input_text_liga_disabled" disabled></span>
<?php 
if($hay_liga_pago > 0){//si tiene ligas de pago deshabilito  
?>
    <span class="cuadroInputs"><input type="text" name="email" id="email" class="input_text_liga_disabled" value="<?php echo $email; ?>" disabled ></span>
    <span class="cuadroInputs"><input type="text" name="telefono" id="telefono" class="input_text_liga_disabled" value="<?php echo $telefono; ?>" disabled ></span>
<?php 
}//fin if
else{//normal 
?>
	<span class="cuadroInputs"><input type="text" name="email" id="email" class="input_text_liga" value="<?php echo $email; ?>" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('email',2)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="telefono" id="telefono" class="input_text_liga" value="<?php echo $telefono; ?>" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('telefono',3,1)" maxlength="11" ></span>
<?php 
}//fin else 
?>
    <span class="cuadroInputs"><input type="password" name="antpassword" id="antpassword" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('antpassword',4)" maxlength="15" ></span>
    <span class="cuadroInputs"><input type="password" name="password" id="password" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('password',5)" maxlength="15" ></span>
    <span class="cuadroInputs"><input type="password" name="repassword" id="repassword" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="compara('password','repassword',6)" maxlength="15" ></span>
<?php
if($dni == ''){//si no tiene dni habilitado
	echo '<span class="cuadroInputs"><input type="text" name="dni" id="dni" class="input_text_liga" onkeypress="return tecla_dni(event)" onblur="limpiadni('."'dni'".',7)"  maxlength="9"></span>';
}
else{//si tiene dni deshabilito
	echo '<span class="cuadroInputs"><input type="text" value="'.$dni.'" class="input_text_liga_disabled" disabled></span>';
}
?> 
	<span class="cuadroInputs"><?php dia($dia,'dia','inputText'); mes($mes,'mes','inputText'); anyo($anyo,'anyo','inputText');?></span>
    <span class="cuadroInputs"><?php zona_juego($zona_juego,'zona_juego','input_text_liga');?></span>
	<span class="cuadroInputs"><input type="text" name="direccion" id="direccion" class="input_text_liga" value="<?php echo ucfirst($direccion); ?>" onKeyPress="return tecla_direccion(event)" onBlur="limpiaDireccion('direccion',9,0)" maxlength="50" ></span>
    <span class="cuadroInputs"><input type="text" name="genero" id="genero" class="input_text_liga_disabled" value="<?php echo $genero; ?>" disabled ></span>
    <span class="cuadroInputs">
    	<!--<select name="pais" id="pais" class="input_select_liga" onchange="lista('pais',2)">
        <option value="">--Elige--</option>
      	<option selected value="<?php echo $pais; ?>"><?php echo obtenLocalizacion(1,$pais); ?></option>
     	</select> -->
    	<select name="pais" id="pais" class="input_text_liga" onChange="lista('pais',2)">
        <option value="">--Elige--</option>
      	<option selected value="<?php echo $pais; ?>"><?php echo obtenLocalizacion(1,$pais); ?></option>
     </select>
     </span>
     <span class="cuadroInputs"><select name="provincia" id="provincia" class="input_text_liga" onChange="lista('provincia',3)">
     	<option value="<?php echo $provincia; ?>"><?php echo obtenLocalizacion(2,$provincia); ?></option>
     	</select>
     </span>
     <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="input_text_liga" onChange="lista('ciudad',4)">
     	<option value="<?php echo $ciudad; ?>"><?php echo obtenLocalizacion(3,$ciudad); ?></option>
     </select>
     </span>
     <div class="horizontal">
     	<br><input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form>
      </div>
</div>
<div class="columna3">
	<div class="cuadroComentario"><span id="nombreCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="apellidosCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="emailCom">* Introduzca el email correctamente.</span></div>
    <div class="cuadroComentario"><span id="telefonoCom">* Introduzca el telefono correctamente.</span></div>
    <div class="cuadroComentario"><span id="antpasswordCom">* Introduzca una contrase&ntilde;a correcta.</span></div>
    <div class="cuadroComentario"><span id="passwordCom">* Introduzca una contrase&ntilde;a correcta.</span></div>
    <div class="cuadroComentario"><span id="repasswordCom">* Las nuevas contrase&ntilde;as son diferentes.</span></div>
    <div class="cuadroComentario"><span id="dniCom">* Introduzca su Nif correctamente.</span></div>
    <div class="cuadroComentario"><span id="cuenta_paypalCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="direccionCom">* Introduzca su direccin correctamente.</span></div>
    <div class="cuadroComentario"><span id="cpCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="paisCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="provinciaCom">&nbsp;</span></div>
    <div class="cuadroComentario"><span id="ciudadCom">&nbsp;</span></div>
	<div class="horizontal">
     	<?php
			if($_SESSION['habilita_elim'] == 0){
				echo '<br><input type="button" onClick="eliminar('.$jugador->getValor("id_jugador").')" value="Eliminar" class="botonEli" />';
			} 
		?>
    </div>
</div>
<div class="columna4">
	<div class="aviso">Si no ve correctamente alguna imgen, es porque se est efectuando la validacin en el servidor, actualice la pgina en unos minutos.</div>
	<div class="selec_foto">
    <?php
		$ruta1 = '../../../../../fotos_jugador/'.$_SESSION["id_jugador"].'.jpg';//foto original
		$ruta2 = 'temp/temp_'.$_SESSION["id_jugador"].'.jpg';//esta es la foto que esta en fase de subir en carpeta temp
		$foto_temp = '../../../fotos_jugador/'.$_SESSION["id_jugador"].'.jpg';//foto ruta cargable
		copy($ruta1, $foto_temp);
		if(file_exists($ruta2)){			
			echo 'Seleccionde su cara y pulse guardar.';
		}
		else{
			echo 'Seleccionar Nueva Foto.';
		}
	?>
    </div>
    <div class="horizontal">
    	<form id="form_selec" enctype="multipart/form-data" action="#" method="post" name="form_selec">
        	<input type="file" name="imagen1" id="imagen1" onChange="enviar_seleccionada();" class="envio" >
        </form>
    </div>
    <div class="div_foto">
    	 <?php
		if(file_exists($ruta2)){//primero mira en temp que significa que ha seleccionado nueva
			if($_SESSION['creacion'] == 2){
				echo '<img class="foto_cara" src="'.$foto_temp.'" />';
				$_SESSION['creacion'] = 0;
			}
			else{
				echo '<img src="'.$ruta2.'" id="cropbox"  />';
				$_SESSION['creacion'] = 1;
			}
		}
		else if(file_exists($ruta1)){//ya ha guardado el trozo de foto
			echo '<img class="foto_cara" src="'.$foto_temp.'" />';
			$_SESSION['creacion'] = 0;
		}
		else{
			if($genero == 'Masculino'){
				echo '<img class="defecto" src="../../../images/usuario_hombre.jpg" />';
			}
			else{
				echo '<img class="defecto" src="../../../images/usuario_mujer.jpg" />';
			}
			$_SESSION['creacion'] = 0;
		}
		?>
    	
    </div>
    <div class="horizontal">
		<!-- This is the form that our event handler fills -->
		<!--<form action="crop.php" method="post" onsubmit="return checkCoords();">-->
        <?php
        if(file_exists($ruta2)){
			echo '<form name="form_foto" id="form_foto" action="#" method="post">
						<input type="hidden" id="x" name="x" />
						<input type="hidden" id="y" name="y" />
						<input type="hidden" id="w" name="w" />
						<input type="hidden" id="h" name="h" />
						<input type="button" id="btn_enviar_foto" value="Guardar Foto" class="boton" /></form>
					</form>';
		}//fin if
		?>
     </div>

</div>
<div id="respuesta" class="horizontal"></div>
</body>
</html>