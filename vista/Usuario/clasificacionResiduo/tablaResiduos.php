<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-green: #2d6e33;
        --secondary-green: #28a745;
        --accent-green: #ccfc7b;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --shadow-card: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    * {
        font-family: 'Inter', sans-serif;
    }

    .results-container {
        width: 100%;
        max-width: none;
        padding: 0;
        margin: 2rem 0;
    }

    .table-container {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-card);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
        width: 100%;
        overflow: hidden;
    }

    .table-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, var(--accent-green) 0%, var(--secondary-green) 100%);
        border-radius: 20px 20px 0 0;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 1.5rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .table-scroll-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        margin: 1rem 0;
    }

    .table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table thead {
        background: linear-gradient(135deg, var(--accent-green) 0%, rgba(204, 252, 123, 0.8) 100%);
    }

    .table th {
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        vertical-align: middle;
        color: var(--primary-green);
        padding: 1rem;
        border: none;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table td {
        background-color: #f8fff5;
        vertical-align: middle;
        color: #333;
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(40, 167, 69, 0.1);
        font-size: 0.95rem;
        transition: background-color 0.3s ease;
    }

    .table tbody tr:hover td {
        background-color: rgba(204, 252, 123, 0.1);
    }

    .table td:first-child {
        font-weight: 600;
        color: var(--secondary-green);
    }

    .alert {
        border-radius: 15px;
        border: none;
        padding: 1.5rem;
        margin: 2rem 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    .alert::before {
        content: '\F33A';
        font-family: "bootstrap-icons";
        font-size: 1.5rem;
    }

    .recommended-section {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-card);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .recommended-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, var(--secondary-green) 0%, var(--accent-green) 100%);
        border-radius: 20px 20px 0 0;
    }

    .recommended-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .recommended-title::before {
        content: '\F5F9';
        font-family: "bootstrap-icons";
        color: var(--secondary-green);
        font-size: 1.3em;
    }

    /* Responsive Design */
    @media (max-width: 80%) {
        .table-container,
        .recommended-section {
            padding: 1.5rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 80%) {
        .table-container,
        .recommended-section {
            padding: 1.25rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
        
        .recommended-title {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 80%) {
        .results-container {
            margin: 1rem 0;
        }
        
        .table-container,
        .recommended-section {
            padding: 1rem;
            border-radius: 15px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.85rem;
        }
        
        .section-title {
            font-size: 1.2rem;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .recommended-title {
            font-size: 1.1rem;
        }
        
        .alert {
            padding: 1rem;
            margin: 1rem 0;
        }
    }

    @media (max-width: 80%) {
        .table-container,
        .recommended-section {
            padding: 0.75rem;
            border-radius: 12px;
        }
        
        .table th,
        .table td {
            padding: 0.4rem 0.2rem;
            font-size: 0.8rem;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
        
        .recommended-title {
            font-size: 1rem;
        }
    }

    /* Table scroll indicators */
    .table-scroll-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-thumb {
        background: var(--secondary-green);
        border-radius: 4px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: var(--primary-green);
    }
</style>
<div class="results-container">
    <!-- Classified Waste Information -->
    <div class="table-container">
        <h4 class="section-title">
            <i class="bi bi-info-circle-fill"></i>
            Información del Residuo Clasificado
        </h4>
        <div class="table-scroll-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($residuoC->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($residuoC->getDescripcion()); ?></td>
                        <td><?php echo htmlspecialchars($residuoC->getCategoria()); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    require_once(__DIR__ . '/../../../logica/Punto_recoleccion.php');
    $puntoObj = new Punto_recoleccion();
    $puntosRecomendados = $puntoObj->puntos_por_residuo($residuoC->getNombre());
    ?>

    <!-- Recommended Collection Points -->
    <?php if (empty($puntosRecomendados)) : ?>
        <div class="alert alert-warning" role="alert">
            En este momento no hay puntos de recolección que reciban este tipo de residuo. 
            Te recomendamos contactar con las autoridades locales para más información.
        </div>
    <?php else : ?>
        <div class="recommended-section">
            <h4 class="recommended-title">
                Puntos de Recolección Recomendados
            </h4>
            <div class="table-scroll-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Punto</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            <th>Colaborador</th>
                            <th>Residuos que Recibe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($puntosRecomendados as $punto) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($punto->getNombre()); ?></td>
                                <td><?php echo htmlspecialchars($punto->getDireccion()); ?></td>
                                <td>
                                    <span class="badge" style="background-color: <?php echo $punto->getEstado() === 'Activo' ? 'var(--secondary-green)' : '#6c757d'; ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem;">
                                        <?php echo htmlspecialchars($punto->getEstado()); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($punto->getColaborador()->getNombre()); ?></td>
                                <td>
                                    <?php 
                                        $residuos = $punto->getResiduos();
                                        if ($residuos && is_array($residuos)) {
                                            echo '<span style="color: var(--primary-green); font-weight: 500;">' . htmlspecialchars(implode(', ', $residuos)) . '</span>';
                                        } else {
                                            echo '<em style="color: #6c757d;">No especificado</em>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>