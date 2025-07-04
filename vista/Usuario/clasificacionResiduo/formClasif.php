<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 xl-10">
            <div class="classification-card">
                <div class="section-header">
                    <h1 class="section-title">
                        <i class="bi bi-recycle title-icon"></i>Clasificar Residuos
                    </h1>
                    <p class="section-subtitle">
                        Encuentra información sobre cómo clasificar correctamente tus residuos.
                    </p>
                </div>
<?php
$residuo = new Residuo();
$mensajeError = '';
if (isset($_POST['clasificar_residuo'])) {
    $nombreR = isset($_POST["nombreR"]) ? trim($_POST["nombreR"]) : '';
    if ($nombreR === '') {
        $mensajeError = '<div class="alert alert-danger">Debe ingresar un nombre para buscar.</div>';
    } else {
        $residuoC = $residuo->clasificar_nombre($nombreR);
        if (!$residuoC) {
            $mensajeError = '<div class="alert alert-warning">No existe un residuo con ese nombre. Intente nuevamente.</div>';
        } else {
            include("tablaResiduos.php");
        }
    }
}
$residuos = $residuo->listar();
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-green: #2d6e33;
        --secondary-green: #28a745;
        --accent-green: #ccfc7b;
        --glass-bg: rgba(255, 255, 255, 0.15);
        --glass-border: rgba(255, 255, 255, 0.2);
        --shadow-primary: 0 8px 32px rgba(45, 110, 51, 0.3);
        --shadow-card: 0 10px 40px rgba(0, 0, 0, 0.1);
        --gradient-primary: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 50%, var(--accent-green) 100%);
        --gradient-secondary: linear-gradient(135deg, var(--accent-green) 0%, rgba(204, 252, 123, 0.8) 100%);
    }

    body {
        background: linear-gradient(135deg, #f0fff4 0%, #e6ffe6 50%, #ccfc7b 100%);
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .container-fluid {
        width: 90%;
        max-width: none;
        padding: 3rem 1rem;
        margin: 0 auto;
    }

    .classification-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--glass-border);
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
        width: 100%;
        max-width: none;
    }

    .classification-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: var(--gradient-secondary);
    }

    .classification-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }

    .section-subtitle {
        font-size: 1.2rem;
        color: var(--secondary-green);
        font-weight: 400;
        opacity: 0.85;
    }

    .title-icon {
        font-size: 1.3em;
        color: var(--secondary-green);
        margin-right: 0.5rem;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }

    .form-label {
        font-weight: 600;
        color: var(--primary-green);
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label::before {
        content: '\F52A';
        font-family: "bootstrap-icons";
        color: var(--secondary-green);
    }

    .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--accent-green);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--primary-green);
        transition: all 0.4s ease;
        width: 100%;
    }

    .form-control::placeholder {
        color: rgba(45, 110, 51, 0.5);
    }

    .form-control:focus {
        border-color: var(--secondary-green);
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.2);
        background: rgba(255, 255, 255, 1);
    }

    .btn-info {
        background: var(--gradient-primary) !important;
        border: none !important;
        border-radius: 16px !important;
        padding: 1.25rem 3rem !important;
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: white !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.4s ease;
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #245c2a 0%, #2d6e33 50%, #ccfc7b 100%) !important;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
    }

    .btn-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .btn-info:hover::before {
        left: 100%;
    }

    .mb-3 {
        margin-bottom: 2rem !important;
    }

    .mt-4 {
        margin-top: 2rem !important;
    }

    .d-grid {
        margin-top: 2rem;
    }

    /* Responsive adjustments */
    @media (min-width: 80%) {
        .container-fluid {
            width: 80%;
        }
    }

    @media (min-width: 80%) and (max-width: 80%) {
        .container-fluid {
            width: 85%;
        }
    }

    @media (min-width: 80%) and (max-width: 80%) {
        .container-fluid {
            width: 90%;
        }
    }

    @media (min-width: 80%) and (max-width: 80%) {
        .container-fluid {
            width: 92%;
        }
        
        .classification-card {
            padding: 2rem;
        }
    }

    @media (max-width: 80%) {
        .container-fluid {
            width: 95%;
        }
        
        .classification-card {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .section-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 80%) {
        .container-fluid {
            width: 98%;
            padding: 2rem 0.5rem;
        }
        
        .classification-card {
            padding: 1.5rem;
            border-radius: 16px;
        }
        
        .section-title {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 80%) {
        .container-fluid {
            width: 100%;
            padding: 1rem 0.25rem;
        }
        
        .classification-card {
            padding: 1rem;
            border-radius: 12px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
    }

    .datalist {
        font-style: italic;
    }
</style>

                <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data" onsubmit="return validarBusqueda()">
                    <div class="mb-3">
                        <label class="form-label">Buscar por nombre exacto</label>
                        <input type="text" id="nombreR" name="nombreR" class="form-control" list="nombreR_list" placeholder="Escriba el nombre del residuo..." autocomplete="off">
                        <datalist id="nombreR_list">
                            <?php foreach($residuos as $temp): ?>
                                <option value="<?= htmlspecialchars($temp->getNombre()) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <?php if (!empty($mensajeError)) echo $mensajeError; ?>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="clasificar_residuo"
                            class="btn btn-info btn-lg">
                            <i class="bi bi-search" style="margin-right: 0.5rem;"></i>
                            Clasificar
                        </button>
                    </div>
                </form>
                <script>
                function validarBusqueda() {
                    var nombre = document.getElementById('nombreR').value.trim();
                    if (nombre === '') {
                        alert('Debe ingresar un nombre para buscar.');
                        document.getElementById('nombreR').focus();
                        return false;
                    }
                    return true;
                }
                </script>
            </div>
        </div>
    </div>
</div>