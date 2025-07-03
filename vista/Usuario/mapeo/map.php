<!DOCTYPE html>
<html>
<head>
    <title>Varias ubicaciones en Leaflet</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <style>
    #map {
        height: 500px;
        width: 100%;
    }
    </style>
</head>
<body>

<h2>Mapa con múltiples ubicaciones</h2>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.min.js"></script>

<script>
const ubicaciones = <?php echo $ubicaciones_json; ?>;

const map = L.map('map').setView([4.6519, -74.0966], 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Mostrar todos los puntos en el mapa
ubicaciones.forEach(function(ubicacion) {
    const contenidoPopup = `
        <strong>${ubicacion.nombre}</strong><br>
        Dirección: ${ubicacion.direcion}<br>
        Estado: ${ubicacion.estado}<br>
        Colaborador: ${ubicacion.colaborador}
    `;

    L.marker([ubicacion.lat, ubicacion.lng])
        .addTo(map)
        .bindPopup(contenidoPopup);
});

// 🚶 Obtener ubicación del usuario y calcular ruta al punto más cercano
map.locate({
    enableHighAccuracy: true,
    timeout: 10000,
    maximumAge: 0
});

map.on('locationfound', function(e) {
    const userLatLng = e.latlng; // ✅ 위치 저장

    // 마커 추가
    L.marker(userLatLng)
        .addTo(map)
        .bindPopup("📍 Aquí estás tú").openPopup();

    // 가장 가까운 위치 계산
    let puntoMasCercano = null;
    let menorDistancia = Infinity;

    ubicaciones.forEach(function(punto) {
        const dist = userLatLng.distanceTo([punto.lat, punto.lng]);
        if (dist < menorDistancia) {
            menorDistancia = dist;
            puntoMasCercano = punto;
        }
    });

    // 경로 및 마커 표시
    if (puntoMasCercano) {
        const contenidoPopup = `
            <strong>${puntoMasCercano.nombre}</strong><br>
            Dirección: ${puntoMasCercano.direcion}<br>
            Estado: ${puntoMasCercano.estado}<br>
            Colaborador: ${puntoMasCercano.colaborador}
        `;

        L.marker([puntoMasCercano.lat, puntoMasCercano.lng])
            .addTo(map)
            .bindPopup(contenidoPopup)
            .openPopup();

        L.Routing.control({
            waypoints: [
                userLatLng,
                L.latLng(puntoMasCercano.lat, puntoMasCercano.lng)
            ],
            routeWhileDragging: false,
            show: false,
            language: 'es',
            createMarker: () => null // 🔧 Routing 마커 비활성화
        }).addTo(map);
    }
});

map.on('locationerror', function(e) {
    alert("No se pudo obtener tu ubicación.");
});

</script>

</body>
</html>

