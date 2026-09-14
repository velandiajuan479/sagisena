<?php
class Mfxus {
    // Atributos
    private $idusu;
    private $idfic;
    private $actfic;

    // Getters
    function getIdusu() { return $this->idusu; }
    function getIdfic() { return $this->idfic; }
    function getActfic() { return $this->actfic; }

    // Setters
    function setIdusu($idusu) { $this->idusu = $idusu; }
    function setIdfic($idfic) { $this->idfic = $idfic; }
    function setActfic($actfic) { $this->actfic = $actfic; }

    // Obtener todos
    public function getAll(){
        try{
            $sql = "SELECT x.idusu, u.nomusu, x.idfic, f.nomfic, x.actfic
                FROM usufic AS x
                INNER JOIN ficha AS f ON x.idfic = f.idfic
                INNER JOIN usuario AS u ON x.idusu = u.idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>GETALL. ";
        }
    }

    // Obtener uno
    public function getOne(){
        try{
            $sql = "SELECT x.idusu, x.idfic, x.actfic, f.nomfic
                FROM usufic AS x
                INNER JOIN ficha AS f ON x.idfic = f.idfic
                WHERE x.idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idusu', $this->idusu);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>GET ONE. ";
        }
    }

    // Guardar nuevo
    public function save(){
        try{
            $sql = "INSERT INTO usufic (idusu, idfic, actfic)
                VALUES (:idusu, :idfic, :actfic)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idusu', $this->idusu);
            $result->bindParam(':idfic', $this->idfic);
            $result->bindParam(':actfic', $this->actfic);
            $result->execute();
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>FUNCTION SAVE. ";
        }
    }

    // Editar existente
    public function edit(){
        try{
            $sql = "UPDATE usufic SET idfic = :idfic, actfic = :actfic
                WHERE idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idusu', $this->idusu);
            $result->bindParam(':idfic', $this->idfic);
            $result->bindParam(':actfic', $this->actfic);
            $result->execute();
        }catch(Exception $e){
        echo "Error: ".$e."<br><br>FUNCTION EDIT. ";
    }
    }

    // Eliminar
    public function del(){
        try{
            $sql = "DELETE FROM usufic WHERE idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idusu', $this->idusu);
            $result->execute();
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>FUNCTION DEL. ";
	    }
    }
    	

	public function getAllUsu(){
        try{
		    $sql = "SELECT idusu, ndocusu, nomusu FROM usuario";
		    $modelo = new conexion();
		    $conexion = $modelo->get_conexion();
		    $result = $conexion->prepare($sql);
		    $result->execute();
		    $res = $result->fetchAll(PDO::FETCH_ASSOC);
		    return $res;
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>GETALLUSU. ";
        }
	}

	public function getAllFic(){
        try{
		    $sql = "SELECT idfic, nomfic FROM ficha";
		    $modelo = new conexion();
		    $conexion = $modelo->get_conexion();
		    $result = $conexion->prepare($sql);
		    $result->execute();
		    $res = $result->fetchAll(PDO::FETCH_ASSOC);
		    return $res;
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>GETALLFIC. ";
	    }
    }
}
?>