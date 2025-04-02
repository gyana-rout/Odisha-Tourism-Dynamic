<?php
include 'db_connect.php';

// Fetch all tourist places
$sql = "SELECT * FROM tourist_places";
$result = $conn->query($sql);
?>
<?php include 'includes/header.php'; ?>
<section id="gallery">
    <h2>Tourist Places</h2>
    <div class="gallery-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="place-card">
                <?php if (pathinfo($row['media'], PATHINFO_EXTENSION) == 'mp4') { ?>
                    <video controls width="100%">
                        <source src="videos/<?php echo $row['media']; ?>" type="video/mp4">
                    </video>
                <?php } else { ?>
                    <img src="images/<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>">
                <?php } ?>
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <button class="book-btn" onclick="bookTour(<?php echo $row['id']; ?>)">Book Now</button>
            </div>
        <?php } ?>
    </div>
</section>

<style>
    #gallery {
        text-align: center;
        padding: 20px;
        background: #f9f9f9;
    }
    .gallery-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    .place-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 300px;
        padding: 15px;
        text-align: center;
    }
    .place-card img, .place-card video {
        width: 100%;
        border-radius: 10px;
    }
    .place-card h3 {
        margin: 10px 0;
        color: #007bff;
    }
    .place-card p {
        font-size: 14px;
        color: #555;
    }
    .book-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
    }
    .book-btn:hover {
        background: #218838;
    }
</style>

<script>
    function bookTour(placeId) {
        window.location.href = "booking.php?place_id=" + placeId;
    }
</script>

