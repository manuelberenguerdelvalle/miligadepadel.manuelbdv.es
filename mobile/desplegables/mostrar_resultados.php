<?php
include("../../class/mysql.php");
include("../funciones/f_general.php");
include("../funciones/f_obten.php");
session_start();
header("Content-Type: text/html;charset=utf-8");
?>
<script src="../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<!--<script src="../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../javascript/validacionesjs" type="text/javascript"></script>-->
<script language="javascript">
function acceso(tipo){
	var retorno = false;
	if(tipo == 0){retorno = true;}
	else if(tipo == 1){
		var pass = document.getElementById('pass').value;
		var repass = document.getElementById('repass').value;
		if(pass == repass){
			retorno = true;
		}
		else{
			retorno = false;
		}
		//alert(pass+'-'+repass);
		//retorno = false;
	}
	else{retorno = false;}
	if(retorno == false){//activo el error
		document.getElementById('errorPass').style.display = 'block';
	}
	return retorno;
}
function eliminarInyeccion(valor){
	var cadenas = ['"."','=""','= ""', "=' '", "=''", "'.'", "%", " OR ", " or ", " AND ", " and ", "`", "*", " FROM ", " from ", " WHERE ", " where ", " UNION SELECT ", " union select ", "&", " LIKE ", " like "];
	var longitud = cadenas.length;
	for(var i=0; i<longitud; i++){
		valor.replace(cadenas[$i], " ");
	}
	return valor;
}
function enviar(){
	var dataString = $("#formulario_contacto").serialize();
	//var url = "contacto/enviar_email.php";
	var url = "contacto/c/enviar_email.php";
	//alert(dataString);
	$.ajax({
				   type: "POST",
				   url: url,
				   data: dataString, // Adjuntar los campos del formulario enviado.
				   success: function(data)
				   {   
				   		//alert(dataString);
					   /*$("#respuesta").html(data); // Mostrar la respuestas del script PHP.		   
					   setTimeout ("window.location.reload();", 2000);*/
				   }
	});
}
</script>
<link rel="stylesheet" type="text/css" href="../css/bpopup.css" />
<style type="text/css">
.cuadroTitulo {
	width:72% !important;
	margin-top:1%;
	font-size: 80%;
	color: #FFF;
	text-align:center;
	font-weight:bold;
	/*border:1px black solid;*/
	float:left;
}
.cuadroTexto {
	width:35% !important;
	height:30% !important;
	margin-top:1.5%;
	font-size:80%;
	color: #FFF;
	font-weight:bold;
	float:left;
	text-align: right;
	/*border:1px black solid;*/
}
.cuadroInput {
	width:17% !important;
	height:40% !important;
	margin-top:1%;
	float:left;
	/*border:1px black solid;*/
}
.input_text_pass {
	width:95%;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:15px;
	font-size:100%;
	font-style:italic;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
	border:5px #8989FE solid;
}
.cuadroBoton {
	width:15% !important;
	height:60% !important;
	float:left;
	/*border:1px black solid;*/
}
.botonIr {
	margin-left: 5%;
	border-radius: 50%;
	background-color:#FFF;
	font-size:100%;
	box-shadow:3px 3px 4px rgba(0,0,0,0.3);
	/*padding-top: 7px;*/
	padding-bottom: 7px;
    padding-right: 10px;
    padding-left: 10px;
	font-weight:bold;
	color:#8989FE;
	float:left;
}
.botonIr:hover {
	margin-left: 5%;
	border-radius: 50%;
	background-color:#34495e;
	box-shadow:3px 3px 4px rgba(0,0,0,0.3);
	/*padding-top: 7px;*/
	padding-bottom: 7px;
    padding-right: 10px;
    padding-left: 10px;
	font-weight:bold;
	color:#FFF;
	float:left;
}
/*.cuadroBoton img {
	height:95% !important;
	/*float:left;
	border:1px black solid;
}*/
.cuadroError {
	width:99% !important;
	float:left;
	margin-top:5%;
	font-weight:bold;
	font-size:80%;
	color: #FFF;
	text-align:center;
	/*border:1px black solid;*/
}
.cuadroError2{
	width:99% !important;
	margin-top:1%;
	float:left;
	font-weight:bold;
	font-size:80%;
	color: #FFF;
	text-align: left;
	/*border:1px black solid;*/
}
.cuadroErrorPass {
	width:30% !important;
	float:left;
	font-weight:bold;
	font-size:65%;
	color: #F00;
	text-align:center;
	display: none;
	/*border:1px black solid;*/
}
/*EL CSS DEL POPUP SE HA PASADO AL GENERAL DE BPOPUP.CSS*/
.input_text_liga {
	width:95%;
	font-size:100%;
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:20px;
	font-weight:bold;
	font-style:italic;
	border:5px #8989FE solid;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
}
.input_text_area{
	color: #181C83;
	background-color: #EDEDFC;
	border-radius:20px;
	font-weight:bold;
	font-style:italic;
	border:5px #8989FE solid;
	box-shadow:5px 5px 7px rgba(0,0,0,0.3);
}
.pinchar{
	font-size:110%;
	font-style:italic;
	color: #181C83;
	text-decoration: underline;
}
.env{
	color:#FFF;
	font-weight:bold;
	text-decoration:none;
}
</style>
<?php
$rpta="";
$elegido=limpiaTexto($_POST["elegido"]);
$rpta= '';	
$db3 = new MySQL('session');//SESSION
$consulta3 = $db3->consulta("SELECT usuario,nombre,tipo_pago,vista,pass,genero,liga,suscripcion,comienzo,precio,num_division FROM liga,division WHERE id_division = '$elegido' AND liga = id_liga; ");
if($consulta3->num_rows>0){
  	$resultados3 = $consulta3->fetch_array(MYSQLI_ASSOC);
	//GUARDAR EN SESSION
	$_SESSION["usuario"] = $resultados3["usuario"];
	$_SESSION["nombre"] = $resultados3["nombre"];
	$_SESSION["tipo_pago"] = $resultados3["tipo_pago"];
	$_SESSION["pass"] = $resultados3["pass"];
	$_SESSION["genero"] = $resultados3["genero"];
	$_SESSION["vista"] = $resultados3["vista"];
	$_SESSION["id_division"] = $elegido;//id_division
	$_SESSION["id_liga"] = $resultados3["liga"];
	$_SESSION["precio"] = $resultados3["precio"];
	$_SESSION["num_division"] = $resultados3["num_division"];
	$id_division = $elegido;
	$id_division .= 'F';
	$id = genera_id_url(50,$id_division,13);
	//cuidado con el _parent que cambia la ruta
	if($resultados3["comienzo"] == 'S'){//VER LIGA
		$rpta .= '<div class="cuadroTitulo">Ver Liga</div>';
		if($resultados3["tipo_pago"] > 0 && $resultados3["tipo_pago"] <= 3){//si es liga de pago
			if($resultados3["vista"] == 1){//privada
				$rpta .= '<input type="hidden" name="repass" id="repass" value="'.$resultados3["pass"].'">
				<div class="cuadroTexto">Contrase&ntilde;a:&nbsp;</div>
				<div class="cuadroInput"><input class="input_text_pass" name="pass" id="pass" type="password" value="" onkeypress="return password(event)" maxlength="6" /></div>
				<div class="cuadroBoton"><a href="ver_liga/p/noticia.php" target="_parent" onclick="return acceso(1);"><label class="botonIr">&rArr;</label></a></div>
				<div id="errorPass" class="cuadroErrorPass">Error en la contrase&ntilde;a.</div>';
			}
			else{//publica
				$rpta .= '<div class="cuadroTexto">&nbsp;</div><div class="cuadroBoton"><a href="ver_liga/p/noticia.php" target="_parent" onclick="return acceso(0);"><label class="botonIr">&rArr;</label></div>';
			}
		}
		else{//si es publica y gratis
			$rpta .= '<div class="cuadroTexto">&nbsp;</div><div class="cuadroBoton"><a href="ver_liga/g/noticia.php" target="_parent" onclick="return acceso(0);"><label class="botonIr">&rArr;</label></div>';
			//HACER CAMBIOS EN USUARIO/REGISTRO/INSCRIBIR_EQUIPO
		}
	}
	else{//INSCRIBIRSE
		if(obten_consultaUnCampo('unicas','pagado','pago_web','usuario',$resultados3["usuario"],'liga',$resultados3["liga"],'division',$elegido,'','','') == 'N'){
			$rpta .= '<div  class="cuadroError2">La liga se encuentra pendiente de confirmaci&oacute;n por el administrador para poder inscribirse.</a></div>';
		}
		elseif( obten_consultaUnCampo('unicas_liga','recibir_pago','usuario','id_usuario',$resultados3["usuario"],'','','','','','','') != 'M' && (obten_consultaUnCampo('unicas_liga','cuenta_paypal','usuario','id_usuario',$resultados3["usuario"],'','','','','','','') == '' || obten_consultaUnCampo('unicas_liga','dni','usuario','id_usuario',$resultados3["usuario"],'','','','','','','') == 0) ){//entra si no tiene cuenta paypal o dni y puede recibir pagos online
			$rpta .= '<div  class="cuadroError2">Para inscribirte el Administrador tiene que configurar su cuenta correctamente o cambiar a pago presencial, puedes contactar pulsando <a class="pinchar" href="#" target="_parent" onclick="'."$('#content_popup').bPopup();".'">Aqu&iacute;.</a></div>';
			$rpta .= '<div id="content_popup">
				<div class="poptitulo"><h2>Contactar con el Administrador</h2></div>
				<div class="popcentro">
					<form id="formulario_contacto" action="#" method="post" name="formulario_contacto" >
					<label class="caja_input"><input name="contacto"  type="text" class="input_text_liga" value="Email" ></label><label id="errorContacto" class="caja_error">*</label>
					<label class="caja_input"><input name="asunto" type="text" class="input_text_liga" value="Asunto" ></label><label id="errorAsunto" class="caja_error">*</label>
					<label class="caja_input_area"><textarea  rows="11" cols="50" name="mensaje" class="input_text_area" >Mensaje</textarea></label><label id="errorTextarea" class="caja_error">*</label>
					<input type="hidden" name="id_liga" value="'.$resultados3["liga"].'"><input type="hidden" name="id_division" value="'.$elegido.'"><input type="hidden" name="bd" value="'.$_SESSION["bd"].'"><input type="hidden" name="modo" value="4">
					</form>
				</div>
				<div class="poppie">
					<span class="button b-close"><span><a class="env" href="#"  onclick="enviar();">ENVIAR</a></span></span>
				</div>
			</div>';
		}//fin if cuenta paypal
		else{//OBTENER EQUIPOS, INSCRIPCIONES EN ESTADO N, PARA EVITAR FALLOS.
			$equipos_inscritos = obten_consultaUnCampo('session','COUNT(id_equipo)','equipo','liga',$resultados3["liga"],'division',$elegido,'','','','','');
			if( $equipos_inscritos < obten_equipos($resultados3["tipo_pago"]) ){//todavía hay plazas
				$rpta .= '<div class="cuadroTitulo">Inscripci&oacute;n:</div>';
				//HACER CAMBIOS EN USUARIO/REGISTRO/INSCRIBIR_EQUIPO
				$rpta .= '<input type="hidden" name="repass" id="repass" value="'.$resultados3["pass"].'">
				<div class="cuadroTexto">Contrase&ntilde;a:&nbsp;</div>
				<div class="cuadroInput"><input class="input_text_pass" name="pass" id="pass" type="password" value="" onkeypress="return tecla_password(event)" maxlength="6" /></div>
				<div class="cuadroBoton"><a href="usuario/registro/inscribir_equipo.php" target="_parent" onclick="return acceso(1);"><label class="botonIr">&rArr;</label></a></div>
				<div id="errorPass" class="cuadroErrorPass">Error en la contrase&ntilde;a.</div>'; 
				//<img src="../images/boton_ir.png" />
			}//fin num equipos
			else{//no hay plazas
				$rpta .= '<div class="cuadroError">Ya no quedan plazas para Inscribirse en la Liga y Divisi&oacute;n seleccionada</div>';
			}
		}//fin else
	}//fin else inscripcion
}
// Desconectarse de la base de datos
$db3->cerrar_conexion();
echo $rpta;		
?>