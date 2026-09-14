
<?php
class Mpse
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT ps.*, f.nomfic, f.codpro, v.nomval, u.nomusu, c.descom, r.nomres, a.nomact, a.duract, a.desact, au.nomaul 
                FROM plansesion ps
                INNER JOIN ficha f ON ps.idfic = f.idfic
                INNER JOIN valprog v ON f.idval = v.idval
                INNER JOIN usuarios u ON ps.idusu = u.idusu
                INNER JOIN competencia c ON ps.idcom = c.idcom
                INNER JOIN resultado r ON ps.idres = r.idres
                INNER JOIN actividad a ON ps.idact = a.idact
                INNER JOIN aula au ON ps.idaul = au.idaul";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
