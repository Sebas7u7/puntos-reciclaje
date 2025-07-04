<?php
require_once(__DIR__ . '/../../logica/Colaborador.php');
require_once(__DIR__ . '/../../logica/Cuenta.php');
require_once(__DIR__ . '/../../logica/Solicitud.php');
require_once(__DIR__ . '/../../logica/Usuario.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_GET["cerrarSesion"]) || !isset($_SESSION["colaborador"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: /puntos-reciclaje/index.php");
    exit();
}
$colaborador = $_SESSION["colaborador"];
$cuenta = $_SESSION["cuenta"] ?? ($colaborador && method_exists($colaborador, 'getCuenta') ? $colaborador->getCuenta() : null);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - Colaborador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
    background: linear-gradient(to top, #ccfc7b 40%, white);
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    font-family: 'Quicksand', sans-serif;
  } 
        
        .hero-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        
        .category-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
        }
        
        .category-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1.5rem;
            margin: 0;
        }
        
        .category-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }
        
        .faq-item {
            border-bottom: 1px solid #e9ecef;
        }
        
        .faq-item:last-child {
            border-bottom: none;
        }
        
        .faq-question {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 1.5rem;
            font-weight: 600;
            color: #495057;
            transition: background-color 0.3s ease;
        }
        
        .faq-question:hover {
            background-color: #f8f9fa;
        }
        
        .faq-question[aria-expanded="true"] {
            background-color: #e8f5e8;
            color: #155724;
        }
        
        .faq-answer {
            padding: 0 1.5rem 1.5rem 1.5rem;
            color: #6c757d;
            line-height: 1.6;
        }
        
        .chevron {
            transition: transform 0.3s ease;
        }
        
        .faq-question[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
        
        .back-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'navbarColaborador.php'; ?>
    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-3">
                        <i class="bi bi-patch-question-fill me-3"></i>
                        Preguntas Frecuentes
                    </h1>
                    <p class="lead">Encuentra respuestas rápidas a las preguntas más comunes sobre tu rol como colaborador</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Gestión de Residuos -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-recycle category-icon"></i>
                Gestión de Residuos
            </h2>
            <div class="accordion" id="residuosAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#residuo1">
                        ¿Cómo clasifico correctamente los residuos?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="residuo1" class="collapse" data-bs-parent="#residuosAccordion">
                        <div class="faq-answer">
                            Los residuos se clasifican en categorías: orgánicos (restos de comida), reciclables (papel, cartón, plástico, vidrio, metal), peligrosos (pilas, medicamentos) y ordinarios (no reciclables). Cada tipo debe ir en contenedores específicos según el código de colores establecido.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#residuo2">
                        ¿Qué hago si encuentro residuos peligrosos?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="residuo2" class="collapse" data-bs-parent="#residuosAccordion">
                        <div class="faq-answer">
                            Los residuos peligrosos requieren manejo especial. Utiliza equipo de protección, almacénalos en contenedores especiales etiquetados y reporta inmediatamente al supervisor. Nunca mezcles residuos peligrosos con otros tipos.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#residuo3">
                        ¿Con qué frecuencia debo actualizar el inventario de residuos?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="residuo3" class="collapse" data-bs-parent="#residuosAccordion">
                        <div class="faq-answer">
                            El inventario debe actualizarse diariamente al final de cada jornada. Registra todos los residuos procesados, cantidades y destinos finales en el sistema.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Puntos de Recolección -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-geo-alt-fill category-icon"></i>
                Puntos de Recolección
            </h2>
            <div class="accordion" id="puntosAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#punto1">
                        ¿Cómo registro un nuevo punto de recolección?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="punto1" class="collapse" data-bs-parent="#puntosAccordion">
                        <div class="faq-answer">
                            Ve a "Registrar punto" en el menú principal, completa todos los campos requeridos (ubicación, horarios, tipos de residuos aceptados), verifica la información y envía el formulario. El punto aparecerá en el mapa después de la aprobación.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#punto2">
                        ¿Puedo modificar la información de un punto existente?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="punto2" class="collapse" data-bs-parent="#puntosAccordion">
                        <div class="faq-answer">
                            Sí, puedes actualizar la información de los puntos que hayas registrado. Contacta al administrador o utiliza la opción de edición en tu panel de control para modificar horarios, ubicación o tipos de residuos aceptados.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#punto3">
                        ¿Qué requisitos debe cumplir un punto de recolección?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="punto3" class="collapse" data-bs-parent="#puntosAccordion">
                        <div class="faq-answer">
                            El punto debe tener fácil acceso vehicular, espacio suficiente para contenedores, estar en zona autorizada, contar con medidas de seguridad básicas y cumplir con las regulaciones ambientales locales.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solicitudes y Recolecciones -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-truck category-icon"></i>
                Solicitudes y Recolecciones
            </h2>
            <div class="accordion" id="solicitudesAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#solicitud1">
                        ¿Cómo gestiono las solicitudes de recolección?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="solicitud1" class="collapse" data-bs-parent="#solicitudesAccordion">
                        <div class="faq-answer">
                            En "Ver solicitudes" encontrarás todas las peticiones pendientes. Puedes aceptar, programar o rechazar solicitudes según disponibilidad y capacidad. Siempre comunica el estado al solicitante.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#solicitud2">
                        ¿Qué hago si no puedo cumplir con una recolección programada?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="solicitud2" class="collapse" data-bs-parent="#solicitudesAccordion">
                        <div class="faq-answer">
                            Comunica inmediatamente al usuario afectado y al supervisor. Reprograma la recolección lo antes posible o transfiere la solicitud a otro colaborador disponible.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#solicitud3">
                        ¿Cuál es el tiempo máximo de respuesta a una solicitud?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="solicitud3" class="collapse" data-bs-parent="#solicitudesAccordion">
                        <div class="faq-answer">
                            Debes responder a las solicitudes dentro de las primeras 24 horas. La recolección debe realizarse máximo en 72 horas después de aceptada, salvo acuerdos especiales con el usuario.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publicaciones y Comunicación -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-megaphone-fill category-icon"></i>
                Publicaciones y Comunicación
            </h2>
            <div class="accordion" id="publicacionesAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#publicacion1">
                        ¿Qué tipos de contenido puedo publicar?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="publicacion1" class="collapse" data-bs-parent="#publicacionesAccordion">
                        <div class="faq-answer">
                            Puedes publicar noticias ambientales, campañas de reciclaje, eventos educativos, consejos de sostenibilidad e información sobre nuevos puntos de recolección. Todo contenido debe ser relevante y educativo.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#publicacion2">
                        ¿Existe alguna política de contenido que deba seguir?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="publicacion2" class="collapse" data-bs-parent="#publicacionesAccordion">
                        <div class="faq-answer">
                            Sí, el contenido debe ser veraz, educativo y relacionado con el medio ambiente. Evita información falsa, contenido ofensivo o promoción de productos no relacionados con la sostenibilidad.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#publicacion3">
                        ¿Puedo incluir imágenes en mis publicaciones?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="publicacion3" class="collapse" data-bs-parent="#publicacionesAccordion">
                        <div class="faq-answer">
                            Sí, las imágenes son bienvenidas y hacen el contenido más atractivo. Asegúrate de que sean de buena calidad, relevantes al tema y que tengas derechos para usarlas.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuenta y Perfil -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-person-gear category-icon"></i>
                Cuenta y Perfil
            </h2>
            <div class="accordion" id="cuentaAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta1">
                        ¿Cómo actualizo mi información personal?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="cuenta1" class="collapse" data-bs-parent="#cuentaAccordion">
                        <div class="faq-answer">
                            Ve a "Actualizar datos" en el menú principal. Puedes modificar tu nombre, correo electrónico, teléfono y foto de perfil. Los cambios se guardan automáticamente.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta2">
                        ¿Cómo cambio mi contraseña?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="cuenta2" class="collapse" data-bs-parent="#cuentaAccordion">
                        <div class="faq-answer">
                            En la sección "Actualizar datos" encontrarás la opción para cambiar contraseña. Necesitarás tu contraseña actual y confirmar la nueva. Usa una contraseña segura con letras, números y símbolos.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta3">
                        ¿Qué hago si olvido mi contraseña?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="cuenta3" class="collapse" data-bs-parent="#cuentaAccordion">
                        <div class="faq-answer">
                            En la página de inicio de sesión, haz clic en "¿Olvidaste tu contraseña?". Se enviará un enlace de recuperación a tu correo electrónico registrado.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="text-center my-5">
            <a href="/puntos-reciclaje/vista/Colaborador/indexColaborador.php" class="back-btn">
                <i class="bi bi-arrow-left me-2"></i>
                Volver al Inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
