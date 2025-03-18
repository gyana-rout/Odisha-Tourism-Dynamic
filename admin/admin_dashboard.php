<?php
include '../db_connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

// Fetch data counts
$users_count = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$places_count = $conn->query("SELECT COUNT(*) AS count FROM tourist_places")->fetch_assoc()['count'];
$bookings_count = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
$guides_count = $conn->query("SELECT COUNT(*) AS count FROM guides")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
        }
        .card h3 {
            color: #007bff;
        }
        .manage-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
        .manage-links a {
            background: #28a745;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .manage-links a:hover {
            background: #218838;
        }
        .logout {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: red;
            font-weight: bold;
            text-decoration: none;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        Admin Dashboard
        <a href="../admin_logout.php" class="logout">Logout</a>
    </header>
    
    <div class="container">
        <h2>Dashboard Overview</h2>
        <div class="dashboard-grid">
            <div class="card"><h3>Users</h3><p><?php echo $users_count; ?></p></div>
            <div class="card"><h3>Tourist Places</h3><p><?php echo $places_count; ?></p></div>
            <div class="card"><h3>Bookings</h3><p><?php echo $bookings_count; ?></p></div>
            <div class="card"><h3>Guides</h3><p><?php echo $guides_count; ?></p></div>
        </div>
        
        <h2>Manage Sections</h2>
        <div class="manage-links">
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_places.php">Manage Tourist Places</a>
            <a href="manage_bookings.php">Manage Bookings</a>
            <a href="manage_guides.php">Manage Guides</a>
        </div>
    </div>
</body>
</html>
