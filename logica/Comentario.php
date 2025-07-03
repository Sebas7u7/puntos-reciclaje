<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once(__DIR__ . '/../persistencia/ComentarioDAO.php');
require_once (__DIR__ . '/Usuario.php');

class Comentario {
    private $id;
    private $contenido;
    private $fecha;
    private $usuario;
    private $comentario;
    // Constructor
    public function __construct($id = 0, $contenido = "", $fecha = "", $usuario = null, $comentario = null) {
        $this->id = $id;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->usuario = $usuario;
        $this->comentario = $comentario;
    }
    public function listarPadres(){
        $usuario = new Usuario();
        $usuarios = $usuario -> mapearPorId();
        $comentariosPadres = array();
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $comentarioDAO = new ComentarioDAO();
        $conexion -> ejecutarConsulta($comentarioDAO -> listarPadres());
        while($registro = $conexion -> siguienteRegistro()){            
            $comentario = new Comentario($registro[0], $registro[1],$registro[2],$usuarios[$registro[3]]);
            array_push($comentariosPadres,$comentario);
        }
        $conexion -> cerrarConexion();
        return $comentariosPadres;
    }
    public function listarHijos($idPadre){
        $usuario = new Usuario();
        $usuarios = $usuario -> mapearPorId();
        $comentariosHijos = array();
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $comentarioDAO = new ComentarioDAO();
        $conexion -> ejecutarConsulta($comentarioDAO -> listarHijos($idPadre));
        while($registro = $conexion -> siguienteRegistro()){            
            $comentario = new Comentario($registro[0], $registro[1],$registro[2],$usuarios[$registro[3]]);
            array_push($comentariosHijos,$comentario);
        }
        $conexion -> cerrarConexion();
        return $comentariosHijos;
    }
    public function guardar($contenido,$fecha,$idUsuario, $idComentarioPadre) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $comentarioDAO = new ComentarioDAO();
        $conexion -> ejecutarConsulta($comentarioDAO -> guardar($contenido,$fecha,$idUsuario, $idComentarioPadre));
        $conexion -> cerrarConexion();
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getComentario() {
        return $this->comentario;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setContenido($contenido) {
        $this->contenido = $contenido;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }
}
