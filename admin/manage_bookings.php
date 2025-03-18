<?php
include '../db_connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Approve booking
if (isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    $conn->query("UPDATE bookings SET status='Approved' WHERE id='$id'");
    header("Location: manage_bookings.php");
    exit();
}

// Delete booking
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM bookings WHERE id='$id'");
    header("Location: manage_bookings.php");
    exit();
}

// Fetch all bookings
$sql = "SELECT b.id, u.name AS user_name, p.name AS place_name, b.booking_date, b.status 
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN tourist_places p ON b.place_id = p.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
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
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .approve-btn, .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
        }
        .approve-btn {
            background: #28a745;
            color: white;
        }
        .approve-btn:hover {
            background: #218838;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <header>
        Manage Bookings
        <a href="admin_dashboard.php" class="approve-btn">Back to Dashboard</a>
    </header>
    
    <div class="container">
        <h2>Booking Requests</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Place</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['place_name']; ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <a href="?approve_id=<?php echo $row['id']; ?>" class="approve-btn">Approve</a>
                        <?php } ?>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>