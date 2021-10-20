<?php
include_once("../../funciones/f_html.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once("../../funciones/f_email.php");
include_once("../../../class/mysql.php");
include_once("../../../class/liga.php");
include_once("../../../class/division.php");
include_once("../../../class/pago_web.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/inscripcion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();

if ( isset($_GET['id']) ){//SI EXISTE VERIFICACION POR ID
		$cadena = limpiaTexto(decodifica($_GET["id"]));
		$pos = strpos($cadena,'F');
		$id = substr($cadena,12,$pos-13);
		$respuesta = substr($cadena,$pos-1,1);
		if($respuesta == 'S' || $respuesta == 'N'){//si la respuesta es correcta
			$id_nt = obten_consultaUnCampo('unicas','id','eliminar_inscripcion','id',$id,'','','','','','','');
			if($id == $id_nt){
				$hay_respuesta = obten_consultaUnCampo('unicas','respuesta','eliminar_inscripcion','id',$id,'','','','','','','');
				$texto1 = 'Respuesta realizada Correctamente.';
				if($hay_respuesta == ''){//no hay respuesta
					realiza_updateGeneral('unicas','eliminar_inscripcion','respuesta = "'.$respuesta.'",fecha = "'.obten_fechahora().'"','id',$id,'','','','','','','','','');
					$texto2 = '- Su respuesta se ha completado correctamente. ';
					if($respuesta == 'S'){
							$texto3 = '- Su inscripci&oacute;n ha sido eliminada.';
							$_SESSION['bd'] = obten_consultaUnCampo('unicas','bd','eliminar_inscripcion','id',$id,'','','','','','','');
							$id_inscripcion = obten_consultaUnCampo('unicas','inscripcion','eliminar_inscripcion','id',$id,'','','','','','','');
							$inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
							$equipo = new Equipo('',$inscripcion->getValor('id_jugador1'),'',$inscripcion->getValor('id_jugador2'),'',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),'','','');
							$tipo_pago = obten_consultaUnCampo('session','tipo_pago','liga','id_liga',$inscripcion->getValor('liga'),'','','','','','','');
							if($tipo_pago > 0){//elimino el pago creado
								$pago_admin = new Pago_admin('',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),$_SESSION['bd'],$equipo->getValor('id_equipo'),'','','','','','','','','','','','','');
								$pago_admin->setValor('equipo',0); //si esta a 0, es eliminado
								$pago_admin->setValor('estado','D'); //si esta a D, es eliminado
								$pago_admin->modificar();
							}
							$inscripcion->borrar();
							$equipo->borrar();
					}
					else{//no continua
							$texto3 = '- Vuelva a contactar con el administrador para recibir su devoluci&oacute;n.';
					}
				}//fin if
				else{//sin hay respuesta
					$texto2 = '- Su respuesta ya ha sido contestada anteriormente';
					$texto3 = '- Contacte con su compa&ntilde;ero de equipo.';
				}
				$imagen = '<img src="../../../images/ok.png" />';
			}
		}//fin respuesta
		else{
			$texto1 = 'Respuesta err&oacute;nea.';
			$texto2 = '- Ha ocurrido un error.';
			$texto3 = '- Vuelva a intentarlo de nuevo, y si el error continua contacte con nosotros.';
			$imagen = '<img src="../../../images/error.png" />';
		}
}//fin if get id
cabecera_inicio();
incluir_general(0,0);
?>
<link rel="stylesheet" type="text/css" href="valida_registro.css" />
<script language="javascript" type="text/javascript">
setTimeout ("document.location.href='http://miligadepadel.manuelbdv.es';", 25000);
</script>
<?php
cabecera_fin();
?>
<div class="principal">
	<!--<div class="izquierdo">&nbsp;</div>-->
    <div class="contenido">
    	<div class="paso">
        	<div class="atras">
            	<a href="http://miligadepadel.manuelbdv.es"><span class="botonAtras">INICIO</span></a>
            </div>
        	<div class="num_pasos">&nbsp;</div>
            <div class="num_pasos">&nbsp;</div>
            <div class="traductor"><div id="google_translate_element"></div></div>
        </div>
        <div class="okImg"> <?php echo $imagen; ?></div>
        <div class="okText"><?php echo $texto1; ?></div>
        <div class="okText2"><?php echo $texto2; ?></div>
        <div class="okText2"><?php echo $texto3; ?></div>
        <div class="cuadro">&nbsp;</div>
    </div>
    <!--<div class="derecho">&nbsp;</div>-->
<?php
pie();
?>
</div>
<?php
cuerpo_fin();
?>