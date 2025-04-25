<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> to book a guide.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guide_id = $_POST['guide_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO guide_bookings (user_id, guide_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $guide_id);

    if ($stmt->execute()) {
        $message = "<p class='success'>✅ Guide booking confirmed!</p>";
    } else {
        $message = "<p class='error'>❌ Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

$sql = "SELECT * FROM guides WHERE availability = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Tour Guide - Odisha Tourism</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eef2f3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #ff5722;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        nav {
            background: #ff5722;
            padding: 15px;
            text-align: center;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .container h2 {
            color: #333;
            margin-bottom: 20px;
        }
        select, button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #ff5722;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #e64a19;
        }
        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .welcome {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Odisha Tourism System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="gallery.php">Tourist Places</a></li>
                <li><a href="guide_booking.php">Book a Guide</a></li>
                <li><a href="my_booking.php">My Bookings</a></li>
                <li><a href="near_places.php">Find Near Places</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } ?>
                <li><a href="admin_login.php" style="color: yellow; font-weight: bold;">Admin Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="welcome">
            <h2>Book a Tour Guide</h2>
            <p>Select from our available local experts to guide you through Odisha’s beauty, culture, and heritage.</p>
        </div>

        <?php echo $message; ?>

        <form method="post">
            <label for="guide_id">Choose a guide:</label>
            <select name="guide_id" id="guide_id" required>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['language']) . ")"; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">Confirm Guide Booking</button>
        </form>
    </div>
</body>
</html>
