<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - EcoGestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to top, #ccfc7b, rgb(225, 238, 253));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Quicksand', sans-serif;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
        }

        .logo-placeholder {
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 20px;
            padding: 0.75rem 1rem;
            border: 1px solidrgb(184, 28, 28);
        }

        .btn-green {
            background-color: #28a745;
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        .form-label {
            color: #4d774e;
            font-weight: 500;
        }

        .alert {
            border-radius: 20px;
        }

        .info-card {
            background-color: #ffffff;
            border-radius: 25px;
            padding: 2.5rem;
            margin-right: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            color: #4d774e;
            text-align: justify;
        }

        .animation-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <!-- Columna izquierda: Información -->
            <div class="col-md-6">
                <div class="info-card">
                    <h2 class="text-success">Bienvenido a EcoGestor 🌱</h2>
                    <p>EcoGestor es un sistema diseñado para facilitar la gestión y localización de puntos de reciclaje. Nuestro objetivo es contribuir al desarrollo sostenible promoviendo el reciclaje y el manejo adecuado de residuos electrónicos (e-waste).</p>
                    <ul>
                        <li>Visualiza puntos de recolección en un mapa interactivo.</li>
                        <li>Regístrate como usuario o colaborador para participar.</li>
                        <li>Contribuye al cuidado del medio ambiente.</li>
                    </ul>
                    <p>¡Gracias por hacer parte del cambio verde con EcoGestor!</p>
                </div>
                <div class="animation-wrapper">
                    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                    <dotlottie-player src="https://lottie.host/1edc64e7-4356-40a7-8490-1b12606e7a5c/urwDP9nX6D.lottie"
                        background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
                </div>
            </div>

            <!-- Columna derecha: Formulario de login -->
            <div class="col-md-6">
                <?php
                // Asegura que la sesión esté iniciada antes de usar $_SESSION
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                ?>
                <div class="logo-placeholder"></div>
                <?php
                if (isset($_SESSION['message'])) {
                    // Soporta success, danger y warning
                    $tipo = in_array($_SESSION['message_type'], ['success', 'danger', 'warning']) ? $_SESSION['message_type'] : 'info';
                    echo '<div class="alert alert-' . $tipo . ' text-center fw-bold">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                }
                include(__DIR__ . '/loginForm.php');
                ?>
            </div>
        </div>
    </div>
</body>


</html>
