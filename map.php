<section id="map">
    <h2>Interactive Map</h2>
    <div id="tourism-map" style="height: 400px;"></div>
</section>
<script>
    var map = L.map('tourism-map').setView([20.9517, 85.0985], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    function bookTour(placeId) {
        window.location.href = 'booking.php?place_id=' + placeId;
    }
</script>