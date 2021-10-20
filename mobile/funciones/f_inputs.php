<?php
session_start();
//crear_hora
//mysql
function autocompletado($autocompletado){
	echo '<select name="auto_completar" id="auto_completar" class="input_select_liga">';
	if($autocompletado == 'S'){
		echo '<option selected="selected" value="S">S&iacute;</option>';
		echo '<option value="N">No</option>';
	}
	else if($autocompletado == 'N'){
		echo '<option value="S">S&iacute;</option>';
		echo '<option selected="selected" value="N">No</option>';
	}
	else{
		echo '<option selected="selected" value="S">S&iacute;</option>';
		echo '<option value="N">No</option>';
	}
    echo '</select>';
}

function generos($genero){
	if($genero == 'M'){
		echo '<option selected="selected" value="M">Masculino</option>';
		echo '<option value="F">Femenino</option>';
		echo '<option value="A">Mixtos</option>';
	}
	else if($genero == 'F'){
		echo '<option value="M">Masculino</option>';
		echo '<option selected="selected" value="F">Femenino</option>';
		echo '<option value="A">Mixtos</option>';
	}
	else if($genero == 'A'){
		echo '<option value="M">Masculino</option>';
		echo '<option value="F">Femenino</option>';
		echo '<option selected="selected" value="A">Mixtos</option>';
	}
	else{
		echo '<option selected="selected" value="M">Masculino</option>';
		echo '<option value="F">Femenino</option>';
		echo '<option value="A">Mixtos</option>';
	}
}
function generos2($genero,$id){//para el jugador, por lo que mixto se elimina
	echo '<select name="'.$id.'" id="'.$id.'" class="input_select_liga">';
	if($genero == 'M'){
		echo '<option selected="selected" value="M">Masculino</option>';
	}
	else{
		echo '<option selected="selected" value="F">Femenino</option>';
	}
    echo '</select>';
}

function vista($vista){
	echo '<select name="vista" id="vista" class="input_select_liga">';
	if($vista == 0){//publica
		echo '<option selected="selected" value="0">P&uacute;blica</option>';
		echo '<option value="1">Privada</option>';
	}
	else if($vista == 1){//privada
		echo '<option value="0">P&uacute;blica</option>';
		echo '<option selected="selected" value="1">Privada</option>';
	}
	else{
		echo '<option selected="selected" value="0">P&uacute;blica</option>';
		echo '<option value="1">Privada</option>';
	}
    echo '</select>';
}

function tipo_pago($tipo_liga){
	if($tipo_liga == 0){//gratis
		echo '<option selected="selected" value="0">8 Equipos max - (Gratis)</option>';
		echo '<option value="1">20 Equipos/Divisi&oacute;n max - (30 &euro;)</option>';
		/*echo '<option value="1">10 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option value="2">15 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">20 Equipos/Divisi&oacute;n - (50 &euro;)</option>';*/
	}
	else if($tipo_liga == 1){//10 equipos
		echo '<option value="0">8 Equipos max - (Gratis)</option>';
		echo '<option selected="selected" value="1">20 Equipos/Divisi&oacute;n max - (30 &euro;)</option>';
		/*echo '<option value="2">15 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">20 Equipos/Divisi&oacute;n - (50 &euro;)</option>';*/
	}/*
	else if($tipo_liga == 2){//15 equipos
		echo '<option value="0">8 Equipos - (Gratis)</option>';
		echo '<option value="1">10 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option selected="selected" value="2">15 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">20 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}
	else if($tipo_liga == 3){//25 equipos
		echo '<option value="0">8 Equipos - (Gratis)</option>';
		echo '<option value="1">10 Equipos/Divisi&oacute;n - (30 &euro;)</option>';
		echo '<option value="2">15 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option selected="selected" value="3">20 Equipos/Divisi&oacute;n - (50 &euro;)</option>';
	}*/
	else{
		echo '<option value="0">8 Equipos max - (Gratis)</option>';
		echo '<option selected="selected" value="1">20 Equipos/Divisi&oacute;n max - (30 &euro;)</option>';
		/*echo '<option value="2">15 Equipos/Divisi&oacute;n - (40 &euro;)</option>';
		echo '<option value="3">20 Equipos/Divisi&oacute;n - (50 &euro;)</option>';*/
	}
}

function idayvuelta($idayvuelta){
	if($idayvuelta == 'S'){
		echo '<option selected="selected" value="S">S&iacute;</option>';
		echo '<option value="N">No</option>';
	}
	else if($idayvuelta == 'N'){
		echo '<option value="S">S&iacute;</option>';
		echo '<option selected="selected" value="N">No</option>';
	}
	else{
		echo '<option value="S">S&iacute;</option>';
		echo '<option selected="selected" value="N">No</option>';
	}
}

