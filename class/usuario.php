<?php
//$usario = new Usuario();
class Usuario{
	//atributos
	protected $id_usuario = '';
    protected $nombre = '';
	protected $apellidos = '';
	protected $email = '';
	protected $bd = '';
	protected $telefono = ''; 
	protected $password = '';
	protected $dni = '';
	protected $cuenta_paypal = '';
	protected $direccion = '';
	protected $cp = '';
	protected $pais = ''; 
	protected $provincia = '';
	protected $ciudad = '';
	protected $fec_registro = '';
	protected $bloqueo = '';
	protected $recibir_pago = '';
	//----------------------------------------
    //constructores de la clase
	//----------------------------------------
    public function __construct($id_usuario,$nombre,$apellidos,$email,$bd,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo,$recibir_pago){
		if($id_usuario != '' && $nombre == '' && $bd == '' && $email == ''){//busco por id_usuario
			$db = new MySQL('unicas_liga');//UNICAS LIGA
			$consulta = $db->consulta("SELECT * FROM usuario WHERE id_usuario = '$id_usuario'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_usuario = $resultados['id_usuario'];
				$this->nombre = $resultados['nombre'];
				$this->apellidos = $resultados['apellidos'];
				$this->email = $resultados['email'];
				$this->bd = $resultados['bd'];
				$this->telefono = $resultados['telefono'];
				$this->password = $resultados['password'];
				$this->dni = $resultados['dni'];
				$this->cuenta_paypal = $resultados['cuenta_paypal'];
				$this->direccion = $resultados['direccion'];
				$this->cp = $resultados['cp'];
				$this->pais = $resultados['pais'];
				$this->provincia = $resultados['provincia'];
				$this->ciudad = $resultados['ciudad'];
				$this->fec_registro = $resultados['fec_registro'];
				$this->bloqueo = $resultados['bloqueo'];
				$this->recibir_pago = $resultados['recibir_pago'];
			}
			$db->cerrar_conexion();
		}
		else if($id_usuario == '' && $nombre == '' && $bd == '' && $email != ''){//busco por email
			$db = new MySQL('unicas_liga');//UNICAS LIGA
			$consulta = $db->consulta("SELECT * FROM usuario WHERE email = '$email'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_usuario = $resultados['id_usuario'];
				$this->nombre = $resultados['nombre'];
				$this->apellidos = $resultados['apellidos'];
				$this->email = $resultados['email'];
				$this->bd = $resultados['bd'];
				$this->telefono = $resultados['telefono'];
				$this->password = $resultados['password'];
				$this->dni = $resultados['dni'];
				$this->cuenta_paypal = $resultados['cuenta_paypal'];
				$this->direccion = $resultados['direccion'];
				$this->cp = $resultados['cp'];
				$this->pais = $resultados['pais'];
				$this->provincia = $resultados['provincia'];
				$this->ciudad = $resultados['ciudad'];
				$this->fec_registro = $resultados['fec_registro'];
				$this->bloqueo = $resultados['bloqueo'];
				$this->recibir_pago = $resultados['recibir_pago'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_usuario = $id_usuario;
			$this->nombre = $nombre;
			$this->apellidos = $apellidos;
			$this->email = $email;
			$this->bd = $bd;
			$this->telefono = $telefono;
			$this->password = $password;
			$this->dni = $dni;
			$this->cuenta_paypal = $cuenta_paypal;
			$this->direccion = $direccion;
			$this->cp = $cp;
			$this->pais = $pais;
			$this->provincia = $provincia;
			$this->ciudad = $ciudad;
			$this->fec_registro = $fec_registro;
			$this->bloqueo = $bloqueo;
			$this->recibir_pago = $recibir_pago;
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
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$db->consulta("INSERT INTO  `usuario` (`id_usuario`,`email`,`bd`,`telefono`,`password`,`nombre`,`apellidos`,`dni`,`cuenta_paypal`,`direccion`,`cp`,`ciudad`,`provincia`,`pais`,`fec_registro`,`bloqueo`,`recibir_pago`) VALUES (NULL,'$this->email','$this->bd','$this->telefono','$this->password','$this->nombre','$this->apellidos','$this->dni','$this->cuenta_paypal','$this->direccion','$this->cp','$this->ciudad','$this->provincia','$this->pais','$this->fec_registro','$this->bloqueo','$this->recibir_pago'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$db->consulta("UPDATE  `usuario`  SET  `email` = '$this->email',`telefono` = '$this->telefono',`password` =  '$this->password',`nombre` = '$this->nombre',`apellidos` = '$this->apellidos',`dni` = '$this->dni',`cuenta_paypal` = '$this->cuenta_paypal',`direccion` = '$this->direccion',`cp` = '$this->cp',`ciudad` = '$this->ciudad',`provincia` = '$this->provincia',`pais` =  '$this->pais',`fec_registro` = '$this->fec_registro',`bloqueo` = '$this->bloqueo',`recibir_pago` = '$this->recibir_pago' WHERE `id_usuario` = '$this->id_usuario'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$db->consulta("DELETE FROM  `usuario` WHERE  `id_usuario` =  '$this->id_usuario'; ");
		$db->cerrar_conexion();
	}
}

?>