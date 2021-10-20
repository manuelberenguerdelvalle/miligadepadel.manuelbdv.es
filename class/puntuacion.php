<?php
class Puntuacion{
	//atributos
	protected $id_puntuacion = '';
    protected $usuario = '';
	protected $liga = '';
	protected $division = '';
	protected $aplicacion = ''; 
	protected $partido = '';
	protected $inscripcion = '';
	protected $victoria_amistoso = '';
	protected $victoria = '';
	protected $dieciseisavos = '';
	protected $octavos = '';
	protected $cuartos = ''; 
	protected $semifynal = '';
	protected $fynal = '';
	protected $primero = '';
	protected $segundo = '';
	protected $tercero = '';
	protected $cuarto = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_puntuacion,$usuario,$liga,$division,$aplicacion,$partido,$inscripcion,$victoria_amistoso,$victoria,$dieciseisavos,$octavos,$cuartos,$semifynal,$fynal,$primero,$segundo,$tercero,$cuarto){
		if(!empty($id_puntuacion) && $usuario == '' && $liga == '' && $division == ''){//busco por id_puntuacion
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM puntuacion WHERE id_puntuacion = '$id_puntuacion';");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_puntuacion = $resultados['id_puntuacion'];
				$this->usuario = $resultados['usuario'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->aplicacion = $resultados['aplicacion']; 
				$this->partido = $resultados['partido'];
				$this->inscripcion = $resultados['inscripcion'];
				$this->victoria_amistoso = $resultados['victoria_amistoso'];
				$this->victoria = $resultados['victoria'];
				$this->dieciseisavos = $resultados['dieciseisavos'];
				$this->octavos = $resultados['octavos'];
				$this->cuartos = $resultados['cuartos']; 
				$this->semifynal = $resultados['semifynal'];
				$this->fynal = $resultados['fynal'];
				$this->primero = $resultados['primero'];
				$this->segundo = $resultados['segundo'];
				$this->tercero = $resultados['tercero'];
				$this->cuarto = $resultados['cuarto'];
			}
			$db->cerrar_conexion();
		}
		else if($id_puntuacion == '' && $usuario != '' && $liga != '' && $division != '' && $aplicacion != ''){//busco por liga,usuario,inscripcion,id_jugador2
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM puntuacion WHERE usuario = '$usuario' AND liga = '$liga' AND division = '$division' AND aplicacion = '$aplicacion' ;");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_puntuacion = $resultados['id_puntuacion'];
				$this->usuario = $resultados['usuario'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->aplicacion = $resultados['aplicacion']; 
				$this->partido = $resultados['partido'];
				$this->inscripcion = $resultados['inscripcion'];
				$this->victoria_amistoso = $resultados['victoria_amistoso'];
				$this->victoria = $resultados['victoria'];
				$this->dieciseisavos = $resultados['dieciseisavos'];
				$this->octavos = $resultados['octavos'];
				$this->cuartos = $resultados['cuartos']; 
				$this->semifynal = $resultados['semifynal'];
				$this->fynal = $resultados['fynal'];
				$this->primero = $resultados['primero'];
				$this->segundo = $resultados['segundo'];
				$this->tercero = $resultados['tercero'];
				$this->cuarto = $resultados['cuarto'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_puntuacion = $id_puntuacion;
			$this->usuario = $usuario;
			$this->liga = $liga;
			$this->division = $division;
			$this->aplicacion = $aplicacion; 
			$this->partido = $partido;
			$this->inscripcion = $inscripcion;
			$this->victoria_amistoso = $victoria_amistoso;
			$this->victoria = $victoria;
			$this->dieciseisavos = $dieciseisavos;
			$this->octavos = $octavos;
			$this->cuartos = $cuartos; 
			$this->semifynal = $semifynal;
			$this->fynal = $fynal;
			$this->primero = $primero;
			$this->segundo = $segundo;
			$this->tercero = $tercero;
			$this->cuarto = $cuarto;
		}  
		
	}
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
		$db->consulta("INSERT INTO `puntuacion` (`id_puntuacion`,`usuario`,`liga`,`division`,`aplicacion`,`partido`,`inscripcion`,`victoria_amistoso`,`victoria`,`dieciseisavos`,`octavos`,`cuartos`,`semifynal`,`fynal`,`primero`,`segundo`,`tercero`,`cuarto`) VALUES (NULL,'$this->usuario','$this->liga','$this->division','$this->aplicacion','$this->partido','$this->inscripcion','$this->victoria_amistoso','$this->victoria','$this->dieciseisavos','$this->octavos','$this->cuartos','$this->semifynal','$this->fynal','$this->primero','$this->segundo','$this->tercero','$this->cuarto');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `puntuacion` SET `usuario`='$this->usuario',`liga`='$this->liga',`division`='$this->division',`aplicacion`='$this->aplicacion',`partido`='$this->partido',`inscripcion`='$this->inscripcion',`victoria_amistoso`='$this->victoria_amistoso',`victoria`='$this->victoria',`dieciseisavos`='$this->dieciseisavos',`octavos`='$this->octavos',`cuartos`='$this->cuartos',`semifynal`='$this->semifynal',`fynal`='$this->fynal',`primero`='$this->primero',`segundo`='$this->segundo',`tercero`='$this->tercero',`cuarto`='$this->cuarto' WHERE `id_puntuacion` = '$this->id_puntuacion'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `puntuacion` WHERE `id_puntuacion` = '$this->id_puntuacion'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($usuario,$apellidos,$email,$telefono,$cuartosword,$dni,$cuenta_paypal,$direccion,$cp,$partido,$aplicacion,$division,$fec_registro,$primero){
	}*/
}

?>