function movimientos($movimientos,$max){
	if($max == 0){$max = 4;}//no hay maximo
	echo '<select name="movimientos" id="movimientos" class="input_select_liga">';
	for($i=0; $i<=$max; $i++){
		if($movimientos == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
    echo '</select>';
}
function duracion_partido($duracion){
	echo '<select name="duracion_partido" id="duracion_partido" class="input_select_liga">';
	for($i=1; $i<4; $i=$i+0.5){
		if($i == 1){$texto = ' hora';}
		else{$texto = ' horas';}
		if($duracion == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.$texto.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.$texto.'</option>';
		}
	}
    echo '</select>';
}

function sets($sets){
	echo '<select name="sets" id="sets" class="input_select_liga">';
	for($i=3; $i<6; $i+=2){
		if($sets == $i){
			echo '<option selected="selected" value="'.$i.'">Al mejor de '.$i.' sets</option>';
		}
		else{
			echo '<option value="'.$i.'">Al mejor de '.$i.' sets</option>';
		}
	}
    echo '</select>';
}
function select_horas($name,$id,$horario){
	echo '<select name="'.$name.'" id="'.$id.'" onchange="color_hora(this)" class="input_select_liga">';
	if($horario == 'M'){$i = 8;$f = 15;}
	else{$i = 15;$f = 24;}
	if(substr($name,0,5) == 'desde'){
		echo '<option value="">Desde</option>';
		for(; $i<$f; $i=$i+0.5){
			echo '<option value="'.$i.'">'.crear_hora($i).'</option>';
		}
	}
	else{
		echo '<option value="">Hasta</option>';
		$i++;
		for(; $i<=$f; $i=$i+0.5){
			echo '<option value="'.$i.'">'.crear_hora($i).'</option>';
		}
	}
    echo '</select>';
}

function select_horas2($datos,$hora){
	echo '<select name="'.$datos.'" id="'.$datos.'" class="input_select_liga">';
	if($hora == '00:00:00' || $hora == '' || $hora == NULL){
		echo '<option value="" selected="selected">--Sin Hora--</option>';
	}
	else{
		echo '<option value="">--Sin Hora--</option>';
	}
	for($i=8; $i<24; $i=$i+0.5){
		$temp = crear_hora($i);
		$completa = $temp.':00';
		if($hora == $completa){
			echo '<option selected="selected" value="'.$i.'">'.$temp.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$temp.'</option>';
		}
	}
    echo '</select>';
}

function check_pistas($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_pista,nombre FROM pista WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		echo '<span class="cuadroInputs"><input type="checkbox" class="radio" name="id_pistas" value="'.$resultados['id_pista'].'" />'.substr($resultados['nombre'],0,18).'</span>';
	}
}
function select_pistas($id_liga,$campo,$pista){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	if($pista == 0 || $hora == '' || $hora == NULL){
		echo '<option value="" selected="selected">--Sin Pista--</option>';
	}
	else{
		echo '<option value="">--Sin Pista--</option>';
	}
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_pista,nombre FROM pista WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['id_pista'] == $pista){
			echo '<option selected="selected" value="'.$resultados["id_pista"].'">'.$resultados["nombre"].'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_pista"].'">'.$resultados["nombre"].'</option>';
		}
	}
    echo '</select>';
}

