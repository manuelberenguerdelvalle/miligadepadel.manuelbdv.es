<?php
//AÑADIR CAMPO MODIFICADO = id_jugador que ha modificado el partido y si es 0.= administrador
class Partido{
	//atributos
	protected $id_partido = '';
	protected $jornada = '';
	protected $fecha = '';
	protected $hora = '';
	protected $local = '';
	protected $visitante = '';
	protected $set1_local = '';
	protected $set2_local = '';
	protected $set3_local = '';
	protected $set4_local = '';
	protected $set5_local = '';
	protected $set1_visitante = '';
	protected $set2_visitante = '';
	protected $set3_visitante = '';
	protected $set4_visitante = '';
	protected $set5_visitante = '';
	protected $ganador = '';
	protected $tiebreak = '';
	protected $estado = '';
	protected $pista = '';
	protected $division = '';
	protected $arbitro_principal = '';
	protected $arbitro_auxiliar = '';
	protected $arbitro_adjunto = '';
	protected $arbitro_silla = '';
	protected $arbitro_ayudante = '';
	protected $modificado = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_partido,$jornada,$fecha,$hora,$local,$visitante,$set1_local,$set2_local,$set3_local,$set4_local,$set5_local,$set1_visitante,$set2_visitante,$set3_visitante,$set4_visitante,$set5_visitante,$ganador,$tiebreak,$estado,$pista,$division,$arbitro_principal,$arbitro_auxiliar,$arbitro_adjunto,$arbitro_silla,$arbitro_ayudante,$modificado){
		if($id_partido != '' && $local == '' && $visitante == '' && $division == ''){//si recibimos solo id_partido
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM partido WHERE id_partido = '$id_partido'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_premio = $resultados['id_premio'];
				$this->id_partido = $resultados['id_partido'];
				$this->jornada = $resultados['jornada'];
				$this->fecha = $resultados['fecha'];
				$this->hora = $resultados['hora'];
				$this->local = $resultados['local'];
				$this->visitante = $resultados['visitante'];
				$this->set1_local = $resultados['set1_local'];
				$this->set2_local = $resultados['set2_local'];
				$this->set3_local = $resultados['set3_local'];
				$this->set4_local = $resultados['set4_local'];
				$this->set5_local = $resultados['set5_local'];
				$this->set1_visitante = $resultados['set1_visitante'];
				$this->set2_visitante = $resultados['set2_visitante'];
				$this->set3_visitante = $resultados['set3_visitante'];
				$this->set4_visitante = $resultados['set4_visitante'];
				$this->set5_visitante = $resultados['set5_visitante'];
				$this->ganador = $resultados['ganador'];
				$this->tiebreak = $resultados['tiebreak'];
				$this->estado = $resultados['estado'];
				$this->pista = $resultados['pista'];
				$this->division = $resultados['division'];
				$this->arbitro_principal = $resultados['arbitro_principal'];
				$this->arbitro_auxiliar = $resultados['arbitro_auxiliar'];
				$this->arbitro_adjunto = $resultados['arbitro_adjunto'];
				$this->arbitro_silla = $resultados['arbitro_silla'];
				$this->arbitro_ayudante = $resultados['arbitro_ayudante'];
				$this->modificado = $resultados['modificado'];
			}
			$db->cerrar_conexion();
		}
		/*else if( ($local != '' || $visitante != '') && $jornada != '' && $division != '' && $id_partido == '' && $estado == ''){//si recibimos equipo (buscar en local y visitante) division y jornada
			$db = new MySQL();
			$consulta = $db->consulta("SELECT * FROM partido WHERE division = '$division' AND jornada = '$jornada' AND ( local = '$local' OR visitante = '$visitante' ); ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_premio = $resultados['id_premio'];
				$this->id_partido = $resultados['id_partido'];
				$this->jornada = $resultados['jornada'];
				$this->fecha = $resultados['fecha'];
				$this->hora = $resultados['hora'];
				$this->local = $resultados['local'];
				$this->visitante = $resultados['visitante'];
				$this->set1_local = $resultados['set1_local'];
				$this->set2_local = $resultados['set2_local'];
				$this->set3_local = $resultados['set3_local'];
				$this->set4_local = $resultados['set4_local'];
				$this->set5_local = $resultados['set5_local'];
				$this->set1_visitante = $resultados['set1_visitante'];
				$this->set2_visitante = $resultados['set2_visitante'];
				$this->set3_visitante = $resultados['set3_visitante'];
				$this->set4_visitante = $resultados['set4_visitante'];
				$this->set5_visitante = $resultados['set5_visitante'];
				$this->ganador = $resultados['ganador'];
				$this->tiebreak = $resultados['tiebreak'];
				$this->estado = $resultados['estado'];
				$this->pista = $resultados['pista'];
				$this->division = $resultados['division'];
				$this->arbitro_principal = $resultados['arbitro_principal'];
				$this->arbitro_auxiliar = $resultados['arbitro_auxiliar'];
				$this->arbitro_adjunto = $resultados['arbitro_adjunto'];
				$this->arbitro_silla = $resultados['arbitro_silla'];
				$this->arbitro_ayudante = $resultados['arbitro_ayudante'];
			}
			$db->cerrar_conexion();
		}*/
		else{
			$this->id_partido = $id_partido;
			$this->jornada = $jornada;
			$this->fecha = $fecha;
			$this->hora = $hora;
			$this->local = $local;
			$this->visitante = $visitante;
			$this->set1_local = $set1_local;
			$this->set2_local = $set2_local;
			$this->set3_local = $set3_local;
			$this->set4_local = $set4_local;
			$this->set5_local = $set5_local;
			$this->set1_visitante = $set1_visitante;
			$this->set2_visitante = $set2_visitante;
			$this->set3_visitante = $set3_visitante;
			$this->set4_visitante = $set4_visitante;
			$this->set5_visitante = $set5_visitante;
			$this->ganador = $ganador;
			$this->tiebreak = $tiebreak;
			$this->estado = $estado;
			$this->pista = $pista;
			$this->division = $division;
			$this->arbitro_principal = $arbitro_principal;
			$this->arbitro_auxiliar = $arbitro_auxiliar;
			$this->arbitro_adjunto = $arbitro_adjunto;
			$this->arbitro_silla = $arbitro_silla;
			$this->arbitro_ayudante = $arbitro_ayudante;
			$this->modificado = $modificado;
		}
	}
	//-------------------------------
    //destructor de la clase
	//---------------------------------
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
	//----------------------------------------
    // Metodos de la clase
	//-----------------------------------------
    public function getValor($atributo){//retornar algún valor
    	return $this->$atributo;
    }
	public function setValor($atributo,$newValor){//cambiar algún valor
    	$this->$atributo = $newValor;
    }
	public function insertar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("INSERT INTO `partido` (`id_partido`,`jornada`,`fecha`,`hora`,`local`,`visitante`,`set1_local`,`set2_local`,`set3_local`,`set4_local`,`set5_local`,`set1_visitante`,`set2_visitante`,`set3_visitante`,`set4_visitante`,`set5_visitante`,`ganador`,`tiebreak`,`estado`,`pista`,`division`,`arbitro_principal`,`arbitro_auxiliar`,`arbitro_adjunto`,`arbitro_silla`,`arbitro_ayudante`,`modificado`) VALUES (NULL,'$this->jornada','$this->fecha','$this->hora','$this->local','$this->visitante','$this->set1_local','$this->set2_local','$this->set3_local','$this->set4_local','$this->set5_local','$this->set1_visitante','$this->set2_visitante','$this->set3_visitante','$this->set4_visitante','$this->set5_visitante','$this->ganador','$this->tiebreak','$this->estado','$this->pista','$this->division','$this->arbitro_principal','$this->arbitro_auxiliar','$this->arbitro_adjunto','$this->arbitro_silla','$this->arbitro_ayudante','$this->modificado'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE  `partido` SET  `jornada` = '$this->jornada',`fecha` = '$this->fecha',`hora` = '$this->hora',`local` = '$this->local',`visitante` = '$this->visitante',`set1_local` = '$this->set1_local',`set2_local` = '$this->set2_local',`set3_local` = '$this->set3_local',`set4_local` = '$this->set4_local',`set5_local` = '$this->set5_local',`set1_visitante` = '$this->set1_visitante',`set2_visitante` = '$this->set2_visitante',`set3_visitante` = '$this->set3_visitante',`set4_visitante` = '$this->set4_visitante',`set5_visitante` = '$this->set5_visitante',`ganador` = '$this->ganador',`tiebreak` = '$this->tiebreak',`estado` = '$this->estado',`pista` = '$this->pista',`division` = '$this->division',`arbitro_principal` = '$this->arbitro_principal',`arbitro_auxiliar` = '$this->arbitro_auxiliar',`arbitro_adjunto` = '$this->arbitro_adjunto',`arbitro_silla` = '$this->arbitro_silla',`arbitro_ayudante` = '$this->arbitro_ayudante',`modificado` = '$this->modificado' WHERE `partido`.`id_partido` = '$this->id_partido'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `partido` WHERE `id_partido` = '$this->id_partido'; ");
		$db->cerrar_conexion();
	}
	
}

?>