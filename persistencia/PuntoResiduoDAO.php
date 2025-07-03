<?php
class PuntoResiduoDAO {
    public function obtenerResiduosPorPunto($idPunto) {
        return "SELECT r.nombre FROM punto_residuo pr ".
               "JOIN residuo r ON pr.Residuo_idResiduo = r.idResiduo ".
               "WHERE pr.Punto_Recoleccion_idPunto_Recoleccion = $idPunto";
    }
}
?>
