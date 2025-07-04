<?php
require_once(__DIR__ . '/navbarColaborador.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido Colaborador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
        }
        .welcome-card {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: 60px;
        }
        .welcome-title {
            color: #0097a7;
            font-weight: 700;
            font-size: 2.2rem;
        }
        .welcome-msg {
            color: #37474f;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="welcome-card text-center">
                <h1 class="welcome-title">¡Bienvenido!</h1>
                <p class="welcome-msg mt-3">Has iniciado sesión como colaborador.</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
