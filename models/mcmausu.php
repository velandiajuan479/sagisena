<?php

/**
 * Modelo para Carga Masiva de Usuarios
 * 
 * Sistema de carga masiva de usuarios
 * 
 * @author Cristian Duarte Hidalgo - Aprendiz SENA
 * @version 1.0.0
 * @since 2025
 */

class Mcmausu
{
    /**
     * Guarda un nuevo usuario
     */
    public function guardar(string $documento, string $nombre): ?int
    {
        if (!$this->validarDocumento($documento) || !$this->validarNombre($nombre)) {
            throw new InvalidArgumentException("Datos de usuario inválidos");
        }

        $sql = "INSERT INTO usuario (ndocusu, nomusu) VALUES (?, ?)";
        $parametros = [$documento, $nombre];

        $conexion = $this->obtenerConexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute($parametros);

        return $conexion->lastInsertId();
    }

    /**
     * Verifica si existe un documento
     */
    public function existeDocumento(string $documento): bool
    {
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE ndocusu = ?";
        $resultado = $this->ejecutarConsultaPreparada($sql, [$documento]);
        return $resultado[0]['total'] > 0;
    }

    /**
     * Obtiene usuario por documento
     */
    public function obtenerPorDocumento(string $documento): ?array
    {
        $sql = "SELECT idusu, ndocusu, nomusu FROM usuario WHERE ndocusu = ?";
        $resultado = $this->ejecutarConsultaPreparada($sql, [$documento]);
        return $resultado ? $resultado[0] : null;
    }

    /**
     * Verifica si existe una ficha
     */
    public function existeFicha(int $idFicha): bool
    {
        $sql = "SELECT COUNT(*) as total FROM ficha WHERE idfic = ?";
        $resultado = $this->ejecutarConsultaPreparada($sql, [$idFicha]);
        return $resultado[0]['total'] > 0;
    }

    /**
     * Crea un registro usuario-ficha
     */
    public function crearRegistro(int $idUsuario, int $idFicha): bool
    {
        if ($this->existeRegistro($idUsuario, $idFicha)) {
            throw new Exception("El usuario ya está registrado en esta ficha");
        }

        $sql = "INSERT INTO registro (idusu, idfic, fecreg) VALUES (?, ?, NOW())";
        return $this->ejecutarConsultaPreparada($sql, [$idUsuario, $idFicha]) !== false;
    }

    /**
     * Verifica si existe un registro usuario-ficha
     */
    public function existeRegistro(int $idUsuario, int $idFicha): bool
    {
        $sql = "SELECT COUNT(*) as total FROM registro WHERE idusu = ? AND idfic = ?";
        $resultado = $this->ejecutarConsultaPreparada($sql, [$idUsuario, $idFicha]);
        return $resultado[0]['total'] > 0;
    }

    // Métodos de validación
    public function validarDocumento(string $documento): bool
    {
        return preg_match('/^[0-9]{7,15}$/', trim($documento));
    }

    public function validarNombre(string $nombre): bool
    {
        $nombre = trim($nombre);
        return !empty($nombre) && strlen($nombre) <= 125 && preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $nombre);
    }

    public function validarTipoDocumento(string $tipo): bool
    {
        $tiposValidos = ['CC', 'TI', 'CE', 'PAS'];
        return in_array(strtoupper(trim($tipo)), $tiposValidos);
    }

    // Métodos privados de utilidad para base de datos
    private function obtenerConexion(): PDO
    {
        $modelo = new conexion();
        return $modelo->get_conexion();
    }

    private function ejecutarConsultaPreparada(string $sql, array $parametros): array|false
    {
        $conexion = $this->obtenerConexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
