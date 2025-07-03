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
    <title>Cambiar clave - Puntos de Reciclaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #e4fddf, #ccfc7b);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            border: 2px solid #ccfc7b;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0.75rem 1.5rem rgba(0, 128, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            color: #014421;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: #2e5731;
            font-weight: 600;
        }

        .form-control {
            background-color: #f7fff0;
            border-color: #b6e19b;
        }

        .btn-success {
            background-color: #7ed957;
            border: none;
            font-weight: bold;
        }

        .btn-success:hover {
            background-color: #68c84d;
        }

        .text-links a {
            color: #014421;
            font-weight: 600;
            text-decoration: none;
        }

        .text-links a:hover {
            text-decoration: underline;
        }

        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2 class="text-center">Cambiar Contraseña</h2>

        <?php
        if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            echo "<div class='alert alert-" . htmlspecialchars($_SESSION['message_type']) . " alert-dismissible fade show' role='alert'>";
            echo htmlspecialchars($_SESSION['message']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <?php include(__DIR__ . '/formCambioClave.php'); ?>

        <hr class="my-4">

        <div class="text-center text-links mb-2">
            <a href="/puntos-reciclaje/index.php">Volver a Iniciar Sesión</a>
        </div>
        <div class="text-center text-links mb-2">
            <a href="/puntos-reciclaje/vista/Usuario/registroCuenta/registrarse.php">Registrarme como usuario</a>
        </div>
        <div class="text-center text-links">
            <a href="/puntos-reciclaje/vista/Colaborador/registroCuenta/registrarse.php">Registrarme como colaborador</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
