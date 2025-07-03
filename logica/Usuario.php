<?php
require_once (__DIR__ . '/../persistencia/Conexion.php');
require_once(__DIR__ . '/../persistencia/UsuarioDAO.php');
require_once (__DIR__ . '/Cuenta.php');

class Usuario{
    private $idUsuario;
    private $nombre;
    private $apellido;
    private $cuenta; // Objeto Cuenta
    private $telefono;
    private $nickname;
    private $foto_perfil;

    public function __construct($idUsuario=0, $nombre="", $apellido="", $telefono="", $nickname="", $foto_perfil="",$cuenta=null){
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cuenta = $cuenta;
        $this->telefono = $telefono;
        $this->nickname = $nickname;
        $this->foto_perfil = $foto_perfil;
    }
    public function mapearPorId(){
        $usuarios = [];
        $cuenta = new Cuenta();
        $cuentas = $cuenta -> mapearPorId();
        $conexion = new Conexion();
        $conexion -> abrirConexion();
        $usuarioDAO = new UsuarioDAO();
        $conexion -> ejecutarConsulta($usuarioDAO -> consultarTodos());
        while($registro = $conexion -> siguienteRegistro()){            
            $usuario = new Usuario($registro[0], $registro[1],$registro[2],$registro[3],$registro[4],$registro[5],$cuentas[$registro[6]]);
            $usuarios[$registro[0]] = $usuario;
        }
        $conexion -> cerrarConexion();
        return $usuarios;   
    }
    public function registrar($nombre, $apellido, $idCuenta){
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $usuarioDAO = new UsuarioDAO();
        $success = $usuarioDAO->registrar($conexion, $nombre, $apellido, $idCuenta);
        $conexion->cerrarConexion();
        return $success;
    }

    public function consultarCuenta(Cuenta $cuentaObject){
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $usuarioDAO = new UsuarioDAO();
        $idCuenta = $cuentaObject->getIdCuenta();
        $datosUsuario = $usuarioDAO->consultarCuenta($conexion, $idCuenta);
        $conexion->cerrarConexion();

        if(!$datosUsuario){
            return false;
        }
        $this->idUsuario = $datosUsuario['idUsuario'];
        $this->nombre = $datosUsuario['nombre'];
        $this->apellido = $datosUsuario['apellido'];
        $this->cuenta = $cuentaObject; // Asigna el objeto Cuenta completo
        return true;
    }

    /**
     * Actualiza los datos del usuario (nombre, apellido) y opcionalmente el correo.
     * @param string $nuevoNombre
     * @param string $nuevoApellido
     * @param string $nuevoCorreo
     * @return array ['success' => bool, 'message' => string]
     */
    public function actualizarMisDatos($nuevoNombre, $nuevoApellido, $nuevoCorreo) {
        $conexion = new Conexion();
        $conexion->abrirConexion();
        $usuarioDAO = new UsuarioDAO();
        $cuentaDAO = new CuentaDAO(); // Para actualizar correo

        $datosActualizados = false;
        $correoActualizado = false;
        $mensajeGlobal = "";

        // 1. Actualizar nombre y apellido en la tabla Usuario
        // Solo actualiza si los datos han cambiado para evitar una consulta innecesaria
        if ($this->nombre !== $nuevoNombre || $this->apellido !== $nuevoApellido) {
            if ($usuarioDAO->actualizar($conexion, $this->idUsuario, $nuevoNombre, $nuevoApellido)) {
                $this->nombre = $nuevoNombre;
                $this->apellido = $nuevoApellido;
                $datosActualizados = true;
            } else {
                // No se pudo actualizar nombre/apellido o no hubo cambios
                // Si no hubo cambios, affected_rows es 0, pero no es un error grave si los datos son iguales.
                // Considera si quieres un mensaje de error específico si la consulta falla vs no hacer nada.
            }
        }


        // 2. Actualizar correo en la tabla Cuenta (si ha cambiado)
        if ($this->cuenta && $this->cuenta->getCorreo() !== $nuevoCorreo) {
            if (empty($nuevoCorreo) || !filter_var($nuevoCorreo, FILTER_VALIDATE_EMAIL)) {
                $mensajeGlobal .= "El nuevo correo electrónico no es válido. ";
            } else if ($cuentaDAO->verificarCorreoExistente($conexion, $nuevoCorreo, $this->cuenta->getIdCuenta())) {
                $mensajeGlobal .= "El nuevo correo electrónico ('" . htmlspecialchars($nuevoCorreo) . "') ya está en uso por otro usuario. ";
            } else {
                if ($cuentaDAO->actualizarCorreo($conexion, $this->cuenta->getIdCuenta(), $nuevoCorreo)) {
                    $this->cuenta->setCorreo($nuevoCorreo); // Actualiza el correo en el objeto Cuenta asociado
                    $correoActualizado = true;
                } else {
                    $mensajeGlobal .= "No se pudo actualizar el correo electrónico. ";
                }
            }
        }

        $conexion->cerrarConexion();

        if ($datosActualizados && $correoActualizado) {
            $mensajeGlobal = "Nombre, apellido y correo actualizados correctamente.";
            return ['success' => true, 'message' => $mensajeGlobal];
        } elseif ($datosActualizados) {
            $mensajeGlobal = "Nombre y apellido actualizados. " . $mensajeGlobal; // Añade mensajes de error de correo si los hubo
            return ['success' => true, 'message' => $mensajeGlobal];
        } elseif ($correoActualizado) {
            $mensajeGlobal = "Correo actualizado. " . $mensajeGlobal; // Añade mensajes de error de nombre/apellido si los hubo
            return ['success' => true, 'message' => $mensajeGlobal];
        } elseif (empty($mensajeGlobal) && ($this->nombre === $nuevoNombre && $this->apellido === $nuevoApellido && $this->cuenta && $this->cuenta->getCorreo() === $nuevoCorreo) ) {
            // No hubo cambios y no hubo errores
            return ['success' => true, 'message' => "No se realizaron cambios."];
        } else {
            if (empty($mensajeGlobal)) $mensajeGlobal = "No se pudo actualizar la información o no hubo cambios detectados.";
            return ['success' => false, 'message' => $mensajeGlobal];
        }
    }

    // Getters y Setters
    public function getIdUsuario(){ return $this->idUsuario; }
    public function setIdUsuario($idUsuario){ $this->idUsuario = $idUsuario; }
    public function getNombre(){ return $this->nombre; }
    public function setNombre($nombre){ $this->nombre = $nombre; }
    public function getApellido(){ return $this->apellido; }
    public function setApellido($apellido){ $this->apellido = $apellido; }
    public function getCuenta(){ return $this->cuenta; } // Devuelve el objeto Cuenta
    public function setCuenta(Cuenta $cuenta){ $this->cuenta = $cuenta; } // Acepta un objeto Cuenta
    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function getNickname() { return $this->nickname; }
    public function setNickname($nickname) { $this->nickname = $nickname; }
    public function getFotoPerfil() { return $this->foto_perfil; }
    public function setFotoPerfil($foto_perfil) { $this->foto_perfil = $foto_perfil; }
}
?>