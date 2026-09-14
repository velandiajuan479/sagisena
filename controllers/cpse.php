
<?php
require_once 'models/mpse.php';

class cpse
{
    private $modelo;

    public function __construct($conexion)
    {
        $this->modelo = new mpse($conexion);
    }

    public function index()
    {
        $planes = $this->modelo->obtenerTodos();
        require 'views/vpse.php';
    }
}
?>