function check_arbitros($id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_arbitro,nombre,apellidos FROM arbitro WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		$nom_completo = $resultados['nombre'].' '.$resultados['apellidos'];
		echo '<span class="cuadroInputs"><input type="checkbox" class="radio" name="id_arbitros" value="'.$resultados['id_arbitro'].'" />'.substr($nom_completo,0,15).'</span>';
	}
}
function select_arbitros($id_liga,$campo,$arbitro){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	if($arbitro == NULL || $arbitro == 0){
		echo '<option selected="selected" value="">'.ucwords(str_replace('_',' ',$campo)).'</option>';//sustituir _ por espacio	
	}
	else{
		echo '<option value="">'.ucwords(str_replace('_',' ',$campo)).'</option>';//sustituir _ por espacio
	}
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_arbitro,nombre,apellidos FROM arbitro WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		//PERMITIR 15 CARACTERES ENTRE NOMBRE Y APELLIDOS
		if($resultados['id_arbitro'] == $arbitro){
			echo '<option selected="selected" value="'.$resultados["id_arbitro"].'">'.substr($resultados["nombre"].' '.$resultados["apellidos"],0,15).'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_arbitro"].'">'.$resultados["nombre"].' '.substr($resultados["apellidos"],0,5).'</option>';
		}
	}
    echo '</select>';
}
function select_arbitros2($id_liga,$campo,$arbitro){//ESTE ES PARA MOSTRAR EL CALENDARIO
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga2">';
	if($arbitro == NULL || $arbitro == 0){
		echo '<option selected="selected" value="">'.ucwords(str_replace('_',' ',$campo)).'</option>';//sustituir _ por espacio	
	}
	else{
		echo '<option value="">'.ucwords(str_replace('_',' ',$campo)).'</option>';//sustituir _ por espacio
	}
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_arbitro,nombre,apellidos FROM arbitro WHERE liga = '$id_liga'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		//PERMITIR 15 CARACTERES ENTRE NOMBRE Y APELLIDOS
		if($resultados['id_arbitro'] == $arbitro){
			echo '<option selected="selected" value="'.$resultados["id_arbitro"].'">'.substr($resultados["nombre"].' '.$resultados["apellidos"],0,15).'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_arbitro"].'">'.$resultados["nombre"].' '.substr($resultados["apellidos"],0,10).'</option>';
		}
	}
    echo '</select>';
}
function tipo_arbitros($tipo){
	$array_tipos = array('Principal','Auxiliar','Adjunto','Silla','Ayudante');
	echo '<select name="tipo" id="tipo" class="input_select_liga">';
	for($i=0; $i<5; $i++){
		if($tipo == $i){
			echo '<option selected="selected" value="'.$i.'">'.$array_tipos[$i].'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$array_tipos[$i].'</option>';
		}
	}
    echo '</select>';
}
function resultados($resultado,$campo){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	for($i=0; $i<8; $i++){
		if($resultado == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
    echo '</select>';
}
function sanciones($max_partidos,$campo){//solo se podrá expulsar n partidos activos -1, porque si no sería expulsión
	if($max_partidos == 0){
		echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga" disabled="disabled">';
	}
	else{//
		echo '<select name="'.$campo.'" id="'.$campo.'" class="input_select_liga">';
	}//fin else
	for($i=0; $i<$max_partidos; $i++){
		if($i == 0){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
}
function dia($resultado,$campo,$class){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="'.$class.'">';
	for($i=1; $i<=31; $i++){
		if($resultado == $i){
			echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
    echo '</select>';
}
function mes($resultado,$campo,$class){
	$meses = array('Mes','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	echo '<select name="'.$campo.'" id="'.$campo.'" class="'.$class.'">';
	for($i=1; $i<=12; $i++){
		if($resultado == $i){
			echo '<option selected="selected" value="'.$i.'">'.$meses[$i].'</option>';
		}
		else{
			echo '<option value="'.$i.'">'.$meses[$i].'</option>';
		}
	}
    echo '</select>';
}
function anyo($resultado,$campo,$class){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="'.$class.'">';
	$inicio = date('Y')-18;
	$fin = date('Y')-100;
	for(; $inicio>=$fin; $inicio--){
		if($inicio == $resultado){
			echo '<option selected="selected" value="'.$inicio.'">'.$inicio.'</option>';
		}
		/*else if($inicio == 1970){
			echo '<option selected="selected" value="'.$inicio.'">'.$inicio.'</option>';
		}*/
		else{
			echo '<option value="'.$inicio.'">'.$inicio.'</option>';
		}
	}
    echo '</select>';
}
function zona_juego($resultado,$campo,$clase){
	echo '<select name="'.$campo.'" id="'.$campo.'" class="'.$clase.'">';
	if($resultado == 'D'){//derecha
		echo '<option selected="selected" value="D">Derecha</option>';
		echo '<option value="I">Izquierda</option>';
		echo '<option value="A">Ambas</option>';
	}
	else if($resultado == 'I'){//izquierda
		echo '<option value="D">Derecha</option>';
		echo '<option selected="selected" value="I">Izquierda</option>';
		echo '<option value="A">Ambas</option>';
	}
	else{//ambas
		echo '<option value="D">Derecha</option>';
		echo '<option value="I">Izquierda</option>';
		echo '<option selected="selected" value="A">Ambas</option>';
	}
    echo '</select>';
}
function desplegable_liga($id_usuario,$id_liga){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_liga,nombre FROM liga WHERE usuario = '$id_usuario' AND bloqueo = 'N'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['id_liga'] == $id_liga){
			echo '<option selected="selected" value="'.$resultados["id_liga"].'">'.$resultados["nombre"].'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_liga"].'">'.$resultados["nombre"].'</option>';
		}
	}
}

function desplegable_division($id_liga,$id_division){
	$db = new MySQL('session');//LIGA PADEL
	$consulta = $db->consulta("SELECT id_division,num_division FROM division WHERE liga = '$id_liga' AND bloqueo = 'N'; ");
	while($resultados = $consulta->fetch_array(MYSQLI_ASSOC)){
		if($resultados['id_division'] == $id_division){
			echo '<option selected="selected" value="'.$resultados["id_division"].'">'.$resultados["num_division"].'</option>';
		}
		else{
			echo '<option value="'.$resultados["id_division"].'">'.$resultados["num_division"].'</option>';
		}
	}
}
?>