<?php
class Premio{
	//atributos
	protected $id_premio = '';
	protected $division = '';
	protected $primero = '';
	protected $segundo = ''; 
	protected $tercero = '';
	protected $cuarto = '';
	protected $quinto = '';
	protected $todos = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_premio,$division,$primero,$segundo,$tercero,$cuarto,$quinto,$todos){
		if($division != '' && $id_premio == '' && $primero == '' && $segundo == '' && $tercero == '' && $cuarto == '' && $quinto == '' && $todos == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM premio WHERE division = '$division'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_premio = $resultados['id_premio'];
				$this->division = $resultados['division'];
				$this->primero = $resultados['primero'];
				$this->segundo = $resultados['segundo']; 
				$this->tercero = $resultados['tercero'];
				$this->cuarto = $resultados['cuarto'];
				$this->quinto = $resultados['quinto'];
				$this->todos = $resultados['todos'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_premio = $id_premio;
			$this->division = $division;
			$this->primero = $primero;
			$this->segundo = $segundo; 
			$this->tercero = $tercero;
			$this->cuarto = $cuarto;
			$this->quinto = $quinto;
			$this->todos = $todos;
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
		$db->consulta("INSERT INTO `premio` (`id_premio`,`division`,`primero`,`segundo`,`tercero`,`cuarto`,`quinto`,`todos`) VALUES (NULL,'$this->division','$this->primero','$this->segundo','$this->tercero','$this->cuarto','$this->quinto','$this->todos');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `premio` SET `primero`='$this->primero',`segundo`='$this->segundo',`tercero`='$this->tercero',`cuarto`='$this->cuarto',`quinto`='$this->quinto',`todos`='$this->todos' WHERE `premio`.`id_premio` = '$this->id_premio'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `premio` WHERE `id_premio` = '$this->id_premio'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>