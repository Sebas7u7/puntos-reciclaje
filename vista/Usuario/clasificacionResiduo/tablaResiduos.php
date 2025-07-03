<style>
    .table-container {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 25px;
        box-shadow: 0 8px 20px rgba(0, 128, 0, 0.1);
        margin-top: 30px;
    }

    .table thead {
        background-color: #d4f5c4;
        color: #2e5e2d;
    }

    .table th {
        font-weight: bold;
        text-transform: capitalize;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        background-color: #f8fff5;
        vertical-align: middle;
        color: #333;
    }

    .table td:first-child {
        font-weight: 500;
        color: #256029;
    }

    .btn-primary {
        background-color: #7ed957;
        border-color: #7ed957;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #6cc748;
        border-color: #6cc748;
    }

    .text-end {
        text-align: right;
    }
</style>
<div class="col d-flex flex-column" style="min-height: 100vh;">
    <div class="table-container">
        <div class="table-scroll-wrapper flex-grow-1">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $residuoC->getNombre(); ?></td>
                        <td><?php echo $residuoC->getDescripcion(); ?></td>
                        <td><?php echo $residuoC->getCategoria(); ?></td>
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
    <div class="mt-4">
    <?php if (empty($puntosRecomendados)) : ?>
        <div class="alert alert-warning text-center" role="alert">
            En este momento no hay punto que reciba este residuo
        </div>
    <?php else : ?>
        <h5 class="mb-3">Puntos recomendados para este residuo</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nombre del punto</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Colaborador</th>
                        <th>Residuos que recibe</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($puntosRecomendados as $punto) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($punto->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($punto->getDireccion()); ?></td>
                        <td><?php echo htmlspecialchars($punto->getEstado()); ?></td>
                        <td><?php echo htmlspecialchars($punto->getColaborador()->getNombre()); ?></td>
                        <td>
                            <?php 
                                $residuos = $punto->getResiduos();
                                echo $residuos ? implode(', ', $residuos) : 'No especificado';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    </div>
</div>