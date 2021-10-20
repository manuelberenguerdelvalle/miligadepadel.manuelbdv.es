<?php
class Inscripcion{
	//atributos
	protected $id_inscripcion = '';
    protected $division = '';
	protected $liga = '';
	protected $pago = '';
	protected $precio = ''; 
	protected $pagado = '';
	protected $id_jugador1 = '';
	protected $dni1 = '';
	protected $nombre1 = '';
	protected $apellidos1 = '';
	protected $password1 = '';
	protected $direccion1 = ''; 
	protected $fec_nac1 = '';
	protected $ciudad1 = '';
	protected $provincia1 = '';
	protected $pais1 = '';
	protected $telefono1 = '';
	protected $email1 = '';
	protected $genero1 = '';
	protected $id_jugador2 = '';
	protected $dni2 = '';
	protected $nombre2 = '';
	protected $apellidos2 = '';
	protected $password2 = '';
	protected $direccion2 = ''; 
	protected $fec_nac2 = '';
	protected $ciudad2 = '';
	protected $provincia2 = '';
	protected $pais2 = '';
	protected $telefono2 = '';
	protected $email2 = '';
	protected $genero2 = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_inscripcion,$division,$liga,$pago,$precio,$pagado,$id_jugador1,$dni1,$nombre1,$apellidos1,$password1,$direccion1,$fec_nac1,$ciudad1,$provincia1,$pais1,$telefono1,$email1,$genero1,$id_jugador2,$dni2,$nombre2,$apellidos2,$password2,$direccion2,$fec_nac2,$ciudad2,$provincia2,$pais2,$telefono2,$email2,$genero2){
		if(!empty($id_inscripcion) && $division == '' && $liga == '' && $id_jugador1 == '' && $id_jugador2 == ''){//busco por id_inscripcion
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM inscripcion WHERE id_inscripcion = '$id_inscripcion';");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_inscripcion = $resultados['id_inscripcion'];
				$this->division = $resultados['division'];
				$this->liga = $resultados['liga'];
				$this->pago = $resultados['pago'];
				$this->precio = $resultados['precio']; 
				$this->pagado = $resultados['pagado'];
				$this->id_jugador1 = $resultados['id_jugador1'];
				$this->dni1 = $resultados['dni1'];
				$this->nombre1 = $resultados['nombre1'];
				$this->apellidos1 = $resultados['apellidos1'];
				$this->password1 = $resultados['password1'];
				$this->direccion1 = $resultados['direccion1']; 
				$this->fec_nac1 = $resultados['fec_nac1'];
				$this->ciudad1 = $resultados['ciudad1'];
				$this->provincia1 = $resultados['provincia1'];
				$this->pais1 = $resultados['pais1'];
				$this->telefono1 = $resultados['telefono1'];
				$this->email1 = $resultados['email1'];
				$this->genero1 = $resultados['genero1'];
				$this->id_jugador2 = $resultados['id_jugador2'];
				$this->dni2 = $resultados['dni2'];
				$this->nombre2 = $resultados['nombre2'];
				$this->apellidos2 = $resultados['apellidos2'];
				$this->password2 = $resultados['password2'];
				$this->direccion2 = $resultados['direccion2']; 
				$this->fec_nac2 = $resultados['fec_nac2'];
				$this->ciudad2 = $resultados['ciudad2'];
				$this->provincia2 = $resultados['provincia2'];
				$this->pais2 = $resultados['pais2'];
				$this->telefono2 = $resultados['telefono2'];
				$this->email2 = $resultados['email2'];
				$this->genero2 = $resultados['genero2'];
			}
			$db->cerrar_conexion();
		}
		else if($id_inscripcion == '' && $division != '' && $liga != '' && $id_jugador1 != '' && $id_jugador2 != '' && $pago == '' && $precio == '' && $pagado == ''){//busco por liga,division,id_jugador1,id_jugador2
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM inscripcion WHERE division = '$division' AND liga = '$liga' AND id_jugador1 = '$id_jugador1' AND id_jugador2 = '$id_jugador2';");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_inscripcion = $resultados['id_inscripcion'];
				$this->division = $resultados['division'];
				$this->liga = $resultados['liga'];
				$this->pago = $resultados['pago'];
				$this->precio = $resultados['precio']; 
				$this->pagado = $resultados['pagado'];
				$this->id_jugador1 = $resultados['id_jugador1'];
				$this->dni1 = $resultados['dni1'];
				$this->nombre1 = $resultados['nombre1'];
				$this->apellidos1 = $resultados['apellidos1'];
				$this->password1 = $resultados['password1'];
				$this->direccion1 = $resultados['direccion1']; 
				$this->fec_nac1 = $resultados['fec_nac1'];
				$this->ciudad1 = $resultados['ciudad1'];
				$this->provincia1 = $resultados['provincia1'];
				$this->pais1 = $resultados['pais1'];
				$this->telefono1 = $resultados['telefono1'];
				$this->email1 = $resultados['email1'];
				$this->genero1 = $resultados['genero1'];
				$this->id_jugador2 = $resultados['id_jugador2'];
				$this->dni2 = $resultados['dni2'];
				$this->nombre2 = $resultados['nombre2'];
				$this->apellidos2 = $resultados['apellidos2'];
				$this->password2 = $resultados['password2'];
				$this->direccion2 = $resultados['direccion2']; 
				$this->fec_nac2 = $resultados['fec_nac2'];
				$this->ciudad2 = $resultados['ciudad2'];
				$this->provincia2 = $resultados['provincia2'];
				$this->pais2 = $resultados['pais2'];
				$this->telefono2 = $resultados['telefono2'];
				$this->email2 = $resultados['email2'];
				$this->genero2 = $resultados['genero2'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_inscripcion = $id_inscripcion;
			$this->division = $division;
			$this->liga = $liga;
			$this->pago = $pago;
			$this->precio = $precio; 
			$this->pagado = $pagado;
			$this->id_jugador1 = $id_jugador1;
			$this->dni1 = $dni1;
			$this->nombre1 = $nombre1;
			$this->apellidos1 = $apellidos1;
			$this->password1 = $password1;
			$this->direccion1 = $direccion1; 
			$this->fec_nac1 = $fec_nac1;
			$this->ciudad1 = $ciudad1;
			$this->provincia1 = $provincia1;
			$this->pais1 = $pais1;
			$this->telefono1 = $telefono1;
			$this->email1 = $email1;
			$this->genero1 = $genero1;
			$this->id_jugador2 = $id_jugador2;
			$this->dni2 = $dni2;
			$this->nombre2 = $nombre2;
			$this->apellidos2 = $apellidos2;
			$this->password2 = $password2;
			$this->direccion2 = $direccion2; 
			$this->fec_nac2 = $fec_nac2;
			$this->ciudad2 = $ciudad2;
			$this->provincia2 = $provincia2;
			$this->pais2 = $pais2;
			$this->telefono2 = $telefono2;
			$this->email2 = $email2;
			$this->genero2 = $genero2;
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
		$db->consulta("INSERT INTO `inscripcion` (`id_inscripcion`,`division`,`liga`,`pago`,`precio`,`pagado`,`id_jugador1`,`dni1`,`nombre1`,`apellidos1`,`password1`,`direccion1`,`fec_nac1`,`ciudad1`,`provincia1`,`pais1`,`telefono1`,`email1`,`genero1`,`id_jugador2`,`dni2`,`nombre2`,`apellidos2`,`password2`,`direccion2`,`fec_nac2`,`ciudad2`,`provincia2`,`pais2`,`telefono2`,`email2`,`genero2`) VALUES (NULL,'$this->division','$this->liga','$this->pago','$this->precio','$this->pagado','$this->id_jugador1','$this->dni1','$this->nombre1','$this->apellidos1','$this->password1','$this->direccion1','$this->fec_nac1','$this->ciudad1','$this->provincia1','$this->pais1','$this->telefono1','$this->email1','$this->genero1','$this->id_jugador2','$this->dni2','$this->nombre2','$this->apellidos2','$this->password2','$this->direccion2','$this->fec_nac2','$this->ciudad2','$this->provincia2','$this->pais2','$this->telefono2','$this->email2','$this->genero2');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `inscripcion` SET `division`='$this->division',`liga`='$this->liga',`pago`='$this->pago',`precio`='$this->precio',`pagado`='$this->pagado',`id_jugador1`='$this->id_jugador1',`dni1`='$this->dni1',`nombre1`='$this->nombre1',`apellidos1`='$this->apellidos1',`password1`='$this->password1',`direccion1`='$this->direccion1',`fec_nac1`='$this->fec_nac1',`ciudad1`='$this->ciudad1',`provincia1`='$this->provincia1',`pais1`='$this->pais1',`telefono1`='$this->telefono1',`email1`='$this->email1',`genero1`='$this->genero1',`id_jugador2`='$this->id_jugador2',`dni2`='$this->dni2',`nombre2`='$this->nombre2',`apellidos2`='$this->apellidos2',`password2`='$this->password2',`direccion2`='$this->direccion2',`fec_nac2`='$this->fec_nac2',`ciudad2`='$this->ciudad2',`provincia2`='$this->provincia2',`pais2`='$this->pais2',`telefono2`='$this->telefono2',`email2`='$this->email2',`genero2`='$this->genero2' WHERE `inscripcion`.`id_inscripcion` = '$this->id_inscripcion'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `inscripcion` WHERE `id_inscripcion` = '$this->id_inscripcion'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($division,$apellidos,$email,$telefono,$direccion1word,$dni,$cuenta_paypal,$direccion,$cp,$pagado,$precio,$pago,$fec_registro,$provincia1){
	}*/
}

?>