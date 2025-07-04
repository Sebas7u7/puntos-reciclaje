<?php

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Panel de Usuario - Puntos de Reciclaje</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            
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

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.8);
            object-fit: cover;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
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

        .stats-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
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

        .action-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
            transition: width 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover::before {
            width: 100%;
            opacity: 0.1;
        }

        .action-icon {
            font-size: 2.5rem;
            color: #28a745;
            margin-bottom: 1rem;
            display: block;
        }

        .action-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d6e33;
            margin-bottom: 0.5rem;
        }

        .action-description {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            color: #6c757d;
            font-weight: 500;
        }

        .quick-actions {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-actions h3 {
            color: #2d6e33;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
        }

        .quick-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            text-decoration: none;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .quick-btn:hover {
            border-color: #28a745;
            background: #f8fff8;
            color: #2d6e33;
            text-decoration: none;
            transform: translateX(5px);
        }

        .badge-status {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .profile-container {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .welcome-text h1 {
                font-size: 2rem;
            }

            .action-cards-container {
                grid-template-columns: 1fr;
            }

            .hero-section {
                padding: 2rem 1rem;
            }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-elements::before {
            content: '';
            position: absolute;
            top: 20%;
            left: 10%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(204, 252, 123, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-elements::after {
            content: '';
            position: absolute;
            bottom: 30%;
            right: 15%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(40, 167, 69, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
    </style>
</head>
<body>
    <div class="floating-elements"></div>
    
    <div class="container">
        <!-- Hero Section with Profile -->
        <div class="hero-section">
            <div class="profile-container">
                <div class="profile-img-container">
                    <?php 
                    $foto = $usuario->getFotoPerfil();
                    $foto_path = $foto ? realpath(__DIR__ . '/../../' . $foto) : false;
                    if ($foto && $foto_path && file_exists($foto_path)) : ?>
                        <img src="/<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" class="profile-image">
                    <?php else: ?>
                        <div class="profile-placeholder">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="welcome-text">
                    <h1>¡Hola, <?php echo htmlspecialchars($usuario->getNombre()); ?>!</h1>
                    <p>Bienvenido a tu panel de reciclaje personal</p>
                    <span class="badge-status">
                        <i class="bi bi-check-circle me-1"></i>
                        Usuario Activo
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">5</span>
                            <span class="stat-label">Solicitudes Realizadas</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">15kg</span>
                            <span class="stat-label">Material Reciclado</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Puntos Visitados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Information Card -->
            <div class="col-lg-4">
                <div class="info-card">
                    <h3 style="color: #2d6e33; font-weight: 600; margin-bottom: 1.5rem; text-align: center;">
                        <i class="bi bi-person-badge me-2"></i>
                        Mi Información
                    </h3>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-person"></i>
                            Nombre Completo
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($usuario->getNombre() . " " . $usuario->getApellido()); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-envelope"></i>
                            Correo Electrónico
                        </div>
                        <div class="info-value">
                            <?php
                                if ($usuario->getCuenta() && method_exists($usuario->getCuenta(), 'getCorreo')) {
                                    echo htmlspecialchars($usuario->getCuenta()->getCorreo());
                                } else {
                                    echo "No disponible";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-telephone"></i>
                            Teléfono
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($usuario->getTelefono() ?: 'No registrado'); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-at"></i>
                            Nickname
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($usuario->getNickname() ?: 'No registrado'); ?></div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3><i class="bi bi-lightning me-2"></i>Acciones Rápidas</h3>
                    
                    <a href="actualizarDatos.php" class="quick-btn">
                        <i class="bi bi-pencil-square"></i>
                        <span>Actualizar mi información</span>
                    </a>
                    
                    <a href="SolicitudRecoleecion/solicitarRecoleccion.php" class="quick-btn">
                        <i class="bi bi-truck"></i>
                        <span>Solicitar recolección</span>
                    </a>
                    
                    <a href="mapeo/mapearPuntos.php" class="quick-btn">
                        <i class="bi bi-geo-alt"></i>
                        <span>Ver puntos cercanos</span>
                    </a>
                </div>
            </div>

            <!-- Main Actions -->
            <div class="col-lg-8">
                <h3 style="color: #2d6e33; font-weight: 600; margin-bottom: 1.5rem;">
                    <i class="bi bi-grid me-2"></i>
                    ¿Qué puedes hacer?
                </h3>
                
                <div class="action-cards-container">
                    <a href="clasificacionResiduo/clasificacion.php" class="action-card">
                        <i class="bi bi-recycle action-icon"></i>
                        <div class="action-title">Clasificar Residuos</div>
                        <div class="action-description">
                            Aprende cómo clasificar correctamente tus residuos para un reciclaje efectivo
                        </div>
                    </a>

                    <a href="mapeo/mapearPuntos.php" class="action-card">
                        <i class="bi bi-geo-alt-fill action-icon"></i>
                        <div class="action-title">Mapa de Puntos</div>
                        <div class="action-description">
                            Encuentra los puntos de recolección más cercanos a tu ubicación
                        </div>
                    </a>

                    <a href="SolicitudRecoleecion/solicitarRecoleccion.php" class="action-card">
                        <i class="bi bi-truck action-icon"></i>
                        <div class="action-title">Solicitar Recolección</div>
                        <div class="action-description">
                            Programa una recolección domiciliaria para tus materiales reciclables
                        </div>
                    </a>

                    <a href="publicacion/publicacionNoticia.php?tipo=noticia" class="action-card">
                        <i class="bi bi-newspaper action-icon"></i>
                        <div class="action-title">Noticias Ambientales</div>
                        <div class="action-description">
                            Mantente informado sobre noticias y campañas de reciclaje
                        </div>
                    </a>

                    <a href="/puntos-reciclaje/vista/Foro/index.php" class="action-card">
                        <i class="bi bi-question-circle action-icon"></i>
                        <div class="action-title">Foros</div>
                        <div class="action-description">
                            Participa en nuestros foros para discutir temas de reciclaje y sostenibilidad
                        </div>
                    </a>

                    <a href="faqusu.php" class="action-card">
                        <i class="bi bi-patch-question action-icon"></i>
                        <div class="action-title">FAQ</div>
                        <div class="action-description">
                            Guía completa de preguntas y respuestas sobre nuestra plataforma
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Environmental Tips Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="info-card">
                    <h3 style="color: #2d6e33; font-weight: 600; margin-bottom: 1.5rem; text-align: center;">
                        <i class="bi bi-lightbulb me-2"></i>
                        Tip Ecológico del Día
                    </h3>
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <i class="bi bi-tree-fill" style="font-size: 3rem; color: #28a745;"></i>
                        </div>
                        <div class="col-md-10">
                            <p style="font-size: 1.1rem; color: #495057; margin: 0; line-height: 1.6;">
                                <strong>¿Sabías que...?</strong> Reciclar una tonelada de papel puede salvar hasta 17 árboles, 
                                ahorrar 3.3 yardas cúbicas de espacio en vertederos y conservar 7,000 galones de agua. 
                                ¡Cada pequeña acción cuenta para nuestro planeta! 🌱
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    
    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats on load
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach(stat => {
                const finalValue = stat.textContent;
                stat.textContent = '0';
                
                setTimeout(() => {
                    let current = 0;
                    const increment = parseFloat(finalValue) / 20;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= parseFloat(finalValue)) {
                            stat.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            stat.textContent = Math.floor(current).toString();
                        }
                    }, 50);
                }, 500);
            });

            // Add hover effect to action cards
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(135deg, #f8fff8 0%, #ffffff 100%)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.background = 'white';
                });
            });
        });
    </script>
</body>
</html>