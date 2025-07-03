<div class="table-scroll-wrapper">
    <table class="table table-bordered mb-0 ms-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>titulo</th>
                <th style="min-width: 300px;">descripcion</th>
                <th>tipo</th>
                <th>fecha_publicacion</th>
                <th>enlace</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($publicaciones as $temp): ?>
            <tr>
                <td>
                    <?= $temp->getId() ?>
                    <input type="hidden" name="id" value="<?= $temp->getId() ?>">
                </td>
                <td>
                    <?= $temp->getTitulo() ?>
                    <input type="hidden" name="titulo" value="<?= $temp->getTitulo() ?>">
                </td>
                <td style="min-width: 300px;">
                    <?= $temp->getDescripcion() ?>
                    <input type="hidden" name="descripcion" value="<?= $temp->getDescripcion() ?>">
                </td>
                <td>
                    <?= $temp->getTipo() ?>
                    <input type="hidden" name="tipo" value="<?= $temp->getTipo() ?>">
                </td>
                <td>
                    <?= $temp->getFechaPublicacion() ?>
                    <input type="hidden" name="fecha_publicacion" value="<?= $temp->getFechaPublicacion() ?>">
                </td>
                <td>
                    <a href="<?= $temp->getEnlace() ?>" target="_blank">
                        <?= $temp->getEnlace() ?>
                    </a>
                    <input type="hidden" name="enlace" value="<?= $temp->getEnlace() ?>">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>