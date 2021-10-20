<?php
class Pista{
	//atributos
	protected $id_pista = '';
	protected $liga = '';
	protected $nombre = '';
	protected $direccion = '';
	protected $cp = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_pista,$liga,$nombre,$direccion,$cp){
		if($id_pista != '' && $id_pista != NULL && $liga == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM pista WHERE id_pista = '$id_pista'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_pista = $resultados['id_pista'];
				$this->liga = $resultados['liga'];
				$this->nombre = $resultados['nombre'];
				$this->direccion = $resultados['direccion'];
				$this->cp = $resultados['cp'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_pista = $id_pista;
			$this->liga = $liga;
			$this->nombre = $nombre;
			$this->direccion = $direccion;
			$this->cp = $cp;
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
		$db->consulta("INSERT INTO `pista` (`id_pista`,`liga`,`nombre`,`direccion`,`cp`) VALUES (NULL,'$this->liga','$this->nombre','$this->direccion','$this->cp');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `pista` SET `liga`='$this->liga',`nombre`='$this->nombre',`direccion`='$this->direccion',`cp`='$this->cp' WHERE `id_pista` = '$this->id_pista'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `pista` WHERE `id_pista` = '$this->id_pista'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$direccion,$password,$nombre,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>