<?php
class ColaboradorDAO{
    
    public function __construct(){
        // Puede estar vacío
    }
    public function consultarTodos() {
        return "SELECT 
            idColaborador,
            nombre,
            servicio_ofrecido,
            telefono,
            direccion,
            foto_perfil,
            idCuenta
        FROM Colaborador;";
    }
    public function registrar(Conexion $conexion, $nombre, $servicio_ofrecido, $idCuenta){
        $sql = "INSERT INTO Colaborador (nombre, servicio_ofrecido, idCuenta) 
                VALUES (?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for ColaboradorDAO::registrar.");
            return false;
        }
        $stmt->bind_param("ssi", $nombre, $servicio_ofrecido, $idCuenta);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed for ColaboradorDAO::registrar: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    public function consultarCuenta(Conexion $conexion, $idCuenta){
        $sql = "SELECT idColaborador, nombre, servicio_ofrecido, idCuenta
                FROM Colaborador
                WHERE idCuenta = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for ColaboradorDAO::consultarCuenta.");
            return null;
        }
        $stmt->bind_param("i", $idCuenta);
        $datos = null;
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows == 1) {
                $datos = $resultado->fetch_assoc();
            }
        } else {
            error_log("Execute failed for ColaboradorDAO::consultarCuenta: " . $stmt->error);
        }
        $stmt->close();
        return $datos;
    }

    /**
     * Actualiza los datos de un colaborador en la tabla Colaborador.
     * @param Conexion $conexion
     * @param int $idColaborador El ID del colaborador a actualizar.
     * @param string $nombre Nuevo nombre.
     * @param string $servicio_ofrecido Nuevo servicio ofrecido.
     * @return bool True si la actualización fue exitosa y afectó filas, false en caso contrario.
     */
    public function actualizar(Conexion $conexion, $idColaborador, $nombre, $servicio_ofrecido) {
        $sql = "UPDATE Colaborador SET nombre = ?, servicio_ofrecido = ? WHERE idColaborador = ?";
        $stmt = $conexion->prepararConsulta($sql);

        if (!$stmt) {
            error_log("Prepare failed for ColaboradorDAO::actualizar: Error en SQL o conexión.");
            return false;
        }

        $stmt->bind_param("sssi", $nombre, $servicio_ofrecido, $idColaborador);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $affected_rows > 0;
        } else {
            error_log("Execute failed for ColaboradorDAO::actualizar: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    public function actualizarDatosCompletos(Conexion $conexion, $idColaborador, $nombre, $telefono, $direccion, $servicio_ofrecido, $foto_perfil) {
        $sql = "UPDATE Colaborador SET nombre = ?, telefono = ?, direccion = ?, servicio_ofrecido = ?, foto_perfil = ? WHERE idColaborador = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for ColaboradorDAO::actualizarDatosCompletos: Error en SQL o conexión.");
            return false;
        }
        $stmt->bind_param("sssssi", $nombre, $telefono, $direccion, $servicio_ofrecido, $foto_perfil, $idColaborador);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function obtenerResiduosColaborador($conexion, $idColaborador) {
        $sql = "SELECT Residuo_idResiduo FROM colaborador_has_residuo WHERE Colaborador_idColaborador = ?";
        $stmt = $conexion->prepararConsulta($sql);
        $ids = [];
        if ($stmt) {
            $stmt->bind_param("i", $idColaborador);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $ids[] = $row['Residuo_idResiduo'];
                }
            }
            $stmt->close();
        }
        return $ids;
    }

    public function obtenerObservacionesResiduos($conexion, $idColaborador) {
        $sql = "SELECT observaciones FROM colaborador_has_residuo WHERE Colaborador_idColaborador = ? LIMIT 1";
        $stmt = $conexion->prepararConsulta($sql);
        $observaciones = '';
        if ($stmt) {
            $stmt->bind_param("i", $idColaborador);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $observaciones = $row['observaciones'];
                }
            }
            $stmt->close();
        }
        return $observaciones;
    }

    public function actualizarResiduosColaborador($conexion, $idColaborador, $residuos, $observaciones) {
        // Eliminar los residuos actuales
        $sqlDel = "DELETE FROM colaborador_has_residuo WHERE Colaborador_idColaborador = ?";
        $stmtDel = $conexion->prepararConsulta($sqlDel);
        if ($stmtDel) {
            $stmtDel->bind_param("i", $idColaborador);
            $stmtDel->execute();
            $stmtDel->close();
        }
        // Insertar los nuevos residuos
        $sqlIns = "INSERT INTO colaborador_has_residuo (Colaborador_idColaborador, Residuo_idResiduo, observaciones) VALUES (?, ?, ?)";
        foreach ($residuos as $idResiduo) {
            $stmtIns = $conexion->prepararConsulta($sqlIns);
            if ($stmtIns) {
                $stmtIns->bind_param("iis", $idColaborador, $idResiduo, $observaciones);
                $stmtIns->execute();
                $stmtIns->close();
            }
        }
        return true;
    }
}
?>