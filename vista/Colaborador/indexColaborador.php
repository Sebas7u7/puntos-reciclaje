<?php
require_once(__DIR__ . '/navbarColaborador.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Colaborador - Puntos de Reciclaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            color: #2d6e33;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.2);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .profile-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #28a745;
            border: 4px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-text p {
            font-size: 1.2rem;
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .action-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .action-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .action-card.primary {
            border-left-color: #28a745;
        }

        .action-card.secondary {
            border-left-color: #20c997;
        }

        .action-card.tertiary {
            border-left-color: #17a2b8;
        }

        .action-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .action-card.primary .icon {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .action-card.secondary .icon {
            background: linear-gradient(45deg, #20c997, #17a2b8);
            color: white;
        }

        .action-card.tertiary .icon {
            background: linear-gradient(45deg, #17a2b8, #6f42c1);
            color: white;
        }

        .action-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d6e33;
        }

        .action-card p {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.5;
        }

        .quick-stats {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #2d6e33;
            font-weight: 500;
        }

        .container {
            padding-top: 1rem;
        }

        @media (max-width: 768px) {
            .profile-container {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .hero-section {
                padding: 2rem 1rem;
            }

            .welcome-text h1 {
                font-size: 2rem;
            }

            .action-cards-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="profile-container">
                <div class="profile-placeholder">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="welcome-text">
                    <h1>¡Bienvenido, Colaborador!</h1>
                    <p>Panel de gestión para colaboradores ambientales</p>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="stat-item">
                    <span class="stat-number">15</span>
                    <span class="stat-label">Puntos Registrados</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">42</span>
                    <span class="stat-label">Solicitudes Gestionadas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">8</span>
                    <span class="stat-label">Publicaciones</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">127</span>
                    <span class="stat-label">Residuos Clasificados</span>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="action-cards-container">
            <a href="/puntos-reciclaje/vista/Colaborador/residuosColaborador.php" class="action-card primary">
                <div class="icon">
                    <i class="bi bi-recycle"></i>
                </div>
                <h3>Gestionar Residuos</h3>
                <p>Administra y clasifica los diferentes tipos de residuos disponibles en el sistema</p>
            </a>

            <a href="/puntos-reciclaje/vista/Colaborador/registro_P_Recolect/registrarP_Recolect.php" class="action-card secondary">
                <div class="icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3>Registrar Punto</h3>
                <p>Añade nuevos puntos de recolección para ampliar la red de reciclaje</p>
            </a>

            <a href="/puntos-reciclaje/vista/Colaborador/solicitud/verSolicitudes.php" class="action-card tertiary">
                <div class="icon">
                    <i class="bi bi-list-check"></i>
                </div>
                <h3>Ver Solicitudes</h3>
                <p>Revisa y gestiona las solicitudes de recolección de los usuarios</p>
            </a>

            <a href="/puntos-reciclaje/vista/Colaborador/registroPublicidad/registrarPublicidad.php" class="action-card primary">
                <div class="icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <h3>Crear Publicación</h3>
                <p>Comparte información importante y promociona el reciclaje responsable</p>
            </a>

            <a href="/puntos-reciclaje/vista/Colaborador/actualizarDatos.php" class="action-card secondary">
                <div class="icon">
                    <i class="bi bi-person-gear"></i>
                </div>
                <h3>Actualizar Perfil</h3>
                <p>Mantén tu información personal y profesional siempre actualizada</p>
            </a>

            <a href="/puntos-reciclaje/vista/Colaborador/faqcol.php" class="action-card tertiary">
                <div class="icon">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
                <h3>Preguntas Frecuentes</h3>
                <p>Encuentra respuestas a las dudas más comunes sobre la plataforma</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
