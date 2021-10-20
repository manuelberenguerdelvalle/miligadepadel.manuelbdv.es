<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/liga.php");
include_once ("../../../class/division.php");
include_once ("../../../class/usuario.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/seguro.php");
session_start();
$pagina = $_SESSION['pagina'];
if($pagina != 'gestion_inscripcion'){
	header ("Location: ../cerrar_sesion.php");
}
$opcion = $_SESSION['opcion'];
$liga = unserialize($_SESSION['liga']);
$id_liga = $liga->getValor('id_liga');
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$usuario = unserialize($_SESSION['usuario']);
//NECESARIO PARA LA PAGINA actualiza_inscripcion.php
$_SESSION['nombre'] = $liga->getValor('nombre');
$_SESSION['tipo_pago'] = $liga->getValor('tipo_pago');
$_SESSION['num_division'] = $division->getValor('num_division');
$_SESSION['id_division'] = $division->getValor('id_division');
$_SESSION['id_liga'] = $liga->getValor('id_liga');
$_SESSION['id_usuario'] = $usuario->getValor('id_usuario');
$num_inscripciones = obten_consultaUnCampo('session','COUNT(id_inscripcion)','inscripcion','liga',$id_liga,'division',$id_division,'','','','','');
if($opcion == 0 && $num_inscripciones > 0){//modificacion
	$alerta_inscrip_rec = obten_consultaUnCampo('session','COUNT(id_notificacion)','notificacion','liga',$id_liga,'division',$id_division,'seccion','modificar_inscripcion.php','leido','N','');
	if($alerta_inscrip_rec > 0){
		realiza_updateGeneral('session','notificacion','leido="S"','liga',$id_liga,'division',$id_division,'seccion','modificar_inscripcion.php','','','','','');
	}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="ISO-8859-1" />
<!--<meta charset="UTF-8">-->
<title>www.mitorneodepadel.es</title>
<link rel="stylesheet" type="text/css" href="../../css/estilo_info_texto.css" />
<link rel="stylesheet" type="text/css" href="css/modificar_inscripcion.css" />
<link rel="stylesheet" type="text/css" href="../../../sweetalert-master/lib/sweet-alert.css" />
<script src="../../javascript/info_texto.js" type="text/javascript"></script>
<script src="javascript/modificar_inscripcion.js" type="text/javascript"></script>
<script src="../../javascript/jquery-1.11.1.js" type="text/javascript"></script>
<script src="../../../sweetalert-master/lib/sweet-alert.min.js" type="text/javascript"></script>
</head>
<body>
<div class="cont_principal">
<?php
if(isset($_SESSION['mensaje_pago'])){
	echo '<div class="caja_pago"><img src="../../../images/ok.png" /><label>'.utf8_decode($_SESSION['mensaje_pago']).'</label></div>';
	unset($_SESSION['mensaje_pago']);
}
if(isset($_SESSION['mensaje_pago_error'])){
	echo '<div class="caja_pago"><img src="../../../images/error.png" /><label>'.utf8_decode($_SESSION['mensaje_pago_error']).'</label></div>';
	unset($_SESSION['mensaje_pago_error']);
}
?>
	<div class="horizontal">&nbsp;</div>
	<div class="horizontal"><div class="titulo"><b>Ver/Modificar Inscripciones (<?php echo $num_inscripciones; ?> parejas en total)</b></div></div>
<?php
$db = new MySQL('session');//LIGA PADEL
$consulta = $db->consulta("SELECT * FROM inscripcion WHERE liga = '$id_liga' AND division = '$id_division'; ");
while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
	if($resultados['id_jugador1'] > 0 || $resultados['id_jugador2'] > 0){
		$equipo = new Equipo('',$resultados['id_jugador1'],'',$resultados['id_jugador2'],'',$resultados['liga'],$resultados['division'],'','','');
	}
?>
	<div class="caja">
        	<div id="flotante"></div>
            <div class="jugador1">
                <?php 
					if($resultados['id_jugador1'] > 0 && $equipo->getValor('seguro_jug1') > 0){
						$seguro = new Seguro($equipo->getValor('seguro_jug1'),'','','','','');
				?>
						<div class="seguro" onMouseOver="showdiv(event,'- Seguro: <?php echo $seguro->getValor('licencia');?> - Validez: <?php echo datepicker_fecha($seguro->getValor('fecha_caducidad'));?> <br>- Federacion: <?php echo $seguro->getValor('federacion');?> <br>- Categoria: <?php echo $seguro->getValor('categoria');?>.');" onMouseOut="hiddenDiv()" style="display:table;">+
                <?php
					}
					else{
						echo '<div class="noseguro">&nbsp;';
					}
				?>
                </div>
                <div class="nombre"><?php echo substr($resultados['nombre1'].' '.$resultados['apellidos1'],0,35);?></div> 
                <div class="edad">
                <?php 
					if($resultados['id_jugador1'] == 0){echo '&nbsp;';} 
					else{echo 'Edad: '.obten_edad($resultados['fec_nac1']);}
				?>
                </div>
                <div class="email">
                <?php
					if($resultados['id_jugador1'] == 0){echo '&nbsp;';}  
					else{echo $resultados['email1'];}
				?>
                </div>
    <?php if($resultados['id_jugador1'] > 0 && obten_consultaUnCampo('unicas','creacion','jugador','id_jugador',$resultados['id_jugador1'],'','','','','','','') == 'A'){ ?>
                <div class="password">
                <?php echo 'Pass: '.$resultados['password1'];?>
                </div>
    <?php } ?>
                <div class="telefono">
                    <?php 
                    if($resultados['telefono1'] != 0 && !empty($resultados['telefono1']) ){
                        echo $resultados['telefono1'];
                    }
                    else{echo ' ';}
                    ?>
                </div>
            </div>
            <div class="jugador2">
                <?php 
					if($resultados['id_jugador2'] > 0 && $equipo->getValor('seguro_jug2') > 0){
						$seguro2 = new Seguro($equipo->getValor('seguro_jug2'),'','','','','');
				?>
						<div class="seguro" onMouseOver="showdiv(event,'Seguro: <?php echo $seguro2->getValor('licencia');?> - Validez: <?php echo $seguro2->getValor('fecha_caducidad');?> - Federacion: <?php echo $seguro2->getValor('federacion');?> - Categoria: <?php echo $seguro2->getValor('categoria');?>.');" onMouseOut="hiddenDiv()" style="display:table;">+
                <?php
					}
					else{
						echo '<div class="noseguro">&nbsp;';
					}
				?>
                </div>
                <div class="nombre"><?php echo substr($resultados['nombre2'].' '.$resultados['apellidos2'],0,35);?></div>
                <div class="edad">
                <?php
					if($resultados['id_jugador2'] == 0){echo '&nbsp;';}  
					else{echo 'Edad: '.obten_edad($resultados['fec_nac2']);}
				?>
                </div>
                <div class="email">
                <?php
					if($resultados['id_jugador2'] == 0){echo '&nbsp;';}  
					else{echo $resultados['email2'];}
				?>
                </div>
    <?php if($resultados['id_jugador2'] > 0 && obten_consultaUnCampo('unicas','creacion','jugador','id_jugador',$resultados['id_jugador2'],'','','','','','','') == 'A'){ ?>
                <div class="password">
                <?php echo 'Pass: '.$resultados['password2'];?>
                </div>
    <?php } ?>
                <div class="telefono">
                    <?php
                    if($resultados['telefono2'] != 0 && !empty($resultados['telefono2']) ){ 
                        echo $resultados['telefono2'];
                    }
                    else{echo ' ';}
                    ?>
                </div>
                <div class="inscripcion">
                <?php echo 'Inscripcion: ';
                if($resultados['pago'] == 'M'){echo 'Manual';}
                else{echo 'Online';}
                ?>
                </div>
                <div class="inscripcion2">
                <?php echo 'Pagado: ';
				//REVISAR LOS DAS LIMITES EN LOS QUE HAY INFORMACION Y CONSULTAR O NO
                if($resultados['pagado'] == 'S'){
					echo 'Si';
					/*
					NO ES POSIBLE COMPROBAR PORQUE CADA CUENTA PAYPAL TIENE SU IDENTIFICADOR DE PAGADOR
					if($resultados['pago'] == 'P'){//si pago paypal compruebo status
						$pago_admin = new Pago_admin('',$resultados['liga'],$resultados['division'],$_SESSION['bd'],$equipo->getValor('id_equipo'),'','','','','','','','','','','','','');
						if(strtotime(date('Y-m-d H:i:s')) < (strtotime($pago_admin->getValor('fecha')) + pasar_segundos(3)) ){//si estamos dentro de los 3 das que tenemos informacin
							$res = paypal_PDT_request($pago_admin->getValor('transaccion'),obten_identPaypal());
							$datos_rec = array();
							$datos_rec = obten_arrayResPaypal($res);
							echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[24]))).')';
							//if($resultados['tarjeta'] != ''){//pago con tarjeta
								//echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[24]))).')';
							//}
							//else{//pago con cuenta
								//echo ' ('.obten_estadoPago(strtolower(obten_resDespuesIgual($datos_rec[25]))).')';
							//}
						}//fin if dias
						else{echo ' (Completado)';}
					}//fin if pago paypal
					else{echo ' (Completado)';}*/
				}
                else{
					echo 'No';
				}
                ?>
                </div>
            </div><!-- fin jugador 2-->
            <div class="subcaja1">
                <div class="opciones">
                    <?php 
						if($resultados['id_jugador1'] == 0 && $resultados['id_jugador2'] == 0){
							echo '&nbsp;';
						}
						else{//no es rapida
					?>
							<img name="<?php echo 'inscripcion'.$resultados['id_inscripcion']; ?>" onClick="javascript: enviar_email_jugador('<?php echo $resultados['id_inscripcion']; ?>')" class="imagen" src="../../../images/email.png">
					<?php 
						}//fin else no es rapida
					?>
                <!--</div>
                <div class="opciones">-->
                        <?php
                        //echo '<input type="button" onClick="javascript: eliminar_inscripcion_tarjeta('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
                        if($division->getValor('comienzo') == 'N'){//SI LA DIVISION NO HA COMENZADO PUEDO ELIMINAR
                            if($resultados['pagado'] == 'S' && $resultados['precio'] > 0 && $resultados['pago'] == 'P'){//ES DE PAGO Y ESTA PAGADA (NO HAGO NADA)
                                //if($pago_admin->getValor('transaccion') != ''){//pago realizado online
                                    /*$descrip_pago = 'Devolucin de Inscripcin: en la liga '.$_SESSION['nombre'].' divisin '.$_SESSION['num_division'];
                                    IMPOSIBLE CONTEMPLAR LA DEVOLUCION YA QUE EL JUGADOR NO VA A TENER CONFIGURADA EL RETORNO AUTOMATICO, POR LO TANTO MOSTRAMOS MENSAJE QUE LA DEVOLUCION LA TIENE QUE REALIZAR A TRAVES DE PAYPAL
                                    muestra_formulario_sinboton($descrip_pago,'DEV-'.$pago_admin->getValor('id_pago_admin'),$resultados['precio'],'http://www.mitorneodepadel.es/web/usuario/inscripcion/gestion_inscripcion.php','http://www.mitorneodepadel.es/web/ep/pa.php','manu_oamuf-facilitator@hotmail.com'); 
                                    //$pago_admin->getValor('emisor') receptor de la devolucion
                                    echo '<input type="submit" value="Eliminar" class="boton" /></form>';*/
                                    //INSERTAR TRANSACCION DE DEVOLUCION
                                    //echo '<input type="button" onClick="javascript: eliminar_inscripcion_tarjeta('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
                                //}
                                //else{//pago presencial
                                    //NO PERMITO ELIMINAR UNA PAGADA ONLINE PARA EVITAR ESTAFAS
                                    //echo '<input type="button" onClick="javascript: eliminar_inscripcion('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
                                //}
                                /*else{//pago con tarjeta, insertar transaccion
                                    echo '<input type="button" onClick="javascript: eliminar_inscripcion_tarjeta('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
                                }*/
                                echo '<input type="button" onClick="javascript: solicitar_eliminacion('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
                            }//fin if pagado == s, precio>0, pagado==P
							else if($resultados['pagado'] == 'S' && $resultados['precio'] > 0 && $resultados['pago'] == 'M'){//INSCRIPCION RAPIDA DEJO ELIMINAR
								echo '<input type="button" onClick="javascript: eliminar_inscripcion('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
							}
                            else{//O ES GRATIS O ES DE PAGO SIN PAGAR
                                if($resultados['pagado'] == 'N'){
                                //inserto marcar pagado
                                    echo '<input type="button" onClick="javascript: pagar_inscripcion('.$resultados["id_inscripcion"].');" value="Pagar" class="boton" />';
                                }
                                echo '<input type="button" onClick="javascript: eliminar_inscripcion('.$resultados["id_inscripcion"].');" value="Eliminar" class="boton" />';
                        ?>
                            <!--<input type="button" onClick="javascript: eliminar_inscripcion(<?php //echo $resultados['id_inscripcion']; ?>);" value="Eliminar" class="boton" />-->
                  <?php 
                            }//fin if condiciones
                        }//fin if comienzo
                ?>
            	</div>
            </div>
	</div><!-- fin caja-->
<?php
	unset($equipo);
}//fin del while
?>
</div>
</body>
</html>
<?php
}//fin de if opcion tipo numInscripciones
?>