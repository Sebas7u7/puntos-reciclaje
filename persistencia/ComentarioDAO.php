<?php

class ComentarioDAO{
    public function listarPadres(){
        return "SELECT idComentario, contenido, fecha, idUsuario
                FROM Comentario
                WHERE idComentarioPadre IS NULL;";
    }
    public function listarHijos($idPadre){
        return "SELECT idComentario, contenido, fecha, idUsuario
                FROM Comentario
                WHERE idComentarioPadre = $idPadre;";
    }
    public function guardar($comentario,$fecha, $idUsuario, $idComentarioPadre){
        return "INSERT INTO comentario (contenido, fecha, idUsuario, idComentarioPadre)
                VALUES ('$comentario', '$fecha', $idUsuario, $idComentarioPadre)";
    }
}
?>