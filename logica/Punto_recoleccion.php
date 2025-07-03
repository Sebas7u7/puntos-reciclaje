<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once (__DIR__ . '/../persistencia/Punto_recoleccionDAO.php');
require_once (__DIR__ . '/Colaborador.php');
class Punto_recoleccion{
    private $idPunto_Recoleccion;
    private $nombre;
    private $direccion;
    private $latitud;
    private $longitud;
    private $estado;
    private $colaborador;

    public function __construct(
        $idPunto_Recoleccion = 0,
        $nombre = "",
        $direccion = "",
        $latitud = 0,
        $longitud = 0,
        $estado = "",
        $colaborador = null
    ) {
        $this->idPunto_Recoleccion = $idPunto_Recoleccion;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->estado = $estado;
        $this->colaborador = $colaborador;
    }
    public function listar(){
        $colaborador = new Colaborador();
        $colaboradores = $colaborador -> mapearPorId();
        $puntos = array();
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $puntoDAO = new Punto_recoleccionDAO(); 
        $conexion -> ejecutarConsulta($puntoDAO -> consultarTodos());
        while($registro = $conexion -> siguienteRegistro()){            
            $punto = new Punto_recoleccion($registro[0], $registro[1],$registro[2],$registro[3]
        ,$registro[4],$registro[5],$colaboradores[$registro[6]]);
            array_push($puntos,$punto);
        }
        $conexion -> cerrarConexion();
        return $puntos;
    }
    public function registrar($nombre, $direccion, $latitud, $longitud, $estado, $colaborador_id) {
        $conexion = new Conexion();
        $conexion->abrirConexion();

        $puntoDAO = new Punto_recoleccionDAO(); 
        $conexion->ejecutarConsultaDirecta(
            $puntoDAO->registrar($nombre, $direccion, $latitud, $longitud, $estado, $colaborador_id)
        );

        $conexion->cerrarConexion();
        return true;
    }
    public function clasificar_by_categoria($categoria){
        $colaborador = new Colaborador();
        $colaboradores = $colaborador -> mapearPorId();
        $puntos = array();
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $puntoDAO = new Punto_recoleccionDAO(); 
        $conexion -> ejecutarConsulta($puntoDAO -> clasificar_by_categoria($categoria));
        while($registro = $conexion -> siguienteRegistro()){            
            $punto = new Punto_recoleccion($registro[0], $registro[1],$registro[2],$registro[3]
        ,$registro[4],$registro[5],$colaboradores[$registro[6]]);
            array_push($puntos,$punto);
        }
        $conexion -> cerrarConexion();
        return $puntos;
    }
    // Getter & Setter for idPunto_Recoleccion
    public function getIdPuntoRecoleccion() {
        return $this->idPunto_Recoleccion;
    }
    public function setIdPuntoRecoleccion($idPunto_Recoleccion) {
        $this->idPunto_Recoleccion = $idPunto_Recoleccion;
    }

    // Getter & Setter for nombre
    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Getter & Setter for direccion
    public function getDireccion() {
        return $this->direccion;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    // Getter & Setter for latitud
    public function getLatitud() {
        return $this->latitud;
    }
    public function setLatitud($latitud) {
        $this->latitud = $latitud;
    }

    // Getter & Setter for longitud
    public function getLongitud() {
        return $this->longitud;
    }
    public function setLongitud($longitud) {
        $this->longitud = $longitud;
    }

    // Getter & Setter for estado
    public function getEstado() {
        return $this->estado;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
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