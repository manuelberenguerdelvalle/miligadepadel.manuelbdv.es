<?php
include_once ("../../funciones/f_html.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_inputs.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
session_start();
$pagina = $_SESSION['pagina'];
if ($pagina != 'index' && $pagina != 'inscribir_equipo'){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
else{
	$_SESSION['pagina']  = 'inscribir_equipo';
}
$id_liga = limpiaTexto3($_SESSION["id_liga"]);
$id_division = limpiaTexto3($_SESSION["id_division"]);
$pass = limpiaTexto3($_SESSION["pass"]);
$tipo_pago = limpiaTexto3($_SESSION["tipo_pago"]);
$precio = limpiaTexto3($_SESSION["precio"]);
$genero_liga = limpiaTexto3($_SESSION["genero"]);
$nombre = limpiaTexto3($_SESSION["nombre"]);
$num_division = limpiaTexto3($_SESSION["num_division"]);
$usuario = limpiaTexto3($_SESSION["usuario"]);
$fec_captur = date('Y-m-d H:i:s');
$recibir_pago = obten_consultaUnCampo('unicas_liga','recibir_pago','usuario','id_usuario',$usuario,'','','','','','','');
if($recibir_pago != 'M'){//recibe pagos online o ambos
	$cuenta_paypal = obten_consultaUnCampo('unicas_liga','cuenta_paypal','usuario','id_usuario',$usuario,'','','','','','','');
}
//unset($_SESSION['id_jugador1'],$_SESSION['id_jugador2']);
cabecera_inicio();
//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
incluir_general(1,1);//(jquery,validaciones) 0=no activo, 1=activo
?>
<link rel="stylesheet" type="text/css" href="css/inscribir_equipo.css" />
<link rel="stylesheet" type="text/css" href="../../css/bpopup.css" />
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../javascript/localizacion.js" type="text/javascript"></script>
<script src="../../javascript/localizacion2.js" type="text/javascript"></script>
<script src="../../javascript/jquery.bpopup.min.js" type="text/javascript"></script>
<script src="javascript/inscribir_equipo.js" type="text/javascript"></script>

<?php
cabecera_fin();
?>
<input type="hidden" id="inicial" name="inicial" value="<?php echo $recibir_pago; ?>" />
<div class="principal">
	<!-- POPUP -->
	<div id="content_popup">
    	<div class="poptitulo"><h2>Condiciones y Pol&iacute;tica de datos</h2></div>
        <div class="popcentro">
        	<?php echo condiciones_generales(); ?>
        </div>
        <div class="poppie">
        	<span class="button b-close"><span>ENTENDIDO</span></span>
        </div>
	</div>
    <input type="hidden" id="tipo_pago" name="tipo_pago" value="<?php echo $tipo_pago;?>"  /><input type="hidden" id="fec_captur" value="<?php echo $fec_captur;?>"  /><input type="hidden" id="precio" name="precio" value="<?php echo $precio;?>"  />
    <!-- FIN POPUP -->
	<!--<div class="izquierdo">&nbsp;</div>-->
    <div class="contenido">
    	<div class="horizontal"><a href="http://miligadepadel.manuelbdv.es"><span class="botonAtras">ATRAS</span></a>
        <?php
			
			if($recibir_pago == 'A' && $precio > 0){//recibe pagos ambos
				$_SESSION['recibir_pago'] = 'M';
				echo '<div class="modo_pago">Modo de Pago:';
				echo '<select name="recibir_pago" id="recibir_pago" class="inputText" onChange="comprueba_pago(this);">';
				echo '<option selected value="M">Presencial</option>';
				echo '<option value="O">Online (PayPal)</option>';
				echo '</select></div>';
			}
			else{$_SESSION['recibir_pago'] = $recibir_pago;}
		?>
        </div>
    	<div class="caja_jugador">
            <div class="horizontal">&nbsp;</div>
            <div class="horizontal">
				<?php 
				if($genero_liga == 'A'){echo 'JUGADOR 1 (Masculino)';} 
				else if($genero_liga == 'F'){echo 'JUGADORA 1';}
				else{echo 'JUGADOR 1';}  
				?>
            </div>
            <div class="horizontal">
                <div class="opcion"><input type="radio" class="radio" name="jugador1" id="jugador1" value="buscar1" checked  onchange="comprueba(this)"><b>Login</b></div>
                <div class="opcion"><input type="radio" class="radio" name="jugador1" id="jugador1" value="insertar1" onchange="comprueba(this)"><b>Nuevo</b></div>
            </div>
            <div id="jug1_col1" class="columna1">
                <div class="cuadroTexto" ><b>Nombre:</b></div>
                <div class="cuadroTexto" ><b>Apellidos:</b></div>
                <!-- AQUI VA LA CONTRASEÑA PERO INSERCIÓN MANUAL NO TIENE -->
                <div class="cuadroTexto" >Direcci&oacute;n:</div>
                <div class="cuadroTexto" ><b>Fecha Nac:</b></div><!-- Si no es de pago se bloquea-->
                <div class="cuadroTexto"><b>Zona de Juego:</b></div>
                <div class="cuadroTexto" ><b>Pa&iacute;s:</b></div>
                <div class="cuadroTexto" ><b>Provincia:</b></div>
                <div class="cuadroTexto" ><b>Ciudad:</b></div>
                <div class="cuadroTexto" >Tel&eacute;fono:</div>
                <div class="cuadroTexto" ><b>E-mail:</b></div>
                <div class="cuadroTexto" ><b>Contrase&ntilde;a:</b></div>
                <div class="cuadroTexto" >Nif:</div>
                <div class="cuadroTexto">G&eacute;nero:</div>
            </div>
            <div id="jug1_col2" class="columna2">
                <span><form id="formulario1" action="#" method="post" name="formulario1"></span>
                <span class="cuadroInputs"><input type="text" name="nombre1" id="nombre1" class="input_text_liga" onkeypress="return soloLetras(event)" onblur="limpiaLetras('nombre1',0)" maxlength="20"></span>
                <span class="cuadroInputs"><input type="text" name="apellidos1" id="apellidos1" class="input_text_liga" onkeypress="return soloLetras(event)" onblur="limpiaLetras('apellidos1',1)" maxlength="30"></span>
                <span class="cuadroInputs"><input type="text" name="direccion1" id="direccion1" class="input_text_liga"  onkeypress="return tecla_direccion(event)" onblur="limpiaDireccion('direccion1',2,0)" maxlength="50"></span>
                <span class="cuadroInputs"><?php dia(0,'dia1','inputText'); mes(0,'mes1','inputText'); anyo(0,'anyo1','inputText');?></span>
                <span class="cuadroInputs"><?php zona_juego("A","zona_juego1","inputText");?></span>
                <span class="cuadroInputs"><select name="pais" id="pais" class="input_select_liga" onchange="lista('pais',3)">
                            <option value="">--Elige--</option>
                            <?php
                            $db = new MySQL('unicas');
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
                 <span class="cuadroInputs"><select name="provincia" id="provincia" class="input_select_liga" onchange="lista('provincia',4)"></select>
                 </span>
                 <span class="cuadroInputs"><select name="ciudad" id="ciudad" class="inputText_ciudad" onchange="lista('ciudad',5)"></select>
                 </span>
                 <span class="cuadroInputs"><input type="text" name="telefono1" id="telefono1" class="input_text_liga" onkeypress="return numeros(event)" onblur="limpiaNumeros('telefono1',6,0)"  maxlength="9"></span>
                 <span class="cuadroInputs"><input type="text" name="email1" id="email1" class="input_text_liga" onkeypress="return tecla_email(event)" onblur="limpiaEmail('email1',7)" maxlength="50"></span>
                 <span class="cuadroInputs"><input type="text" name="password1" id="password1" class="input_text_liga" onkeypress="return tecla_password(event)" onblur="limpiaPassword('password1',8)" maxlength="15"></span>
                 <span class="cuadroInputs"><input type="text" name="dni1" id="dni1"class="input_text_liga" onkeypress="return tecla_dni(event)" onblur="limpiadni('dni1',9)"  maxlength="9"></span>
                 <?php 
                 if($genero_liga == 'A'){//si es liga mixta
                    echo '<span class="cuadroInputs">'.generos2("M","genero1").'</span>';
                 }
                 else{//si es por genero
                    echo '<span class="cuadroInputs">'.generos2($genero_liga,"genero1").'</span>';
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
                 if($genero_liga == 'A'){//si es liga mixta
                    echo '<div class="cuadroComentario"><span id="genero1Com">* Error.</span></div>';
                 }
                 ?>
            </div>
            <div id="div_bus1" class="cuadroBusqueda">
            	<div class="horizontal">Estoy registrado en miligadepadel.manuelbdv.es o mitorneodepadel.es</div>
                <div class="horizontal">&nbsp;</div>
            	<div class="cuadroBusquedaNombre">Email:</div>
            	<div class="cuadroBusquedaInput"><input type="text" name="l_email1" id="l_email1" class="input_text_liga" onkeypress="return tecla_email(event)" onblur="limpiaEmail('l_email1',21)" maxlength="50"></div>
                <div class="cuadroBusquedaComentario"><span id="l_email1Com">*</span></div>
                <div class="cuadroBusquedaNombre">Nombre:</div>
            	<div class="cuadroBusquedaInput"><input type="text" name="l_password1" id="l_password1" class="input_text_liga" onkeypress="return soloLetras(event)" onblur="limpiaLetras('l_password1',22)" maxlength="15"></div>
                <div class="cuadroBusquedaComentario"><span id="l_password1Com">*</span></div>
            </div>
            <div id="resultado1" class="cuadroResultado"></div>
        </div><!-- FIN DIV JUGADOR 1 -->
        <div class="caja_jugador">
            <div class="horizontal">&nbsp;</div>
            <div class="horizontal">
            	<?php 
				if($genero_liga == 'A'){echo 'JUGADORA 2 (Femenina)';} 
				else if($genero_liga == 'F'){echo 'JUGADORA 2';}
				else{echo 'JUGADOR 2';}  
				?>
            </div>
            <div class="horizontal">
                <div class="opcion"><input type="radio" class="radio" name="jugador2" value="buscar2" checked onchange="comprueba(this)"><b>Login</b></div>
                <div class="opcion"><input type="radio" class="radio" name="jugador2" value="insertar2" onchange="comprueba(this)"><b>Nuevo</b></div>
            </div>
            <div id="jug2_col1" class="columna1">
                <div class="cuadroTexto" ><b>Nombre:</b></div>
                <div class="cuadroTexto" ><b>Apellidos:</b></div>
                <!-- AQUI VA LA CONTRASEÑA PERO INSERCIÓN MANUAL NO TIENE -->
                <div class="cuadroTexto" >Direcci&oacute;n:</div>
                <div class="cuadroTexto" ><b>Fecha Nac:</b></div><!-- Si no es de pago se bloquea-->
                <div class="cuadroTexto"><b>Zona de Juego:</b></div>
                <div class="cuadroTexto" ><b>Pa&iacute;s:</b></div>
                <div class="cuadroTexto" ><b>Provincia:</b></div>
                <div class="cuadroTexto" ><b>Ciudad:</b></div>
                <div class="cuadroTexto" >Tel&eacute;fono:</div>
                <div class="cuadroTexto" ><b>E-mail:</b></div>
                <div class="cuadroTexto" ><b>Contrase&ntilde;a:</b></div>
                <div class="cuadroTexto" >Nif:</div>
                <div class="cuadroTexto">G&eacute;nero:</div>
            </div>
            <div id="jug2_col2" class="columna2">
                <span><form id="formulario2" action="#" method="post" name="formulario2"></span>
                <span class="cuadroInputs"><input type="text" name="nombre2" id="nombre2" class="input_text_liga" onkeypress="return soloLetras(event)" onblur="limpiaLetras('nombre2',10)" maxlength="20"></span>
                <span class="cuadroInputs"><input type="text" name="apellidos2" id="apellidos2" class="input_text_liga" onkeypress="return soloLetras(event)" onblur="limpiaLetras('apellidos2',11)" maxlength="30"></span>
                <span class="cuadroInputs"><input type="text" name="direccion2" id="direccion2" class="input_text_liga"  onkeypress="return tecla_direccion(event)" onblur="limpiaDireccion('direccion2',12,<?php echo $tipo_pago ?>)" maxlength="50"></span>
                <span class="cuadroInputs"><?php dia(0,'dia2','inputText'); mes(0,'mes2','inputText'); anyo(0,'anyo2','inputText');?></span>
                <span class="cuadroInputs"><?php zona_juego("A","zona_juego2","inputText");?></span>
                <span class="cuadroInputs"><select name="pais2" id="pais2" class="input_select_liga" onchange="lista('pais2',13)">
                            <option value="">--Elige--</option>
                            <?php
                            $db4 = new MySQL('unicas');
                            $consulta4 = $db4->consulta("SELECT Name,Code FROM paises WHERE Code='ESP' ");
                            if($consulta4->num_rows>0){
                              while($resultados4 = $consulta4->fetch_array(MYSQLI_ASSOC)){
                                 if($opcion == 0 && $pais == $resultados4['Code']){
                                    echo '<option selected value="'.$resultados4['Code'].'">'.$resultados4['Name'].'</option>';
                                 }
                                 else{
                                    echo '<option  value="'.$resultados4['Code'].'">'.$resultados4['Name'].'</option>';
                                 }
                              }
                            }
                         ?>
                        </select>
                 </span>
                 <span class="cuadroInputs"><select name="provincia2" id="provincia2" class="input_select_liga" onchange="lista('provincia2',14)"></select>
                 </span>
                 <span class="cuadroInputs"><select name="ciudad2" id="ciudad2" class="inputText_ciudad" onchange="lista('ciudad2',15)"></select>
                 </span>
                 <span class="cuadroInputs"><input type="text" name="telefono2" id="telefono2" class="input_text_liga" onkeypress="return numeros(event)" onblur="limpiaNumeros('telefono2',16,0)"  maxlength="9"></span>
                 <span class="cuadroInputs"><input type="text" name="email2" id="email2" class="input_text_liga" onkeypress="return tecla_email(event)" onblur="limpiaEmail('email2',17)" maxlength="50"></span>
                 <span class="cuadroInputs"><input type="text" name="password2" id="password2" class="input_text_liga" onkeypress="return tecla_password(event)" onblur="limpiaPassword('password2',18)" maxlength="15"></span>
                 <span class="cuadroInputs"><input type="text" name="dni2" id="dni2"class="input_text_liga" onkeypress="return tecla_dni(event)" onblur="limpiadni('dni2',19)"  maxlength="9"></span>
                 <?php 
                 if($genero_liga == 'A'){//si es liga mixta
                    echo '<span class="cuadroInputs">'.generos2("F","genero2").'</span>';
                 }
                 else{//si es por genero
                    echo '<span class="cuadroInputs">'.generos2($genero_liga,"genero2").'</span>';
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
                 if($genero_liga == 'A'){//si es liga mixta
                    echo '<div class="cuadroComentario"><span id="genero2Com">* Error.</span></div>';
                 }
                 ?>
            </div>
            <div id="div_bus2" class="cuadroBusqueda">
            	<div class="horizontal">Estoy registrado en miligadepadel.manuelbdv.es o mitorneodepadel.es</div>
                <div class="horizontal">&nbsp;</div>
            	<div class="cuadroBusquedaNombre">Email:</div>
            	<div class="cuadroBusquedaInput"><input type="text" name="l_email2" id="l_email2" class="input_text_liga" onkeypress="return tecla_email(event)" onblur="limpiaEmail('l_email2',23)" maxlength="50"></div>
                <div class="cuadroBusquedaComentario"><span id="email2Com">* Error.</span></div>
                <div class="cuadroBusquedaNombre">Nombre:</div>
            	<div class="cuadroBusquedaInput"><input type="text" name="l_password2" id="l_password2" class="input_text_liga" onkeypress="return soloLetras(event)" onblur="limpiaLetras('l_password2',24)" maxlength="15"></div>
                <div class="cuadroBusquedaComentario"><span id="password1Com">* Error.</span></div>
            </div>
            <div id="resultado2" class="cuadroResultado"></div>
        	</div><!-- FIN DIV JUGADOR 2 -->
            <div class="horizontal">&nbsp;</div>
            <div class="horizontal"><input type="checkbox" class="radio" name="condiciones"  id="btn_condiciones">&nbsp;Al hacer clic en Inscribir, Aceptas las Condiciones y <br />confirmas que has le&iacute;do nuestra Pol&iacute;tica de datos.</div>
            <div class="horizontal">&nbsp;</div>
        	<div class="horizontal">
				<?php
				if( ($recibir_pago == 'O' || $recibir_pago == 'A') && $precio > 0){
					$descrip_pago = 'Inscripcion en Liga: '.$nombre.' division: '.$num_division;
					$id_pago = 'Inscripcion -'.$usuario.'-'.$fec_captur;
					//PONER EMAIL DEL ADMIN NO EL MIO DE PAGOS
					//muestra_formulario_sinboton($descrip_pago,$id_pago,0.5,'http://miligadepadel.manuelbdv.es/mobile/usuario/registro/pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php',$cuenta_paypal);
					muestra_formulario_sinboton(utf8_encode($descrip_pago),$id_pago,$precio,'http://miligadepadel.manuelbdv.es/mobile/usuario/registro/pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php',$cuenta_paypal);
					//muestra_formulario_sinboton($descrip_pago,$id_pago,$precio,'http://miligadepadel.manuelbdv.es/mobile/usuario/registro/pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php',cuenta_admin());	
					//muestra_formulario_sinboton($descrip_pago,$id_pago,$precio,'http://miligadepadel.manuelbdv.es/mobile/usuario/registro/pago.php','http://miligadepadel.manuelbdv.es/mobile/ep/pa.php','manu_oamuf-facilitator@hotmail.com');
					
					
					//aqui va el administrador de la liga $_SESSION['usuario']
					//echo '<input id="btn_enviar" type="image" src="http://www.paypal.com/es_XC/i/btn/x-click-but01.gif" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet." >';
					echo '</form>';	
				}
				else{
					echo '<form id="form_paypal" action="http://miligadepadel.manuelbdv.es" method="post"></form>';
				}
				echo '<input type="button" id="btn_enviar" value="Inscribir" class="boton" />';
				?>
            </div>
        	<div id="respuesta" class="horizontal">
            <div id="actualizacion" class="actualizacion"><img id="imagenOk" src="../../../images/ok.png" /><img id="imagenError" src="../../../images/error.png" /><span id="actualizacionTexto">&nbsp;</span></div></div>
            <div class="horizontal">&nbsp;</div>
            <div class="horizontal">&nbsp;</div>
    	</div><!-- fin caja jugador
    </div><!--fin contenido-->
    <!--<div class="derecho">&nbsp;</div>-->
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>
