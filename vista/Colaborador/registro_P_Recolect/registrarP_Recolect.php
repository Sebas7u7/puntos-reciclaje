<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel del Colaborador - Puntos de Reciclaje</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background: linear-gradient(to top, #ccfc7b 40%, white);
            min-height: 100vh;
            font-family: 'Quicksand', sans-serif;
        }
        
        .form-container {
            margin-top: 20px;
            margin-bottom: 30px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        
        .header-title {
            font-weight: bold;
            color: #014421; /* verde oscuro */
            font-size: 32px;
            text-align: center;
        }
        
        .btn-custom {
            background-color: #ccfc7b;
            color: #000;
            border: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-custom:hover {
            background-color: #b3e066;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: #14532d; /* verde más oscuro */
        }
        
        hr {
            border-top: 2px solid #014421;
            opacity: 0.3;
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    include(__DIR__ . '/../navbarColaborador.php');
    
    // Verificación de seguridad
    if (!isset($_SESSION["colaborador"])) {
        header("Location: /PuntosReciclaje/index.php");
        exit();
    }
    ?>
    
    <div class="container text-center mt-4">
        <h1 class="header-title">Panel del Colaborador</h1>
        <hr class="mb-4">
    </div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8 col-xl-7">
                <div class="form-container">
                    <div class="mb-3 text-end">
                        <a href="asignar_residuos_punto.php" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle"></i> Asignar residuos a punto existente
                        </a>
                    </div>
                    <?php include (__DIR__ . '/formRegister.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>