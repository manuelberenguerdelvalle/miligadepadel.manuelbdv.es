<?php
class Pago_admin{
	//atributos
	protected $id_pago_admin = '';
    protected $liga = '';
	protected $division = '';
	protected $bd = '';
	protected $equipo = ''; 
	protected $precio = '';
	protected $modo_pago = '';
	protected $pagado = '';
	protected $receptor = '';
	protected $usuario = '';
	protected $emisor = ''; 
	protected $fecha = '';
	protected $transaccion = '';
	protected $tarjeta = '';
	protected $datos = '';
	protected $jugador1 = '';
	protected $jugador2 = '';
	protected $estado = '';
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_pago_admin,$liga,$division,$bd,$equipo,$precio,$modo_pago,$pagado,$receptor,$usuario,$emisor,$fecha,$transaccion,$tarjeta,$datos,$jugador1,$jugador2,$estado){
		if($liga == '' && $division == '' && !empty($id_pago_admin) && $equipo == ''){//SI EL PRECIO ES VACIO ES PORQUE RECUPERO DE LA BD
			$db = new MySQL('unicas_liga');//UNICAS LIGA
			$consulta = $db->consulta("SELECT * FROM pago_admin WHERE id_pago_admin = '$id_pago_admin'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_pago_admin = $resultados['id_pago_admin'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->bd = $resultados['bd'];
				$this->equipo = $resultados['equipo']; 
				$this->precio = $resultados['precio'];
				$this->modo_pago = $resultados['modo_pago'];
				$this->pagado = $resultados['pagado'];
				$this->receptor = $resultados['receptor'];
				$this->usuario = $resultados['usuario'];
				$this->emisor = $resultados['emisor'];
				$this->fecha = $resultados['fecha']; 
				$this->transaccion = $resultados['transaccion'];
				$this->tarjeta = $resultados['tarjeta'];
				$this->datos = $resultados['datos'];
				$this->jugador1 = $resultados['jugador1'];
				$this->jugador2 = $resultados['jugador2'];
				$this->estado = $resultados['estado'];
			}
			$db->cerrar_conexion();
		}
		else if($liga != '' && $division != '' && $equipo != '' && $bd != '' && empty($id_pago_admin) && $precio == '' && $modo_pago == '' && $pagado == ''){//buscamos con 4 datos
			$db = new MySQL('unicas_liga');//UNICAS LIGA
			$consulta = $db->consulta("SELECT * FROM pago_admin WHERE liga = '$liga' AND division = '$division' AND equipo = '$equipo' AND bd = '$bd'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_pago_admin = $resultados['id_pago_admin'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->bd = $resultados['bd'];
				$this->equipo = $resultados['equipo']; 
				$this->precio = $resultados['precio'];
				$this->modo_pago = $resultados['modo_pago'];
				$this->pagado = $resultados['pagado'];
				$this->receptor = $resultados['receptor'];
				$this->usuario = $resultados['usuario'];
				$this->emisor = $resultados['emisor'];
				$this->fecha = $resultados['fecha']; 
				$this->transaccion = $resultados['transaccion'];
				$this->tarjeta = $resultados['tarjeta'];
				$this->datos = $resultados['datos'];
				$this->jugador1 = $resultados['jugador1'];
				$this->jugador2 = $resultados['jugador2'];
				$this->estado = $resultados['estado'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_pago_admin = $id_pago_admin;
			$this->liga = $liga;
			$this->division = $division;
			$this->bd = $bd;
			$this->equipo = $equipo;
			$this->precio = $precio;
			$this->modo_pago = $modo_pago;
			$this->pagado = $pagado;
			$this->receptor = $receptor;
			$this->usuario = $usuario;
			$this->emisor = $emisor;
			$this->fecha = $fecha; 
			$this->transaccion = $transaccion;
			$this->tarjeta = $tarjeta;
			$this->datos = $datos;
			$this->jugador1 = $jugador1;
			$this->jugador2 = $jugador2;
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
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$db->consulta("INSERT INTO `pago_admin` (`id_pago_admin`,`liga`,`division`,`bd`,`equipo`,`precio`,`modo_pago`,`pagado`,`receptor`,`usuario`,`emisor`,`fecha`,`transaccion`,`tarjeta`,`datos`,`jugador1`,`jugador2`,`estado`) VALUES (NULL,'$this->liga','$this->division','$this->bd','$this->equipo','$this->precio','$this->modo_pago','$this->pagado','$this->receptor','$this->usuario','$this->emisor','$this->fecha','$this->transaccion','$this->tarjeta','$this->datos','$this->jugador1','$this->jugador2','$this->estado'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$db->consulta("UPDATE `pago_admin` SET `liga`='$this->liga',`division`='$this->division',`bd`='$this->bd',`equipo`='$this->equipo',`precio`='$this->precio',`modo_pago`='$this->modo_pago',`pagado`='$this->pagado',`receptor`='$this->receptor',`usuario`='$this->usuario',`emisor`='$this->emisor',`fecha`='$this->fecha',`transaccion`='$this->transaccion',`tarjeta`='$this->tarjeta',`datos`='$this->datos' ,`jugador1`='$this->jugador1',`jugador2`='$this->jugador2',`estado`='$this->estado'  WHERE `id_pago_admin` = '$this->id_pago_admin'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$db->consulta("DELETE FROM `pago_admin` WHERE `id_pago_admin` = '$this->id_pago_admin'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>