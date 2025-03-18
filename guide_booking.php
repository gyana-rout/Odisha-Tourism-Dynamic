<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> to book a guide.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guide_id = $_POST['guide_id'];
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO guide_bookings (user_id, guide_id) VALUES ('$user_id', '$guide_id')";
    if ($conn->query($sql)) {
        echo "Guide booking confirmed!";
    } else {
        echo "Error: " . $conn->error;
    }
}
$sql = "SELECT * FROM guides WHERE availability = 1";
$result = $conn->query($sql);
?>
<form method="post">
    <select name="guide_id" required>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>">
                <?php echo $row['name'] . " - " . $row['language']; ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit">Book Guide</button>
</form>