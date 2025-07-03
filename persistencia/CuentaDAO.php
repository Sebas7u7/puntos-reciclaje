<?php
class CuentaDAO{

    public function __construct(){
        // Constructor can be empty if DAO is stateless
    }

    public function consultarTodos() {
        return "SELECT idCuenta,correo,clave,rol,estado
        FROM Cuenta;";
    }
    // ... (consultarPorCorreo, cambiarClave, registrar ya existen y están bien) ...

    /**
     * Verifica si un correo ya existe en la tabla Cuenta, excluyendo un idCuenta específico.
     * @param Conexion $conexion
     * @param string $correo
     * @param int $idCuentaExcluir El idCuenta del usuario actual, para permitirle "guardar" su propio correo.
     * @return bool True si el correo existe para OTRO usuario, false en caso contrario.
     */
    public function verificarCorreoExistente(Conexion $conexion, $correo, $idCuentaExcluir) {
        $sql = "SELECT idCuenta FROM Cuenta WHERE correo = ? AND idCuenta != ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for CuentaDAO::verificarCorreoExistente");
            return true; // Asumir que existe para prevenir colisión en caso de error de preparación
        }
        $stmt->bind_param("si", $correo, $idCuentaExcluir);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            $existe = $resultado->num_rows > 0;
            $stmt->close();
            return $existe;
        }
        $stmt->close();
        return true; // Asumir que existe en caso de error de ejecución
    }


    /**
     * Actualiza el correo de una cuenta.
     * @param Conexion $conexion
     * @param int $idCuenta
     * @param string $nuevoCorreo
     * @return bool True si la actualización fue exitosa y afectó filas, false en caso contrario.
     */
    public function actualizarCorreo(Conexion $conexion, $idCuenta, $nuevoCorreo) {
        $sql = "UPDATE Cuenta SET correo = ? WHERE idCuenta = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) {
            error_log("Prepare failed for CuentaDAO::actualizarCorreo");
            return false;
        }
        $stmt->bind_param("si", $nuevoCorreo, $idCuenta);
        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $affected_rows > 0;
        } else {
            error_log("Execute failed for CuentaDAO::actualizarCorreo: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
    /**
     * Fetches account details by email.
     * @param Conexion $conexion The database connection object.
     * @param string $correo The email to search for.
     * @return array|null Account data as an associative array or null if not found/error.
     */
    public function consultarPorCorreo(Conexion $conexion, $correo){
        $sql = "SELECT idCuenta, correo, clave, rol, estado FROM Cuenta WHERE correo = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return null;

        $stmt->bind_param("s", $correo);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows == 1) {
                $cuentaData = $resultado->fetch_assoc();
                $stmt->close();
                return $cuentaData;
            }
        } else {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $stmt->close();
        return null;
    }
    /**
     * Updates the password for a given email.
     * @param Conexion $conexion The database connection object.
     * @param string $correo The email of the account to update.
     * @return bool True on success, false on failure.
     */
    public function activar(Conexion $conexion, $correo){
        $sql = "UPDATE Cuenta SET estado = ? WHERE correo = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return false;

        $estado = 1;
        $stmt->bind_param("is", $estado,$correo);
        $success = $stmt->execute();
        if (!$success) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        // Check if any row was actually updated
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $success && ($affected_rows > 0);
    }
    /**
     * Updates the password for a given email.
     * @param Conexion $conexion The database connection object.
     * @param string $correo The email of the account to update.
     * @param string $nuevaHashedClave The new hashed password.
     * @return bool True on success, false on failure.
     */
    public function cambiarClave(Conexion $conexion, $correo, $nuevaHashedClave){
        $sql = "UPDATE Cuenta SET clave = ? WHERE correo = ?";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ss", $nuevaHashedClave, $correo);
        $success = $stmt->execute();
        if (!$success) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        // Considera éxito aunque la contraseña sea igual (no importa affected_rows)
        $stmt->close();
        return $success;
    }

    /**
     * Registers a new account.
     * @param Conexion $conexion The database connection object.
     * @param string $correo User's email.
     * @param string $hashedClave Hashed password.
     * @param int $rol User's role.
     * @return int|false The new account ID on success, false on failure.
     */
    public function registrar(Conexion $conexion, $correo, $hashedClave, $rol){
        $sql = "INSERT INTO Cuenta (correo, clave, rol) VALUES (?, ?, ?)";
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ssi", $correo, $hashedClave, $rol);
        if ($stmt->execute()) {
            $idCuenta = $conexion->obtenerLlaveAutonumerica();
            $stmt->close();
            return $idCuenta;
        } else {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $stmt->close();
        return false;
    }
    
    /**
     * Fetches account details by ID.
     * @param Conexion $conexion The database connection object.
     * @param int $idCuenta The account ID.
     * @return array|null Account data as an associative array or null if not found/error.
     */
    public function consultarId(Conexion $conexion, $idCuenta){ // Corrected SQL comma issue from original
        $sql = "SELECT idCuenta, correo, clave, rol FROM Cuenta WHERE idCuenta = ?"; // Removed extra comma
        $stmt = $conexion->prepararConsulta($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $idCuenta);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows == 1) {
                $cuentaData = $resultado->fetch_assoc();
                $stmt->close();
                return $cuentaData;
            }
        } else {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $stmt->close();
        return null;
    }    
}
?>