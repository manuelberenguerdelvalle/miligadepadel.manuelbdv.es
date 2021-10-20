<?php
class Sancion_jugador{
	//atributos
	protected $id_sancion = '';
	protected $jugador = '';
	protected $fecha = '';
	protected $tipo = ''; 
	protected $descripcion = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_sancion,$jugador,$fecha,$tipo,$descripcion){
		if($id_sancion == '' && $jugador != '' && $fecha == '' && $tipo != '' && $descripcion == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM sancion_jugador WHERE jugador = '$jugador' AND tipo = '$tipo' ORDER BY id_sancion DESC; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_sancion = $resultados['id_sancion'];
				$this->jugador = $resultados['jugador'];
				$this->fecha = $resultados['fecha'];
				$this->tipo = $resultados['tipo']; 
				$this->descripcion = $resultados['descripcion']; 
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_sancion = $id_sancion;
			$this->jugador = $jugador;
			$this->fecha = $fecha;
			$this->tipo = $tipo; 
			$this->descripcion = $descripcion;
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
		$db->consulta("INSERT INTO  `sancion_jugador` (`id_sancion`,`jugador`,`fecha`,`tipo`,`descripcion`) VALUES (NULL,'$this->jugador','$this->fecha','$this->tipo','$this->descripcion' );");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE  `sancion_jugador` SET `jugador`='$this->jugador',`fecha`='$this->fecha',`tipo`='$this->tipo',`descripcion`='$this->descripcion' WHERE `sancion_jugador`.`id_sancion` = '$this->id_sancion' ; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM  `sancion_jugador` WHERE `id_sancion` = '$this->id_sancion'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$){
	}*/
}

?>