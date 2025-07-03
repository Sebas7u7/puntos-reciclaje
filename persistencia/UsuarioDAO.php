<?php
class UsuarioDAO{
    public function __construct(){
        // Puede estar vacío
    }

    public function consultarTodos() {
        return "SELECT 
            idUsuario,
            nombre,
            apellido,
            telefono,
            nickname,
            foto_perfil,
            idCuenta
        FROM Usuario;";
    }
    public function registrar(Conexion $conexion, $nombre, $apellido, $idCuenta){
        $sql = "INSERT INTO Usuario (nombre, apellido, idCuenta) VALUES (?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for UsuarioDAO::registrar: Error en SQL o conexión.");
            return false;
        }
        $stmt->bind_param("ssi", $nombre, $apellido, $idCuenta);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed for UsuarioDAO::registrar: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    public function consultarCuenta(Conexion $conexion, $idCuenta){
        $sql = "SELECT idUsuario, nombre, apellido, idCuenta FROM Usuario WHERE idCuenta = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for UsuarioDAO::consultarCuenta: Error en SQL o conexión.");
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
            error_log("Execute failed for UsuarioDAO::consultarCuenta: " . $stmt->error);
        }
        $stmt->close();
        return $datos;
    }

    /**
     * Actualiza los datos de un usuario en la tabla Usuario.
     * @param Conexion $conexion
     * @param int $idUsuario El ID del usuario a actualizar.
     * @param string $nombre Nuevo nombre.
     * @param string $apellido Nuevo apellido.
     * @return bool True si la actualización fue exitosa y afectó filas, false en caso contrario.
     */
    public function actualizar(Conexion $conexion, $idUsuario, $nombre, $apellido) {
        $sql = "UPDATE Usuario SET nombre = ?, apellido = ? WHERE idUsuario = ?";
        $stmt = $conexion->prepararConsulta($sql);

        $stmt->bind_param("ssi", $nombre, $apellido, $idUsuario);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $affected_rows > 0; // Devuelve true si al menos una fila fue afectada
        } else {
            error_log("Execute failed for UsuarioDAO::actualizar: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    /**
     * Actualiza todos los datos de un usuario en la tabla Usuario.
     * @param Conexion $conexion
     * @param int $idUsuario El ID del usuario a actualizar.
     * @param string $nombre Nuevo nombre.
     * @param string $apellido Nuevo apellido.
     * @param string $telefono Nuevo teléfono.
     * @param string $nickname Nuevo nickname.
     * @param string $foto_perfil Nueva foto de perfil.
     * @return bool True si la actualización fue exitosa y afectó filas, false en caso contrario.
     */
    public function actualizarDatosCompletos(Conexion $conexion, $idUsuario, $nombre, $apellido, $telefono, $nickname, $foto_perfil) {
        $sql = "UPDATE Usuario SET nombre = ?, apellido = ?, telefono = ?, nickname = ?, foto_perfil = ? WHERE idUsuario = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for UsuarioDAO::actualizarDatosCompletos: Error en SQL o conexión.");
            return false;
        }
        $stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $nickname, $foto_perfil, $idUsuario);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>