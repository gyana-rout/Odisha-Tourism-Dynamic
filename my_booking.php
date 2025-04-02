<?php
include 'db_connect.php';

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

<?php include 'includes/header.php'; ?>
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
                        <td><img src="images/<?php echo htmlspecialchars($row['photo']); ?>" width="100"></td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <span style="color: orange; font-weight: bold;">Pending</span>
                            <?php } else { ?>
                                <span style="color: green; font-weight: bold;">Approved</span>
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

<?php $stmt->close(); ?>
