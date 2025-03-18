<?php
include 'db_connect.php';

// Fetch places from the database
$places = [];
$sql = "SELECT id, name, latitude, longitude FROM tourist_places";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $places[] = $row;
}
?>

<!-- Add this in the <head> section -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    #tourism-map {
        width: 100%;
        height: 500px;
        border: 2px solid #007bff;
        border-radius: 10px;
    }
    .map-container {
        text-align: center;
        margin-bottom: 10px;
    }
    select {
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
</style>

<section id="map">
    <h2>Interactive Map</h2>

    <div class="map-container">
        <label for="placeSelect">Choose a Tourist Place:</label>
        <select id="placeSelect">
            <option value="">-- Select a Place --</option>
            <?php foreach ($places as $place) { ?>
                <option value="<?php echo $place['latitude'] . ',' . $place['longitude']; ?>">
                    <?php echo htmlspecialchars($place['name']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div id="tourism-map"></div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof L === "undefined") {
            console.error("Leaflet failed to load.");
            return;
        }

        // Default Map Center (Odisha, India)
        var map = L.map('tourism-map').setView([20.9517, 85.0985], 7);

        // Load Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add Dropdown Event Listener
        document.getElementById('placeSelect').addEventListener('change', function () {
            var coords = this.value.split(",");
            if (coords.length === 2) {
                var lat = parseFloat(coords[0]);
                var lon = parseFloat(coords[1]);
                
                if (!isNaN(lat) && !isNaN(lon)) {
                    map.setView([lat, lon], 12);

                    // Remove existing marker
                    if (typeof marker !== "undefined") {
                        map.removeLayer(marker);
                    }

                    // Add new marker
                    marker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(this.options[this.selectedIndex].text)
                        .openPopup();
                }
            }
        });
    });
</script>
