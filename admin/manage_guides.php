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
    <title>Manage Tour Guides</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background: #007bff;
            color: white;
            padding: 20px;
            font-size: 24px;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form input, form button {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        form label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        form button {
            background: #28a745;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        form button:hover {
            background: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .back-btn {
            background: #17a2b8;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        .back-btn:hover {
            background: #138496;
        }
    </style>
</head>
<body>
    <header>
        Manage Tour Guides
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </header>
    
    <div class="container">
        <h2>Add New Guide</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Guide Name" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="text" name="language" placeholder="Languages Spoken" required>
            <label>
                <input type="checkbox" name="availability"> Available
            </label>
            <button type="submit" name="add_guide">Add Guide</button>
        </form>
    </div>

    <div class="container">
        <h2>Existing Guides</h2>
        <table>
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
                    <td><?php echo $row['availability'] ? '✅ Available' : '❌ Not Available'; ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this guide?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
