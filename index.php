<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odisha Tourism</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eef2f3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        h1 {
            color: #ff5722;
            text-align: center;
            font-size: 2.5rem;
        }
        .card {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            text-align: center;
        }
        .places, .districts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .card img {
            width: 100%;
            border-radius: 10px;
        }
        
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <section class="card">
            <h1>Discover the Beauty of Odisha</h1>
            <p>Immerse yourself in the vibrant culture, breathtaking landscapes, and historic sites of Odisha.</p>
        </section>
        <?php include 'map.php'; ?>
        <?php include 'districts.php'; ?>
        
        <section class="card">
            <h2>Must-Visit Tourist Destinations</h2>
            <div class="places">
                <?php
                $query = "SELECT * FROM tourist_places LIMIT 6";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>
                        <h3>{$row['name']}</h3>
                        <p>{$row['description']}</p>
                        <img src='images/{$row['photo']}' alt='{$row['name']}'>
                    </div>";
                }
                ?>
            </div>
        </section>
        
        <section class="card">
            <h2>Explore Odisha’s Cultural Heritage</h2>
            <div class="districts">
                <?php
                $query = "SELECT * FROM districts LIMIT 4";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>
                        <h3>{$row['name']}</h3>
                        <p><strong>Culture:</strong> {$row['culture']}</p>
                        <p><strong>Festivals:</strong> {$row['festivals']}</p>
                    </div>";
                }
                ?>
            </div>
        </section>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
