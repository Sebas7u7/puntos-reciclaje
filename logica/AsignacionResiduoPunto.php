<?php
require_once(__DIR__ . '/../persistencia/Conexion.php');
require_once(__DIR__ . '/../persistencia/PuntoResiduoDAO.php');
require_once(__DIR__ . '/../persistencia/ColaboradorDAO.php');
require_once(__DIR__ . '/Residuo.php');

class AsignacionResiduoPunto {
    // Obtener residuos asignables por colaborador
    public static function obtenerResiduosColaborador($idColaborador) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $colaboradorDAO = new ColaboradorDAO();
        $idsResiduos = $colaboradorDAO->obtenerResiduosColaborador($conexion, $idColaborador);
        $conexion->cerrarConexion();
        $residuos = [];
        if (!empty($idsResiduos)) {
            $residuoObj = new Residuo();
            $todos = $residuoObj->mapearPorId();
            foreach ($idsResiduos as $id) {
                if (isset($todos[$id])) {
                    $residuos[] = $todos[$id];
                }
            }
        }
        return $residuos;
    }

    // Obtener residuos asignados a un punto
    public static function obtenerResiduosDePunto($idPunto) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $dao = new PuntoResiduoDAO();
        $conexion->ejecutarConsulta($dao->obtenerResiduosPorPunto($idPunto));
        $res = [];
        while ($row = $conexion->siguienteRegistro()) {
            $res[] = $row[0];
        }
        $conexion->cerrarConexion();
        return $res;
    }

    // Asignar residuos a un punto (sobrescribe los existentes)
    public static function asignarResiduosAPunto($idPunto, $idsResiduos) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $conexion->ejecutarConsultaDirecta("DELETE FROM punto_residuo WHERE Punto_Recoleccion_idPunto_Recoleccion = $idPunto");
        foreach ($idsResiduos as $idResiduo) {
            $sql = "INSERT INTO punto_residuo (Residuo_idResiduo, Punto_Recoleccion_idPunto_Recoleccion) VALUES ($idResiduo, $idPunto)";
            $conexion->ejecutarConsultaDirecta($sql);
        }
        $conexion->cerrarConexion();
    }
}
