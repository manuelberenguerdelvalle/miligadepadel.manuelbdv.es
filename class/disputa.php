<?php
class Disputa{
	//atributos
	protected $id_disputa = '';
	protected $division = '';
	protected $partido = '';
	protected $fecha = '';
	protected $jugador = '';
	protected $respuesta = '';
	protected $texto = '';
	
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_disputa,$division,$partido,$fecha,$jugador,$respuesta,$texto){
		if($id_disputa != '' && $division == '' && $partido == ''){
			$db = new MySQL('session');
			$consulta = $db->consulta("SELECT * FROM disputa WHERE id_disputa = '$id_disputa'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_disputa = $resultados['id_disputa'];
				$this->division = $resultados['division'];
				$this->partido = $resultados['partido'];
				$this->fecha = $resultados['fecha'];
				$this->jugador = $resultados['jugador'];
				$this->respuesta = $resultados['respuesta'];
				$this->texto = $resultados['texto'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_disputa = $id_disputa;
			$this->division = $division;
			$this->partido = $partido;
			$this->fecha = $fecha;
			$this->jugador = $jugador;
			$this->respuesta = $respuesta;
			$this->texto = $texto;
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
		$db = new MySQL('session');//division PADEL
		$db->consulta("INSERT INTO `disputa` (`id_disputa`,`division`,`partido`,`fecha`,`jugador`,`respuesta`,`texto`) VALUES (NULL,'$this->division','$this->partido','$this->fecha','$this->jugador','$this->respuesta','$this->texto');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//division PADEL
		$db->consulta("UPDATE `disputa` SET `division`='$this->division',`partido`='$this->partido',`fecha`='$this->fecha',`jugador`='$this->jugador',`respuesta`='$this->respuesta',`texto`='$this->texto' WHERE `disputa`.`id_disputa` = '$this->id_disputa'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//division PADEL
		$db->consulta("DELETE FROM `disputa` WHERE `id_disputa` = '$this->id_disputa'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($jugador,$respuesta,$email,$fecha,$password,$partido,$cuenta_paypal,$,$texto,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>