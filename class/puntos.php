<?php
class Puntos{
	//atributos
	protected $id_puntos = '';
	protected $usuario = '';
	protected $jugador = '';
	protected $bd = '';
	protected $liga = '';
	protected $division = ''; 
	protected $partido = ''; 
	protected $fecha = '';
	protected $puntos = '';
	protected $tipo = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_puntos,$usuario,$jugador,$bd,$liga,$division,$partido,$fecha,$puntos,$tipo){
		if($id_puntos != '' && $id_puntos != NULL && empty($usuario) && empty($jugador) && empty($bd) && empty($liga) && empty($division) && empty($partido)){
			$db = new MySQL('unicas');//UNICAS
			$consulta = $db->consulta("SELECT * FROM puntos WHERE id_puntos = '$id_puntos';");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_puntos = $resultados['id_puntos'];
				$this->usuario = $resultados['usuario'];
				$this->jugador = $resultados['jugador'];
				$this->bd = $resultados['bd'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division']; 
				$this->partido = $resultados['partido']; 
				$this->fecha = $resultados['fecha'];
				$this->puntos = $resultados['puntos'];
				$this->tipo = $resultados['tipo'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_puntos = $id_puntos;
			$this->usuario = $usuario;
			$this->jugador = $jugador;
			$this->bd = $bd;
			$this->liga = $liga;
			$this->division = $division; 
			$this->partido = $partido; 
			$this->fecha = $fecha;
			$this->puntos = $puntos;
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
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("INSERT INTO `puntos` (`id_puntos`,`usuario`,`jugador`,`bd`,`liga`,`division`,`partido`,`fecha`,`puntos`,`tipo`) VALUES ('$this->id_puntos','$this->usuario', '$this->jugador', '$this->bd', '$this->liga', '$this->division', '$this->partido', '$this->fecha', '$this->puntos', '$this->tipo');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("UPDATE  `puntos` SET `usuario`='$this->usuario',`jugador`='$this->jugador',`bd`='$this->bd',`liga`='$this->liga',`division`='$this->division',`partido`='$this->partido',`fecha`='$this->fecha',`puntos`='$this->puntos',`tipo`='$this->tipo' WHERE `id_puntos` = '$this->id_puntos'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("DELETE FROM `puntos` WHERE `id_puntos` = '$this->id_puntos'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($division,$bd,$email,$telefono,$division1word,$usuario,$cuenta_paypal,$division,$cp,$pagado,$precio,$tipo_pago,$fec_registro,$provincia1){
	}*/
}

?>