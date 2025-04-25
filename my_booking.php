<?php
include 'db_connect.php';
session_start(); // <-- this is missing in your code

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user bookings
$sql = "SELECT b.id, p.name AS place_name, p.photo, b.booking_date, b.status 
        FROM bookings b
        JOIN tourist_places p ON b.place_id = p.id
        WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

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
<section id="my-bookings">
    <h2>My Bookings</h2>
    <div class="bookings-container">
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <tr>
                    <th>Place</th>
                    <th>Photo</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['place_name']); ?></td>
                        <td>
                            <img src="images/<?php echo htmlspecialchars($row['photo']); ?>" width="100" alt="Place Photo">
                        </td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending') { ?>
                                <span style="color: orange; font-weight: bold;">Pending</span>
                            <?php } elseif ($row['status'] === 'Approved') { ?>
                                <span style="color: green; font-weight: bold;">Approved</span>
                            <?php } else { ?>
                                <span style="color: red; font-weight: bold;"><?php echo htmlspecialchars($row['status']); ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No bookings found.</p>
        <?php } ?>
    </div>
</section>

<style>
    #my-bookings {
        text-align: center;
        padding: 20px;
        background: #f9f9f9;
    }
    .bookings-container {
        max-width: 800px;
        margin: auto;
        background: white;
        padding: 20px;
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
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background: #007bff;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>

<?php
$stmt->close();
$conn->close(); // optional, to clean up
?>
