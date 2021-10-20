<?php
class Liga{
	//atributos
	protected $id_liga = '';
    protected $nombre = '';
	protected $fec_creacion = '';
	protected $ciudad = '';
	protected $provincia = ''; 
	protected $pais = '';
	protected $usuario = '';
	protected $tipo_pago = '';
	protected $pagado = '';
	protected $vista = '';
	protected $pass = ''; 
	protected $auto_completar = '';
	protected $movimientos = '';
	protected $bloqueo = '';
	protected $genero = '';
	protected $idayvuelta = '';
	protected $estilo = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_liga,$nombre,$fec_creacion,$ciudad,$provincia,$pais,$usuario,$tipo_pago,$pagado,$vista,$pass, $auto_completar,$movimientos,$bloqueo,$genero,$idayvuelta,$estilo){
		if($nombre == '' && $id_liga != ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM liga WHERE id_liga = '$id_liga';");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_liga = $resultados['id_liga'];
				$this->nombre = $resultados['nombre'];
				$this->fec_creacion = $resultados['fec_creacion'];
				$this->ciudad = $resultados['ciudad'];
				$this->provincia = $resultados['provincia']; 
				$this->pais = $resultados['pais'];
				$this->usuario = $resultados['usuario'];
				$this->tipo_pago = $resultados['tipo_pago'];
				$this->pagado = $resultados['pagado'];
				$this->vista = $resultados['vista'];
				$this->pass = $resultados['pass']; 
				$this->auto_completar = $resultados['auto_completar'];
				$this->movimientos = $resultados['movimientos'];
				$this->bloqueo = $resultados['bloqueo'];
				$this->genero = $resultados['genero'];
				$this->idayvuelta = $resultados['idayvuelta'];
				$this->estilo = $resultados['estilo'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_liga = $id_liga;
			$this->nombre = $nombre;
			$this->fec_creacion = $fec_creacion;
			$this->ciudad = $ciudad;
			$this->provincia = $provincia; 
			$this->pais = $pais;
			$this->usuario = $usuario;
			$this->tipo_pago = $tipo_pago;
			$this->pagado = $pagado;
			$this->vista = $vista;
			$this->pass = $pass; 
			$this->auto_completar = $auto_completar;
			$this->movimientos = $movimientos;
			$this->bloqueo = $bloqueo;
			$this->genero = $genero;
			$this->idayvuelta = $idayvuelta;
			$this->estilo = $estilo;
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
		$db->consulta("INSERT INTO  `liga` (`id_liga`,`nombre`,`fec_creacion`,`ciudad`,`provincia`,`pais`,`usuario`,`tipo_pago`,`pagado`,`vista`,`pass`,`auto_completar`,`movimientos`,`bloqueo`,`genero`,`idayvuelta`,`estilo`) VALUES (NULL,  '$this->nombre', '$this->fec_creacion', '$this->ciudad', '$this->provincia', '$this->pais', '$this->usuario', '$this->tipo_pago', '$this->pagado', '$this->vista', '$this->pass', '$this->auto_completar', '$this->movimientos', '$this->bloqueo', '$this->genero', '$this->idayvuelta', '$this->estilo');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE  `liga` SET `nombre`='$this->nombre',`ciudad`='$this->ciudad',`provincia`='$this->provincia',`pais`='$this->pais',`tipo_pago`='$this->tipo_pago',`pagado`='$this->pagado',`vista`='$this->vista',`pass`='$this->pass',`auto_completar`='$this->auto_completar',`movimientos`='$this->movimientos',`genero`='$this->genero',`idayvuelta`='$this->idayvuelta' ,`estilo`='$this->estilo' WHERE `liga`.`id_liga` = '$this->id_liga'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM  `liga` WHERE `id_liga` = '$this->id_liga'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>