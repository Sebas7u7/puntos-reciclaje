<?php
class Punto_recoleccionDAO{
    public function __construct(){
        // Puede estar vacío
    }
    public function registrar($nombre, $direccion, $latitud, $longitud, $estado, $colaborador_id) {
        return "INSERT INTO Punto_Recoleccion (
            nombre,
            direccion,
            latitud,
            longitud,
            estado,
            Colaborador_idColaborador
        ) VALUES (
            '$nombre',
            '$direccion',
            $latitud,
            $longitud,
            '$estado',
            $colaborador_id
        );";
    }
    public function consultarTodos(){
        return "SELECT * FROM punto_recoleccion";
    }
    public function clasificar_by_categoria($categoria) {
        return "SELECT * FROM punto_recoleccion WHERE nombre LIKE '%$categoria%'";
    }
    // Buscar puntos que reciben un residuo por nombre exacto
    public function puntos_por_residuo($nombreResiduo) {
        return "SELECT prc.*, c.nombre as colaborador_nombre FROM punto_recoleccion prc
                JOIN punto_residuo pr ON pr.Punto_Recoleccion_idPunto_Recoleccion = prc.idPunto_Recoleccion
                JOIN residuo r ON pr.Residuo_idResiduo = r.idResiduo
                JOIN colaborador c ON prc.Colaborador_idColaborador = c.idColaborador
                WHERE r.nombre = '" . addslashes($nombreResiduo) . "'";
    }
}
?>