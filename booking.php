<?php
session_start(); // Add this at the very beginning
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['name'])) {
    echo "<script>alert('You must be logged in to book a tour.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['name'];

// Check if place_id is set and sanitize it
if (!isset($_GET['place_id']) || !filter_var($_GET['place_id'], FILTER_VALIDATE_INT)) {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit();
}

$place_id = intval($_GET['place_id']); // sanitizing ID

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
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if already booked
    $check_sql = "SELECT * FROM bookings WHERE user_id = ? AND place_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $place_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('You have already booked this tour.'); window.location.href='my_booking.php';</script>"; // Redirect to bookings page
    } else {
        // Proceed with booking
        $sql = "INSERT INTO bookings (user_id, username, place_id, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $user_id, $username, $place_id);

        if ($stmt->execute()) {
            echo "<script>alert('Booking Confirmed!'); window.location.href = 'my_booking.php';</script>"; // Redirect to user's bookings page
        } else {
            echo "<script>alert('Booking failed. Please try again.');</script>";
        }
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

    <?php if (pathinfo($place['media'], PATHINFO_EXTENSION) === 'mp4') { ?>
        <video width="100%" controls>
            <source src="videos/<?php echo htmlspecialchars($place['media']); ?>" type="video/mp4">
        </video>
    <?php } else { ?>
        <img src="images/<?php echo htmlspecialchars($place['photo']); ?>" width="100%" alt="Tourist Place">
    <?php } ?>

    <form method="post">
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
