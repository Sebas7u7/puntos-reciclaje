
<?php

class SolicitudDAO {

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
    public function crearSolicitudPuertaAPuerta($conexion, $idUsuario, $idColaborador, $idResiduo, $direccion, $fecha, $hora, $cantidad, $comentarios) {
        $sql = "INSERT INTO solicitud_recoleccion (direccion, fecha_solicitud, fecha_programada, estado, Usuario_idUsuario, Residuo_idResiduo, Colaborador_idColaborador, cantidad, comentarios) VALUES (?, ?, CONCAT(?, ' ', ?), 'pendiente', ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return false;
        $fecha_solicitud = date('Y-m-d');
        $fecha_programada = $fecha;
        $stmt->bind_param("ssssiiiis", $direccion, $fecha_solicitud, $fecha_programada, $hora, $idUsuario, $idResiduo, $idColaborador, $cantidad, $comentarios);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function actualizar($id, $fechaProgramada,$estado) {
        $fechaProgramadaSQL = $fechaProgramada !== null ? "'$fechaProgramada'" : "NULL";

        return "
            UPDATE Solicitud_Recoleccion 
            SET fecha_programada = $fechaProgramadaSQL,
            estado = '$estado'
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
                s.comentarios
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
