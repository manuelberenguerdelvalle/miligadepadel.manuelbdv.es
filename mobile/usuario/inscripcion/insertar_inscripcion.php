<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_inscripcion'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$tipo_pago = $liga->getValor('tipo_pago');
$genero = $liga->getValor('genero');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$comienzo = $division->getValor('comienzo');
$num_equipos = obten_consultaUnCampo('session','COUNT(id_equipo)','equipo','liga',$id_liga,'division',$id_division,'','','','','');
if($num_equipos < obten_equipos($tipo_pago) && $opcion == 1){//VALIDAR SI SE PUEDE INSCRIBIR YA O NO Y EL NUMERO DE EQUIPOS (PUEDEN HABER INSCRIPCIONES NO PAGADAS)
	//SE GUARDA EN SESSION
	$_SESSION['id_liga'] = $id_liga;
	$_SESSION['nombre'] = $liga->getValor('nombre');
	$_SESSION['tipo_pago'] = $tipo_pago;
	$_SESSION['genero'] = $genero;

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>www.mitorneodepadel.es</title>
<link rel="stylesheet" type="text/css" href="css/insertar_inscripcion.css" />
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/localizacion2.js" type="text/javascript"></script>
<script src="../../javascript/validaciones.js" type="text/javascript"></script>

<script language="javascript"> 
	var genero_liga='<?php echo $genero; ?>';
</script><!-- para saber la combinacin de generos-->

<script src="javascript/insertar_inscripcion.js" type="text/javascript"></script>
</head>
<body>
<div class="caja_jugador">
    <div class="horizontal">&nbsp;</div>
    <div class="horizontal">JUGADOR 1 <?php if($genero == 'A'){echo '(Masculino)';} ?></div>
    <div class="horizontal">
        <div class="opcion"><input type="radio" name="jugador1" id="jugador1" value="rapido1" checked onClick="comprueba(this)" class="radio"><b>Temporal</b></div>
        <div class="opcion"><input type="radio" name="jugador1" id="jugador1" value="insertar1" onClick="comprueba(this)" class="radio"><b>Registrar</b></div>
    </div>
    <div id="jug1_col1_rapido" class="columna1">
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Nombre.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Nombre:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Apellidos.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Apellidos:</b></div>
    </div>
    <div id="flotante"></div>
    <div id="jug1_col2_rapido" class="columna2">
        <span><form id="formulario1_rapido" action="#" method="post" name="formulario1"></span>
        <span class="cuadroInputs"><input type="text" name="nombre1_rapido" id="nombre1_rapido" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('nombre1_rapido',0)" ></span>
        <span class="cuadroInputs"><input type="text" name="apellidos1_rapido" id="apellidos1_rapido" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('apellidos1_rapido',1)" ></span>
         </form>
    </div>
    <div id="jug1_col3_rapido" class="columna3">
        <div class="cuadroComentario"><span id="nombre1_rapidoCom">*</span></div>
        <div class="cuadroComentario"><span id="apellidos1_rapidoCom">*</span></div>
    </div>
    
    <div id="jug1_col1" class="columna1">
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Nombre.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Nombre:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Apellidos.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Apellidos:</b></div>
        <!-- AQUI VA LA CONTRASE`A PERO INSERCI"N MANUAL NO TIENE -->
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Direcci&oacute;n.');" onMouseOut="hiddenDiv()" style="display:table;">Direcci&oacute;n:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Fecha de Nacimiento.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Nacimiento:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Zona de juego preferida por el jugador.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Juego:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Pa&iacute;s.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Pa&iacute;s:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Provincia.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Provincia:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Ciudad.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Ciudad:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Tel&eacute;fono.');" onMouseOut="hiddenDiv()" style="display:table;">Tel&eacute;fono:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Email, se utilizar para introducir resultados y acceso a tu perfil.');" onMouseOut="hiddenDiv()" style="display:table;"><b>E-mail:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Contrase&ntilde;a, se utilizar para introducir resultados y acceso a tu perfil.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Pass:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Nif.');" onMouseOut="hiddenDiv()" style="display:table;">Nif:</div>
        <div class="cuadroTexto"><b>G&eacute;nero:</b></div>
         <?php 
         /*if($genero == 'A'){//si es liga mixta
            echo '<div class="cuadroTexto">Gnero:</div>';
         }*/
         ?>
    </div>
    <div id="flotante"></div>
    <div id="jug1_col2" class="columna2">
        <span><form id="formulario1" action="#" method="post" name="formulario1"></span>
        <span class="cuadroInputs"><input type="text" name="nombre1" id="nombre1" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('nombre1',0)" ></span>
        <span class="cuadroInputs"><input type="text" name="apellidos1" id="apellidos1" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('apellidos1',1)" ></span>
        <span class="cuadroInputs"><input type="text" name="direccion1" id="direccion1" class="input_text_liga"  onkeypress="return tecla_direccion(event)" onBlur="limpiaDireccion('direccion1',2,0" ></span>
        <span class="cuadroInputs"><?php dia(0,'dia1','input_select_liga'); mes(0,'mes1','input_select_liga'); anyo(0,'anyo1','input_select_liga');?></span>
        <span class="cuadroInputs"><?php zona_juego("A","zona_juego1","inputText");?></span>
        <span class="cuadroInputs"><select name="pais" id="pais" class="input_select_liga" onChange="lista('pais',3)">
                    <option value="">--Elige--</option>
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
                 ?>
                </select>
         </span>
         <span class="cuadroInputs"><select name="provincia" id="provincia" class="input_select_liga" onChange="lista('provincia',4)"></select>
         </span>
         <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="inputText_ciudad" onChange="lista('ciudad',5)"></select>
         </span>
         <span class="cuadroInputs"><input type="text" name="telefono1" id="telefono1" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('telefono1',6,0)"  maxlength="9"></span>
         <span class="cuadroInputs"><input type="text" name="email1" id="email1" class="input_text_liga" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('email1',7)" ></span>
         <span class="cuadroInputs"><input type="text" name="password1" id="password1" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('password1',8)" maxlength="15"></span>
         <span class="cuadroInputs"><input type="text" name="dni1" id="dni1"class="input_text_liga" onKeyPress="return tecla_dni(event)" onBlur="limpiadni('dni1',9)"  maxlength="9"></span>
         <?php 
         if($genero == 'A'){//si es liga mixta
            echo '<span class="cuadroInputs">'.generos2("M","genero1").'</span>';
         }
		 else{//si es por genero
            echo '<span class="cuadroInputs">'.generos2($genero,"genero1").'</span>';
         }
         ?>
         </form>
    </div>
    <div id="jug1_col3" class="columna3">
        <div class="cuadroComentario"><span id="nombre1Com">*</span></div>
        <div class="cuadroComentario"><span id="apellidos1Com">*</span></div>
        <div class="cuadroComentario"><span id="direccion1Com">&nbsp;</span></div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario"><span id="paisCom">*</span></div>
        <div class="cuadroComentario"><span id="provinciaCom">*</span></div>
        <div class="cuadroComentario"><span id="ciudadCom">*</span></div>
        <div class="cuadroComentario"><span id="telefono1Com">*</span></div>
        <div class="cuadroComentario"><span id="email1Com">*</span></div>
        <div class="cuadroComentario"><span id="password1Com">*</span></div>
        <div class="cuadroComentario"><span id="dni1Com">&nbsp;</span></div>
        <?php 
         if($genero == 'A'){//si es liga mixta
            echo '<div class="cuadroComentario"><span id="genero1Com">*</span></div>';
         }
         ?>
    </div>
</div><!-- FIN DIV JUGADOR 1 -->
<div class="caja_jugador">
	<div class="horizontal">&nbsp;</div>
    <div class="horizontal">JUGADOR 2 <?php if($genero == 'A'){echo '(Femenino)';} ?></div>
    <div class="horizontal">
        <div class="opcion"><input type="radio" name="jugador2" value="rapido2" checked onClick="comprueba(this)" class="radio"><b>Temporal</b></div>
        <div class="opcion"><input type="radio" name="jugador2" value="insertar2" onClick="comprueba(this)" class="radio"><b>Registrar</b></div>
    </div>
    <div id="jug2_col1_rapido" class="columna1">
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Nombre.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Nombre:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Apellidos.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Apellidos:</b></div>
    </div>
    <div id="flotante"></div>
    <div id="jug2_col2_rapido" class="columna2">
        <span><form id="formulario2_rapido" action="#" method="post" name="formulario2"></span>
        <span class="cuadroInputs"><input type="text" name="nombre2_rapido" id="nombre2_rapido" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('nombre2_rapido',10)" ></span>
        <span class="cuadroInputs"><input type="text" name="apellidos2_rapido" id="apellidos2_rapido" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('apellidos2_rapido',11)" ></span>
         </form>
    </div>
    <div id="jug2_col3_rapido" class="columna3">
        <div class="cuadroComentario"><span id="nombre2_rapidoCom">*</span></div>
        <div class="cuadroComentario"><span id="apellidos2_rapidoCom">*</span></div>
    </div>
    
    <div id="jug2_col1" class="columna1">
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Nombre.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Nombre:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Apellidos.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Apellidos:</b></div>
        <!-- AQUI VA LA CONTRASE`A PERO INSERCI"N MANUAL NO TIENE -->
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Direcci&oacute;n.');" onMouseOut="hiddenDiv()" style="display:table;">Direcci&oacute;n:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Fecha de Nacimiento.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Nacimiento:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Zona de juego preferida por el jugador.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Juego:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Pa&iacute;s.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Pa&iacute;s:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Provincia.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Provincia:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Ciudad.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Ciudad:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Tel&eacute;fono.');" onMouseOut="hiddenDiv()" style="display:table;">Tel&eacute;fono:</div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Email, se utilizar para introducir resultados y acceso a tu perfil.');" onMouseOut="hiddenDiv()" style="display:table;"><b>E-mail:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Obligatorio: Contrase&ntilde;a, se utilizar&aacute; para introducir resultados y acceso a tu perfil.');" onMouseOut="hiddenDiv()" style="display:table;"><b>Pass:</b></div>
        <div class="cuadroTexto" onMouseOver="showdiv(event,'Opcional: Nif.');" onMouseOut="hiddenDiv()" style="display:table;">Nif:</div>
         <div class="cuadroTexto"><b>G&eacute;nero:</b></div>
    </div>
    <div id="flotante"></div>
    <div id="jug2_col2" class="columna2">
        <span><form id="formulario2" action="#" method="post" name="formulario2"></span>
        <span class="cuadroInputs"><input type="text" name="nombre2" id="nombre2" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('nombre2',10)" ></span>
        <span class="cuadroInputs"><input type="text" name="apellidos2" id="apellidos2" class="input_text_liga" onKeyPress="return soloLetras(event)" onBlur="limpiaLetras('apellidos2',11)" ></span>
        <span class="cuadroInputs"><input type="text" name="direccion2" id="direccion2" class="input_text_liga"  onkeypress="return tecla_direccion(event)" onBlur="limpiaDireccion('direccion2',12,<?php echo $tipo_pago ?>)" ></span>
        <span class="cuadroInputs"><?php dia(0,'dia2','input_select_liga'); mes(0,'mes2','input_select_liga'); anyo(0,'anyo2','input_select_liga');?></span>
        <span class="cuadroInputs"><?php zona_juego("A","zona_juego2","inputText");?></span>
        <span class="cuadroInputs"><select name="pais2" id="pais2" class="input_select_liga" onChange="lista('pais2',13)">
                    <option value="">--Elige--</option>
                    <?php
                    $db4 = new MySQL('unicas');//UNICAS
                    $consulta4 = $db4->consulta("SELECT Name,Code FROM paises WHERE Code='ESP'; ");
                    if($consulta4->num_rows>0){
                      while($resultados4 = $consulta4->fetch_array(MYSQLI_ASSOC)){
                         if($opcion == 0 && $pais == $resultados4['Code']){
                            echo '<option selected value="'.$resultados4['Code'].'">'.$resultados4['Name'].'</option>';
							echo 'entra1';
                         }
                         else{
                            echo '<option  value="'.$resultados4['Code'].'">'.$resultados4['Name'].'</option>';
							echo 'entra2';
                         }
                      }
                    }
                 ?>
                </select>
         </span>
         <span class="cuadroInputs"><select name="provincia2" id="provincia2" class="input_select_liga" onChange="lista('provincia2',14)"></select>
         </span>
         <span class="cuadroInputs"><select name="ciudad2" id="ciudad2" class="inputText_ciudad" onChange="lista('ciudad2',15)"></select>
         </span>
         <span class="cuadroInputs"><input type="text" name="telefono2" id="telefono2" class="input_text_liga" onKeyPress="return numeros(event)" onBlur="limpiaNumeros('telefono2',16,0)"  maxlength="9"></span>
         <span class="cuadroInputs"><input type="text" name="email2" id="email2" class="input_text_liga" onKeyPress="return tecla_email(event)" onBlur="limpiaEmail('email2',17)" ></span>
         <span class="cuadroInputs"><input type="text" name="password2" id="password2" class="input_text_liga" onKeyPress="return tecla_password(event)" onBlur="limpiaPassword('password2',18)" maxlength="15"></span>
         <span class="cuadroInputs"><input type="text" name="dni2" id="dni2"class="input_text_liga" onKeyPress="return tecla_dni(event)" onBlur="limpiadni('dni2',19)"  maxlength="9"></span>
         <?php 
         if($genero == 'A'){//si es liga mixta
            echo '<span class="cuadroInputs">'.generos2("F","genero2").'</span>';
         }
		 else{//si es por genero
            echo '<span class="cuadroInputs">'.generos2($genero,"genero2").'</span>';
         }
         ?>
         </form>
    </div>
    <div id="jug2_col3" class="columna3">
        <div class="cuadroComentario"><span id="nombre2Com">*</span></div>
        <div class="cuadroComentario"><span id="apellidos2Com">*</span></div>
        <div class="cuadroComentario"><span id="direccion2Com">&nbsp;</span></div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario">&nbsp;</div>
        <div class="cuadroComentario"><span id="pais2Com">*</span></div>
        <div class="cuadroComentario"><span id="provincia2Com">*</span></div>
        <div class="cuadroComentario"><span id="ciudad2Com">*</span></div>
        <div class="cuadroComentario"><span id="telefono2Com">*</span></div>
        <div class="cuadroComentario"><span id="email2Com">*</span></div>
        <div class="cuadroComentario"><span id="password2Com">*</span></div>
        <div class="cuadroComentario"><span id="dni2Com">&nbsp;</span></div>
        <?php 
         if($genero == 'A'){//si es liga mixta
            echo '<div class="cuadroComentario"><span id="genero2Com">*</span></div>';
         }
         ?>
    </div>
</div><!-- FIN DIV JUGADOR 2 -->
<div class="horizontal"><input type="button" id="btn_enviar" value="Inscribir" class="boton" /></div>
<div id="respuesta" class="horizontal"></div>
<?php
}//fin de if de inscripciones
else{//SI ES LIGA GRATIS NO PUEDE INSERTAR
	echo '<style>.cuadroError{color:#34495e; font-family:Arial; font-size:80%; width:50%; border:1px solid #34495e;	background-color:#c5fbc6; padding:1%; border-radius:5px; margin:0 auto;margin-top:1%;}</style>';
	echo '<div class="cuadroError">'.utf8_encode('Esta divisi&oacute;n ya est&aacute; completa.').'</div>';
}
?>
</body>
</html>