<?php
include_once ("../../funciones/f_inputs.php");
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
//GUARDAMOS EN SESSION
$_SESSION['id_usuario'] = $usuario->getValor('id_usuario');
$_SESSION['cuenta_paypal'] = $usuario->getValor('cuenta_paypal');
$_SESSION['email'] = $usuario->getValor('email');
$opcion = $_SESSION['opcion'];
if($opcion == 1){
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>miligadepadel.manuelbdv.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_liga.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>
<script src="javascript/insertar_liga.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
</head>
<body>
<div class="horizontal">&nbsp;</div>
<div class="horizontal">Crear Nueva Liga</div>
<div class="horizontal">&nbsp;</div>
<div class="columna1">
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Nombre:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Se utilizar&aacute; para el acceso a tu liga si la visiblidad es privada y para insertar resultados de partidos.');" onMouseOut="hiddenDiv()" style='display:table;'>Contrase&ntilde;a:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Si est&aacute; activo, los propios jugadores pueden a&ntilde;adir los resultados de los partidos.');" onMouseOut="hiddenDiv()" style='display:table;'>Autocompletado:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Introduce el tipo de liga Gratis o Premium con servicios y coste adicional.');" onMouseOut="hiddenDiv()" style="display:table;">Tipo de liga:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Elige si quieres que tu liga sea visible, o con acceso privado a trav&eacute;s de la contrase&ntilde;a.');" onMouseOut="hiddenDiv()" style='display:table;'>Visibilidad:</div><!-- Si no es de pago se bloquea-->
	<div class="cuadroTexto">G&eacute;nero</div>
	<div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Pa&iacute;s:</div>
	<div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Provincia:</div>
	<div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio.');" onMouseOut="hiddenDiv()" style="display:table;">Ciudad:</div>
	<div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Esta opci&oacute;n genera el doble de partidos para tu liga, y tiene un coste adicional.');" onMouseOut="hiddenDiv()" style="display:table;">Ida y Vuelta:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Liga Premium: Introduce el n&uacute;mero de ascensos y descensos.');" onMouseOut="hiddenDiv()" style='display:table;'>Ascensos/Descensos:</div>
    <div class="cuadroTexto" onMouseOver="showdiv(event,'Elige el color de la interfaz de tu Liga.');" onMouseOut="hiddenDiv()" style='display:table;'>Estilo:</div>
</div>
<div id="flotante"></div>
<div class="columna2">
	<span><form id="formulario" action="#" method="post" name="formulario"></span>
	<span class="cuadroInputs"><input type="text" name="nombre" id="nombre" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('nombre',0)" maxlength="40" ></span>
    <span class="cuadroInputs"><input type="text" name="pass" id="pass" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('pass',1)" maxlength="6"></span>
    <span class="cuadroInputs"><?php autocompletado(''); ?></span>
    <span class="cuadroInputs"><select name="tipo_pago" id="tipo_pago" onChange="return setLimita('<?php echo $dni_usuario; ?>','<?php echo $telefono; ?>');" class="input_select_liga"><?php tipo_pago(0); ?></select></span>
    <span class="cuadroInputs"><?php vista(''); ?></span>
    <span class="cuadroInputs"><select name="genero" id="genero" class="input_select_liga"><?php generos('M'); ?></select></span>
	<span class="cuadroInputs">
    	<select name="pais" id="pais" class="input_select_liga" onChange="lista('pais',2)">
            	<option value="">--Pais--</option>
				<?php
				$db = new MySQL('unicas');//UNICAS
				$consulta = $db->consulta("SELECT Name,Code FROM paises WHERE Code='ESP' ");
				if($consulta->num_rows>0){
				  while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
					 if($opcion == 0 && $pais == $resultados['Code']){
						echo '<option selected value="'.$resultados['Code'].'">'.$resultados['Name'].'</option>';
					 }
					 else{
					 	echo '<option  value="'.$resultados['Code'].'">'.$resultados['Name'].'</option>';
					 }
				  }
				}
				$db->cerrar_conexion();// Desconectarse de la base de datos ?>
     	</select>
     </span>
     <span class="cuadroInputs"><select name="provincia" id="provincia" class="inputText" onChange="lista('provincia',3)"></select>
     </span>
     <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="inputText" onChange="lista('ciudad',4)"></select>
     </span>
     <span class="cuadroInputs"><select name="idayvuelta" id="idayvuelta" class="input_select_liga"><?php idayvuelta(''); ?></select></span>
     
     <span class="cuadroInputs"><?php movimientos('',0); ?></span>
     <span class="cuadroInputs">
<?php
$max = 5;
for($i=0; $i<$max; $i++){
	if($i == 0){
		echo '<input type="radio" id="radio'.$i.'" name="estilo" value="'.$i.'" checked><span class="color'.$i.'">&nbsp;</span>';
	}
	else{
		echo '<input type="radio" id="radio'.$i.'" name="estilo" value="'.$i.'"><span class="color'.$i.'">&nbsp;</span>';
	}
}
?>	
      </span>
</div>
<div class="columna3">
	<div class="cuadroComentario"><span id="nombreCom">* Introduzca solo letras.</span></div>
    <div class="cuadroComentario"><span id="passwordCom">* Utilice entre 4-6 letras o n&uacute;meros.</span></div>
    <div class="cuadroComentario">&nbsp;</div>
    <div class="cuadroComentario">&nbsp;</div>
    <div class="cuadroComentario">&nbsp;</div>
    <div class="cuadroComentario">&nbsp;</div>
    <div class="cuadroComentario"><span id="paisCom">* Introduzca un pa&iacute;s.</span></div>
    <div class="cuadroComentario"><span id="provinciaCom">* Introduzca una provincia.</span></div>
    <div class="cuadroComentario"><span id="ciudadCom">* Introduzca una ciudad.</span></div>
</div>
<div class="horizontal"><input type="button" id="btn_enviar" value="Crear Liga"  class="boton" /></form></div>
<div id="respuesta" class="horizontal"></div>
<?php 
}
?>
</body>
</html>