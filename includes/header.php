<?php
session_start(); // Always start the session at the very top
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odisha Tourism System</title>
    <link rel="stylesheet" href="../styles.css">
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
        }
        nav {
            background: #ff5722;
            padding: 15px;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
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
                <li><a href="admin_login.php" style="color: red; font-weight: bold;">Admin Login</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
