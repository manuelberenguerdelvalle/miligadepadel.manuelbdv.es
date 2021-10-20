<?php
class Publicidad{
	//atributos
	protected $id_publicidad = '';
	protected $usuario_publi = '';
    protected $pago_web = '';
	protected $provincia = '';
	protected $ciudad = '';
	protected $liga = ''; 
	protected $division = '';
	protected $posicion_publi = '';
	protected $url = '';
	protected $contador = '';
	protected $fecha = ''; 
	protected $fecha_fin = '';
	protected $ultima_rep = '';
	protected $pagado = '';
	protected $estado = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_publicidad,$usuario_publi,$pago_web,$provincia,$ciudad,$liga,$division,$posicion_publi,$url,$contador,$fecha,$fecha_fin,$ultima_rep,$pagado,$estado){
		if($usuario_publi == '' && $pago_web == '' && $liga == '' && $division == '' && $id_publicidad != ''){//recupero bd id_publicidad_gratis
			$db = new MySQL('session');//SESSION
			$consulta = $db->consulta("SELECT * FROM publicidad WHERE id_publicidad = '$id_publicidad'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_publicidad = $resultados['id_publicidad'];
				$this->usuario_publi = $resultados['usuario_publi'];
				$this->pago_web = $resultados['pago_web'];
				$this->provincia = $resultados['provincia'];
				$this->ciudad = $resultados['ciudad'];
				$this->liga = $resultados['liga']; 
				$this->division = $resultados['division'];
				$this->posicion_publi = $resultados['posicion_publi'];
				$this->url = $resultados['url'];
				$this->contador = $resultados['contador'];
				$this->fecha = $resultados['fecha'];
				$this->fecha_fin = $resultados['fecha_fin']; 
				$this->ultima_rep = $resultados['ultima_rep'];
				$this->pagado = $resultados['pagado'];
				$this->estado = $resultados['estado'];
			}
			$db->cerrar_conexion();
		}
		else if($usuario_publi == '' && $id_publicidad == '' && $liga == '' && $division == '' && $pago_web != ''){//recupero bd pago_web
			$db = new MySQL('session');//SESSION
			$consulta = $db->consulta("SELECT * FROM publicidad WHERE pago_web = '$pago_web'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_publicidad = $resultados['id_publicidad'];
				$this->usuario_publi = $resultados['usuario_publi'];
				$this->pago_web = $resultados['pago_web'];
				$this->provincia = $resultados['provincia'];
				$this->ciudad = $resultados['ciudad'];
				$this->liga = $resultados['liga']; 
				$this->division = $resultados['division'];
				$this->posicion_publi = $resultados['posicion_publi'];
				$this->url = $resultados['url'];
				$this->contador = $resultados['contador'];
				$this->fecha = $resultados['fecha'];
				$this->fecha_fin = $resultados['fecha_fin']; 
				$this->ultima_rep = $resultados['ultima_rep'];
				$this->pagado = $resultados['pagado'];
				$this->estado = $resultados['estado'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_publicidad = $id_publicidad;
			$this->usuario_publi = $usuario_publi;
			$this->pago_web = $pago_web;
			$this->provincia = $provincia;
			$this->ciudad = $ciudad;
			$this->liga = $liga; 
			$this->division = $division;
			$this->posicion_publi = $posicion_publi;
			$this->url = $url;
			$this->contador = $contador;
			$this->fecha = $fecha;
			$this->fecha_fin = $fecha_fin; 
			$this->ultima_rep = $ultima_rep;
			$this->pagado = $pagado;
			$this->estado = $estado;
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
		$db = new MySQL('session');//UNICAS
		$db->consulta("INSERT INTO `publicidad` (`id_publicidad`,`usuario_publi`,`pago_web`,`provincia`,`ciudad`,`liga`,`division`,`posicion_publi`,`url`,`contador`,`fecha`,`fecha_fin`,`ultima_rep`,`pagado`,`estado`) VALUES (NULL,'$this->usuario_publi','$this->pago_web','$this->provincia','$this->ciudad','$this->liga','$this->division','$this->posicion_publi','$this->url','$this->contador','$this->fecha','$this->fecha_fin','$this->ultima_rep','$this->pagado','$this->estado'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//UNICAS
		$db->consulta("UPDATE `publicidad` SET `provincia`='$this->provincia',`ciudad`='$this->ciudad',`url`='$this->url',`contador`='$this->contador',`fecha_fin`='$this->fecha_fin',`ultima_rep`='$this->ultima_rep',`pagado`='$this->pagado',`estado`='$this->estado' WHERE `id_publicidad` = '$this->id_publicidad'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//UNICAS
		$db->consulta("DELETE FROM `publicidad` WHERE `id_publicidad` = '$this->id_publicidad'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>