<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once (__DIR__ . '/../persistencia/CuentaDAO.php');

class Cuenta{
    private $idCuenta;
    private $correo;
    // private $clave; // No almacenar la clave (ni siquiera codificada) en el objeto si no es necesario
    private $rol;
    private $estado;
    public function __construct($idCuenta=0, $correo="", $rol=0,$estado=0){
        $this->idCuenta = $idCuenta;
        $this->correo = $correo;
        $this->rol = $rol;
        $this->estado = $estado;
    }
    public function mapearPorId(){
        $cuentas = [];
        
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $cuentaDAO = new CuentaDAO();
        $conexion -> ejecutarConsulta($cuentaDAO -> consultarTodos());
        
        while($registro = $conexion -> siguienteRegistro()){            
            $cuenta = new Cuenta($registro[0], $registro[1],$registro[2],$registro[3]);
            $cuentas[$registro[0]] = $cuenta;
        }
        $conexion -> cerrarConexion();
        return $cuentas;   
    }
    /**
     * Registra una nueva cuenta, CODIFICANDO la contraseña con base64.
     * ADVERTENCIA: ESTO NO ES SEGURO.
     */
    public function registrar($correo, $clave, $rol){
        // 1. CODIFICA LA CONTRASEÑA CON BASE64
        $encodedClave = base64_encode($clave);

        // Para depuración (eliminar en producción)
        // error_log("REGISTRO (BASE64): Correo: $correo, Clave Plana: $clave, Clave Codificada: $encodedClave");
        
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $cuentaDAO = new CuentaDAO();
        
        // 2. PASA LA CONTRASEÑA CODIFICADA AL DAO
        $idCuenta = $cuentaDAO->registrar($conexion, $correo, $encodedClave, $rol);
        
        $conexion->cerrarConexion();
        return $idCuenta;
    }
    
    /**
     * Autentica un usuario comparando la contraseña codificada en base64.
     * ADVERTENCIA: ESTO NO ES SEGURO.
     */
    public function autenticar($correo, $clave_ingresada){ // $clave_ingresada es el texto plano del formulario
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $cuentaDAO = new CuentaDAO();
        
        $cuentaData = $cuentaDAO->consultarPorCorreo($conexion, $correo);
        
        $conexion->cerrarConexion();

        if(!$cuentaData || !isset($cuentaData['clave'])){
            // error_log("AUTENTICAR FALLO (BASE64): Usuario no encontrado o sin clave en BD para correo: " . $correo);
            return false;
        }

        // $cuentaData['clave'] DEBERÍA ser la contraseña codificada en base64 desde la BD.
        $encoded_clave_almacenada = $cuentaData['clave'];
        
        // Codifica la clave ingresada para compararla
        $encoded_clave_ingresada = base64_encode($clave_ingresada);

        if ($encoded_clave_ingresada === $encoded_clave_almacenada) {
            // Contraseñas codificadas coinciden
            $this->idCuenta = $cuentaData['idCuenta'];
            $this->correo = $cuentaData['correo'];
            $this->rol = $cuentaData['rol'];
            $this->estado = $cuentaData['estado'];
            return true;
        } else {
            // Contraseñas codificadas NO coinciden
            // error_log("AUTENTICAR FALLO (BASE64): Contraseña no coincide para correo: " . $correo);
            return false;
        }
    }
    public function activar($correo){
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $cuentaDAO = new CuentaDAO();
        $success = $cuentaDAO->activar($conexion,$correo);
        $conexion->cerrarConexion();
        return $success;
    }
    /**
     * Cambia la contraseña, CODIFICÁNDOLA con base64.
     * ADVERTENCIA: ESTO NO ES SEGURO.
     */
    public function cambiarClave($correo, $nuevaClave){
        $encodedNuevaClave = base64_encode($nuevaClave);

        $conexion = new Conexion();
        $conexion->abrirConexion();
        $cuentaDAO = new CuentaDAO();
        $success = $cuentaDAO->cambiarClave($conexion, $correo, $encodedNuevaClave); // Pasa la clave codificada
        $conexion->cerrarConexion();
        return $success;
    }

    // Getters y Setters
    public function getIdCuenta(){ return $this->idCuenta; }
    public function setIdCuenta($idCuenta){ $this->idCuenta = $idCuenta; }
    public function getCorreo(){ return $this->correo; }
    public function setCorreo($correo){ $this->correo = $correo; }
    public function getRol(){ return $this->rol; }
    public function setRol($rol){ $this->rol = $rol; }
    public function getEstado(){ return $this->estado; }
    public function setEstado($estado){ $this->estado = $estado; }
}
?>