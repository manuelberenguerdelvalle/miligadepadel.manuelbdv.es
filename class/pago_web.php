<?php
class Pago_web{
	//atributos
	protected $id_pago_web = '';
	protected $bd = '';
    protected $liga = '';
	protected $division = '';
	protected $tipo = '';
	protected $posicion_publi = ''; 
	protected $precio = '';
	protected $modo_pago = '';
	protected $pagado = '';
	protected $receptor = '';
	protected $emisor = ''; 
	protected $usuario = ''; 
	protected $fecha = '';
	protected $fecha_limite = '';
	protected $transaccion = '';
	protected $tarjeta = '';
	protected $estado = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_pago_web,$bd,$liga,$division,$tipo,$posicion_publi,$precio,$modo_pago,$pagado,$receptor,$emisor,$usuario,$fecha,$fecha_limite,$transaccion,$tarjeta,$estado){
		if($precio == '' && $liga == '' && $division == '' && $id_pago_web != ''){//SI EL PRECIO ES VACIO ES PORQUE RECUPERO DE LA BD
			$db = new MySQL('unicas');//UNICAS
			$consulta = $db->consulta("SELECT * FROM pago_web WHERE id_pago_web = '$id_pago_web'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_pago_web = $resultados['id_pago_web'];
				$this->bd = $resultados['bd'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->tipo = $resultados['tipo'];
				$this->posicion_publi = $resultados['posicion_publi']; 
				$this->precio = $resultados['precio'];
				$this->modo_pago = $resultados['modo_pago'];
				$this->pagado = $resultados['pagado'];
				$this->receptor = $resultados['receptor'];
				$this->emisor = $resultados['emisor'];
				$this->usuario = $resultados['usuario'];
				$this->fecha = $resultados['fecha']; 
				$this->fecha_limite = $resultados['fecha_limite'];
				$this->transaccion = $resultados['transaccion'];
				$this->tarjeta = $resultados['tarjeta'];
				$this->estado = $resultados['estado'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_pago_web = $id_pago_web;
			$this->bd = $bd;
			$this->liga = $liga;
			$this->division = $division;
			$this->tipo = $tipo;
			$this->posicion_publi = $posicion_publi; 
			$this->precio = $precio;
			$this->modo_pago = $modo_pago;
			$this->pagado = $pagado;
			$this->receptor = $receptor;
			$this->emisor = $emisor;
			$this->usuario = $usuario;
			$this->fecha = $fecha; 
			$this->fecha_limite = $fecha_limite;
			$this->transaccion = $transaccion;
			$this->tarjeta = $tarjeta;
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
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("INSERT INTO `pago_web` (`id_pago_web`,`bd`,`liga`,`division`,`tipo`,`posicion_publi`,`precio`,`modo_pago`,`pagado`,`receptor`,`emisor`,`usuario`,`fecha`,`fecha_limite`,`transaccion`,`tarjeta`,`estado`) VALUES (NULL,'$this->bd','$this->liga','$this->division','$this->tipo','$this->posicion_publi','$this->precio','$this->modo_pago','$this->pagado','$this->receptor','$this->emisor','$this->usuario','$this->fecha','$this->fecha_limite','$this->transaccion','$this->tarjeta','$this->estado'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("UPDATE `pago_web` SET `liga`='$this->liga',`division`='$this->division',`tipo`='$this->tipo',`posicion_publi`='$this->posicion_publi',`precio`='$this->precio',`modo_pago`='$this->modo_pago',`pagado`='$this->pagado',`receptor`='$this->receptor',`emisor`='$this->emisor',`usuario`='$this->usuario',`fecha`='$this->fecha',`fecha_limite`='$this->fecha_limite',`transaccion`='$this->transaccion',`tarjeta`='$this->tarjeta',`estado`='$this->estado'  WHERE `id_pago_web` = '$this->id_pago_web'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas');//UNICAS
		$db->consulta("DELETE FROM `pago_web` WHERE `id_pago_web` = '$this->id_pago_web'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>