<?php
require_once(__DIR__ . '/../logica/ForoRespuesta.php');
require_once(__DIR__ . '/Conexion.php');
require_once(__DIR__ . '/../logica/Usuario.php');

class ForoRespuestaDAO {
    public function listarPorTema($idTema) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $respuestas = array();
        $sql = "SELECT idRespuesta, idTema, idUsuario, contenido, fecha, idRespuestaPadre FROM foro_respuesta WHERE idTema = ? ORDER BY fecha ASC";
        $stmt = $conexion->prepararConsulta($sql);
        $stmt->bind_param("i", $idTema);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = new Usuario();
        $usuarios = $usuario->mapearPorId();
        while ($registro = $result->fetch_row()) {
            $user = isset($usuarios[$registro[2]]) ? $usuarios[$registro[2]] : null;
            $respuestas[] = new ForoRespuesta($registro[0], $registro[1], $user, $registro[3], $registro[4], $registro[5]);
        }
        $stmt->close();
        $conexion->cerrarConexion();
        return $respuestas;
    }
    public function crear($idTema, $idUsuario, $contenido, $idRespuestaPadre = null) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $fecha = date('Y-m-d H:i:s');
        $sql = "INSERT INTO foro_respuesta (idTema, idUsuario, contenido, fecha, idRespuestaPadre) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        // Si idRespuestaPadre es null, usa 'iisss' y pasa null, si no, usa el valor
        if ($idRespuestaPadre === null) {
            $stmt->bind_param("iisss", $idTema, $idUsuario, $contenido, $fecha, $idRespuestaPadre);
        } else {
            $stmt->bind_param("iissi", $idTema, $idUsuario, $contenido, $fecha, $idRespuestaPadre);
        }
        $stmt->execute();
        $stmt->close();
        $conexion->cerrarConexion();
    }
}
