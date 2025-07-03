<?php
// Ensure session is started at the very beginning of any script that uses sessions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/logica/Colaborador.php');
require_once(__DIR__ . '/logica/Usuario.php');
require_once(__DIR__ . '/logica/Cuenta.php'); // Aquí se carga la clase Cuenta

if (isset($_POST["inicioSesion"])) {
    $correo_ingresado = $_POST["correo"];
    $clave_ingresada = $_POST["clave"];

    $cuenta = new Cuenta(); // Se crea una instancia de Cuenta
    
    // Aquí es donde se llama a autenticar
    $autenticado = $cuenta->autenticar($correo_ingresado, $clave_ingresada); 
    
    if(!$autenticado){ // Si autenticar() devuelve false
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = 'Correo o contraseña incorrectos.';
        header("Location: /puntos-reciclaje/index.php"); // Vuelve al login
        exit();
    }
    if($cuenta->getEstado()==0){
        $_SESSION['message_type'] = 'warning';
        $_SESSION['message'] = 'Tu cuenta aún no ha sido activada. Por favor revisa tu correo electrónico y sigue el enlace de activación antes de iniciar sesión.';
        header("Location: /puntos-reciclaje/index.php");
        exit();
    }
    $_SESSION["cuenta"] = $cuenta;
    // Session already started, $_SESSION["usuario"] or $_SESSION["colaborador"] will be set
    if($cuenta->getRol()==1){
        $usuario = new Usuario();
        $usuario->consultarCuenta($cuenta);
        $_SESSION["usuario"] = $usuario;
        header("Location: /puntos-reciclaje/vista/Usuario/actualizarDatos.php");
        exit();
    }else{
        $colaborador = new Colaborador();
        $colaborador->consultarCuenta($cuenta);
        $_SESSION["colaborador"] = $colaborador;
        header("Location: /puntos-reciclaje/vista/Colaborador/actualizarDatos.php");
        exit();
    }
}
?>