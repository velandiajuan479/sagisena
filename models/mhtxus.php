<?php
class Mhdt {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    /**
     * Trae el norad de hoja de trabajo mediante nombre o id de usuario desde hdtxusu.
     * @param string|null $nomusu
     * @param int|null $idusu
     * @return array|null
     */
    public function buscarNorad($nomusu = null, $idusu = null) {
        $sql = "SELECT hdtxusu.idnorad, usuario.idusu, usuario.nomusu
                FROM hdtxusu
                INNER JOIN usuario ON usuario.idusu = hdtxusu.idusu
                WHERE 1=1";
        $params = [];

        if ($nomusu !== null && $nomusu !== '') {
            $sql .= " AND usuario.nomusu LIKE :nomusu";
            $params[':nomusu'] = "%$nomusu%";
        }
        if ($idusu !== null && $idusu !== '') {
            $sql .= " AND usuario.idusu = :idusu";
            $params[':idusu'] = $idusu;
        }

        $stmt = $this->con->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>