<?php
require_once(__DIR__ . '/../logica/ForoCategoria.php');
require_once(__DIR__ . '/Conexion.php');

class ForoCategoriaDAO {
    public function listar() {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $categorias = array();
        $sql = "SELECT idCategoria, nombre, descripcion FROM foro_categoria";
        $conexion->ejecutarConsulta($sql);
        while ($registro = $conexion->siguienteRegistro()) {
            $categorias[] = new ForoCategoria($registro[0], $registro[1], $registro[2]);
        }
        $conexion->cerrarConexion();
        return $categorias;
    }
}
