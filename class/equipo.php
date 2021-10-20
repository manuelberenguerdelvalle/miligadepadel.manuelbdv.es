<?php
class Equipo{
	//atributos
	protected $id_equipo = '';
    protected $jugador1 = '';
	protected $seguro_jug1 = '';
	protected $jugador2 = '';
	protected $seguro_jug2 = '';
	protected $liga = '';
	protected $division = '';
	protected $pagado = ''; 
	protected $estado = '';
	protected $fec_creacion = '';///AÑADIDO CAMPO FEC_CREACION
	//------------------------------
    //constructores de la clase
	//------------------------------
    public function __construct($id_equipo,$jugador1,$seguro_jug1,$jugador2,$seguro_jug2,$liga,$division,$pagado,$estado,$fec_creacion){
		if($jugador2 == '' && $jugador1 == '' && !empty($id_equipo)){//SI EL jugador2 ES VACIO ES PORQUE RECUPERO DE LA BD
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM equipo WHERE id_equipo = '$id_equipo'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_equipo = $resultados['id_equipo'];
				$this->jugador1 = $resultados['jugador1'];
				$this->seguro_jug1 = $resultados['seguro_jug1'];
				$this->jugador2 = $resultados['jugador2'];
				$this->seguro_jug2 = $resultados['seguro_jug2'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->pagado = $resultados['pagado'];
				$this->estado = $resultados['estado']; 
				$this->fec_creacion = $resultados['fec_creacion'];
			}
			$db->cerrar_conexion();
		}
		else if($jugador2 != '' && $jugador1 != '' && $liga != '' && $division != '' && $id_equipo == '' && $pagado == '' && $estado == ''){//busco por jugadores,liga y division
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM equipo WHERE jugador1 = '$jugador1' AND jugador2 = '$jugador2' AND liga = '$liga' AND division = '$division'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_equipo = $resultados['id_equipo'];
				$this->jugador1 = $resultados['jugador1'];
				$this->seguro_jug1 = $resultados['seguro_jug1'];
				$this->jugador2 = $resultados['jugador2'];
				$this->seguro_jug2 = $resultados['seguro_jug2'];
				$this->liga = $resultados['liga'];
				$this->division = $resultados['division'];
				$this->pagado = $resultados['pagado'];
				$this->estado = $resultados['estado']; 
				$this->fec_creacion = $resultados['fec_creacion'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_equipo = $id_equipo;
			$this->jugador1 = $jugador1;
			$this->seguro_jug1 = $seguro_jug1;
			$this->jugador2 = $jugador2;
			$this->seguro_jug2 = $seguro_jug2;
			$this->liga = $liga;
			$this->division = $division;
			$this->pagado = $pagado;
			$this->estado = $estado; 
			$this->fec_creacion = $fec_creacion;
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
		$db->consulta("INSERT INTO `equipo` (`id_equipo`,`jugador1`,`seguro_jug1`,`jugador2`,`seguro_jug2`,`liga`,`division`,`pagado`,`estado`,`fec_creacion`) VALUES (NULL,'$this->jugador1','$this->seguro_jug1','$this->jugador2','$this->seguro_jug2','$this->liga','$this->division','$this->pagado','$this->estado','$this->fec_creacion'); ");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE `equipo` SET `jugador1`='$this->jugador1',`seguro_jug1`='$this->seguro_jug1',`jugador2`='$this->jugador2',`seguro_jug2`='$this->seguro_jug2',`liga`='$this->liga',`division`='$this->division',`pagado`='$this->pagado',`estado`='$this->estado' WHERE `id_equipo` = '$this->id_equipo'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM `equipo` WHERE `id_equipo` = '$this->id_equipo'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>