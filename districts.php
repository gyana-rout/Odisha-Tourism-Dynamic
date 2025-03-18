<section>
    <h2>Explore Odisha Districts</h2>
    <?php
    $sql = "SELECT * FROM districts";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<div><h3>{$row['name']}</h3><p>{$row['culture']}</p><p>{$row['festivals']}</p></div>";
    }
    ?>
</section>