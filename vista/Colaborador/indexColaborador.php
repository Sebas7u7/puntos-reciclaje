<?php
// Cabeceras anti-caché
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once(__DIR__ . '/navbarColaborador.php');

if (!isset($_SESSION["colaborador"])) {
    header("Location: /PuntosReciclaje/index.php");
    exit();
}
$colaborador = $_SESSION["colaborador"];

$faltanDatos = empty($colaborador->getTelefono()) || empty($colaborador->getDireccion()) || empty($colaborador->getFotoPerfil());
if ($faltanDatos) {
    header("Location: actualizarDatos.php?completar=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil del Colaborador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
        }

        .profile-card-colab {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: 60px;
        }

        .profile-img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #607d8b;
            font-size: 0.95rem;
        }

        .info-value {
            font-weight: 500;
            color: #37474f;
        }

        .btn-custom-update-colab {
            background: #26c6da;
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-custom-update-colab:hover {
            background: #0097a7;
        }

        h2 {
            color: #0097a7;
            font-weight: 700;
        }

        hr {
            margin: 1.5rem 0;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card-colab text-center">
                <?php if ($colaborador->getFotoPerfil()) : ?>
                    <img src="<?php echo htmlspecialchars($colaborador->getFotoPerfil()); ?>" alt="Foto de perfil" class="profile-img">
                <?php endif; ?>
                <h2>Perfil del Colaborador</h2>
                <p class="text-muted mb-4">Bienvenido, <strong><?php echo htmlspecialchars($colaborador->getNombre()); ?></strong>. Aquí puedes ver los detalles de tu perfil.</p>

                <hr>

                <div class="text-start">
                    <div class="row mb-3">
                        <div class="col-sm-5 info-label">Nombre de Entidad/Persona:</div>
                        <div class="col-sm-7 info-value"><?php echo htmlspecialchars($colaborador->getNombre()); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 info-label">Correo Electrónico:</div>
                        <div class="col-sm-7 info-value">
                            <?php
                            if ($colaborador->getCuenta() && method_exists($colaborador->getCuenta(), 'getCorreo')) {
                                echo htmlspecialchars($colaborador->getCuenta()->getCorreo());
                            } else {
                                echo "Correo no disponible";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 info-label">Tipo de Residuo que Gestiona:</div>
                        <div class="col-sm-7 info-value">
                            <?php echo htmlspecialchars($colaborador->getTipoResiduo()->getNombre() ?? 'No especificado'); ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 info-label">Servicio Ofrecido:</div>
                        <div class="col-sm-7 info-value"><?php echo htmlspecialchars($colaborador->getServicioOfrecido()); ?></div>
                    </div>
                </div>

                <hr>

                <a href="actualizarDatos.php" class="btn btn-custom-update-colab mt-3">Actualizar Información</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
