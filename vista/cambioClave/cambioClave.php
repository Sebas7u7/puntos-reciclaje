<?php
// Ensure session is started to display potential messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Cambiar clave - Puntos de Reciclaje</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4 text-center">Cambiar Contraseña</h2>
                <?php
            // Display messages if any (e.g., if redirected back to this form on error)
            // Ensure session_start() is called in the parent file (cambioClave.php)
            if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
                echo "<div class='alert alert-" . htmlspecialchars($_SESSION['message_type']) . " alert-dismissible fade show' role='alert'>";
                echo htmlspecialchars($_SESSION['message']);
                echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                echo "</div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
                <?php include (__DIR__ . '/formCambioClave.php');?>
                <hr>
                <div class="text-center mb-2">
                    <a href="/puntos-reciclaje/index.php">Volver a Iniciar Sesión</a>
                </div>
                <div class="text-center mb-2">
                    <a href="/puntos-reciclaje/vista/Usuario/registroCuenta/registrarse.php">Registrarme como
                        usuario</a>
                </div>
                <div class="text-center">
                    <a href="/puntos-reciclaje/vista/Colaborador/registroCuenta/registrarse.php">Registrarme como
                        colaborador</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>