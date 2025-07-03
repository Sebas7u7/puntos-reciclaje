<?php
require_once(__DIR__ . '/Residuo.php');
header('Content-Type: application/json');
$residuo = new Residuo();
$residuos = $residuo->listar();
$data = [];
foreach ($residuos as $r) {
    $data[] = [
        'id' => method_exists($r, 'getIdResiduo') ? $r->getIdResiduo() : (property_exists($r, 'idResiduo') ? $r->idResiduo : null),
        'nombre' => method_exists($r, 'getNombre') ? $r->getNombre() : (property_exists($r, 'nombre') ? $r->nombre : null),
        'descripcion' => method_exists($r, 'getDescripcion') ? $r->getDescripcion() : (property_exists($r, 'descripcion') ? $r->descripcion : null),
        'categoria' => method_exists($r, 'getCategoria') ? $r->getCategoria() : (property_exists($r, 'categoria') ? $r->categoria : null)
    ];
}
echo json_encode(['success' => true, 'residuos' => $data]);
