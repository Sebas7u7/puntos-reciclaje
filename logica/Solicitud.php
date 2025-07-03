<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once (__DIR__ . '/../persistencia/SolicitudDAO.php');
require_once (__DIR__ . '/Colaborador.php');
require_once (__DIR__ . '/Residuo.php');
require_once (__DIR__ . '/Usuario.php');
class Solicitud{
    private $id;
    private $direccion;
    private $fecha_solicitud;
    private $fecha_programada;
    private $estado;
    private $usuario;
    private $residuo;
    private $colaborador;
    public function __construct(
        $id = null,
        $direccion = "",
        $fecha_solicitud = "",
        $fecha_programada = null,
        $estado = "",
        $usuario = null,
        $residuo = null,
        $colaborador = null
    ) {
        $this->id = $id;
        $this->direccion = $direccion;
        $this->fecha_solicitud = $fecha_solicitud;
        $this->fecha_programada = $fecha_programada;
        $this->estado = $estado;
        $this->usuario = $usuario;
        $this->residuo = $residuo;
        $this->colaborador = $colaborador;
    }
    public function listar($idColaborador){
        $solicitudes = array();
        $usuario = new Usuario();
        $usuarios = $usuario -> mapearPorId();
        $colaborador = new Colaborador();
        $colaboradores = $colaborador -> mapearPorId();
        $residuo = new Residuo;
        $residuos = $residuo -> mapearPorId();
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $solicitudDAO = new SolicitudDAO();
        $conexion -> ejecutarConsulta($solicitudDAO -> listar($idColaborador));
        while($registro = $conexion -> siguienteRegistro()){            
            $solicitud = new Solicitud($registro[0], $registro[1],$registro[2]
            ,$registro[3],$registro[4],$usuarios[$registro[5]],$residuos[$registro[6]]
            ,$colaboradores[$registro[7]]);
            array_push($solicitudes, $solicitud);
        }
        $conexion -> cerrarConexion();
        return $solicitudes;        
    }
    public function actualizar($id,$fechaProgramada,$estado){
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $solicitudDAO = new SolicitudDAO();
        $conexion -> ejecutarConsulta($solicitudDAO -> actualizar($id,$fechaProgramada,$estado));
        $conexion -> cerrarConexion();
    }
    // Getter & Setter for id
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    // Getter & Setter for direccion
    public function getDireccion() {
        return $this->direccion;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    // Getter & Setter for fecha_solicitud
    public function getFechaSolicitud() {
        return $this->fecha_solicitud;
    }
    public function setFechaSolicitud($fecha_solicitud) {
        $this->fecha_solicitud = $fecha_solicitud;
    }

    // Getter & Setter for fecha_programada
    public function getFechaProgramada() {
        return $this->fecha_programada;
    }
    public function setFechaProgramada($fecha_programada) {
        $this->fecha_programada = $fecha_programada;
    }

    // Getter & Setter for estado
    public function getEstado() {
        return $this->estado;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    // Getter & Setter for usuario
    public function getUsuario() {
        return $this->usuario;
    }
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    // Getter & Setter for residuo
    public function getResiduo() {
        return $this->residuo;
    }
    public function setResiduo($residuo) {
        $this->residuo = $residuo;
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