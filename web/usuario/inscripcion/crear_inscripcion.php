<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/division.php");
include_once ("../../../class/noticia.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/notificacion.php");
include_once ("../../../class/jugador.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/puntos.php");
include_once ("../../../class/puntuacion.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style type="text/css">
.actualizacion {
	border-radius:7px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:15%;
	color:#006;
}
.actualizacion img{
	width:2%;
}
</style>
<?php
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
$id_liga = $_SESSION['id_liga'];
$tipo_pago = $_SESSION['tipo_pago'];
$division = unserialize($_SESSION['division']);
$id_division = $division->getValor('id_division');
$precio = $division->getValor('precio');
$comienzo = $division->getValor('comienzo');
$suscripcion = $division->getValor('suscripcion');
$id_usuario = $_SESSION['id_usuario'];
if($_SESSION['cuenta_paypal'] != ''){$email_ins = $_SESSION['cuenta_paypal'];}
else{$email_ins = $_SESSION['email'];}
$bd = $_SESSION['bd'];
if ( $pagina != 'gestion_inscripcion' || $opcion != 1 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	include_once ("../../funciones/f_recoger_post.php");//NECESITA F_GENERAL
	$texto = 'La Inscripción se ha realizado correctamente.';
	$descripcion_noticia = '';
	$id_jugador1 = 0;
	$id_jugador2 = 0;
	if($nombre1_rapido != '' && $nombre1 == ''){//JUGADOR RAPIDO1
		$dni1 = 0;
		$nombre1 = ucwords($nombre1_rapido);
		$apellidos1 = ucwords($apellidos1_rapido);
		$password1 = '';
		$direccion1 = '';
		$fec_nac1 = date('Y-m-d');
		$zona_juego1 = 'A';
		$ciudad = 0;
		$provincia = 0;
		$pais = 0;
		$telefono1 = 0;
		$email1 = 'rapido1_'.obten_fechahora();
		$genero1 = 'R';
	}
	else{//JUGADOR INSERTADO
		$jugador_encontrado = buscar_jugador('','',$email1,'','');
		//comprobamos el email que no este en jugadores
		if($jugador_encontrado > 0){//si existe el email cargamos con el id_jugador
			$id_jugador1 = $jugador_encontrado;
			$jugador1 = new Jugador($id_jugador1,'','','','','','','','','','','','','','','');
			$dni1 = $jugador1->getValor('dni');
			$nombre1 = $jugador1->getValor('nombre');
			$apellidos1 = $jugador1->getValor('apellidos');
			$password1 = $jugador1->getValor('password');
			$direccion1 = $jugador1->getValor('direccion');
			$fec_nac1 = $jugador1->getValor('fec_nac');
			$zona_juego1 = $jugador1->getValor('zona_juego');
			$ciudad = $jugador1->getValor('ciudad');
			$provincia = $jugador1->getValor('provincia');
			$pais = $jugador1->getValor('pais');
			$telefono1 = $jugador1->getValor('telefono');
			$email1 = $jugador1->getValor('email');
			$genero1 = $jugador1->getValor('genero');
			//echo 'seleccionado1';
		}
		else{//no encontrado, insertamos y monto la fecha nacimiento
			$nombre1 = ucwords($nombre1);
			$apellidos1 = ucwords($apellidos1);
			$fec_nac1 = $anyo1.'-'.$mes1.'-'.$dia1;
		}
	}//FIN ELSE JUGADOR1

	if($nombre2_rapido != '' && $nombre2 == ''){//JUGADOR RAPIDO2
		$dni2 = 0;
		$nombre2 = ucwords($nombre2_rapido);
		$apellidos2 = ucwords($apellidos2_rapido);
		$password2 = '';
		$direccion2 = '';
		$fec_nac2 = date('Y-m-d');
		$ciudad2 = 0;
		$provincia2 = 0;
		$pais2 = 0;
		$telefono2 = 0;
		$email2 = 'rapido2_'.obten_fechahora();
		$genero2 = 'R';
	}
	else{//JUGADOR INSERTADO
		$jugador_encontrado = buscar_jugador('','',$email2,'','');
		//comprobamos el email que no este en jugadores
		if($jugador_encontrado > 0){//si existe el email cargamos con el id_jugador
			$id_jugador2 = $jugador_encontrado;
			$jugador2 = new Jugador($jugador_encontrado,'','','','','','','','','','','','','','','');
			$id_jugador2 = $jugador2->getValor('id_jugador');
			$dni2 = $jugador2->getValor('dni');
			$nombre2 = $jugador2->getValor('nombre');
			$apellidos2 = $jugador2->getValor('apellidos');
			$password2 = $jugador2->getValor('password');
			$direccion2 = $jugador2->getValor('direccion');
			$fec_nac2 = $jugador2->getValor('fec_nac');
			$ciudad2 = $jugador2->getValor('ciudad');
			$provincia2 = $jugador2->getValor('provincia');
			$pais2 = $jugador2->getValor('pais');
			$telefono2 = $jugador2->getValor('telefono');
			$email2 = $jugador2->getValor('email');
			$genero2 = $jugador2->getValor('genero');
		}
		else{//no encontrado, insertamos y monto la fecha nacimiento
			$nombre2 = ucwords($nombre2);
			$apellidos2 = ucwords($apellidos2);
			$fec_nac2 = $anyo2.'-'.$mes2.'-'.$dia2;
		}
	}//FIN ELSE JUGADOR INSERTADO
	//verificar obligatorios nombre apellidos email
	if(!empty($nombre1) && !empty($nombre2) && !empty($apellidos1) && !empty($apellidos2) && !empty($email1) && !empty($email2)){//si entra aqui todo bien
		if($id_jugador1 > 0){$jug1_enc = busca_jugadorEnOtroEquipo($id_liga,$id_jugador1);}//si existe es porque el jugador1 va por login
		if($id_jugador2 > 0){$jug2_enc = busca_jugadorEnOtroEquipo($id_liga,$id_jugador2);}//si existe es porque el jugador2 va por login
		if($jug1_enc == '' && $jug2_enc == ''){//si estan vacios, jugador nuevo o no está registrado en la liga
			if($id_jugador1 == 0 && $nombre1_rapido == ''){//si existe es buscado o encontrado NO INSERTO
					$jugador1 =  new Jugador(NULL,$dni1,$nombre1,$apellidos1,$password1,$direccion1,$fec_nac1,$zona_juego1,$ciudad,$provincia,$pais,$telefono1,$email1,$genero1,0,'A');
					$jugador1->insertar();
					$id_jugador1 = obten_consultaUnCampo('unicas','id_jugador','jugador','nombre',ucwords($nombre1),'apellidos',ucwords($apellidos1),'email',$email1,'','','');
			}
			if($id_jugador2 == 0 && $nombre2_rapido == ''){//si existe es buscado o encontrado NO INSERTO
					$jugador2 =  new Jugador(NULL,$dni2,$nombre2,$apellidos2,$password2,$direccion2,$fec_nac2,$zona_juego2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2,0,'A');
					$jugador2->insertar();
					$id_jugador2 = obten_consultaUnCampo('unicas','id_jugador','jugador','nombre',ucwords($nombre2),'apellidos',ucwords($apellidos2),'email',$email2,'','','');
			}
			$inscripcion = new Inscripcion(NULL,$id_division,$id_liga,'M',$precio,'S',$id_jugador1,$dni1,$nombre1,$apellidos1,$password1,$direccion1,$fec_nac1,$ciudad,$provincia,$pais,$telefono1,$email1,$genero1,$id_jugador2,$dni2,$nombre2,$apellidos2,$password2,$direccion2,$fec_nac2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2);
			$inscripcion->insertar();//inserto inscripcion
			$seguro1 = NULL;
			$seguro2 = NULL;
			if($nombre1_rapido != ''){//PARA LOS EQUIPOS RAPIDOS, LOS ID_JUGADORES ES 0 Y EL SEGURO ES EL ID_INSCRIPCION
				$id_inscripcion = obten_consultaUnCampo('session','id_inscripcion','inscripcion','liga',$id_liga,'division',$id_division,'email1',$email1,'','','');
				$seguro1 = $id_inscripcion;
			}
			if($nombre2_rapido != ''){//PARA LOS EQUIPOS RAPIDOS, LOS ID_JUGADORES ES 0 Y EL SEGURO ES EL ID_INSCRIPCION
				$id_inscripcion = obten_consultaUnCampo('session','id_inscripcion','inscripcion','liga',$id_liga,'division',$id_division,'email2',$email2,'','','');
				$seguro2 = $id_inscripcion;
			}
			//PONER LOS SEGUROS
			$equipo = new Equipo(NULL,$id_jugador1,$seguro1,$id_jugador2,$seguro2,$id_liga,$id_division,'S',0,obten_fechaHora());///AÑADIDO CAMPO FEC_CREACION
			$equipo->insertar();//inserto equipo
			$notificacion = new Notificacion('',$id_usuario,$id_liga,$id_division,'modificar_inscripcion.php',date('Y-m-d H:i:s'),'N');
			$notificacion->insertar();
			//cambiar por tipo pago
			if($tipo_pago > 0){//si no es gratis
				if($nombre1_rapido == '' && $nombre2_rapido != ''){$email_usuario = $email1;}
				else if($nombre1_rapido != '' && $nombre2_rapido == ''){$email_usuario = $email2;}
				else{$email_usuario = $email1;}
                $id_equipo = obten_consultaUnCampo('session','id_equipo','equipo','liga',$id_liga,'division',$id_division,'jugador1',$id_jugador1,'jugador2',$id_jugador2,'');
				$pago_admin = new Pago_admin(NULL,$id_liga,$id_division,$bd,$id_equipo,$precio,'M','S',$email_ins,$id_usuario,$email_usuario,obten_fechaHora(),'','','',$id_jugador1,$id_jugador2,'R');
				$pago_admin->insertar();//inserto el pago
			}
			//SI NO HAY FECHA DE SUSCRIPCION, ACTIVO LA SUSCRIPCION
			if($suscripcion == '0000-00-00 00:00:00' || empty($suscripcion)){
				$division->setValor('suscripcion',obten_fechaHora());
				$division->modificar();
			}
			$id_puntuacion = obten_consultaUnCampo('session','id_puntuacion','puntuacion','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'aplicacion','L','');
			if($id_puntuacion > 0){//si a insertado en algun momento en puntuaciones
				//solo es posible insertar/eliminar puntuaciones al actualizar partido si es partido de grupo, o si es la final
				$puntuacion = new Puntuacion($id_puntuacion,'','','','','','','','','','','','','','','','','');
				$tipo = 0;
				$tipo_puntuacion = 'inscripcion';
				$id_partido = 0;
				$hay_puntos1 = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$id_jugador1,'');
				$hay_puntos2 = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$id_jugador2,'');
				if($id_jugador1 > 0 && $hay_puntos1 == 0 && $puntuacion->getValor($tipo_puntuacion) > 0){//si no es temporal
					//insertamos si no hay puntos
					$puntosj1 = new Puntos('',$id_usuario,$id_jugador1,$bd,$id_liga,$id_division,$id_partido,obten_fechahora(),$puntuacion->getValor($tipo_puntuacion),$tipo);
					$puntosj1->insertar();
				}//fin j1
				if($id_jugador2 > 0 && $hay_puntos2 == 0 && $puntuacion->getValor($tipo_puntuacion) > 0){//si no es temporal
					//insertamos si no hay puntos
					$puntosj2 = new Puntos('',$id_usuario,$id_jugador2,$bd,$id_liga,$id_division,$id_partido,obten_fechahora(),$puntuacion->getValor($tipo_puntuacion),$tipo);
					$puntosj2->insertar();
				}//fin j2
				unset($puntosj1,$puntosj2);
			}//fin de puntuacion
			$descripcion_noticia .= utf8_decode('El administrador ha inscrito un nuevo equipo compuesto por '.$nombre1.' '.substr($apellidos1,0,1).'. y '.$nombre2.' '.substr($apellidos2,0,1).'.');
		}
		else{//si jugador1 o 2 estan en otro equipo de la misma liga
			$texto = 'Error de inscripción, el jugador ';
			if($jug1_enc != ''){$texto .= '1 ya está registrado en esta liga.';}
			else{$texto .= '2 ya está registrado en esta liga.';}
		}
	}//fin if campos obligatorios
	if(!empty($descripcion_noticia)){
		$resumen_noticia = utf8_decode('Sección: Inscripción -> Crear Nueva.');
		$fecha_noticia = obten_fechahora();
		$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,$fecha_noticia,'');
		$noticia->insertar();
		unset($noticia);
	}
	unset($inscripcion,$equipo,$pago_admin,$notificacion);
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
}

?>