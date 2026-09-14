<?php
class mchdt {
    public function guardarArchivo($rutaTemporal, $nombreArchivo) {
        $carpetaDestino = 'uploads/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $rutaDestino = $carpetaDestino . basename($nombreArchivo);
        return move_uploaded_file($rutaTemporal, $rutaDestino) ? $rutaDestino : false;
    }
}
?>