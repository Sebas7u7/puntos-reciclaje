<?php
session_start();
if (isset($_GET['cuenta'])) {
    require_once (__DIR__ . '/../../logica/Cuenta.php');
    $correo = $_GET['cuenta'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_clave'])) {
        $nueva_clave = $_POST['nueva_clave'];
        $confirmar_clave = $_POST['confirmar_clave'];
        // Validaciones básicas
        if (empty($nueva_clave) || empty($confirmar_clave)) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = 'Debes ingresar y confirmar la nueva contraseña.';
        } elseif ($nueva_clave !== $confirmar_clave) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = 'Las contraseñas no coinciden.';
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}$/', $nueva_clave)) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = 'La contraseña debe tener mínimo 5 caracteres, al menos 1 letra y 1 número.';
        } else {
            $cuenta = new Cuenta();
            $resultado = $cuenta->cambiarClave($correo, $nueva_clave);
            if ($resultado) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = '¡Contraseña cambiada exitosamente! Ahora puedes iniciar sesión con tu nueva contraseña.';
                header("Location: /puntos-reciclaje/index.php");
                exit();
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = 'Error al cambiar la contraseña. Verifica el correo electrónico o inténtalo más tarde.';
            }
        }
    }
    // Mostrar formulario de nueva contraseña
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cambiar contraseña</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Cambia tu contraseña</h4>
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                                <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="nueva_clave" class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmar_clave" class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" required>
                            </div>
                            <button type="submit" class="btn btn-success">Cambiar contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit();
}
header("Location: /puntos-reciclaje/index.php");
?>