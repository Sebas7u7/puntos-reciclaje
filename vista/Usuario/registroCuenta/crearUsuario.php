<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once (__DIR__ . '/../../../logica/Usuario.php');
require_once (__DIR__ . '/../../../logica/Cuenta.php');

if (isset($_POST["registrar"])) {
    // Basic validation (you should add more)
    if (empty($_POST["correo"]) || empty($_POST["clave"]) || empty($_POST["nombre"]) || empty($_POST["apellido"])) {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = 'Todos los campos son obligatorios.';
        // Redirect back to registration form
        header("Location: registrarse.php"); 
        exit();
    }
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}$/', $_POST["clave"])) {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = 'la contraseña debe tener mínimo 5 caracteres, al menos 1 letra y 1 número.';
        // Redirect back to registration form
        header("Location: registrarse.php"); 
        exit();
    }
    if ($_POST["clave"] !== $_POST["confirm_clave"]) {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = 'Las contraseñas no coinciden.';
        header("Location: registrarse.php"); // Redirect back to the form view page
        exit();
    }
    $cuenta = new Cuenta();
    $usuario = new Usuario();
    // registrar now returns ID or false
    $idCuenta = $cuenta->registrar($_POST["correo"], $_POST["clave"], 1); 

    if ($idCuenta) {
        // registrar for UsuarioDAO should also be updated to use prepared statements
        // and return true/false
        $usuarioRegistrado = $usuario->registrar($_POST["nombre"], $_POST["apellido"], $idCuenta);
        if ($usuarioRegistrado) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = '¡Usuario registrado exitosamente! Ahora tienes que activar cuenta.';
            $_SESSION["email_pending"] = $_POST["correo"];
            header(header: "Location: /puntos-reciclaje/vista/activacionCuenta/autenticarCorreo.php");
            exit();
        } else {
            // Potentially delete the created Cuenta if Usuario registration fails (more advanced)
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = 'Error al registrar los detalles del usuario.';
            header("Location: registrarse.php"); // Or index.php
            exit();
        }
    } else {
        $_SESSION['message_type'] = 'danger';
        // More specific error: e.g., email already exists (needs check in Cuenta::registrar or DAO)
        $_SESSION['message'] = 'Error al registrar la cuenta. El correo podría ya estar en uso.';
        header("Location: registrarse.php"); // Stay on registration page
        exit();
    }
}
?>