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
    // Nueva función: consultar todas las publicaciones
    public function consultarTodos(){
        return "SELECT idPublicacion,titulo,descripcion,tipo,fecha_publicacion,enlace,Colaborador_idColaborador FROM Publicacion";
    }

    // Nueva función: consultar publicaciones por tipo
    public function consultar_por_tipo($tipo){
        return "SELECT idPublicacion,titulo,descripcion,tipo,fecha_publicacion,enlace,Colaborador_idColaborador FROM Publicacion WHERE tipo = '$tipo' ORDER BY fecha_publicacion DESC";
    }

    // Nueva función: consultar publicación por ID
    public function consultarPorId($id){
        return "SELECT idPublicacion,titulo,descripcion,tipo,fecha_publicacion,enlace,Colaborador_idColaborador FROM Publicacion WHERE idPublicacion = $id";
    }

    // Nueva función: eliminar publicación por ID
    public function eliminarPorId($id){
        return "DELETE FROM Publicacion WHERE idPublicacion = $id";
    }

    // Nueva función: actualizar publicación por ID
    public function actualizarPorId($id, $titulo, $descripcion, $tipo, $fecha_publicacion, $enlace, $colaborador_id){
        return "UPDATE Publicacion SET "+
            "titulo = '$titulo', "+
            "descripcion = '$descripcion', "+
            "tipo = '$tipo', "+
            "fecha_publicacion = '$fecha_publicacion', "+
            "enlace = '$enlace', "+
            "Colaborador_idColaborador = $colaborador_id "+
            "WHERE idPublicacion = $id";
    }
}
?>