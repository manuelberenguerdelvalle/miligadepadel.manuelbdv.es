<?php
class Arbitro{
	//atributos
	protected $id_arbitro = '';
	protected $liga = '';
	protected $dni = '';
	protected $telefono = '';
	protected $nombre = '';
	protected $apellidos = '';
	protected $cp = '';
	protected $direccion = ''; 
	protected $tipo = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_arbitro,$liga,$dni,$telefono,$nombre,$apellidos,$cp,$direccion,$tipo){
		if($id_arbitro != '' && $id_arbitro != NULL && $liga == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM arbitro WHERE id_arbitro = '$id_arbitro'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_arbitro = $resultados['id_arbitro'];
				$this->liga = $resultados['liga'];
				$this->dni = $resultados['dni'];
				$this->telefono = $resultados['telefono'];
				$this->nombre = $resultados['nombre'];
				$this->apellidos = $resultados['apellidos'];
				$this->cp = $resultados['cp'];
				$this->direccion = $resultados['direccion'];
				$this->tipo = $resultados['tipo'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_arbitro = $id_arbitro;
			$this->liga = $liga;
			$this->dni = $dni;
			$this->telefono = $telefono;
			$this->nombre = $nombre;
			$this->apellidos = $apellidos;
			$this->cp = $cp;
			$this->direccion = $direccion;
			$this->tipo = $tipo;
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
		$db->consulta("INSERT INTO `arbitro` (`id_arbitro`,`liga`,`dni`,`telefono`,`nombre`,`apellidos`,`cp`,`direccion`,`tipo`) VALUES (NULL,'$this->liga','$this->dni','$this->telefono','$this->nombre','$this->apellidos','$this->cp','$this->direccion','$this->tipo');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `arbitro` SET `liga`='$this->liga',`dni`='$this->dni',`telefono`='$this->telefono',`nombre`='$this->nombre',`apellidos`='$this->apellidos',`cp`='$this->cp',`direccion`='$this->direccion',`tipo`='$this->tipo' WHERE `id_arbitro` = '$this->id_arbitro'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `arbitro` WHERE `id_arbitro` = '$this->id_arbitro'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>