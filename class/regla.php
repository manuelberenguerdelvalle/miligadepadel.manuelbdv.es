<?php
class Regla{
	//atributos
	protected $id_regla = '';
	protected $liga = '';
	protected $texto = '';
	protected $fecha = '';
	protected $cp = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_regla,$liga,$texto,$fecha){
		if($id_regla == '' && $liga != '' && $texto == '' && $fecha == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM regla WHERE liga = '$liga'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_regla = $resultados['id_regla'];
				$this->liga = $resultados['liga'];
				$this->texto = $resultados['texto'];
				$this->fecha = $resultados['fecha'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_regla = $id_regla;
			$this->liga = $liga;
			$this->texto = $texto;
			$this->fecha = $fecha;
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
		$db->consulta("INSERT INTO `regla` (`id_regla`,`liga`,`texto`,`fecha`) VALUES (NULL,'$this->liga','$this->texto','$this->fecha');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `regla` SET `liga`='$this->liga',`texto`='$this->texto',`fecha`='$this->fecha'  WHERE `regla`.`id_regla` = '$this->id_regla'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `regla` WHERE `id_regla` = '$this->id_regla'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($texto,$apellidos,$email,$fecha,$password,$texto,$cuenta_paypal,$fecha,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>