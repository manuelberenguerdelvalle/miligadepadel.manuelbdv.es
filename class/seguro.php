<?php
class Seguro{
	//atributos
	protected $id_seguro = '';
	protected $licencia = '';
	protected $categoria = '';
	protected $federacion = '';
	protected $fecha_caducidad = '';
	protected $jugador = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_seguro,$licencia,$categoria,$federacion,$fecha_caducidad,$jugador){
		if($id_seguro != '' && $id_seguro != NULL && $licencia == ''){//busco por id_seguro
			$db = new MySQL('unicas');//licencia PADEL
			$consulta = $db->consulta("SELECT * FROM seguro WHERE id_seguro = '$id_seguro'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_seguro = $resultados['id_seguro'];
				$this->licencia = $resultados['licencia'];
				$this->categoria = $resultados['categoria'];
				$this->federacion = $resultados['federacion'];
				$this->fecha_caducidad = $resultados['fecha_caducidad'];
				$this->jugador = $resultados['jugador'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_seguro = $id_seguro;
			$this->licencia = $licencia;
			$this->categoria = $categoria;
			$this->federacion = $federacion;
			$this->fecha_caducidad = $fecha_caducidad;
			$this->jugador = $jugador;
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
		$db = new MySQL('unicas');//licencia PADEL
		$db->consulta("INSERT INTO `seguro` (`id_seguro`,`licencia`,`categoria`,`federacion`,`fecha_caducidad`,`jugador`) VALUES (NULL,'$this->licencia','$this->categoria','$this->federacion','$this->fecha_caducidad','$this->jugador');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas');//licencia PADEL
		$db->consulta("UPDATE `seguro` SET `licencia`='$this->licencia',`categoria`='$this->categoria',`federacion`='$this->federacion',`fecha_caducidad`='$this->fecha_caducidad' WHERE `id_seguro` = '$this->id_seguro'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas');//licencia PADEL
		$db->consulta("DELETE FROM `seguro` WHERE `id_seguro` = '$this->id_seguro'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$fecha_caducidad,$password,$categoria,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>