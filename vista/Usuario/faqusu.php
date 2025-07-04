<?php
require_once(__DIR__ . '/../../logica/Usuario.php');
require_once(__DIR__ . '/../../logica/Residuo.php');
require_once(__DIR__ . '/../../logica/Colaborador.php');
require_once(__DIR__ . '/../../logica/Punto_recoleccion.php');
require_once(__DIR__ . '/../../logica/Comentario.php');

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (isset($_GET["cerrarSesion"]) || !isset($_SESSION["usuario"])) {
    $_SESSION = [];
    session_destroy();

    header("Location: /puntos-reciclaje/index.php");
    exit();
}
$usuario = $_SESSION["usuario"];
$cuenta = $_SESSION["cuenta"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - Usuario</title>
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
            background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
            color: #155724;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .category-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-header {
            background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
            color: #155724;
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
            background-color: #d4edda;
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
            background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            color: #155724;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s ease;
            font-weight: 600;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            color: #0c4128;
        }

        .highlight-tip {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border-left: 4px solid #17a2b8;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>

<body>
    <?php include 'navbarUser.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-3">
                        <i class="bi bi-patch-question-fill me-3"></i>
                        Preguntas Frecuentes
                    </h1>
                    <p class="lead">Encuentra respuestas a las preguntas más comunes sobre reciclaje y nuestra plataforma</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Clasificación de Residuos -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-recycle category-icon"></i>
                Clasificación de Residuos
            </h2>
            <div class="accordion" id="clasificacionAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#clasificacion1">
                        ¿Cómo debo separar correctamente mis residuos?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="clasificacion1" class="collapse" data-bs-parent="#clasificacionAccordion">
                        <div class="faq-answer">
                            Separa tus residuos en 4 categorías principales:
                            <div class="highlight-tip">
                                <strong>🟢 Orgánicos:</strong> Restos de comida, cáscaras, residuos de jardín<br>
                                <strong>🔵 Reciclables:</strong> Papel, cartón, plástico, vidrio, metal<br>
                                <strong>🔴 Peligrosos:</strong> Pilas, medicamentos, químicos<br>
                                <strong>⚫ Ordinarios:</strong> Residuos no reciclables ni peligrosos
                            </div>
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#clasificacion2">
                        ¿Qué residuos NO debo mezclar?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="clasificacion2" class="collapse" data-bs-parent="#clasificacionAccordion">
                        <div class="faq-answer">
                            Nunca mezcles residuos peligrosos con otros tipos. Los aceites usados, pilas, medicamentos vencidos y productos químicos requieren disposición especial. Tampoco mezcles residuos orgánicos húmedos con papel o cartón.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#clasificacion3">
                        ¿Cómo preparo los envases para reciclar?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="clasificacion3" class="collapse" data-bs-parent="#clasificacionAccordion">
                        <div class="faq-answer">
                            Enjuaga los envases para remover restos de comida, retira etiquetas cuando sea posible, aplasta botellas y latas para ahorrar espacio, y separa tapas de botellas si son de diferente material.
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
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#puntos1">
                        ¿Cómo encuentro el punto de recolección más cercano?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="puntos1" class="collapse" data-bs-parent="#puntosAccordion">
                        <div class="faq-answer">
                            Utiliza la opción "Mapa" en el menú principal. El mapa te mostrará todos los puntos de recolección cercanos a tu ubicación, sus horarios de funcionamiento y qué tipos de residuos aceptan.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#puntos2">
                        ¿Qué horarios manejan los puntos de recolección?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="puntos2" class="collapse" data-bs-parent="#puntosAccordion">
                        <div class="faq-answer">
                            Los horarios varían según el punto. La mayoría opera de lunes a sábado de 8:00 AM a 5:00 PM. Algunos puntos tienen horarios extendidos o funcionan domingos. Consulta el mapa para ver horarios específicos.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#puntos3">
                        ¿Todos los puntos aceptan los mismos tipos de residuos?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="puntos3" class="collapse" data-bs-parent="#puntosAccordion">
                        <div class="faq-answer">
                            No, cada punto tiene especialidades. Algunos se enfocan en reciclables comunes, otros en residuos especiales como electrónicos o peligrosos. Revisa la información de cada punto en el mapa antes de visitarlo.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recolección Puerta a Puerta -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-truck category-icon"></i>
                Recolección Puerta a Puerta
            </h2>
            <div class="accordion" id="recoleccionAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#recoleccion1">
                        ¿Cómo solicito el servicio de recolección en mi hogar?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="recoleccion1" class="collapse" data-bs-parent="#recoleccionAccordion">
                        <div class="faq-answer">
                            Ve a "Solicitar Recolección" en el menú, completa el formulario con tu dirección, tipos y cantidades de residuos, fecha preferida y información de contacto. Un colaborador se pondrá en contacto contigo para confirmar.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#recoleccion2">
                        ¿Cuánto tiempo tardan en responder mi solicitud?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="recoleccion2" class="collapse" data-bs-parent="#recoleccionAccordion">
                        <div class="faq-answer">
                            Las solicitudes se procesan dentro de las primeras 24 horas. El colaborador asignado te contactará para coordinar fecha y hora de recolección. El servicio se realiza generalmente dentro de 2-3 días hábiles.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#recoleccion3">
                        ¿El servicio de recolección tiene algún costo?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="recoleccion3" class="collapse" data-bs-parent="#recoleccionAccordion">
                        <div class="faq-answer">
                            El servicio básico de recolección es gratuito para cantidades normales de residuos domésticos. Para grandes volúmenes o residuos especiales, puede aplicar una tarifa mínima que se te informará al momento de la solicitud.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publicaciones e Información -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-newspaper category-icon"></i>
                Publicaciones e Información
            </h2>
            <div class="accordion" id="publicacionesAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#publicaciones1">
                        ¿Qué tipo de información encuentro en las publicaciones?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="publicaciones1" class="collapse" data-bs-parent="#publicacionesAccordion">
                        <div class="faq-answer">
                            Encontrarás campañas ambientales, noticias sobre reciclaje, eventos educativos, información sobre nuevos puntos de recolección, consejos de sostenibilidad y actualizaciones del programa de reciclaje.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#publicaciones2">
                        ¿Con qué frecuencia se actualizan las publicaciones?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="publicaciones2" class="collapse" data-bs-parent="#publicacionesAccordion">
                        <div class="faq-answer">
                            Las publicaciones se actualizan regularmente. Nuevas noticias e información se publican semanalmente, mientras que eventos y campañas se anuncian con anticipación suficiente para tu participación.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#publicaciones3">
                        ¿Puedo sugerir temas para futuras publicaciones?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="publicaciones3" class="collapse" data-bs-parent="#publicacionesAccordion">
                        <div class="faq-answer">
                            ¡Por supuesto! Puedes enviar tus sugerencias a través del formulario de contacto o directamente a nuestros colaboradores. Valoramos las ideas de la comunidad para crear contenido relevante.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuenta y Perfil -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-person-gear category-icon"></i>
                Mi Cuenta
            </h2>
            <div class="accordion" id="cuentaAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta1">
                        ¿Cómo actualizo mi información personal?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="cuenta1" class="collapse" data-bs-parent="#cuentaAccordion">
                        <div class="faq-answer">
                            Ve a "Mis datos" en el menú principal. Allí puedes actualizar tu nombre, correo electrónico, teléfono, dirección y foto de perfil. Los cambios se guardan automáticamente.
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
                            En la sección "Mis datos" encontrarás la opción para cambiar contraseña. Necesitarás tu contraseña actual para confirmar el cambio. Usa una contraseña segura con al menos 8 caracteres.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#cuenta3">
                        ¿Puedo eliminar mi cuenta?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="cuenta3" class="collapse" data-bs-parent="#cuentaAccordion">
                        <div class="faq-answer">
                            Sí, puedes solicitar la eliminación de tu cuenta contactando al soporte. Ten en cuenta que esto eliminará todo tu historial de recolecciones y no se puede deshacer.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consejos Ambientales -->
        <div class="category-card">
            <h2 class="category-header">
                <i class="bi bi-tree-fill category-icon"></i>
                Consejos Ambientales
            </h2>
            <div class="accordion" id="consejosAccordion">
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#consejos1">
                        ¿Cómo puedo reducir la cantidad de residuos que genero?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="consejos1" class="collapse" data-bs-parent="#consejosAccordion">
                        <div class="faq-answer">
                            Aplica las 3 R's: <strong>Reduce</strong> el consumo innecesario, <strong>Reutiliza</strong> envases y materiales, <strong>Recicla</strong> correctamente. Compra productos con menos empaque, usa bolsas reutilizables y repara antes de desechar.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#consejos2">
                        ¿Qué puedo hacer con los residuos orgánicos en casa?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="consejos2" class="collapse" data-bs-parent="#consejosAccordion">
                        <div class="faq-answer">
                            Puedes hacer compostaje casero con restos de frutas, verduras y residuos de jardín. El compost resultante es excelente fertilizante natural. Evita incluir carnes, lácteos o grasas en el compost.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#consejos3">
                        ¿Cómo puedo involucrar a mi familia en el reciclaje?
                        <i class="bi bi-chevron-down chevron float-end"></i>
                    </button>
                    <div id="consejos3" class="collapse" data-bs-parent="#consejosAccordion">
                        <div class="faq-answer">
                            Educa con el ejemplo, asigna responsabilidades específicas a cada miembro, haz del reciclaje una actividad divertida con juegos y recompensas, y explica la importancia ambiental de manera simple y clara.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="text-center my-5">
            <a href="/puntos-reciclaje/vista/Usuario/indexUsuario.php" class="back-btn">
                <i class="bi bi-arrow-left me-2"></i>
                Volver al Inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>