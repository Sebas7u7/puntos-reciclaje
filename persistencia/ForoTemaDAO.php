<?php
require_once(__DIR__ . '/../logica/ForoTema.php');
require_once(__DIR__ . '/Conexion.php');
require_once(__DIR__ . '/ForoCategoriaDAO.php');
require_once(__DIR__ . '/../logica/Usuario.php');

class ForoTemaDAO {
    public function listar() {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $temas = array();
        $sql = "SELECT idTema, titulo, descripcion, fecha_creacion, idUsuario, idCategoria FROM foro_tema ORDER BY fecha_creacion DESC";
        $conexion->ejecutarConsulta($sql);
        $categoriaDAO = new ForoCategoriaDAO();
        $categorias = $categoriaDAO->listar();
        $usuario = new Usuario();
        $usuarios = $usuario->mapearPorId();
        while ($registro = $conexion->siguienteRegistro()) {
            $cat = null;
            foreach ($categorias as $c) {
                if ($c->getIdCategoria() == $registro[5]) { $cat = $c; break; }
            }
            $user = isset($usuarios[$registro[4]]) ? $usuarios[$registro[4]] : null;
            $temas[] = new ForoTema($registro[0], $registro[1], $registro[2], $registro[3], $user, $cat);
        }
        $conexion->cerrarConexion();
        return $temas;
    }
    public function crear($titulo, $descripcion, $idUsuario, $idCategoria) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $fecha = date('Y-m-d H:i:s');
        $sql = "INSERT INTO foro_tema (titulo, descripcion, fecha_creacion, idUsuario, idCategoria) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        $stmt->bind_param("sssii", $titulo, $descripcion, $fecha, $idUsuario, $idCategoria);
        $stmt->execute();
        $stmt->close();
        $conexion->cerrarConexion();
    }
}
