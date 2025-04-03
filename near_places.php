<?php
include 'db_connect.php';

// Fetch all tourist places from the database
$sql = "SELECT id, name, latitude, longitude FROM tourist_places";
$result = $conn->query($sql);

$places = [];
while ($row = $result->fetch_assoc()) {
    $places[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nearby Places with Distance</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 500px;
            width: 90%;
            margin: 20px auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
    <h2>Saved Tourist Places & Nearby Locations with Distance</h2>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([20.5937, 78.9629], 5); // Default center India
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Define custom icons
        var icons = {
            tourist: L.icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png', iconSize: [32, 32] }),
            restaurant: L.icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png', iconSize: [32, 32] }),
            fuel: L.icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png', iconSize: [32, 32] }),
            hospital: L.icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png', iconSize: [32, 32] }),
            bus_stop: L.icon({ iconUrl: 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png', iconSize: [32, 32] })
        };

        var places = <?php echo json_encode($places); ?>; // Get tourist places from PHP

        places.forEach(place => {
            var lat = place.latitude;
            var lon = place.longitude;

            L.marker([lat, lon], {icon: icons.tourist})
                .addTo(map)
                .bindPopup(`<strong>${place.name}</strong><br><button onclick="fetchNearbyPlaces(${lat}, ${lon})">Show Nearby</button>`);
        });

        function fetchNearbyPlaces(lat, lon) {
            var query = `
                [out:json];
                (
                    node["amenity"="restaurant"](around:3000, ${lat}, ${lon});
                    node["amenity"="fuel"](around:3000, ${lat}, ${lon});
                    node["amenity"="hospital"](around:3000, ${lat}, ${lon});
                    node["highway"="bus_stop"](around:3000, ${lat}, ${lon});
                );
                out body;`;

            var url = "https://overpass-api.de/api/interpreter?data=" + encodeURIComponent(query);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.elements.forEach(place => {
                        var placeLat = place.lat;
                        var placeLon = place.lon;
                        var placeName = place.tags.name || "Unknown";

                        var type = "Unknown";
                        var icon = icons.fuel; // Default icon

                        if (place.tags.amenity === "restaurant") {
                            type = "🍽️ Restaurant";
                            icon = icons.restaurant;
                        } else if (place.tags.amenity === "fuel") {
                            type = "⛽ Petrol Pump";
                            icon = icons.fuel;
                        } else if (place.tags.amenity === "hospital") {
                            type = "🏥 Hospital";
                            icon = icons.hospital;
                        } else if (place.tags.highway === "bus_stop") {
                            type = "🚌 Bus Stop";
                            icon = icons.bus_stop;
                        }

                        // Calculate distance using Haversine formula
                        var distance = getDistanceFromLatLonInKm(lat, lon, placeLat, placeLon).toFixed(2);

                        L.marker([placeLat, placeLon], {icon: icon})
                            .addTo(map)
                            .bindPopup(`<strong>${type}</strong><br>${placeName}<br><b>Distance:</b> ${distance} km`);
                    });
                })
                .catch(error => console.log("Error fetching data:", error));
        }

        // Haversine formula to calculate distance
        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = deg2rad(lat2 - lat1);
            var dLon = deg2rad(lon2 - lon1);
            var a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2); 
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            return R * c; // Distance in km
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180);
        }
    </script>

</body>
</html>
