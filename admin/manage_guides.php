/* manage_guides.php - Manage Tour Guides */
<?php
include '../db_connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Add a new guide
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_guide'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $language = $_POST['language'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    
    $sql = "INSERT INTO guides (name, contact, language, availability) VALUES ('$name', '$contact', '$language', '$availability')";
    $conn->query($sql);
}

// Delete a guide
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM guides WHERE id='$id'");
    header("Location: manage_guides.php");
    exit();
}

// Fetch all guides
$result = $conn->query("SELECT * FROM guides");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guides</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Manage Tour Guides</h1>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </header>
    
    <section>
        <h2>Add New Guide</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Guide Name" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="text" name="language" placeholder="Languages Spoken" required>
            <label><input type="checkbox" name="availability"> Available</label>
            <button type="submit" name="add_guide">Add Guide</button>
        </form>
    </section>
    
    <section>
        <h2>Existing Guides</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Languages</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['contact']; ?></td>
                    <td><?php echo $row['language']; ?></td>
                    <td><?php echo $row['availability'] ? 'Available' : 'Not Available'; ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>