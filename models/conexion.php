<?php
class conexion{
	public function get_conexion(){
		include ("configuracion.php");
		$conexion=new PDO("mysql:host=$host;dbname=$db;", $user, $pass);
		
		return $conexion;
	}

	public function getOneModV(){
		$sql = "SELECT actmod FROM modulo WHERE idmod=1";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		//echo "<br>".$sql."<br>";
		$result->execute();		
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;

	}
}
?>