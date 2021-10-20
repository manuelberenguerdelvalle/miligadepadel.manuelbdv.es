<?php
class Datos{
	//atributos
	protected $id_datos = '';
	protected $password = '';
	protected $c1 = '';
	protected $c2 = '';
	protected $c3 = '';
	protected $c4 = '';
	protected $c5 = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_datos,$password,$c1,$c2,$c3,$c4,$c5){
		if($id_datos != '' && $id_datos != NULL && $password == ''){
			$db = new MySQL('unicas');//password PADEL
			$consulta = $db->consulta("SELECT * FROM datos WHERE id_datos = '$id_datos'; ");
			if($consulta->num_rows>0){
				$resultados = $consulta->fetch_array(MYSQLI_ASSOC);
				$this->id_datos = $resultados['id_datos'];
				$this->password = $resultados['password'];
				$this->c1 = $resultados['c1'];
				$this->c2 = $resultados['c2'];
				$this->c3 = $resultados['c3'];
				$this->c4 = $resultados['c4'];
				$this->c5 = $resultados['c5'];
			}
			$db->cerrar_conexion();
		}
		else{
			$this->id_datos = $id_datos;
			$this->password = $password;
			$this->c1 = $c1;
			$this->c2 = $c2;
			$this->c3 = $c3;
			$this->c4 = $c4;
			$this->c5 = $c5;
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
		$db = new MySQL('unicas');//password PADEL
		$db->consulta("INSERT INTO `datos` (`id_datos`,`password`,`c1`,`c2`,`c3`,`c4`,`c5`) VALUES (NULL,'$this->password','$this->c1','$this->c2','$this->c3','$this->c4','$this->c5');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('unicas');//password PADEL
		$db->consulta("UPDATE `datos` SET `password`='$this->password',`c1`='$this->c1',`c2`='$this->c2',`c3`='$this->c3',`c4`='$this->c4',`c5`='$this->c5' WHERE `id_datos` = '$this->id_datos'; ");
		$db->cerrar_conexion();
	}
	public function borrar(){
		$db = new MySQL('unicas');//password PADEL
		$db->consulta("DELETE FROM `datos` WHERE `id_datos` = '$this->id_datos'; ");
		$db->cerrar_conexion();
	}
	/*public function __destruct($c3,$c4,$email,$c2,$password,$c1,$cuenta_paypal,$direccion,$c5,$pais,$provincia,$ciudad,$fec_registro,$bloqueo){
	}*/
}

?>