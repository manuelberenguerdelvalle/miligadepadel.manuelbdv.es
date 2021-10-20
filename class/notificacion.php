<?php
class Notificacion{
	//atributos
	protected $id_notificacion = '';
	protected $usuario = '';
	protected $liga = '';
	protected $division = '';
	protected $seccion = '';
	protected $fecha = '';
	protected $leido = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_notificacion,$usuario,$liga,$division,$seccion,$fecha,$leido,$direccion,$tipo){
		if($id_notificacion != '' && $id_notificacion != NULL && $usuario == ''){
			$db = new MySQL('session');//usuario PADEL
			$consulta = $db->consulta("SELECT * FROM notificacion WHERE id_notificacion = '$id_notificacion'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_notificacion = $resultados['id_notificacion'];
				$this->usuario = $resultados['usuario'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->seccion = $resultados['seccion'];
				$this->fecha = $resultados['fecha'];
				$this->leido = $resultados['leido'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_notificacion = $id_notificacion;
			$this->usuario = $usuario;
			$this->liga = $liga;
			$this->division = $division;
			$this->seccion = $seccion;
			$this->fecha = $fecha;
			$this->leido = $leido;
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
		$db = new MySQL('session');//usuario PADEL
		$db->consulta("INSERT INTO `notificacion` (`id_notificacion`,`usuario`,`liga`,`division`,`seccion`,`fecha`,`leido`) VALUES (NULL,'$this->usuario','$this->liga','$this->division','$this->seccion','$this->fecha','$this->leido');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//usuario PADEL
		$db->consulta("UPDATE `notificacion` SET `usuario`='$this->usuario',`liga`='$this->liga',`division`='$this->division',`seccion`='$this->seccion',`fecha`='$this->fecha',`leido`='$this->leido' WHERE `id_notificacion` = '$this->id_notificacion'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//usuario PADEL
		$db->consulta("DELETE FROM `notificacion` WHERE `id_notificacion` = '$this->id_notificacion'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($seccion,$fecha,$email,$division,$password,$liga,$cuenta_paypal,$direccion,$leido,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>