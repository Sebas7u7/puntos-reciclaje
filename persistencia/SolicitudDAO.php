<?php
class SolicitudDAO {

    public function actualizar($id, $fechaProgramada,$estado) {
        $fechaProgramadaSQL = $fechaProgramada !== null ? "'$fechaProgramada'" : "NULL";

        return "
            UPDATE Solicitud_Recoleccion 
            SET fecha_programada = $fechaProgramadaSQL,
            estado = '$estado'
            WHERE idSolicitud_Recoleccion = $id;
        ";
    }

    public function listar($idColaborador) {
        return "SELECT 
                s.idSolicitud_Recoleccion,
                s.direccion,
                s.fecha_solicitud,
                s.fecha_programada,
                s.estado,
                s.Usuario_idUsuario,
                s.Residuo_idResiduo,
                s.Colaborador_idColaborador
            FROM 
                Solicitud_Recoleccion s
            WHERE 
                s.Colaborador_idColaborador = $idColaborador;";
    }
}
?>
