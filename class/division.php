<?php
class Division{
	//atributos
	protected $id_division = '';
	protected $fec_creacion = '';
	protected $precio = '';
	protected $liga = ''; 
	protected $suscripcion = '';
	protected $num_division = '';
	protected $max_equipos = '';
	protected $comienzo = '';
	protected $bloqueo = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_division,$fec_creacion,$precio,$liga,$suscripcion,$num_division,$max_equipos,$comienzo,$bloqueo){
		//EN ESTA CONSULTA ENTRA SI TIENE LA LIGA Y EL NUMERO DE DIVISION
		if($liga != '' && $num_division != '' && $id_division == '' && $fec_creacion == '' && $precio == '' && $max_equipos == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM division WHERE liga = '$liga' and num_division = '$num_division'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_division = $resultados['id_division'];
				$this->fec_creacion = $resultados['fec_creacion'];
				$this->precio = $resultados['precio'];
				$this->liga = $resultados['liga']; 
				$this->suscripcion = $resultados['suscripcion'];
				$this->num_division = $resultados['num_division'];
				$this->max_equipos = $resultados['max_equipos'];
				$this->comienzo = $resultados['comienzo'];
				$this->bloqueo = $resultados['bloqueo'];
			}
			$db->cerrar_conexion();
		}
		//EN ESTA CONSULTA ENTRA SI TIENE LA LIGA Y EL ID_DIVISION, SE USA PARA CREAR LA DIVISION Y ADEMAS ASIGARLA A SESSION
		else if($liga != '' && $id_division != '' && $num_division == '' && $fec_creacion == '' && $precio == '' && $max_equipos == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM division WHERE liga = '$liga' and id_division = '$id_division'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_division = $resultados['id_division'];
				$this->fec_creacion = $resultados['fec_creacion'];
				$this->precio = $resultados['precio'];
				$this->liga = $resultados['liga']; 
				$this->suscripcion = $resultados['suscripcion'];
				$this->num_division = $resultados['num_division'];
				$this->max_equipos = $resultados['max_equipos'];
				$this->comienzo = $resultados['comienzo'];
				$this->bloqueo = $resultados['bloqueo'];
			}
			$db->cerrar_conexion();
		}
		else if($id_division != '' && $liga == '' && $num_division == '' && $fec_creacion == '' && $precio == '' && $max_equipos == ''){
			$db = new MySQL('session');//LIGA PADEL
			$consulta = $db->consulta("SELECT * FROM division WHERE id_division = '$id_division'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_division = $resultados['id_division'];
				$this->fec_creacion = $resultados['fec_creacion'];
				$this->precio = $resultados['precio'];
				$this->liga = $resultados['liga']; 
				$this->suscripcion = $resultados['suscripcion'];
				$this->num_division = $resultados['num_division'];
				$this->max_equipos = $resultados['max_equipos'];
				$this->comienzo = $resultados['comienzo'];
				$this->bloqueo = $resultados['bloqueo'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_division = $id_division;
			$this->fec_creacion = $fec_creacion;
			$this->precio = $precio;
			$this->liga = $liga; 
			$this->suscripcion = $suscripcion;
			$this->num_division = $num_division;
			$this->max_equipos = $max_equipos;
			$this->comienzo = $comienzo;
			$this->bloqueo = $bloqueo;
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
		$db->consulta("INSERT INTO  `division` (`id_division`,`fec_creacion`,`precio`,`liga`,`suscripcion`,`num_division`,`max_equipos`,`comienzo`,`bloqueo`) VALUES (NULL,'$this->fec_creacion','$this->precio','$this->liga','$this->suscripcion','$this->num_division','$this->max_equipos','$this->comienzo','$this->bloqueo');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE  `division` SET `fec_creacion`='$this->fec_creacion',`precio`='$this->precio',`liga`='$this->liga',`suscripcion`='$this->suscripcion',`num_division`='$this->num_division',`max_equipos`='$this->max_equipos',`comienzo`='$this->comienzo',`bloqueo`='$this->bloqueo' WHERE `division`.`id_division` = '$this->id_division'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("DELETE FROM  `division` WHERE `id_division` = '$this->id_division'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>