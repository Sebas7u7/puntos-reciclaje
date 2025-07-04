<?php
class ForoCategoria {
    private $idCategoria;
    private $nombre;
    private $descripcion;

    public function __construct($idCategoria = null, $nombre = '', $descripcion = '') {
        $this->idCategoria = $idCategoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }
    public function getIdCategoria() { return $this->idCategoria; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function setIdCategoria($idCategoria) { $this->idCategoria = $idCategoria; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
