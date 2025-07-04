<?php
class ForoTema {
    private $idTema;
    private $titulo;
    private $descripcion;
    private $fechaCreacion;
    private $usuario;
    private $categoria;

    public function __construct($idTema = null, $titulo = '', $descripcion = '', $fechaCreacion = '', $usuario = null, $categoria = null) {
        $this->idTema = $idTema;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fechaCreacion = $fechaCreacion;
        $this->usuario = $usuario;
        $this->categoria = $categoria;
    }
    public function getIdTema() { return $this->idTema; }
    public function getTitulo() { return $this->titulo; }
    public function getDescripcion() { return $this->descripcion; }
    public function getFechaCreacion() { return $this->fechaCreacion; }
    public function getUsuario() { return $this->usuario; }
    public function getCategoria() { return $this->categoria; }
    public function setIdTema($idTema) { $this->idTema = $idTema; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setFechaCreacion($fechaCreacion) { $this->fechaCreacion = $fechaCreacion; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
}
