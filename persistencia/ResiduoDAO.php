<?php
class ResiduoDAO {
    public function consultarTodos() {
        return "SELECT * FROM Residuo;";
    }
    public function clasificar_nombre($nombre){
        return "SELECT * FROM Residuo where nombre = '$nombre';";
    }
    public function listarTodos($conexion) {
        $sql = "SELECT idResiduo, nombre, descripcion, categoria FROM residuo ORDER BY nombre ASC";
        $stmt = $conexion->prepararConsulta($sql);
        $residuos = [];
        if ($stmt && $stmt->execute()) {
            $resultado = $stmt->get_result();
            while ($row = $resultado->fetch_assoc()) {
                $residuos[] = $row;
            }
        }
        $stmt->close();
        return $residuos;
    }
}
?>
