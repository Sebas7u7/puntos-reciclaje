
<?php
class SolicitudDAO {
    public function listarPorUsuario($idUsuario) {
        $sql = "SELECT 
                s.idSolicitud_Recoleccion,
                s.direccion,
                s.fecha_solicitud,
                s.fecha_programada,
                s.estado,
                s.Usuario_idUsuario,
                s.Residuo_idResiduo,
                s.Colaborador_idColaborador,
                s.cantidad,
                s.comentarios,
                s.descripcion_proceso
            FROM 
                Solicitud_Recoleccion s
            WHERE s.Usuario_idUsuario = " . intval($idUsuario) . 
            " ORDER BY s.fecha_solicitud DESC;";
        return $sql;
    }

    /**
     * Inserta una solicitud de recolección puerta a puerta en la base de datos.
     * @param Conexion $conexion
     * @param int $idUsuario
     * @param int $idColaborador
     * @param string $tipoResiduo (ejemplo: 'electronico')
     * @param string $direccion
     * @param string $fecha (YYYY-MM-DD)
     * @param string $hora (HH:MM)
     * @param int $cantidad
     * @param string $comentarios
     * @return bool
     */
    public function crearSolicitudPuertaAPuerta($conexion, $idUsuario, $idColaborador, $idResiduo, $direccion, $fecha, $hora, $cantidad, $comentarios, $descripcion_proceso = null) {
        $sql = "INSERT INTO solicitud_recoleccion (direccion, fecha_solicitud, fecha_programada, estado, Usuario_idUsuario, Residuo_idResiduo, Colaborador_idColaborador, cantidad, comentarios, descripcion_proceso) VALUES (?, ?, CONCAT(?, ' ', ?), 'pendiente', ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return false;
        $fecha_solicitud = date('Y-m-d');
        $fecha_programada = $fecha;
        $stmt->bind_param("ssssiiiiss", $direccion, $fecha_solicitud, $fecha_programada, $hora, $idUsuario, $idResiduo, $idColaborador, $cantidad, $comentarios, $descripcion_proceso);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function actualizar($id, $fechaProgramada, $estado, $descripcion_proceso = null) {
        $fechaProgramadaSQL = $fechaProgramada !== null ? "'$fechaProgramada'" : "NULL";
        $descripcionSQL = $descripcion_proceso !== null ? ", descripcion_proceso = '" . addslashes($descripcion_proceso) . "'" : "";
        return "
            UPDATE Solicitud_Recoleccion 
            SET fecha_programada = $fechaProgramadaSQL,
            estado = '$estado' $descripcionSQL
            WHERE idSolicitud_Recoleccion = $id;
        ";
    }

    public function listar($idColaborador = null) {
        $sql = "SELECT 
                s.idSolicitud_Recoleccion,
                s.direccion,
                s.fecha_solicitud,
                s.fecha_programada,
                s.estado,
                s.Usuario_idUsuario,
                s.Residuo_idResiduo,
                s.Colaborador_idColaborador,
                s.cantidad,
                s.comentarios,
                s.descripcion_proceso
            FROM 
                Solicitud_Recoleccion s";
        if ($idColaborador !== null) {
            $sql .= " WHERE s.Colaborador_idColaborador = " . intval($idColaborador);
        }
        $sql .= ";";
        return $sql;
    }
}
?>
