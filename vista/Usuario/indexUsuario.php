<?php
// Ya tienes el navbarUser.php que inicia la sesión y obtiene $usuario
// Si no es así, asegúrate de tenerlo:
require_once(__DIR__ . '/navbarUser.php'); // Este archivo ya debería manejar la sesión y el objeto $usuario

// Si $usuario no está seteado por alguna razón (aunque navbarUser.php debería manejarlo),
// podrías añadir un chequeo adicional y redirigir si es necesario.
if (!isset($_SESSION["usuario"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$usuario = $_SESSION["usuario"]; // Obtenemos el objeto Usuario de la sesión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Panel de Usuario - Puntos de Reciclaje</title>
    <style>
        body {
            background-color: #f4f7f6; /* Un gris claro diferente */
            font-family: Arial, sans-serif;
        }
        .user-profile-card {
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .user-profile-card h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .user-profile-card .info-label {
            font-weight: bold;
            color: #555;
        }
        .user-profile-card .info-value {
            color: #333;
        }
        .btn-custom-update {
            background-color: #28a745; /* Verde */
            border-color: #28a745;
            color: white;
            margin-top: 20px;
        }
        .btn-custom-update:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <?php
    // navbarUser.php ya está incluido al principio indirectamente o directamente.
    // Si no lo tienes en navbarUser.php, asegúrate que $usuario y $usuario->getCuenta() no sean null.
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="user-profile-card text-center">
                    <h2>Bienvenido, <?php echo htmlspecialchars($usuario->getNombre()); ?>!</h2>
                    <p class="lead">Aquí puedes ver y gestionar la información de tu cuenta.</p>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-sm-end info-label">Nombre Completo:</div>
                        <div class="col-sm-8 text-sm-start info-value"><?php echo htmlspecialchars($usuario->getNombre() . " " . $usuario->getApellido()); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-sm-end info-label">Correo Electrónico:</div>
                        <div class="col-sm-8 text-sm-start info-value">
                            <?php
                                // Asumimos que el objeto Cuenta está disponible a través de $usuario->getCuenta()
                                // y que Cuenta tiene un método getCorreo()
                                if ($usuario->getCuenta() && method_exists($usuario->getCuenta(), 'getCorreo')) {
                                    echo htmlspecialchars($usuario->getCuenta()->getCorreo());
                                } else {
                                    echo "Correo no disponible";
                                }
                            ?>
                        </div>
                    </div>
                    <a href="actualizarDatos.php" class="btn btn-custom-update btn-lg">Actualizar mi Información</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>
</html>