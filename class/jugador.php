<?php
class Jugador{
	//atributos
	protected $id_jugador = '';
	protected $dni = '';
	protected $nombre = '';
	protected $apellidos = '';
	protected $password = '';
	protected $direccion = ''; 
	protected $fec_nac = '';
	protected $zona_juego = '';
	protected $ciudad = '';
	protected $provincia = '';
	protected $pais = '';
	protected $telefono = '';
	protected $email = '';
	protected $genero = '';
	protected $estado = '';
	protected $creacion = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_jugador,$dni,$nombre,$apellidos,$password,$direccion,$fec_nac,$zona_juego,$ciudad,$provincia,$pais,$telefono,$email,$genero,$estado,$creacion){
		if($id_jugador != '' && $id_jugador != NULL && empty($nombre) && empty($apellidos) && empty($telefono) && empty($email) ){
			$db = new MySQL('unicas');//UNICAS
			$consulta = $db->consulta("SELECT * FROM jugador WHERE id_jugador = '$id_jugador';");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_jugador = $resultados['id_jugador'];
				$this->dni = $resultados['dni'];
				$this->nombre = $resultados['nombre'];
				$this->apellidos = $resultados['apellidos'];
				$this->password = $resultados['password'];
				$this->direccion = $resultados['direccion']; 
				$this->fec_nac = $resultados['fec_nac'];
				$this->zona_juego = $resultados['zona_juego'];
				$this->ciudad = $resultados['ciudad'];
				$this->provincia = $resultados['provincia'];
				$this->pais = $resultados['pais'];
				$this->telefono = $resultados['telefono'];
				$this->email = $resultados['email'];
				$this->genero = $resultados['genero'];
				$this->estado = $resultados['estado'];
				$this->creacion = $resultados['creacion'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_jugador = $id_jugador;
			$this->dni = $dni;
			$this->nombre = $nombre;
			$this->apellidos = $apellidos;
			$this->password = $password;
			$this->direccion = $direccion; 
			$this->fec_nac = $fec_nac;
			$this->zona_juego = $zona_juego;
			$this->ciudad = $ciudad;
			$this->provincia = $provincia;
			$this->pais = $pais;
			$this->telefono = $telefono;
			$this->email = $email;
			$this->genero = $genero;
			$this->estado = $estado;
			$this->creacion = $creacion;
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
		$db->consulta("INSERT INTO `jugador` (`id_jugador`,`dni`,`nombre`,`apellidos`,`password`,`direccion`,`fec_nac`,`zona_juego`,`ciudad`,`provincia`,`pais`,`telefono`,`email`,`genero`,`estado`,`creacion`) VALUES ('$this->id_jugador','$this->dni', '$this->nombre', '$this->apellidos', '$this->password', '$this->direccion', '$this->fec_nac', '$this->zona_juego', '$this->ciudad', '$this->provincia', '$this->pais', '$this->telefono', '$this->email', '$this->genero', '$this->estado','$this->creacion');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("UPDATE  `jugador` SET `dni`='$this->dni',`nombre`='$this->nombre',`apellidos`='$this->apellidos',`password`='$this->password',`direccion`='$this->direccion',`fec_nac`='$this->fec_nac',`zona_juego`='$this->zona_juego',`ciudad`='$this->ciudad',`provincia`='$this->provincia',`pais`='$this->pais',`telefono`='$this->telefono',`email`='$this->email',`genero`='$this->genero',`estado`='$this->estado',`creacion`='$this->creacion' WHERE `id_jugador` = '$this->id_jugador'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("DELETE FROM `jugador` WHERE `id_jugador` = '$this->id_jugador'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($division,$apellidos,$email,$telefono,$direccion1word,$dni,$cuenta_paypal,$direccion,$cp,$pagado,$precio,$tipo_pago,$fec_registro,$provincia1){
	}*/
}

?>