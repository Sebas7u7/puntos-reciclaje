<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Mapa de Puntos de Reciclaje</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

    <!-- Estilo personalizado -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0fdf4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #2d6a4f;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        #map {
            height: 600px;
            width: 90%;
            margin: 0 auto 40px auto;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 128, 0, 0.2);
            border: 2px solid #b7e4c7;
        }

        .leaflet-popup-content-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #a3d9a5;
            padding: 8px 12px;
            font-size: 14px;
            color: #333;
        }

        .leaflet-popup-content strong {
            color: #1b4332;
        }
    </style>
</head>
<body>

    <h2>Mapa con Puntos de Reciclaje</h2>
    <div id="map"></div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.min.js"></script>

    <script>
    const ubicaciones = <?php echo $ubicaciones_json; ?>;

    const map = L.map('map').setView([4.6519, -74.0966], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Añadir marcadores
    ubicaciones.forEach(function(ubicacion) {
        const popup = `
            <strong>${ubicacion.nombre}</strong><br>
            Dirección: ${ubicacion.direcion}<br>
            Estado: ${ubicacion.estado}<br>
            Colaborador: ${ubicacion.colaborador}
        `;
        L.marker([ubicacion.lat, ubicacion.lng])
            .addTo(map)
            .bindPopup(popup);
    });

    // Geolocalización del usuario
    map.locate({ enableHighAccuracy: true });

    map.on('locationfound', function(e) {
        const userLatLng = e.latlng;

        L.marker(userLatLng).addTo(map)
            .bindPopup("📍 Aquí estás tú").openPopup();

        let puntoMasCercano = null;
        let menorDistancia = Infinity;

        ubicaciones.forEach(function(punto) {
            const distancia = userLatLng.distanceTo([punto.lat, punto.lng]);
            if (distancia < menorDistancia) {
                menorDistancia = distancia;
                puntoMasCercano = punto;
            }
        });

        if (puntoMasCercano) {
            const popup = `
                <strong>${puntoMasCercano.nombre}</strong><br>
                Dirección: ${puntoMasCercano.direcion}<br>
                Estado: ${puntoMasCercano.estado}<br>
                Colaborador: ${puntoMasCercano.colaborador}
            `;

            L.marker([puntoMasCercano.lat, puntoMasCercano.lng])
                .addTo(map)
                .bindPopup(popup)
                .openPopup();

            L.Routing.control({
                waypoints: [
                    userLatLng,
                    L.latLng(puntoMasCercano.lat, puntoMasCercano.lng)
                ],
                routeWhileDragging: false,
                show: false,
                createMarker: () => null
            }).addTo(map);
        }
    });

    map.on('locationerror', function() {
        alert("No se pudo obtener tu ubicación.");
    });
    </script>

</body>
</html>
