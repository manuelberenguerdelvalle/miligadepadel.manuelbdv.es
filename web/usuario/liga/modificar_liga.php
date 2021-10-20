<?php
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_liga'){
	header ("Location: ../cerrar_sesion.php");
}
$usuario = unserialize($_SESSION['usuario']);
$dni_usuario = $usuario->getValor('dni');
$telefono = $usuario->getValor('telefono');
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor("id_liga");
$tipo_pago = $liga->getValor('tipo_pago');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$id_usuario = $usuario->getValor('id_usuario');
//GUARDAR EN SESSION
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['cuenta_paypal'] = $usuario->getValor('cuenta_paypal');
$_SESSION['dni_usuario'] = $dni_usuario;
$_SESSION['telefono'] = $telefono;
$_SESSION['email'] = $usuario->getValor('email');
$_SESSION['id_liga'] = $id_liga;
//$_SESSION['bd_usuario'] = $usuario->getValor('bd');
$opcion = $_SESSION['opcion'];
if($opcion == 0){//modificacion
	$nombre = $liga->getValor("nombre");
	$pass = $liga->getValor("pass");
	$auto_completar = $liga->getValor("auto_completar");
	$vista = $liga->getValor("vista");
	$genero = $liga->getValor("genero");
	$pais = $liga->getValor("pais");
	$provincia = $liga->getValor("provincia");
	$ciudad = $liga->getValor("ciudad");
	$tipo_pago = $liga->getValor("tipo_pago");
	$idayvuelta = $liga->getValor("idayvuelta");
	$movimientos = $liga->getValor("movimientos");
	$pagado = $liga->getValor("pagado");
	if($liga->getValor("pagado") == 'S'){$pagado = 'Si';}
	else{$pagado = 'No';}
}
else{//otro
	header ("Location: ../cerrar_sesion.php");
}
$num_inscripciones = obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$id_liga,'','','','','','','');
$hay_pago_web = count(obtenPagoDivisionesPagadas($usuario->getValor('bd'),$id_liga));//obtiene si se ha hecho ya algn pago 
$hay_suscripcion = obten_numDivisionesSuscripcion($id_liga);
if($tipo_pago == 0){$max_movimientos = 0;}
else if($tipo_pago == 1){$max_movimientos = 2;}
else if($tipo_pago == 2){$max_movimientos = 3;}
else if($tipo_pago == 3){$max_movimientos = 4;}
else{}
if($movimientos > $max_movimientos){
	realiza_updateGeneral('session','liga','movimientos = '.$max_movimientos,'id_liga',$id_liga,'','','','','','','','','');
	$movimientos = $max_movimientos;
}
//obten_consultaUnCampo('session','COUNT(id_division)','division','liga',$id_liga,'comienzo','S','','','','','') > 0
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/modificar_liga.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="../../../jcrop/js/jquery.Jcrop.js"></script>
<script>
<?php
//DATOS JAVASCRIPT:Entra aqui si es nueva liga o modifica por primera vez
if($nombre == 'Sin nombre' && $pais == '' && $provincia == '' && $ciudad == ''){
	echo 'formulario = new formularioLiga("error","error","error","error","error");';
}
else{
	echo 'formulario = new formularioLiga("null","null","null","null","null");';
}
?>
</script>
<script src="javascript/modificar_liga.js" type="text/javascript"></script>
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
			if(extensionImagen(fileExtension)){//formato valido y tamao menos a 5Mb
				//alert(file.size);
				$(".selec_foto").html(''); 
				//$(".selec_foto").html('Cargando, Por favor espere...'); // Mostrar la respuestas del script PHP.
				$(".selec_foto").html('<div style="float:left;">Cargando, Por favor espere...</div><div style="margin-left:2%;float:left;"><img src="../../../images/28.gif" width="25" /></div>');
					var formData=new FormData($('#form_selec')[0]);
					$.ajax({
						url:'foto_grande.php',
						type:'POST',
						data:formData,
						cache:false,
						contentType:false,
						processData:false,
						success: function(data){
							//alert(data);
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
<div class="caja1">
    <div class="columna1">
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Nombre:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'La contrase&ntilde;a se utilizar&aacute; para que los jugadores puedan inscribirse primero y desp&uacute;es para acceder a tu liga si la visibilidad es privada.');" onMouseOut="hiddenDiv()" style='display:table;'>Contrase&ntilde;a:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Si est&aacute; activo, los propios jugadores pueden a&ntilde;adir los resultados de los partidos.');" onMouseOut="hiddenDiv()" style='display:table;'>Autocompletado:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Elige si quieres que tu liga sea visible, o con acceso privado a trav&eacute;s de la contrase&ntilde;a.');" onMouseOut="hiddenDiv()" style='display:table;'>Visibilidad:</div><!-- Si no es de pago se bloquea-->
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduce el tipo de liga Gratis o Premium con servicios y coste adicional.');" onMouseOut="hiddenDiv()" style="display:table;">Tipo de liga:</div>
        <div class="cuadroTexto">G&eacute;nero</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Pa&iacute;s:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Provincia:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Ciudad:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Esta opci&oacute;n genera el doble de partidos para tu liga, y tiene un coste adicional.');" onMouseOut="hiddenDiv()" style="display:table;">Ida y Vuelta:</div> 
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Introduce el n&uacute;mero de ascensos y descensos.');" onMouseOut="hiddenDiv()" style='display:table;'>Ascensos/Descensos:</div><!-- Se bloquearn en tener un partido disputado -->
        <!-- Solo si es de pago. Se muestra como caja de texto bloqueado -->
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Elige el color de la interfaz de tu Liga.');" onMouseOut="hiddenDiv()" style='display:table;'>Estilo:</div>
    <?php
	if($tipo_pago > 0){
		echo '<div class="cuadroTexto">Pagado:</div>';
	}
	?>
	</div>
	<div id="flotante"></div>
	<div class="columna2">
        <span><form id="formulario" action="#" method="post" name="formulario"></span>
        <span class="cuadroInputs"><input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('nombre',0)" maxlength="40" ></span>
        <span class="cuadroInputs"><input type="text" name="pass" id="pass" value="<?php echo $pass; ?>" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('pass',1)" maxlength="6"></span>
        <span class="cuadroInputs"><?php autocompletado($auto_completar); ?></span>
        <span class="cuadroInputs"><?php vista($vista); ?></span>
	<?php
    if($liga->getValor("pagado") == 'S' || $num_inscripciones > 0 || $hay_pago_web > 0 || $hay_suscripcion > 0){//deshabilitados
    ?>
        <span class="cuadroInputs"><select name="tipo_pago" id="tipo_pago" onChange="setLimita();" class="input_select_liga_disabled" disabled><?php tipo_pago($tipo_pago); ?></select></span>
        <span class="cuadroInputs"><select name="genero" id="genero" class="input_select_liga_disabled" disabled><?php generos($genero); ?></select></span>
        <span class="cuadroInputs"><select name="pais" id="pais" class="input_select_liga_disabled" onChange="lista('pais',2)" disabled>
            <option selected value="<?php echo $pais; ?>">	<?php if($opcion == 0){ echo obtenLocalizacion(1,$pais); } ?></option>
         </select></span>
         <span class="cuadroInputs"><select name="provincia" id="provincia" class="input_select_liga_disabled" onChange="lista('provincia',3)" disabled>
            <option value="<?php echo $provincia; ?>"><?php if(!empty($provincia)){ echo obtenLocalizacion(2,$provincia); } ?></option>
            </select>
         </span>
         <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="input_select_liga_disabled" onChange="lista('ciudad',4)" disabled>
            <option value="<?php echo $ciudad; ?>"><?php if(!empty($ciudad)){ echo obtenLocalizacion(3,$ciudad); } ?></option>
            </select>
         </span>
    <?php
    }//fin if
    else{//habilitados
    ?>
        <span class="cuadroInputs"><select name="tipo_pago" id="tipo_pago" on onChange="setLimita(<?php echo $dni_usuario.','.$telefono;?>);" class="input_select_liga"><?php tipo_pago($tipo_pago); ?></select></span>
        <span class="cuadroInputs"><select name="genero" id="genero" class="input_select_liga"><?php generos($genero); ?></select></span>
        <span class="cuadroInputs"><select name="pais" id="pais" class="input_select_liga" onChange="lista('pais',2)">
            <option selected value="">--Elige--</option>
            <?php 
            if(empty($pais)){ 
                    $db = new MySQL('unicas');//UNICAS
                    $consulta = $db->consulta("SELECT Name,Code FROM paises WHERE Code='ESP' ");
                    if($consulta->num_rows>0){
                      while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
                            echo '<option value="'.$resultados['Code'].'">'.$resultados['Name'].'</option>';
                      }
                    }
                    $db->cerrar_conexion();// Desconectarse de la base de datos 
            }//fin if
            else{
                echo '<option selected value="'.$pais.'">'; 
                echo obtenLocalizacion(1,$pais); 
                echo '</option>';
             } ?>
         </select></span>
         <span class="cuadroInputs"><select name="provincia" id="provincia" class="inputText" onChange="lista('provincia',3)">
            <option value="<?php echo $provincia; ?>"><?php if($opcion == 0){ echo obtenLocalizacion(2,$provincia); } ?></option>
            </select>
         </span>
         <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="inputText" onChange="lista('ciudad',4)">
            <option value="<?php echo $ciudad; ?>"><?php if($opcion == 0){ echo obtenLocalizacion(3,$ciudad); } ?></option>
            </select>
         </span>
    <?php
    }//fin else
    //comprobamos la ida y vuelta a parte del resto
    //if( ($idayvuelta == 'S' && obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','liga',$id_liga,'division',$id_division,'tipo','I','pagado','S','') == 1) || ($hay_suscripcion > 0) ){//si esta pagada deshabilito
	if(obten_numMaxPartidos($id_liga) > 0){//si esta pagada deshabilito
    ?>
        <span class="cuadroInputs"><select name="idayvuelta" id="idayvuelta" class="input_select_liga_disabled" disabled><?php idayvuelta($idayvuelta); ?></select></span>
    <?php
    }
    else{//si no hay pago habilito
    ?>
         <span class="cuadroInputs"><select name="idayvuelta" id="idayvuelta" class="input_select_liga"><?php idayvuelta($idayvuelta); ?></select></span>
    <?php
    }
    ?>
         <span class="cuadroInputs"><?php movimientos($movimientos,$max_movimientos); ?></span>
         <span class="cuadroInputs">
    <?php
    if($tipo_pago > 0){$max = 5;}
    else{$max = 2;}
    for($i=0; $i<$max; $i++){
        if($liga->getValor('estilo') == $i){
            echo '<input type="radio" id="radio'.$i.'" name="estilo" value="'.$i.'" checked><span class="color'.$i.'">&nbsp;</span>';
        }
        else{
            echo '<input type="radio" id="radio'.$i.'" name="estilo" value="'.$i.'"><span class="color'.$i.'">&nbsp;</span>';
        }
    }
    ?>	
          </span>
    <?php 
        if($tipo_pago > 0){ 
            echo '<span class="cuadroInputs"><input type="text" id="muestra_pago" value="'.$pagado.'" disabled class="input_text_liga" ></span>';
        }
    ?>
         
    </div>
    <div class="columna3">
        <div class="cuadroComentario"><span id="nombreCom">* Introduzca solo letras.</span></div>
        <div class="cuadroComentario"><span id="passwordCom">* Utilice entre 4-6 letras o n&uacute;meros.</span></div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario"><span id="paisCom">* Introduzca un pa&iacute;s.</span></div>
        <div class="cuadroComentario"><span id="provinciaCom">* Introduzca una provincia.</span></div>
        <div class="cuadroComentario"><span id="ciudadCom">* Introduzca una ciudad.</span></div>
        <div class="cuadroLogo">
        <?php
            if($opcion == 0){
                $destino = '../../../logos/'.$_SESSION['bd'];
                $mostrar = '';
                $mostrar .= '<span>Logo actual:</span>';
                if(file_exists($destino.$id_liga.'.jpg')){
                    $mostrar .= '<img src="'.$destino.$id_liga.'.jpg">';
                }
                else if(file_exists($destino.$id_liga.'.jpeg')){
                    $mostrar .= '<img src="'.$destino.$id_liga.'.jpeg">';
                }
                else if(file_exists($destino.$id_liga.'.png')){
                    $mostrar .= '<img src="'.$destino.$id_liga.'.png">';
                }
                else if(file_exists($destino.$id_liga.'.bmp')){
                    $mostrar .= '<img src="'.$destino.$id_liga.'.bmp">';
                }
                else{
                    $mostrar .= '<img src="../../../logos/0'.$liga->getValor('estilo').'.jpg">';
                }
                echo $mostrar;
            }
            else{
                echo '&nbsp;';
            }
        ?>
            
        </div>
        <div class="cuadroComentario"><span id="logoCom">* El formato es diferente a |.jpg| |.jpeg| |.png| |.bmp| o el tama&ntilde;o de la imagen es superior a 500 kb.</span></div>
        <div class="cuadroComentario"><span id="estiloCom">&nbsp;</span></div>
	</div>
    <div class="horizontal"><input type="button" id="btn_enviar" value="Actualizar" class="boton" /></form>
	<?php
    if($tipo_pago > 0){//ligas de pago
        if(obten_consultaUnCampo('unicas','COUNT(id_pago_web)','pago_web','liga',$id_liga,'usuario',$id_usuario,'pagado','S','estado','E','') == 0){
            echo '<input type="button" value="Eliminar" onClick="eliminar('.$id_usuario.','.$id_liga.');" class="botonEli" />';
        }
    }
    else{//ligas gratis
        if($num_inscripciones == 0){
            echo '<input type="button" value="Eliminar" onClick="eliminar('.$id_usuario.','.$id_liga.');" class="botonEli" />';
        }
    }
    ?>
    </div>
    <div class="horizontal">&nbsp;</div>
    <div id="respuesta" class="horizontal">&nbsp;</div>
</div><!-- fin caja1-->
<div class="caja2">
        <div class="imagen_boton">
            <form id="form_selec" enctype="multipart/form-data" action="#" method="post" name="form_selec">
                <input type="file" name="imagen1" id="imagen1" onChange="enviar_seleccionada();" >
            </form>
        </div>
        <div class="selec_foto">
        	<?php
				$ruta2 = 'temp/temp_'.$_SESSION['bd'].$_SESSION['id_liga'].'.jpg';//esta es la foto que esta en fase de subir en carpeta temp
				if(file_exists($ruta2)){
					echo '<img src="'.$ruta2.'" id="cropbox"  />';		
					//echo 'Seleccionde su cara y pulse guardar.';
				}
				else{
					echo '<span class="aviso">Si desea cambiar el logo seleccione una nueva foto.</span>';
				}
			?>
        </div>
        <div class="imagen_boton">
        	<?php
				if(file_exists($ruta2)){;
					echo '<form name="form_foto" id="form_foto" action="#" method="post">
							<input type="hidden" id="x" name="x" />
							<input type="hidden" id="y" name="y" />
							<input type="hidden" id="w" name="w" />
							<input type="hidden" id="h" name="h" />
							<input type="button" id="btn_enviar_foto" value="Guardar Foto" class="boton" /></form>
							</form>';
				}
			?>
        </div>
</div><!-- fin caja2-->
</body>
</html>