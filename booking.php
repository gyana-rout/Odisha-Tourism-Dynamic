<?php
include 'db_connect.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to book a tour.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Check if place_id is set
if (!isset($_GET['place_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit();
}

$place_id = $_GET['place_id'];

// Fetch place details
$sql = "SELECT * FROM tourist_places WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $place_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Tourist place not found.'); window.location.href='index.php';</script>";
    exit();
}

$place = $result->fetch_assoc();

// Handle booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO bookings (user_id, username, place_id, status) VALUES (?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $place_id);

    if ($stmt->execute()) {
        echo "<script>alert('Booking Confirmed!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tour - <?php echo htmlspecialchars($place['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h2>Book Tour - <?php echo htmlspecialchars($place['name']); ?></h2>
        <p><?php echo htmlspecialchars($place['description']); ?></p>
        
        <?php if (pathinfo($place['media'], PATHINFO_EXTENSION) == 'mp4') { ?>
            <video width="100%" controls>
                <source src="videos/<?php echo htmlspecialchars($place['media']); ?>" type="video/mp4">
            </video>
        <?php } else { ?>
            <img src="images/<?php echo htmlspecialchars($place['photo']); ?>" width="100%">
        <?php } ?>

        <form method="post">
            <input type="hidden" name="place_id" value="<?php echo $place_id; ?>">
            <button type="submit" class="book-btn">Confirm Booking</button>
        </form>

        <a href="index.php">Back to Home</a>
    </div>

    <style>
        .container {
            max-width: 600px;
            margin: auto;
            text-align: center;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .book-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 5px;
        }
        .book-btn:hover {
            background: #218838;
        }
    </style>

</body>
</html>
