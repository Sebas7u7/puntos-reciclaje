<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once (__DIR__ . '/../persistencia/PublicacionDAO.php');
require_once (__DIR__ . '/../persistencia/ColaboradorDAO.php');
class Publicacion{
    private $id;
    private $titulo;
    // private $clave; // No almacenar la clave (ni siquiera codificada) en el objeto si no es necesario
    private $descripcion;
    private $tipo;
    private $fecha_publicacion;
    private $enlace;
    private $colaborador;
    public function __construct($id = 0, $titulo = "", $descripcion = "", $tipo = "", $fecha_publicacion = "", $enlace = "", $colaborador = null) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->tipo = $tipo;
        $this->fecha_publicacion = $fecha_publicacion;
        $this->enlace = $enlace;
        $this->colaborador = $colaborador;
    }

    public function registrar($titulo, $descripcion, $tipo, $fecha_publicacion, $enlace, $colaborador_id){
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $publicacionDAO = new PublicacionDAO();
        $conexion->ejecutarConsultaDirecta($publicacionDAO->registrar(
            $titulo, $descripcion, $tipo, $fecha_publicacion, $enlace, $colaborador_id
        ));
        $conexion->cerrarConexion();
        return true;
    }
    // Getter & Setter for id
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    // Getter & Setter for titulo
    public function getTitulo() {
        return $this->titulo;
    }
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    // Getter & Setter for descripcion
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    // Getter & Setter for tipo
    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    // Getter & Setter for fecha_publicacion
    public function getFechaPublicacion() {
        return $this->fecha_publicacion;
    }
    public function setFechaPublicacion($fecha_publicacion) {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    // Getter & Setter for enlace
    public function getEnlace() {
        return $this->enlace;
    }
    public function setEnlace($enlace) {
        $this->enlace = $enlace;
    }

    // Getter & Setter for colaborador
    public function getColaborador() {
        return $this->colaborador;
    }
    public function setColaborador($colaborador) {
        $this->colaborador = $colaborador;
    }
}
?>