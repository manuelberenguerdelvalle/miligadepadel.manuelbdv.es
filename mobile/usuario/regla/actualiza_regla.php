<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_fechasHoras.php");
include_once ("../../../class/regla.php");
include_once ("../../../class/noticia.php");
header("Content-Type: text/html;charset=ISO-8859-1");
?>
<style>
.actualizacion {
	border-radius:10px;
	background-color:#c5fbc6;
	text-align:center;
	font-size:80%;
	padding:12px;
	margin-left:15%;
	color:#006;
}
.actualizacion img{
	width:10%;
	margin-top:1%;
	margin-right:1%;
}
</style>
<link rel="stylesheet" type="text/css" href="../../css/respuesta.css" />
<?php
session_start();
$pagina = $_SESSION['pagina'];
$id_liga = $_SESSION['id_liga'];
$id_division = $_SESSION['id_division'];
$opcion = $_SESSION['opcion'];
if ( $pagina != 'gestion_regla' || $opcion != 0 ){
	header ("Location: ../cerrar_sesion.php");
}
else {
	$reglas = limpiaTexto2($_POST['reglas']);
	$texto = 'Las reglas se han actualizado correctamente.';
	$descripcion_noticia = '';
	$id_regla = obten_consultaUnCampo('session','id_regla','regla','liga',$id_liga,'','','','','','','');
	if($id_regla == 0 || $id_regla == NULL || $id_regla == ''){//SI NO EXISTE LA REGLA SE CREA NUEVA
		if($reglas == '--Introduce aqui las reglas--' || $reglas == ''){//NO CONTIENE NADA NO LO CREO
			$texto = 'Las reglas han sido desactivadas correctamente.';
		}
		else{//INSERTO
			$regla = new Regla(NULL,$id_liga,utf8_decode($reglas),obten_fechaHora());
			$regla->insertar();
			$descripcion_noticia .= utf8_decode('Se han creado las reglas de la liga correctamente.');
		}
	}
	else{//SI YA EXISTE MODIFICAMOS
		$regla = new Regla('',$id_liga,'','');
		if($reglas == '--Introduce aquí las reglas--' || $reglas == ''){//NO CONTIENE NADA LO PONGO A VACIO
			$texto = 'Las reglas han sido desactivadas correctamente.';
			$reglas = 'vacio';
			$regla->setValor('texto',$reglas);
			$regla->setValor('fecha',obten_fechaHora());
			$regla->modificar();
			$descripcion_noticia .= utf8_decode('Las reglas de la liga se han desactivado.');
		}
		else{//MODIFICO
			$regla->setValor('texto',utf8_decode($reglas));
			$regla->setValor('fecha',obten_fechaHora());
			$regla->modificar();
			$descripcion_noticia .= utf8_decode('Las reglas de la liga se han modificado.');
		}
	}
	if($descripcion_noticia != ''){
		$resumen_noticia = utf8_decode('Sección: Reglas -> Ver/Modificar.');
		$fecha_noticia = obten_fechahora();
		$noticia = new Noticia(NULL,$id_liga,$id_division,$resumen_noticia,$descripcion_noticia,$fecha_noticia,'');
		$noticia->insertar();
		unset($noticia);
	}
	echo '<span class="actualizacion"><img src="../../../images/ok.png" />'.utf8_decode($texto).'</span>';
	unset($regla);
}//fin else

?>