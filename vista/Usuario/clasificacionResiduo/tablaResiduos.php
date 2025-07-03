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
                        <th>nombre</th>
                        <th>descripcion</th>
                        <th>categoria</th>
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
    <!-- Botón dentro del mismo contenedor -->
    <div class="mt-3 text-end">
        <a class="btn btn-primary" href="/puntos-reciclaje/vista/Usuario/mapeo/puntos_categoria.php?categoria=<?php echo $residuoC->getCategoria(); ?>" target="_blank" role="button">
            Ver puntos recomendados
        </a>
    </div>
</div>