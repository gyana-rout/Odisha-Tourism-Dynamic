<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $place_id = $_POST['place_id'];
    $user_id = $_POST['user_id'];
    $sql = "INSERT INTO bookings (user_id, place_id) VALUES ('$user_id', '$place_id')";
    $conn->query($sql);
    echo "Booking Confirmed!";
}
?>
<form method="post">
    <input type="hidden" name="place_id" value="<?php echo $_GET['place_id']; ?>">
    <input type="text" name="user_id" placeholder="Enter Your User ID" required>
    <button type="submit">Confirm Booking</button>
</form>