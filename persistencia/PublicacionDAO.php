<?php
class PublicacionDAO{
    public function __construct(){
        // Puede estar vacío
    }
    public function registrar($titulo, $descripcion, $tipo, $fecha_publicacion, $enlace, $colaborador_id){
        return "INSERT INTO Publicacion (
    titulo,
    descripcion,
    tipo,
    fecha_publicacion,
    enlace,
    Colaborador_idColaborador
    ) VALUES ('$titulo', '$descripcion', '$tipo', '$fecha_publicacion', '$enlace', $colaborador_id
    );";
    }
}
?>