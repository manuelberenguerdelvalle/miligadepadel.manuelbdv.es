<?php
class Usuario_publi{
	//atributos
	protected $id_usuario_publi = '';
	protected $email = '';
    protected $nombre = '';
	protected $empresa = '';
	protected $telefono = ''; 
	protected $password = '';
	protected $cif = '';
	protected $cuenta_paypal = '';
	protected $direccion = '';
	protected $cp = '';
	protected $pais = ''; 
	protected $provincia = '';
	protected $ciudad = '';
	protected $fec_registro = '';
	protected $bloqueo = '';
	protected $comercial = '';
	//----------------------------------------
    //constructores de la clase
	//----------------------------------------
    public function __construct($id_usuario_publi,$email,$nombre,$empresa,$telefono,$password,$cif,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo,$comercial){
		if($email == '' && $id_usuario_publi != '' && $nombre == '' && $telefono == ''){//entro por id_usuario_publi
			$db = new MySQL('unicas');//UNICAS
			$consulta = $db->consulta("SELECT * FROM usuario_publi WHERE id_usuario_publi = '$id_usuario_publi'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_usuario_publi = $resultados['id_usuario_publi'];
				$this->nombre = $resultados['nombre'];
				$this->empresa = $resultados['empresa'];
				$this->email = $resultados['email'];
				$this->telefono = $resultados['telefono'];
				$this->password = $resultados['password'];
				$this->cif = $resultados['cif'];
				$this->cuenta_paypal = $resultados['cuenta_paypal'];
				$this->direccion = $resultados['direccion'];
				$this->cp = $resultados['cp'];
				$this->pais = $resultados['pais'];
				$this->provincia = $resultados['provincia'];
				$this->ciudad = $resultados['ciudad'];
				$this->fec_registro = $resultados['fec_registro'];
				$this->bloqueo = $resultados['bloqueo'];
				$this->comercial = $resultados['comercial'];
			}
			$db->cerrar_conexion();
		}
		else if($email != '' && $id_usuario_publi == '' && $nombre == '' && $telefono == ''){//entro por email
			$db = new MySQL('unicas');//UNICAS
			$consulta = $db->consulta("SELECT * FROM usuario_publi WHERE email = '$email'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_usuario_publi = $resultados['id_usuario_publi'];
				$this->nombre = $resultados['nombre'];
				$this->empresa = $resultados['empresa'];
				$this->email = $resultados['email'];
				$this->telefono = $resultados['telefono'];
				$this->password = $resultados['password'];
				$this->cif = $resultados['cif'];
				$this->cuenta_paypal = $resultados['cuenta_paypal'];
				$this->direccion = $resultados['direccion'];
				$this->cp = $resultados['cp'];
				$this->pais = $resultados['pais'];
				$this->provincia = $resultados['provincia'];
				$this->ciudad = $resultados['ciudad'];
				$this->fec_registro = $resultados['fec_registro'];
				$this->bloqueo = $resultados['bloqueo'];
				$this->comercial = $resultados['comercial'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_usuario_publi = $id_usuario_publi;
			$this->nombre = $nombre;
			$this->empresa = $empresa;
			$this->email = $email;
			$this->telefono = $telefono;
			$this->password = $password;
			$this->cif = $cif;
			$this->cuenta_paypal = $cuenta_paypal;
			$this->direccion = $direccion;
			$this->cp = $cp;
			$this->pais = $pais;
			$this->provincia = $provincia;
			$this->ciudad = $ciudad;
			$this->fec_registro = $fec_registro;
			$this->bloqueo = $bloqueo;
			$this->comercial = $comercial;
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
		$db->consulta("INSERT INTO  `usuario_publi` (`id_usuario_publi`,`email`,`telefono`,`password`,`nombre`,`empresa`,`cif`,`cuenta_paypal`,`direccion`,`cp`,`ciudad`,`provincia`,`pais`,`fec_registro`,`bloqueo`,`comercial`) VALUES (NULL,'$this->email','$this->telefono','$this->password','$this->nombre','$this->empresa','$this->cif','$this->cuenta_paypal','$this->direccion','$this->cp','$this->ciudad','$this->provincia','$this->pais','$this->fec_registro','$this->bloqueo','$this->comercial'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("UPDATE  `usuario_publi`  SET  `telefono` = '$this->telefono',`password` =  '$this->password',`nombre` = '$this->nombre',`empresa` = '$this->empresa',`cif` = '$this->cif',`cuenta_paypal` = '$this->cuenta_paypal',`direccion` = '$this->direccion',`cp` = '$this->cp',`ciudad` = '$this->ciudad',`provincia` = '$this->provincia',`pais` =  '$this->pais',`fec_registro` = '$this->fec_registro',`bloqueo` = '$this->bloqueo' WHERE `id_usuario_publi` = '$this->id_usuario_publi'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("DELETE FROM  `usuario_publi` WHERE  `id_usuario_publi` =  '$this->id_usuario_publi'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$cif,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>