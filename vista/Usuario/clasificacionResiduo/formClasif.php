<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="update-form-card">
                <h2 class="text-center">Clasificar residuos</h2>
<?php
$residuo = new Residuo();

if (isset($_POST['clasificar_residuo'])) {
    $residuoC = $residuo->clasificar_nombre($_POST["nombreR"]);
    include("tablaResiduos.php");
}
$residuos = $residuo->listar();
?>
                <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                            <label for="nombreR" class="form-label">Nombre residuo</label>
                            <input type="text" id="nombreR" name="nombreR" class="form-control"
                                list="nombreR_list" placeholder="Seleccione o escriba..." autocomplete="off">
                            <datalist id="nombreR_list">
                                <?php foreach($residuos as $temp): ?>
                                <option value="<?= htmlspecialchars($temp->getNombre()) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="clasificar_residuo"
                            class="btn btn-info btn-lg">Clasificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>