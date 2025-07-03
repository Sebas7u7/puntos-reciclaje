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