<?php
class Noticia{
	//atributos
	protected $id_noticia = '';
	protected $liga = '';
	protected $division = '';
	protected $resumen = ''; 
	protected $descripcion = '';
	protected $fecha = '';
	protected $imagenes = '';
	//-------------------------------
    //constructores de la clase
	//---------------------------------
    public function __construct($id_noticia,$liga,$division,$resumen,$descripcion,$fecha,$imagenes){
			$this->id_noticia = $id_noticia;
			$this->liga = $liga;
			$this->division = $division;
			$this->resumen = $resumen; 
			$this->descripcion = $descripcion;
			$this->fecha = $fecha;
			$this->imagenes = $imagenes;
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
		$db->consulta("INSERT INTO  `noticia` (`id_noticia` ,`liga` ,`division` ,`resumen` ,`descripcion` ,`fecha` ,`imagenes`) VALUES (NULL ,  '$this->liga', '$this->division', '$this->resumen', '$this->descripcion', '$this->fecha', '$this->imagenes');");
		$db->cerrar_conexion();
	}
	public function modificar(){
		$db = new MySQL('session');//LIGA PADEL
		$db->consulta("UPDATE  `noticia` SET `liga`='$this->liga' ,`division`='$this->division' ,`resumen`='$this->resumen' ,`descripcion`='$this->descripcion' ,`fecha`='$this->fecha' ,`imagenes`='$this->imagenes' WHERE `noticia`.`id_noticia` = '$this->id_noticia'; ");
		$db->cerrar_conexion();
	}
	/*public function borrar(){
		$db = new MySQL();
		$db->consulta("DELETE FROM  `noticia` WHERE `id_noticia` = '$this->id_noticia'; ");
		$db->cerrar_conexion();
	}*/
	/*public function __destruct($nombre,$apellidos,$email,$telefono,$password,$dni,$cuenta_paypal,$direccion,$cp,$pais,$provincia,$ciudad,$fec_registro,$){
	}*/
}

?>