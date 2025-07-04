<?php
class ForoRespuesta {
    private $idRespuesta;
    private $tema;
    private $usuario;
    private $contenido;
    private $fecha;
    private $respuestaPadre;

    public function __construct($idRespuesta = null, $tema = null, $usuario = null, $contenido = '', $fecha = '', $respuestaPadre = null) {
        $this->idRespuesta = $idRespuesta;
        $this->tema = $tema;
        $this->usuario = $usuario;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->respuestaPadre = $respuestaPadre;
    }
    public function getIdRespuesta() { return $this->idRespuesta; }
    public function getTema() { return $this->tema; }
    public function getUsuario() { return $this->usuario; }
    public function getContenido() { return $this->contenido; }
    public function getFecha() { return $this->fecha; }
    public function getRespuestaPadre() { return $this->respuestaPadre; }
    public function setIdRespuesta($idRespuesta) { $this->idRespuesta = $idRespuesta; }
    public function setTema($tema) { $this->tema = $tema; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }
    public function setContenido($contenido) { $this->contenido = $contenido; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setRespuestaPadre($respuestaPadre) { $this->respuestaPadre = $respuestaPadre; }
}
