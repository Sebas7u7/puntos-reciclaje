<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once (__DIR__ . '/../persistencia/ResiduoDAO.php');
class Residuo {
    private $id;
    private $nombre;
    private $descripcion;
    private $categoria;

    public function __construct($id = null, $nombre = "", $descripcion = "", $categoria = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->categoria = $categoria;
    }
    public function listar(){
        $residuos = array();
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $residuoDAO = new ResiduoDAO;
        $conexion -> ejecutarConsulta($residuoDAO -> consultarTodos());
        while($registro = $conexion -> siguienteRegistro()){            
            $residuo = new Residuo($registro[0], $registro[1],$registro[2],$registro[3]);
            array_push($residuos,$residuo);
        }
        $conexion -> cerrarConexion();
        return $residuos;
    }
    public function clasificar_nombre($nombre){
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $residuoDAO = new ResiduoDAO;
        $conexion -> ejecutarConsulta($residuoDAO -> clasificar_nombre($nombre));
        $registro = $conexion -> siguienteRegistro();          
        $residuo = new Residuo($registro[0], $registro[1],$registro[2],$registro[3]);

        $conexion -> cerrarConexion();
        return $residuo;
    }
    public function mapearPorId(){
        $residuos = [];
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $residuoDAO = new ResiduoDAO;
        $conexion -> ejecutarConsulta($residuoDAO -> consultarTodos());
        while($registro = $conexion -> siguienteRegistro()){            
            $residuo = new Residuo($registro[0], $registro[1],$registro[2],$registro[3]);
            $residuos[$registro[0]] = $residuo;
        }
        $conexion -> cerrarConexion();
        return $residuos;   
    }
    // Getter y Setter para id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter y Setter para nombre
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Getter y Setter para descripcion
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    // Getter y Setter para categoria
    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }
}
?>
