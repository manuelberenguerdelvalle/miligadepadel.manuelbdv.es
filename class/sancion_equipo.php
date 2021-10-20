<?php
//INSERTAR TAMBIEN division PARA FACILITAR ELIMINACION
class Sancion_equipo{
	//atributos
	protected $id_sancion = '';
	protected $equipo = '';
	protected $partido = '';
	protected $tipo = ''; 
	protected $fecha = '';
	protected $descripcion = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_sancion,$equipo,$partido,$tipo,$fecha,$descripcion){
		if($id_sancion == '' && $equipo != '' && $partido == '' && $tipo != '' && $fecha == '' && $descripcion == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM sancion_equipo WHERE equipo = '$equipo' AND tipo = '$tipo' ORDER BY id_sancion DESC; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_sancion = $resultados['id_sancion'];
				$this->equipo = $resultados['equipo'];
				$this->partido = $resultados['partido'];
				$this->tipo = $resultados['tipo']; 
				$this->fecha = $resultados['fecha'];
				$this->descripcion = $resultados['descripcion']; 
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_sancion = $id_sancion;
			$this->equipo = $equipo;
			$this->partido = $partido;
			$this->tipo = $tipo;
			$this->fecha = $fecha;
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
		$db->consulta("INSERT INTO  `sancion_equipo` (`id_sancion`,`equipo`,`partido`,`tipo`,`fecha`,`descripcion`) VALUES (NULL,'$this->equipo','$this->partido','$this->tipo','$this->fecha','$this->descripcion' );");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE  `sancion_equipo` SET `equipo`='$this->equipo',`partido`='$this->partido',`tipo`='$this->tipo',`fecha`='$this->fecha',`descripcion`='$this->descripcion' WHERE `sancion_equipo`.`id_sancion` = '$this->id_sancion'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM  `sancion_equipo` WHERE `id_sancion` = '$this->id_sancion'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$){
	}*/
}

?>